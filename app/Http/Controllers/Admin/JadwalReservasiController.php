<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with([
            'pasien:id_pasien,nama_depan,nama_belakang,no_telepon',
            'detailReservasi.treatment:id_treatment,nama_treatment',
            'pembayaran:id_pembayaran,id_reservasi,status_pembayaran'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pasien', fn($q) => $q->whereRaw("CONCAT(nama_depan,' ',nama_belakang) LIKE ?", "%$search%")
                                                 ->orWhere('id_pasien', 'like', "%$search%"))
                  ->orWhereHas('detailReservasi.treatment', fn($q) => $q->where('nama_treatment', 'like', "%$search%"));
            });
        }

        if ($request->filled('treatment') && $request->treatment != 'all') {
            $query->whereHas('detailReservasi', fn($q) => $q->where('id_treatment', $request->treatment));
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal_reservasi', $request->date);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $map = ['completed'=>'done','in-progress'=>'confirmed','scheduled'=>'requested','cancelled'=>'cancelled'];
            $query->where('status', $map[$request->status] ?? $request->status);
        }

        $statusOrder = "CASE 
            WHEN status = 'requested' THEN 0
            WHEN status = 'confirmed' THEN 1
            WHEN status = 'waiting-payment' THEN 2
            WHEN status = 'done' THEN 3
            WHEN status = 'completed' THEN 4
            WHEN status = 'cancelled' THEN 5
            ELSE 6 END";

        // Urutkan berdasarkan tanggal (descending) dulu, lalu prioritaskan status di dalam setiap tanggal
        $reservasi = $query->orderBy('tanggal_reservasi', 'desc')
                           ->orderByRaw($statusOrder)
                           ->paginate(10)
                           ->withQueryString();

        $treatments = Treatment::active()->orderBy('nama_treatment')->get(['id_treatment','nama_treatment']);

        return view('admin.jadwal_reservasi', compact('reservasi', 'treatments'));
    }

    public function show($id)
    {
        $reservasi = Reservasi::with([
            'pasien:id_pasien,nama_depan,nama_belakang,no_telepon,tanggal_lahir,jenis_kelamin,alamat',
            'user:id_user,username,role',
            'jadwalOperasional:id_jadwal,hari_tanggal,jam_mulai,jam_selesai,status_operasional',
            'detailReservasi' => function($query) {
                $query->with('treatment:id_treatment,nama_treatment,deskripsi,harga,durasi,foto_treatment')
                    ->whereNull('deleted_at');
            },
            'pembayaran:id_pembayaran,id_reservasi,tanggal_pembayaran,total_pembayaran,metode_pembayaran,status_pembayaran,bukti_pembayaran'
        ])->findOrFail($id);

        // Hitung durasi total treatment
        $totalDurasi = $reservasi->detailReservasi->sum(function($detail) {
            return $detail->treatment->durasi * $detail->quantity;
        });

        // Hitung jam selesai estimasi
        $jamSelesai = \Carbon\Carbon::parse($reservasi->jam_reservasi)
                        ->addMinutes($totalDurasi);

        // Cek apakah ada promo yang digunakan
        $adaPromo = $reservasi->detailReservasi->filter(function($detail) {
            return $detail->harga_saat_reservasi < $detail->treatment->harga;
        })->count() > 0;

        // Hitung total hemat jika ada promo
        $totalHemat = $reservasi->detailReservasi->sum(function($detail) {
            return ($detail->treatment->harga - $detail->harga_saat_reservasi) * $detail->quantity;
        });

        return view('admin.detail_reservasi', compact(
            'reservasi', 
            'totalDurasi', 
            'jamSelesai', 
            'adaPromo', 
            'totalHemat'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|in:confirmed,cancelled,done',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);

            // ✅ Handle Konfirmasi (requested → confirmed)
            if ($request->status === 'confirmed' && $reservasi->status === 'requested') {
                DB::statement('CALL KonfirmasiReservasi(?, ?)', [$id, auth()->id()]);
            }
            // ✅ Handle Pembatalan
            elseif ($request->status === 'cancelled') {
                $alasan = $request->keterangan ?? 'Dibatalkan oleh admin';
                DB::statement('CALL BatalkanReservasi(?, ?, ?)', [$id, $alasan, auth()->id()]);
            }
            // ✅ Handle Treatment Selesai (confirmed → done → waiting-payment)
            elseif ($request->status === 'done' && $reservasi->status === 'confirmed') {
                // Update ke done dulu
                $reservasi->update([
                    'status'     => 'done',
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);

                // ✅ LANGSUNG UBAH KE WAITING-PAYMENT (AUTO)
                $reservasi->update([
                    'status'     => 'waiting-payment',
                    'updated_at' => now()
                ]);

                // Log activity
                \Log::info('Auto changed status', [
                    'id_reservasi' => $id,
                    'from' => 'done',
                    'to' => 'waiting-payment'
                ]);
            }
            // ✅ Handle status lainnya (fallback)
            else {
                $reservasi->update([
                    'status'     => $request->status,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);
            }


            return response()->json([
                'success' => true,
                'message' => 'Status reservasi berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Update Status Error', [
                'id_reservasi' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            // Query dengan filter yang sama seperti index
            $query = Reservasi::with([
                'pasien',
                'detailReservasi.treatment',
                'pembayaran'
            ]);

            // Apply filters (sama seperti di index)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('pasien', function($subQ) use ($search) {
                        $subQ->where(DB::raw("CONCAT(nama_depan, ' ', nama_belakang)"), 'like', "%{$search}%");
                    });
                });
            }

            if ($request->filled('treatment') && $request->treatment != 'all') {
                $query->whereHas('detailReservasi', function($q) use ($request) {
                    $q->where('id_treatment', $request->treatment);
                });
            }

            if ($request->filled('date')) {
                $query->whereDate('tanggal_reservasi', $request->date);
            }

            if ($request->filled('status') && $request->status != 'all') {
                $statusMap = [
                    'completed' => 'done',
                    'in-progress' => 'confirmed',
                    'scheduled' => 'requested',
                    'cancelled' => 'cancelled'
                ];
                $query->where('status', $statusMap[$request->status] ?? $request->status);
            }

            $statusOrder = "CASE 
                WHEN status = 'requested' THEN 0
                WHEN status = 'confirmed' THEN 1
                WHEN status = 'waiting-payment' THEN 2
                WHEN status = 'done' THEN 3
                WHEN status = 'completed' THEN 4
                WHEN status = 'cancelled' THEN 5
                ELSE 6 END";

            // Saat export: tanggal desc dulu, lalu status prioritas
            $reservasi = $query->orderBy('tanggal_reservasi', 'desc')
                            ->orderByRaw($statusOrder)
                            ->get();

            // Prepare filter info untuk ditampilkan di PDF
            $filterInfo = [];
            if ($request->filled('date')) {
                $filterInfo[] = 'Tanggal: ' . \Carbon\Carbon::parse($request->date)->format('d M Y');
            }
            if ($request->filled('status') && $request->status != 'all') {
                $filterInfo[] = 'Status: ' . ucfirst($request->status);
            }
            if ($request->filled('treatment') && $request->treatment != 'all') {
                $treatment = \App\Models\Treatment::find($request->treatment);
                if ($treatment) {
                    $filterInfo[] = 'Treatment: ' . $treatment->nama_treatment;
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('admin.export-pdf', [
                'reservasi' => $reservasi,
                'tanggal_export' => now()->format('d F Y'),
                'total_reservasi' => $reservasi->count(),
                'filter_info' => $filterInfo
            ]);

            // Set paper size landscape untuk muat semua kolom
            $pdf->setPaper('a4', 'landscape');

            $filename = 'jadwal_reservasi_' . date('Y-m-d_His') . '.pdf';

            // Download PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error export reservasi: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal export data reservasi: ' . $e->getMessage());
        }
    }
}