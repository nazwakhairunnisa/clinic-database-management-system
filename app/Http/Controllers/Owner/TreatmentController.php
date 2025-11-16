<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TreatmentController extends Controller
{
    /**
     * Display a listing of treatments.
     */
    public function index(Request $request)
    {
        $query = Treatment::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_treatment', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $treatments = $query->latest()->paginate(10);

        return view('layouts.owner.treatment', compact('treatments'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create()
    {
        return view('layouts.owner.add_treatment');
    }

    /**
     * Store a newly created treatment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_treatment' => 'required|string|max:255|unique:treatment,nama_treatment',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|integer|min:1',
            'foto_treatment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_treatment.required' => 'Nama treatment wajib diisi',
            'nama_treatment.unique' => 'Nama treatment sudah ada',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'durasi.required' => 'Durasi wajib diisi',
            'foto_treatment.image' => 'File harus berupa gambar',
            'foto_treatment.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_treatment')) {
            $validated['foto_treatment'] = $request->file('foto_treatment')
                ->store('treatments', 'public');
        }

        Treatment::create($validated);

        return redirect()
            ->route('owner.treatment')
            ->with('success', 'Treatment berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the treatment.
     */
    public function edit($id)
    {
        $treatment = Treatment::findOrFail($id);
        return view('layouts.owner.edit_treatment', compact('treatment'));
    }

    /**
     * Update the specified treatment.
     */
    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);

        $validated = $request->validate([
        'nama_treatment' => 'required|string|max:255|unique:treatment,nama_treatment,' . $id . ',id_treatment',
        'deskripsi' => 'nullable|string',
        'harga' => 'required|numeric|min:0',
        'durasi' => 'required|integer|min:1',
        'foto_treatment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_treatment.required' => 'Nama treatment wajib diisi',
            'nama_treatment.unique' => 'Nama treatment sudah ada',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'durasi.required' => 'Durasi wajib diisi',
            'durasi.integer' => 'Durasi harus berupa angka',
            'durasi.min' => 'Durasi minimal 1 menit',
            'foto_treatment.image' => 'File harus berupa gambar',
            'foto_treatment.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_treatment.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload HANYA jika ada file baru yang di-upload
        if ($request->hasFile('foto_treatment')) {
            // Delete old image jika ada
            if ($treatment->foto_treatment && Storage::disk('public')->exists($treatment->foto_treatment)) {
                Storage::disk('public')->delete($treatment->foto_treatment);
            }
            
            // Upload gambar baru
            $validated['foto_treatment'] = $request->file('foto_treatment')->store('treatments', 'public');
        } else {
            // Jika tidak upload gambar baru, hapus dari array validated
            // Agar tidak overwrite gambar lama dengan null
            unset($validated['foto_treatment']);
        }

        $treatment->update($validated);

        return redirect()
            ->route('owner.treatment')
            ->with('success', 'Treatment berhasil diupdate!');
    }

    /**
     * Remove the specified treatment.
     */
    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);

        // Delete image if exists
        if ($treatment->foto_treatment) {
            Storage::disk('public')->delete($treatment->foto_treatment);
        }

        $treatment->delete(); // Soft delete

        return redirect()
            ->route('owner.treatment')
            ->with('success', 'Treatment berhasil dihapus!');
    }
}