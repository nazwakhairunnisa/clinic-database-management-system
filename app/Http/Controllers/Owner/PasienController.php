<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\RekamKondisiKulit;
use App\Models\Reservasi;
use App\Models\JadwalOperasional;
use App\Models\Treatment;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienController extends Controller
{
    /**
     * Display a listing of pasien
     */
    public function index(Request $request)
    {
        $query = Pasien::with(['rekamMedis', 'reservasi']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where(DB::raw("CONCAT(nama_depan, ' ', nama_belakang)"), 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhere('id_pasien', 'like', "%{$search}%");
            });
        }

        $pasien = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('owner.pasien.index', compact('pasien'));
    }

    /**
     * Show detail pasien with rekam medis
     */
    public function show($id)
    {
        // Tetap ambil data dasar pasien + rekam medis dari view (biar cepat)
        $pasien = DB::table('v_profil_pasien_lengkap')
            ->where('id_pasien', $id)
            ->first();

        if (!$pasien) {
            return redirect()
                ->route('owner.pasien.index')
                ->with('error', 'Data pasien tidak ditemukan');
        }

        // === TAMBAHAN: Hitung ulang kunjungan secara real-time ===
        $totalKunjungan = DB::table('reservasi')
            ->where('id_pasien', $id)
            ->count();

        $kunjunganSelesai = DB::table('reservasi')
            ->where('id_pasien', $id)
            ->where('status', 'completed')
            ->count();

        $totalBelanja = DB::table('pembayaran')
            ->join('reservasi', 'pembayaran.id_reservasi', '=', 'reservasi.id_reservasi')
            ->where('reservasi.id_pasien', $id)
            ->where('pembayaran.status_pembayaran', 'lunas')
            ->sum('pembayaran.total_pembayaran');

        $pasien->total_belanja = $totalBelanja;

        // Override nilai dari view dengan yang real-time
        $pasien->total_kunjungan = $totalKunjungan;
        $pasien->kunjungan_selesai = $kunjunganSelesai;

        // Rekam medis terbaru (sudah kita perbaiki kemarin)
        $rekamMedisTerbaru = RekamMedis::where('id_pasien', $id)
            ->with('kondisiKulit')
            ->latest('created_at')
            ->first();

        if ($rekamMedisTerbaru) {
            $pasien->keluhan_awal = $rekamMedisTerbaru->keluhan;
            $pasien->jenis_kulit = $rekamMedisTerbaru->jenis_kulit;
            $pasien->kelembapan = $rekamMedisTerbaru->kelembapan;
            $pasien->kondisi_pasien = $rekamMedisTerbaru->kondisi_pasien;
            $pasien->produk_terakhir_dipakai = $rekamMedisTerbaru->produk_terakhir_dipakai;
            $pasien->riwayat_penyakit = $rekamMedisTerbaru->riwayat_penyakit;
            $pasien->riwayat_alergi = $rekamMedisTerbaru->riwayat_alergi;
            $pasien->riwwayat_pengobatan = $rekamMedisTerbaru->riwwayat_pengobatan ?? null;
            $pasien->tanggal_rekam_medis_awal = $rekamMedisTerbaru->created_at;
        }

        // Kondisi kulit
        $kondisiKulit = [];
        if ($rekamMedisTerbaru) {
            $kondisiKulit = $rekamMedisTerbaru->kondisiKulit()
                ->whereNull('deleted_at')
                ->get()
                ->groupBy('jenis_kondisi');
        }

        return view('owner.pasien.show', compact('pasien', 'kondisiKulit'));
    }

    /**
     * Show form for creating new patient WITH RESERVATION
     */
    public function create()
    {
        // Get jadwal operasional yang tersedia
        $jadwal = JadwalOperasional::available()
            ->where('hari_tanggal', '>=', now()->toDateString())
            ->orderBy('hari_tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Get jadwal hari ini yang tersedia (untuk default selection)
        $jadwalHariIni = JadwalOperasional::available()
            ->where('hari_tanggal', now()->toDateString())
            ->orderBy('jam_mulai')
            ->first();

        // Get treatments
        $treatments = Treatment::active()
            ->orderBy('nama_treatment')
            ->get();

        return view('owner.pasien.create', compact('jadwal', 'treatments', 'jadwalHariIni'));
    }

    /**
     * Store new patient with reservation (menggunakan stored procedure BuatReservasiBaru)
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            // Data Pasien
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'no_telepon' => 'required|string|unique:pasien,no_telepon',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            
            // Data Reservasi
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'jam_reservasi' => 'required|date_format:H:i',
            'id_jadwal' => 'required|exists:jadwal_operasional,id_jadwal',
            'treatments' => 'required|array|min:1',
            'treatments.*.id_treatment' => 'required|exists:treatment,id_treatment',
            'treatments.*.quantity' => 'required|integer|min:1',
            
            // Data Pembayaran
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
            'status_pembayaran' => 'required|in:belum,lunas',
        ], [
            'nama_depan.required' => 'Nama depan wajib diisi',
            'nama_belakang.required' => 'Nama belakang wajib diisi',
            'no_telepon.required' => 'Nomor telepon wajib diisi',
            'no_telepon.regex' => 'Format nomor telepon tidak valid',
            'no_telepon.unique' => 'Nomor telepon sudah terdaftar',
            'alamat.required' => 'Alamat wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'tanggal_reservasi.required' => 'Tanggal reservasi wajib diisi',
            'tanggal_reservasi.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini',
            'jam_reservasi.required' => 'Jam reservasi wajib diisi',
            'id_jadwal.required' => 'Jadwal operasional wajib dipilih',
            'treatments.required' => 'Treatment wajib dipilih',
            'treatments.min' => 'Minimal pilih 1 treatment',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'status_pembayaran.required' => 'Status pembayaran wajib dipilih',
        ]);

        try {
            // 1. Insert Pasien
            $pasien = Pasien::create([
                'id_user' => Auth::id(),
                'nama_depan' => $validated['nama_depan'],
                'nama_belakang' => $validated['nama_belakang'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat'],
            ]);

            // 2. Prepare treatments untuk stored procedure
            $treatmentsJson = json_encode(
                array_map(function($treatment) {
                    return [
                        'id_treatment' => $treatment['id_treatment'],
                        'quantity' => $treatment['quantity']
                    ];
                }, $validated['treatments'])
            );

            // 3. Call Stored Procedure BuatReservasiBaru
            DB::statement('SET @id_reservasi_baru = 0');
            
            DB::statement('CALL BuatReservasiBaru(?, ?, ?, ?, ?, ?, ?, ?, @id_reservasi_baru)', [
                $pasien->id_pasien,
                Auth::id(),
                $validated['id_jadwal'],
                $validated['tanggal_reservasi'],
                $validated['jam_reservasi'],
                'manual', // metode_reservasi
                'Reservasi dibuat saat registrasi pasien baru',
                $treatmentsJson
            ]);

            // Get ID reservasi yang baru dibuat
            $result = DB::select('SELECT @id_reservasi_baru as id_reservasi')[0];
            $idReservasi = $result->id_reservasi;

            // 4. Handle Pembayaran
            if ($validated['status_pembayaran'] == 'lunas') {
                // Jika lunas, panggil stored procedure untuk lunaskan pembayaran
                $pembayaran = Pembayaran::where('id_reservasi', $idReservasi)->first();
                
                if ($pembayaran) {
                    DB::statement('CALL ProsesLunaskanPembayaran(?, ?, ?)', [
                        $pembayaran->id_pembayaran,
                        Auth::id(),
                        null // bukti_pembayaran (optional untuk manual)
                    ]);
                }
            } else {
                // Update metode pembayaran untuk nanti
                Pembayaran::where('id_reservasi', $idReservasi)
                    ->update([
                        'metode_pembayaran' => $validated['metode_pembayaran']
                    ]);
            }

            $message = 'Pasien berhasil ditambahkan dan reservasi telah dibuat';
            if ($validated['status_pembayaran'] == 'lunas') {
                $message .= '. Pembayaran telah lunas dan tercatat di pendapatan.';
            } else {
                $message .= '. Pembayaran akan dicatat setelah pelunasan.';
            }

            return redirect()
                ->route('owner.pasien.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error add pasien with reservation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pasien: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing rekam medis
     */
    public function editRekamMedis($id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Get rekam medis terakhir (jika ada)
        $rekamMedis = RekamMedis::where('id_pasien', $id)
            ->with('kondisiKulit')
            ->latest('created_at')
            ->first();

        return view('owner.pasien.edit_rekam_medis', compact('pasien', 'rekamMedis'));
    }

    /**
     * Update or create rekam medis (menggunakan stored procedure BuatRekamMedisLengkap)
     */
    public function updateRekamMedis(Request $request, $id)
    {
        // Validasi basic dulu
        $validated = $request->validate([
            'keluhan' => 'required|string',
            'jenis_kulit' => 'required|in:normal,dry,oily,sensitive,kombinasi',
            'kelembapan' => 'required|in:baik,cukup,kurang',
            'kondisi_pasien' => 'required|array',
            'kondisi_pasien.*' => 'in:hamil,menyusui,kontrasepsi,normal',
            'produk_terakhir_dipakai' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'riwayat_pengobatan' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
            
            // Kondisi kulit - basic validation
            'kondisi_kulit' => 'required|array|min:1',
            'kondisi_kulit.*.jenis_kondisi' => 'required|string',
            'kondisi_kulit.*.status_kondisi' => 'required|in:ada,tidak ada',
            'kondisi_kulit.*.area' => 'nullable|string|max:255', // ← Ubah jadi nullable
            'kondisi_kulit.*.derajat' => 'nullable|in:ringan,sedang,berat',
        ]);

        // Custom validation: Area wajib diisi kalau status = 'ada'
        foreach ($validated['kondisi_kulit'] as $index => $kondisi) {
            if ($kondisi['status_kondisi'] === 'ada') {
                if (empty($kondisi['area'])) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors([
                            "kondisi_kulit.{$index}.area" => "Area harus diisi untuk {$kondisi['jenis_kondisi']} yang berstatus 'Ada'"
                        ]);
                }
            }
        }

        try {
            // Prepare kondisi pasien
            $kondisiPasien = in_array('normal', $validated['kondisi_pasien']) 
                ? 'normal' 
                : implode(',', $validated['kondisi_pasien']);

            // Prepare kondisi kulit JSON — pastikan kalau "tidak ada" → area & derajat jadi string kosong (bukan null)
            $kondisiKulitData = collect($validated['kondisi_kulit'])->map(function ($kondisi) {
                if ($kondisi['status_kondisi'] === 'tidak ada') {
                    return [
                        'jenis_kondisi'  => $kondisi['jenis_kondisi'],
                        'status_kondisi' => $kondisi['status_kondisi'],
                        'area'           => '',   // string kosong, bukan null
                        'derajat'        => ''
                    ];
                }
                return [
                    'jenis_kondisi'  => $kondisi['jenis_kondisi'],
                    'status_kondisi' => $kondisi['status_kondisi'],
                    'area'           => $kondisi['area'] ?? '',
                    'derajat'        => $kondisi['derajat'] ?? ''
                ];
            })->values()->toArray();

            $kondisiKulitJson = json_encode($kondisiKulitData, JSON_UNESCAPED_UNICODE);

            // Call Stored Procedure
            DB::statement('SET @id_rekam_medis_baru = 0');
            
            DB::statement('CALL BuatRekamMedisLengkap(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @id_rekam_medis_baru)', [
                $id,
                Auth::id(),
                $validated['keluhan'],
                $validated['jenis_kulit'],
                $validated['kelembapan'],
                $kondisiPasien,
                $validated['produk_terakhir_dipakai'] ?? '',
                $validated['riwayat_penyakit'] ?? '',
                $validated['riwayat_pengobatan'] ?? '',
                $validated['riwayat_alergi'] ?? '',
                $kondisiKulitJson
            ]);

            return redirect()
                ->route('owner.pasien.show', $id)
                ->with('success', 'Rekam medis berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error update rekam medis: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui rekam medis: ' . $e->getMessage());
        }
    }

    /**
     * Delete pasien (soft delete menggunakan stored procedure HapusPasien)
     */
    public function destroy($id)
    {
        try {
            // Call stored procedure HapusPasien
            DB::statement('CALL HapusPasien(?, ?)', [
                $id,
                Auth::id()
            ]);

            return redirect()
                ->route('owner.pasien.index')
                ->with('success', 'Data pasien berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error delete pasien: ' . $e->getMessage());
            
            // Check error message from stored procedure
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'reservasi aktif')) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak bisa menghapus pasien yang memiliki reservasi aktif!');
            }

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pasien: ' . $errorMessage);
        }
    }

    /**
     * Export pasien data
     */
    public function export(Request $request)
    {
        try {
            $query = Pasien::query();

            // Apply search filter hanya jika search benar-benar diisi (bukan kosong)
            if ($request->filled('search') && trim($request->search) !== '') {
                $search = trim($request->search);
                $query->where(function($q) use ($search) {
                    $q->where(DB::raw("CONCAT(nama_depan, ' ', nama_belakang)"), 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%")
                    ->orWhere('id_pasien', 'like', "%{$search}%");
                });
            }

            $pasien = $query->orderBy('nama_depan')->get();

            // Generate PDF
            $pdf = Pdf::loadView('owner.pasien.export-pdf', [
                'pasien' => $pasien,
                'tanggal_export' => now()->format('d F Y'),
                'total_pasien' => $pasien->count()
            ]);

            // Set paper size dan orientation
            $pdf->setPaper('a4', 'landscape');

            $filename = 'daftar_pasien_' . date('Y-m-d_His') . '.pdf';

            // Download PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error export pasien: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal export data pasien: ' . $e->getMessage());
        }
    }
}