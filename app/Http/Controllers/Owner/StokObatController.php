<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StokObat;
use Illuminate\Http\Request;

class StokObatController extends Controller
{
    // List stok obat
    public function index()
    {
        $stokObat = StokObat::orderBy('nama_obat')->get();
        return view('layouts.owner.stok_obat', compact('stokObat'));
    }

    // Form tambah obat baru
    public function create()
    {
        return view('layouts.owner.add_stok_obat');
    }

    // Simpan obat baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:stok_obat,nama_obat',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'stok_awal' => 'required|integer|min:0',
        ]);

        $validated['stok_terkini'] = $validated['stok_awal'];
        $validated['tanggal_update'] = now();

        StokObat::create($validated);

        return redirect()->route('owner.stok-obat')->with('success', 'Obat berhasil ditambahkan!');
    }

    // Form edit obat
    public function edit($id)
    {
        $obat = StokObat::findOrFail($id);
        return view('layouts.owner.edit_stok_obat', compact('obat'));
    }

    // Update obat
    public function update(Request $request, $id)
    {
        $obat = StokObat::findOrFail($id);
        
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:stok_obat,nama_obat,' . $id . ',id_obat',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'stok_terkini' => 'required|integer|min:0',
        ]);

        $validated['tanggal_update'] = now();

        $obat->update($validated);

        return redirect()->route('owner.stok-obat')->with('success', 'Data obat berhasil diupdate!');
    }

    // Hapus obat
    public function destroy($id)
    {
        $obat = StokObat::findOrFail($id);
        
        // Cek apakah ada pembelian terkait
        if ($obat->pembelian()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus obat yang sudah memiliki riwayat pembelian!');
        }

        $obat->delete();

        return redirect()->route('owner.stok-obat')->with('success', 'Obat berhasil dihapus!');
    }
}