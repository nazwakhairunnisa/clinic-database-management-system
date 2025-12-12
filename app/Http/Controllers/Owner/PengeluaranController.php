<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of pengeluaran.
     */
    public function index(Request $request)
    {
        $query = TransaksiKeuangan::pengeluaran()
            ->with('user')
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

        $pengeluaran = $query->paginate(10);

        return view('owner.pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new pengeluaran.
     */
    public function create()
    {
        return view('owner.pengeluaran.create');
    }

    /**
     * Store a newly created pengeluaran in storage.
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
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi',
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
                'jenis_transaksi' => 'pengeluaran',
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            

            return redirect()
                ->route('owner.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified pengeluaran.
     */
    public function edit($id)
    {
        $pengeluaran = TransaksiKeuangan::pengeluaran()
            ->findOrFail($id);

        return view('owner.pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Update the specified pengeluaran in storage.
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
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
            'tanggal_transaksi.date' => 'Format tanggal tidak valid',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
        ]);

        try {
            

            $pengeluaran = TransaksiKeuangan::pengeluaran()->findOrFail($id);

            // Cek apakah transaksi ini terkait dengan pembelian obat
            if ($pengeluaran->id_pembelian_obat) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat mengubah pengeluaran yang terkait dengan pembelian obat');
            }

            $pengeluaran->update([
                'nama_transaksi' => $validated['nama_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            

            return redirect()
                ->route('owner.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pengeluaran from storage.
     */
    public function destroy($id)
    {
        try {
            

            $pengeluaran = TransaksiKeuangan::pengeluaran()->findOrFail($id);

            // Cek apakah transaksi ini terkait dengan pembelian obat
            if ($pengeluaran->id_pembelian_obat) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus pengeluaran yang terkait dengan pembelian obat');
            }

            $pengeluaran->delete();

            

            return redirect()
                ->route('owner.pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Export pengeluaran ke PDF
     */
    public function export(Request $request)
    {
        try {
            $query = TransaksiKeuangan::pengeluaran()
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

            $pengeluaran = $query->get();
            $total_pengeluaran = $pengeluaran->sum('jumlah');

            $pdf = Pdf::loadView('owner.pengeluaran.export-pdf', [
                'pengeluaran'       => $pengeluaran,
                'tanggal_export'    => now()->format('d F Y'),
                'total_transaksi'   => $pengeluaran->count(),
                'total_pengeluaran' => $total_pengeluaran
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'laporan_pengeluaran_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error export pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export data pengeluaran: ' . $e->getMessage());
        }
    }
}