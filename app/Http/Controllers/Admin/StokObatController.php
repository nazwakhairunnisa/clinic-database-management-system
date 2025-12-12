<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StokObatController extends Controller
{
    public function index(Request $request)
    {
        $query = StokObat::with(['pembelianTerakhir.supplier']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $stokObat = $query->orderBy('stok_terkini', 'asc')
                         ->orderBy('nama_obat', 'asc')
                         ->paginate(10);
        
        return view('admin.stok-obat.index', compact('stokObat'));
    }

    public function create()
    {
        return view('admin.stok-obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:stok_obat,nama_obat',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'stok_awal' => 'required|integer|min:0',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi',
            'nama_obat.unique' => 'Nama obat sudah terdaftar',
            'satuan.required' => 'Satuan wajib diisi',
            'stok_awal.required' => 'Stok awal wajib diisi',
            'stok_awal.min' => 'Stok awal minimal 0',
        ]);

        try {
            

            $validated['stok_terkini'] = $validated['stok_awal'];
            $validated['tanggal_update'] = now();

            StokObat::create($validated);

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menambahkan obat baru: ' . $validated['nama_obat'],
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('admin.stok-obat.index')
                ->with('success', 'Obat berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating stok obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan obat: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $obat = StokObat::findOrFail($id);
        return view('admin.stok-obat.edit', compact('obat'));
    }

    public function update(Request $request, $id)
    {
        $obat = StokObat::findOrFail($id);
        
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:stok_obat,nama_obat,' . $id . ',id_obat',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'stok_terkini' => 'required|integer|min:0',  // Tambahkan validasi untuk stok_terkini
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi',
            'nama_obat.unique' => 'Nama obat sudah terdaftar',
            'satuan.required' => 'Satuan wajib diisi',
            'stok_terkini.required' => 'Stok terkini wajib diisi',
            'stok_terkini.min' => 'Stok terkini minimal 0',
        ]);

        try {
            

            $validated['tanggal_update'] = now();
            $stokLama = $obat->stok_terkini;  // Simpan stok lama untuk logging

            $obat->update($validated);

            // Log activity umum
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Mengupdate data obat: ' . $validated['nama_obat'],
                    'created_at' => now()
                ]);

                // Log khusus jika stok berubah
                if ($stokLama != $validated['stok_terkini']) {
                    DB::table('log_activity')->insert([
                        'id_user' => Auth::id(),
                        'activity' => 'Mengubah stok obat "' . $validated['nama_obat'] . '" dari ' . $stokLama . ' menjadi ' . $validated['stok_terkini'],
                        'created_at' => now()
                    ]);
                }
            }

            

            return redirect()
                ->route('admin.stok-obat.index')
                ->with('success', 'Data obat berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating stok obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate obat: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            

            $obat = StokObat::findOrFail($id);
            
            // Cek apakah ada pembelian terkait
            if ($obat->pembelian()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus obat yang sudah memiliki riwayat pembelian!');
            }

            $namaObat = $obat->nama_obat;
            $obat->delete();

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menghapus obat: ' . $namaObat . ' (ID: ' . $id . ')',
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('admin.stok-obat.index')
                ->with('success', 'Obat berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting stok obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $query = StokObat::query();

            // Filter search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            }

            $stokObat = $query->orderBy('stok_terkini', 'asc')
                            ->orderBy('nama_obat', 'asc')
                            ->get();

            $obat_habis = $stokObat->where('status_stok', 'Habis')->count();

            $pdf = Pdf::loadView('admin.stok-obat.export-pdf', [
                'stokObat'       => $stokObat,
                'tanggal_export' => now()->format('d F Y'),
                'total_obat'     => $stokObat->count(),
                'obat_habis'     => $obat_habis
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'daftar_stok_obat_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error export stok obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export data stok obat: ' . $e->getMessage());
        }
    }
}