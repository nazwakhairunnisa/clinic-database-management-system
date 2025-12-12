<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Tampilkan list pembayaran yang menunggu
     */
    public function index(Request $request)
    {
        // Query pembayaran dengan status waiting-payment
        $query = Pembayaran::with([
            'reservasi.pasien',
            'reservasi.detailReservasi.treatment'
        ])->whereHas('reservasi', function($q) {
            $q->where('status', 'waiting-payment');
        });

        // Filter by date
        if ($request->filled('date')) {
            $query->whereHas('reservasi', function($q) use ($request) {
                $q->whereDate('tanggal_reservasi', $request->date);
            });
        }

        // Filter by search (nama pasien)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('reservasi.pasien', function($q) use ($search) {
                $q->where(DB::raw("CONCAT(nama_depan, ' ', nama_belakang)"), 'like', "%{$search}%");
            });
        }

        // Sort by tanggal terbaru
        $pembayaran = $query->orderBy('created_at', 'desc')
                           ->paginate(15)
                           ->withQueryString();

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Tampilkan form proses pembayaran
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'reservasi.pasien',
            'reservasi.detailReservasi.treatment',
            'reservasi.jadwalOperasional'
        ])->findOrFail($id);

        // Validasi: hanya bisa proses jika status waiting-payment
        if ($pembayaran->reservasi->status !== 'waiting-payment') {
            return redirect()
                ->route('admin.pembayaran.index')
                ->with('error', 'Pembayaran ini tidak dalam status waiting-payment');
        }

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Proses pembayaran (lunaskan)
     */
    public function proses(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'jumlah_bayar.required' => 'Jumlah pembayaran wajib diisi',
            'jumlah_bayar.min' => 'Jumlah pembayaran tidak valid',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            $pembayaran = Pembayaran::with('reservasi')->findOrFail($id);

            // Validasi status
            if ($pembayaran->reservasi->status !== 'waiting-payment') {
                return back()->with('error', 'Pembayaran tidak dalam status waiting-payment');
            }

            // Validasi jumlah bayar
            if ($request->jumlah_bayar < $pembayaran->total_pembayaran) {
                return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan');
            }

            // Upload bukti pembayaran jika ada
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')
                    ->store('bukti_pembayaran', 'public');
            } else {
                $buktiPath = '-'; // ✅ Default value untuk cash payment
            }

            // Panggil stored procedure
            DB::select('CALL ProsesLunaskanPembayaran(?, ?, ?)', [
            $pembayaran->id_pembayaran,
            Auth::id(),
            $buktiPath
        ]);

            // Update metode pembayaran
            $pembayaran->update([
                'metode_pembayaran' => $request->metode_pembayaran
            ]);

            // Hitung kembalian
            $kembalian = $request->jumlah_bayar - $pembayaran->total_pembayaran;

            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', "Pembayaran berhasil diproses! Kembalian: Rp " . number_format($kembalian, 0, ',', '.'));

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Riwayat pembayaran (sudah lunas)
     */
    public function riwayat(Request $request)
    {
        $query = Pembayaran::with([
            'reservasi.pasien',
            'reservasi.detailReservasi.treatment'
        ])->where('status_pembayaran', 'lunas');

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('tanggal_pembayaran', [
                $request->date_from,
                $request->date_to
            ]);
        }

        // Filter by metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('reservasi.pasien', function($q) use ($search) {
                $q->where(DB::raw("CONCAT(nama_depan, ' ', nama_belakang)"), 'like', "%{$search}%");
            });
        }

        $riwayat = $query->orderBy('tanggal_pembayaran', 'desc')
                        ->paginate(20)
                        ->withQueryString();

        // Hitung total pemasukan
        $totalPemasukan = $query->sum('total_pembayaran');

        return view('admin.pembayaran.riwayat', compact('riwayat', 'totalPemasukan'));
    }

    /**
     * Detail riwayat pembayaran
     */
    public function detailRiwayat($id)
    {
        $pembayaran = Pembayaran::with([
            'reservasi.pasien',
            'reservasi.detailReservasi.treatment',
            'reservasi.jadwalOperasional',
            'user',
            'transaksiKeuangan'
        ])->findOrFail($id);

        return view('admin.pembayaran.detail', compact('pembayaran'));
    }

    /**
     * Print struk pembayaran
     */
    public function printStruk($id)
    {
        $pembayaran = Pembayaran::with([
            'reservasi.pasien',
            'reservasi.detailReservasi.treatment',
            'user'
        ])->findOrFail($id);

        return view('admin.pembayaran.struk', compact('pembayaran'));
    }

    /**
     * Batalkan pembayaran (jika ada kesalahan)
     */
    public function batal($id)
    {
        try {
            $pembayaran = Pembayaran::with('reservasi')->findOrFail($id);

            // Validasi: hanya bisa batalkan jika belum lunas
            if ($pembayaran->status_pembayaran === 'lunas') {
                return back()->with('error', 'Tidak bisa membatalkan pembayaran yang sudah lunas');
            }

            // Kembalikan status reservasi ke done
            $pembayaran->reservasi->update([
                'status' => 'done'
            ]);

            // Log activity
            DB::table('log_activity')->insert([
                'id_user' => Auth::id(),
                'activity' => "Membatalkan pembayaran ID {$pembayaran->id_pembayaran}",
                'created_at' => now()
            ]);

            return redirect()
                ->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pembayaran: ' . $e->getMessage());
        }
    }
}