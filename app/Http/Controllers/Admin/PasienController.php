<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.pasien.index', compact('pasien'));
    }

    /**
     * Show detail pasien with rekam medis
     */
    public function show($id)
    {
        // Gunakan view database untuk profil lengkap
        $pasien = DB::table('v_profil_pasien_lengkap')
            ->where('id_pasien', $id)
            ->first();

        if (!$pasien) {
            return redirect()
                ->route('admin.pasien.index')
                ->with('error', 'Data pasien tidak ditemukan');
        }

        // Get kondisi kulit detail
        $kondisiKulit = [];
        if ($pasien->id_rekam_medis) {
            $kondisiKulit = DB::table('rekam_kondisi_kulit')
                ->where('id_rekam_medis', $pasien->id_rekam_medis)
                ->whereNull('deleted_at')
                ->get()
                ->groupBy('jenis_kondisi');
        }

        return view('admin.pasien.show', compact('pasien', 'kondisiKulit'));
    }

    /**
     * Show form for creating new patient WITH RESERVATION
     */
    public function create()
    {
        // Kirim semua treatment aktif untuk dropdown
        $treatments = Treatment::whereNull('deleted_at')
            ->orderBy('nama_treatment')
            ->get();

        return view('admin.pasien.create', compact('treatments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Pasien
            'nama_depan'      => 'required|string|max:255',
            'nama_belakang'   => 'required|string|max:255',
            'no_telepon'      => 'required|string|unique:pasien,no_telepon',
            'alamat'          => 'required|string',
            'tanggal_lahir'   => 'required|date|before:today',
            'jenis_kelamin'   => 'required|in:L,P',

            // Reservasi
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'jam_reservasi'     => 'required|date_format:H:i',

            // Treatment
            'selected_treatments'   => 'required|array|min:1',
            'selected_treatments.*' => 'exists:treatment,id_treatment',
        ]);

        try {
            // 1. Buat Pasien
            $pasien = Pasien::create([
                'id_user'       => Auth::id(),
                'nama_depan'    => $validated['nama_depan'],
                'nama_belakang' => $validated['nama_belakang'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon'    => $validated['no_telepon'],
                'alamat'        => $validated['alamat'],
            ]);

            // 2. Format treatments
            $treatments = array_map(function($id) {
                return ['id_treatment' => $id, 'quantity' => 1];
            }, $validated['selected_treatments']);

            $treatmentsJson = json_encode($treatments);

            // 3. Call BuatReservasiBaru
            DB::statement('SET @id_reservasi_baru = 0');

            DB::statement('CALL BuatReservasiBaru(?, ?, ?, ?, ?, ?, ?, @id_reservasi_baru)', [
                $pasien->id_pasien,
                Auth::id(),
                $validated['tanggal_reservasi'],
                $validated['jam_reservasi'],
                'manual',
                'Reservasi walk-in oleh admin',
                $treatmentsJson
            ]);

            $result = DB::select('SELECT @id_reservasi_baru as id_reservasi')[0];
            $idReservasi = $result->id_reservasi;

            return redirect()
                ->route('admin.pasien.index')
                ->with('success', 'Pasien & reservasi berhasil dibuat! Pembayaran akan dilakukan setelah treatment selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error buat pasien walk-in: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
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

        return view('admin.pasien.edit_rekam_medis', compact('pasien', 'rekamMedis'));
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
            'kondisi_kulit.*.area' => 'nullable|string|max:255',
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

            // Prepare kondisi kulit JSON
            $kondisiKulitData = collect($validated['kondisi_kulit'])->map(function ($kondisi) {
                if ($kondisi['status_kondisi'] === 'tidak ada') {
                    return [
                        'jenis_kondisi'  => $kondisi['jenis_kondisi'],
                        'status_kondisi' => $kondisi['status_kondisi'],
                        'area'           => '',
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
                ->route('admin.pasien.show', $id)
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
                ->route('admin.pasien.index')
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
            $pdf = Pdf::loadView('admin.pasien.export-pdf', [
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