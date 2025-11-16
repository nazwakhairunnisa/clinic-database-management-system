<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PembelianObat;
use App\Models\StokObat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianObatController extends Controller
{
    // List pembelian obat
    public function index()
    {
        $pembelian = PembelianObat::with(['obat', 'supplier'])
            ->orderBy('tanggal_beli', 'desc')
            ->get();
        
        return view('layouts.owner.obat', compact('pembelian'));
    }

    // Form tambah pembelian
    public function create()
    {
        $obatList = StokObat::all();
        $suppliers = Supplier::all();
        
        return view('layouts.owner.add_obat', compact('obatList', 'suppliers'));
    }

    // Simpan pembelian baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_obat' => 'required|exists:stok_obat,id_obat',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal_beli' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum,lunas',
            'tanggal_jatuh_tempo' => 'required_if:status_pembayaran,belum|nullable|date|after_or_equal:tanggal_beli'
        ]);

        DB::beginTransaction();
        try {
            // Simpan pembelian
            $pembelian = PembelianObat::create($validated);

            // Update stok obat (otomatis nambah stok)
            $obat = StokObat::findOrFail($validated['id_obat']);
            $obat->stok_terkini += $validated['jumlah'];
            $obat->tanggal_update = now();
            $obat->save();

            // TODO: Nanti bisa auto create transaksi keuangan juga
            // if ($validated['status_pembayaran'] === 'lunas') {
            //     TransaksiKeuangan::create([...]);
            // }

            DB::commit();
            return redirect()->route('owner.obat')->with('success', 'Pembelian obat berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Form edit pembelian
    public function edit($id)
    {
        $pembelian = PembelianObat::with(['obat', 'supplier'])->findOrFail($id);
        $obatList = StokObat::all();
        $suppliers = Supplier::all();
        
        return view('layouts.owner.edit_obat', compact('pembelian', 'obatList', 'suppliers'));
    }

    // Update pembelian
    public function update(Request $request, $id)
    {
        $pembelian = PembelianObat::findOrFail($id);
        
        $validated = $request->validate([
            'id_obat' => 'required|exists:stok_obat,id_obat',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal_beli' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum,lunas',
            'tanggal_jatuh_tempo' => 'required_if:status_pembayaran,belum|nullable|date|after_or_equal:tanggal_beli'
        ]);

        DB::beginTransaction();
        try {
            // Jika jumlah berubah, update stok
            if ($pembelian->jumlah != $validated['jumlah'] || $pembelian->id_obat != $validated['id_obat']) {
                // Kembalikan stok lama
                $obatLama = StokObat::findOrFail($pembelian->id_obat);
                $obatLama->stok_terkini -= $pembelian->jumlah;
                $obatLama->save();

                // Tambah stok baru
                $obatBaru = StokObat::findOrFail($validated['id_obat']);
                $obatBaru->stok_terkini += $validated['jumlah'];
                $obatBaru->tanggal_update = now();
                $obatBaru->save();
            }

            $pembelian->update($validated);

            DB::commit();
            return redirect()->route('owner.obat')->with('success', 'Pembelian obat berhasil diupdate!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Hapus pembelian
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pembelian = PembelianObat::findOrFail($id);
            
            // Kurangi stok obat
            $obat = StokObat::findOrFail($pembelian->id_obat);
            $obat->stok_terkini -= $pembelian->jumlah;
            $obat->tanggal_update = now();
            $obat->save();

            $pembelian->delete();

            DB::commit();
            return redirect()->route('owner.obat')->with('success', 'Pembelian obat berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}