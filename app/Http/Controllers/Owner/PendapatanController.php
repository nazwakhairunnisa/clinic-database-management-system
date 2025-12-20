<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PendapatanController extends Controller
{
    /**
     * Display a listing of pendapatan.
     */
    public function index(Request $request)
    {
        $query = TransaksiKeuangan::pemasukan()
            ->with(['user', 'pembayaran.reservasi.pasien'])
            ->orderBy('tanggal_transaksi', 'desc');

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan metode pembayaran
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        $pendapatan = $query->paginate(10);

        return view('owner.pendapatan.index', compact('pendapatan'));
    }

    /**
     * Show the form for creating a new pendapatan.
     */
    public function create()
    {
        return view('owner.pendapatan.create');
    }

    /**
     * Store a newly created pendapatan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
            'keterangan' => 'nullable|string',
        ], [
            'nama_transaksi.required' => 'Nama transaksi wajib diisi',
            'jumlah.required' => 'Jumlah pendapatan wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
            'tanggal_transaksi.date' => 'Format tanggal tidak valid',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
        ]);

        try {
            

            TransaksiKeuangan::create([
                'id_user' => Auth::id(),
                'nama_transaksi' => $validated['nama_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'jenis_transaksi' => 'pemasukan',
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            

            return redirect()
                ->route('owner.pendapatan')
                ->with('success', 'Data pendapatan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pendapatan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified pendapatan.
     */
    public function edit($id)
    {
        $pendapatan = TransaksiKeuangan::pemasukan()
            ->findOrFail($id);

        return view('owner.edit_pendapatan', compact('pendapatan'));
    }

    /**
     * Update the specified pendapatan in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
            'keterangan' => 'nullable|string',
        ], [
            'nama_transaksi.required' => 'Nama transaksi wajib diisi',
            'jumlah.required' => 'Jumlah pendapatan wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
            'tanggal_transaksi.date' => 'Format tanggal tidak valid',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
        ]);

        try {
            

            $pendapatan = TransaksiKeuangan::pemasukan()->findOrFail($id);

            // Cek apakah transaksi ini terkait dengan pembayaran reservasi
            if ($pendapatan->id_pembayaran) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat mengubah pendapatan yang terkait dengan pembayaran reservasi');
            }

            $pendapatan->update([
                'nama_transaksi' => $validated['nama_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            

            return redirect()
                ->route('owner.pendapatan')
                ->with('success', 'Data pendapatan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data pendapatan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pendapatan from storage.
     */
    public function destroy($id)
    {
        try {
            

            $pendapatan = TransaksiKeuangan::pemasukan()->findOrFail($id);

            // Cek apakah transaksi ini terkait dengan pembayaran reservasi
            if ($pendapatan->id_pembayaran) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus pendapatan yang terkait dengan pembayaran reservasi');
            }

            $pendapatan->delete();

            

            return redirect()
                ->route('owner.pendapatan')
                ->with('success', 'Data pendapatan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pendapatan: ' . $e->getMessage());
        }
    }

    /**
     * Export pendapatan ke PDF
     */
    public function export(Request $request)
    {
        try {
            $query = TransaksiKeuangan::pemasukan()
                ->orderBy('tanggal_transaksi', 'desc');

            // Terapkan filter search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_transaksi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }

            // Filter metode pembayaran
            if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '') {
                $query->where('metode_pembayaran', $request->metode_pembayaran);
            }

            // Filter tanggal
            if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
                $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
            }
            if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
                $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
            }

            $pendapatan = $query->get();
            $total_pendapatan = $pendapatan->sum('jumlah');

            $pdf = Pdf::loadView('owner.pendapatan.export-pdf', [
                'pendapatan'        => $pendapatan,
                'tanggal_export'    => now()->format('d F Y'),
                'total_transaksi'   => $pendapatan->count(),
                'total_pendapatan'  => $total_pendapatan
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'laporan_pendapatan_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error export pendapatan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export data pendapatan: ' . $e->getMessage());
        }
    }
}