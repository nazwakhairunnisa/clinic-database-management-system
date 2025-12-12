<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalOperasionalController extends Controller
{
    /**
     * Tampilkan halaman kelola jadwal operasional
     */
    public function index(Request $request)
    {
        // Filter berdasarkan bulan (default: bulan sekarang)
        $bulan = $request->get('bulan', date('Y-m'));
        
        // Ambil jadwal operasional untuk bulan tersebut
        $jadwal = DB::table('jadwal_operasional')
            ->whereRaw('DATE_FORMAT(hari_tanggal, "%Y-%m") = ?', [$bulan])
            ->orderBy('hari_tanggal', 'asc')
            ->get();
        
        return view('admin.jadwal_operasional.index', compact('jadwal', 'bulan'));
    }

    /**
     * Form tambah jadwal operasional
     */
    public function create()
    {
        return view('admin.jadwal_operasional.create');
    }

    /**
     * Simpan jadwal operasional baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari_tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status_operasional' => 'required|in:buka,tutup',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            // Cek apakah sudah ada jadwal untuk tanggal ini
            $exists = DB::table('jadwal_operasional')
                ->where('hari_tanggal', $request->hari_tanggal)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'hari_tanggal' => 'Jadwal untuk tanggal ini sudah ada. Silakan edit jadwal yang ada.'
                ])->withInput();
            }

            DB::table('jadwal_operasional')->insert([
                'hari_tanggal' => $request->hari_tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status_operasional' => $request->status_operasional,
                'keterangan' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()
                ->route('admin.jadwal_operasional.index')
                ->with('success', 'Jadwal operasional berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal menambahkan jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Form edit jadwal operasional
     */
    public function edit($id)
    {
        $jadwal = DB::table('jadwal_operasional')
            ->where('id_jadwal', $id)
            ->first();

        if (!$jadwal) {
            return redirect()
                ->route('admin.jadwal_operasional.index')
                ->withErrors(['error' => 'Jadwal tidak ditemukan']);
        }

        return view('admin.jadwal_operasional.edit', compact('jadwal'));
    }

    /**
     * Update jadwal operasional
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status_operasional' => 'required|in:buka,tutup',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $jadwal = DB::table('jadwal_operasional')
                ->where('id_jadwal', $id)
                ->first();

            if (!$jadwal) {
                return back()->withErrors(['error' => 'Jadwal tidak ditemukan']);
            }

            // Cek apakah ada reservasi confirmed/requested untuk tanggal ini
            $adaReservasi = DB::table('reservasi')
                ->where('tanggal_reservasi', $jadwal->hari_tanggal)
                ->whereIn('status', ['requested', 'confirmed'])
                ->exists();

            if ($adaReservasi && $request->status_operasional == 'tutup') {
                return back()->withErrors([
                    'status_operasional' => 'Tidak bisa tutup klinik karena ada reservasi aktif pada tanggal ini'
                ])->withInput();
            }

            DB::table('jadwal_operasional')
                ->where('id_jadwal', $id)
                ->update([
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status_operasional' => $request->status_operasional,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);

            return redirect()
                ->route('admin.jadwal_operasional.index')
                ->with('success', 'Jadwal operasional berhasil diperbarui');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal memperbarui jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Hapus jadwal operasional
     */
    public function destroy($id)
    {
        try {
            $jadwal = DB::table('jadwal_operasional')
                ->where('id_jadwal', $id)
                ->first();

            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Cek apakah ada reservasi untuk tanggal ini
            $adaReservasi = DB::table('reservasi')
                ->where('tanggal_reservasi', $jadwal->hari_tanggal)
                ->whereIn('status', ['requested', 'confirmed', 'done', 'waiting-payment', 'completed'])
                ->exists();

            if ($adaReservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus jadwal karena ada reservasi pada tanggal ini'
                ], 422);
            }

            DB::table('jadwal_operasional')
                ->where('id_jadwal', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal operasional berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate jadwal untuk beberapa hari sekaligus (bulk)
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status_operasional' => 'required|in:buka,tutup',
            'hari_kerja' => 'required|array|min:1',
            'hari_kerja.*' => 'in:1,2,3,4,5,6,0', // 0=Minggu, 1=Senin, dst
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {

            $tanggalMulai = Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $hariKerja = $request->hari_kerja; // Array: [1,2,3,4,5] untuk Senin-Jumat
            
            $inserted = 0;
            $skipped = 0;

            for ($date = $tanggalMulai; $date <= $tanggalSelesai; $date->addDay()) {
                // Cek apakah hari ini termasuk hari kerja yang dipilih
                if (!in_array($date->dayOfWeek, $hariKerja)) {
                    continue;
                }

                // Cek apakah sudah ada jadwal untuk tanggal ini
                $exists = DB::table('jadwal_operasional')
                    ->where('hari_tanggal', $date->format('Y-m-d'))
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::table('jadwal_operasional')->insert([
                    'hari_tanggal' => $date->format('Y-m-d'),
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'status_operasional' => $request->status_operasional,
                    'keterangan' => $request->keterangan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $inserted++;
            }


            $message = "Berhasil menambahkan {$inserted} jadwal.";
            if ($skipped > 0) {
                $message .= " {$skipped} tanggal dilewati karena sudah ada jadwal.";
            }

            return redirect()
                ->route('admin.jadwal_operasional.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Gagal menambahkan jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * API: Get available time slots untuk user saat booking
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_treatment' => 'required|exists:treatment,id_treatment'
        ]);

        try {
            // Ambil durasi treatment
            $treatment = DB::table('treatment')
                ->where('id_treatment', $request->id_treatment)
                ->where('deleted_at', null)
                ->first(['durasi']);

            if (!$treatment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Treatment tidak ditemukan'
                ], 404);
            }

            // Generate slots menggunakan function
            $result = DB::selectOne(
                'SELECT GenerateTimeSlots(?, ?) as slots',
                [$request->tanggal, $treatment->durasi]
            );

            $slots = json_decode($result->slots, true);

            return response()->json([
                'success' => true,
                'slots' => $slots ?? [],
                'durasi' => $treatment->durasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil slot: ' . $e->getMessage()
            ], 500);
        }
    }
}