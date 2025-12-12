<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('nomor_supplier', 'like', "%{$search}%");
        }

        $suppliers = $query->orderBy('nama_supplier', 'asc')->paginate(10);
        
        return view('owner.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('owner.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:supplier,nama_supplier',
            'nomor_supplier' => 'required|string|unique:supplier,nomor_supplier',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'nama_supplier.unique' => 'Nama supplier sudah terdaftar',
            'nomor_supplier.required' => 'Nomor supplier wajib diisi',
            'nomor_supplier.unique' => 'Nomor supplier sudah terdaftar',
        ]);

        try {
            

            Supplier::create($validated);

            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menambahkan supplier baru: ' . $validated['nama_supplier'],
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.supplier.index')
                ->with('success', 'Supplier berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Handle error dari database trigger
            if (strpos($e->getMessage(), 'Nomor supplier tidak valid') !== false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Format nomor tidak valid. Gunakan format Indonesia (+62 / 08...)');
            }
            
            \Log::error('Error creating supplier: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('owner.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:supplier,nama_supplier,' . $id . ',id_supplier',
            'nomor_supplier' => 'required|string|unique:supplier,nomor_supplier,' . $id . ',id_supplier',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'nama_supplier.unique' => 'Nama supplier sudah terdaftar',
            'nomor_supplier.required' => 'Nomor supplier wajib diisi',
            'nomor_supplier.unique' => 'Nomor supplier sudah terdaftar',
        ]);

        try {
            

            $supplier->update($validated);

            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Mengupdate data supplier: ' . $validated['nama_supplier'],
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.supplier.index')
                ->with('success', 'Data supplier berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Handle error dari database trigger
            if (strpos($e->getMessage(), 'Nomor supplier tidak valid') !== false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Format nomor tidak valid. Gunakan format Indonesia (+62 / 08...)');
            }
            
            \Log::error('Error updating supplier: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate supplier: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            

            $supplier = Supplier::findOrFail($id);
            
            if ($supplier->pembelianObat()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus supplier yang sudah memiliki riwayat pembelian!');
            }

            $namaSupplier = $supplier->nama_supplier;
            $supplier->delete();

            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menghapus supplier: ' . $namaSupplier . ' (ID: ' . $id . ')',
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.supplier.index')
                ->with('success', 'Supplier berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting supplier: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }

    /**
     * Export daftar supplier ke PDF
     */
    public function export(Request $request)
    {
        try {
            $query = Supplier::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_supplier', 'like', "%{$search}%")
                    ->orWhere('nomor_supplier', 'like', "%{$search}%");
            }

            $suppliers = $query->orderBy('nama_supplier', 'asc')->get();

            $pdf = Pdf::loadView('owner.supplier.export-pdf', [
                'suppliers'      => $suppliers,
                'tanggal_export' => now()->format('d F Y'),
                'total_supplier' => $suppliers->count()
            ]);

            $pdf->setPaper('a4', 'portrait'); // Portrait lebih cocok karena kolom sedikit

            $filename = 'daftar_supplier_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error export supplier: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export data supplier: ' . $e->getMessage());
        }
    }
}