<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\JadwalOperasional;
use App\Models\Reservasi;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Halaman buat reservasi baru
     */
    public function create()
    {
        // Ambil semua treatment yang aktif
        $treatments = Treatment::whereNull('deleted_at')
            ->orderBy('nama_treatment')
            ->get();

        // Ambil jadwal yang buka (bukan status available lagi, tapi status_operasional = buka)
        // Dan hanya tanggal >= hari ini
        $jadwals = JadwalOperasional::where('status_operasional', 'buka')
            ->where('hari_tanggal', '>=', now()->toDateString())
            ->orderBy('hari_tanggal')
            ->get();

        return view('reservation', compact('treatments', 'jadwals'));
    }

    /**
     * Simpan reservasi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'jam_reservasi'     => 'required',
            'treatments'        => 'required|array|min:1',
            'treatments.*.id_treatment' => 'required|exists:treatment,id_treatment',
            'treatments.*.quantity'     => 'required|integer|min:1',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        try {
            // Cek apakah user sudah punya data pasien
            $pasien = Pasien::where('id_user', Auth::id())
                ->whereNull('deleted_at')
                ->first();

            // KALAU BELUM ADA DATA PASIEN SAMA SEKALI → WAJIB ISI PROFIL DULU
            if (!$pasien) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda belum memiliki data pasien. Silakan lengkapi profil Anda terlebih dahulu sebelum membuat reservasi.')
                    ->with('profile_link', route('user.profile.edit'));
            }

            // KALAU SUDAH ADA DATA PASIEN TAPI MASIH PAKAI DEFAULT (dari create otomatis sebelumnya)
            // ATAU ADA YANG KOSONG → TETAP SURUH ISI PROFIL
            if (
                $pasien->no_telepon === 'Belum diisi' || 
                $pasien->alamat === 'Belum diisi' || 
                empty(trim($pasien->no_telepon)) || 
                empty(trim($pasien->alamat))
            ) {
                return back()
                    ->withInput()
                    ->with('error', 'Profil Anda belum lengkap! Silakan isi nomor telepon dan alamat terlebih dahulu.')
                    ->with('profile_link', route('user.profile.edit'));
            }

            // Cek jadwal operasional
            $jadwal = JadwalOperasional::where('hari_tanggal', $validated['tanggal_reservasi'])
                ->where('status_operasional', 'buka')
                ->first();

            if (!$jadwal) {
                return back()
                    ->withInput()
                    ->with('error', 'Klinik tutup pada tanggal yang dipilih. Silakan pilih tanggal lain.');
            }

            // Format treatments
            $treatments = array_values($validated['treatments']);
            $treatments_json = json_encode($treatments);

            // Call procedure
            DB::statement('SET @id_reservasi_baru = 0');
            DB::statement('CALL BuatReservasiBaru(?, ?, ?, ?, ?, ?, ?, @id_reservasi_baru)', [
                $pasien->id_pasien,
                Auth::id(),
                $validated['tanggal_reservasi'],
                $validated['jam_reservasi'],
                'online',
                $validated['keterangan'] ?? 'Reservasi dari web',
                $treatments_json
            ]);

            $result = DB::select('SELECT @id_reservasi_baru as id_reservasi')[0];

            return redirect()
                ->route('user.reservasi.my')
                ->with('success', 'Reservasi berhasil dibuat! Silakan tunggu konfirmasi dari klinik.');

        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'sudah dibooking')) {
                return back()->withInput()->with('error', 'Jam yang dipilih sudah dibooking oleh pasien lain. Silakan pilih jam lain.');
            }

            return back()->withInput()->with('error', 'Gagal membuat reservasi. Silakan coba lagi atau hubungi admin.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Lihat riwayat reservasi user
     */
    public function myReservations()
    {
        // Ambil data pasien user yang login
        $pasien = Pasien::where('id_user', Auth::id())
            ->whereNull('deleted_at')
            ->first();

        if (!$pasien) {
            // Jika belum ada data pasien
            return view('user.reservasi.index', [
                'reservations' => collect([]),
                'message' => 'Anda belum memiliki riwayat reservasi. Silakan buat reservasi pertama Anda!'
            ]);
        }

        // Ambil semua reservasi user (pakai view untuk data lengkap)
        $reservations = DB::table('v_reservasi_lengkap')
            ->where('id_pasien', $pasien->id_pasien)
            ->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_reservasi', 'desc')
            ->get();

        return view('user.reservasi.index', compact('reservations'));
    }

    /**
     * AJAX: Get available time slots berdasarkan tanggal & durasi treatment
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $tanggal = $request->tanggal;
        $durasi_treatment = $request->durasi ?? 60; // Default 60 menit jika tidak ada

        try {
            // Panggil function GenerateTimeSlots dari database
            $result = DB::select("SELECT GenerateTimeSlots(?, ?) as slots", [
                $tanggal,
                $durasi_treatment
            ]);

            $slots = json_decode($result[0]->slots, true);

            return response()->json([
                'success' => true,
                'slots' => $slots ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil slot waktu: ' . $e->getMessage(),
                'slots' => []
            ], 500);
        }
    }

    /**
     * AJAX: Get jadwal info berdasarkan tanggal
     */
    public function getJadwalByDate(Request $request)
    {
        $tanggal = $request->tanggal;

        $jadwal = JadwalOperasional::where('hari_tanggal', $tanggal)
            ->first();

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal operasional belum tersedia untuk tanggal ini.'
            ]);
        }

        if ($jadwal->status_operasional === 'tutup') {
            return response()->json([
                'success' => false,
                'message' => 'Klinik tutup pada tanggal ini. ' . ($jadwal->keterangan ?? '')
            ]);
        }

        return response()->json([
            'success' => true,
            'jadwal' => [
                'id_jadwal' => $jadwal->id_jadwal,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
                'status_operasional' => $jadwal->status_operasional,
                'keterangan' => $jadwal->keterangan
            ]
        ]);
    }
}