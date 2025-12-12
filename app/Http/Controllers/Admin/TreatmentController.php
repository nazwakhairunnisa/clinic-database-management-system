<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TreatmentController extends Controller
{
    /**
     * Display a listing of treatments
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

        // Order by nama treatment
        $query->orderBy('nama_treatment', 'asc');

        // Paginate
        $treatments = $query->paginate(10);

        return view('admin.treatment.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create()
    {
        return view('admin.treatment.create');
    }

    /**
     * Store a newly created treatment.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_treatment' => 'required|string|max:255|unique:treatment,nama_treatment',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0.01',
                'durasi' => 'required|integer|min:1',
                'foto_treatment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
                'foto_treatment.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'foto_treatment.max' => 'Ukuran gambar maksimal 2MB',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        try {
            

            // Handle file upload
            if ($request->hasFile('foto_treatment')) {
                $validated['foto_treatment'] = $request->file('foto_treatment')
                    ->store('treatments', 'public');
            }

            // Create treatment
            $treatment = Treatment::create($validated);

            // Log activity - hanya jika user sudah login
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menambahkan treatment baru: ' . $validated['nama_treatment'] . 
                                    ' dengan harga Rp ' . number_format($validated['harga'], 0, ',', '.'),
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('admin.treatment.index')
                ->with('success', 'Treatment berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if exists
            if (isset($validated['foto_treatment'])) {
                Storage::disk('public')->delete($validated['foto_treatment']);
            }

            \Log::error('Error creating treatment: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan treatment: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the treatment.
     */
    public function edit($id)
    {
        $treatment = Treatment::findOrFail($id);
        
        return view('admin.treatment.edit', compact('treatment'));
    }

    /**
     * Update the specified treatment.
     * Menggunakan stored procedure UpdateTreatment
     */
    public function update(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);

        $validated = $request->validate([
            'nama_treatment' => 'required|string|max:255|unique:treatment,nama_treatment,' . $id . ',id_treatment',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0.01',
            'durasi' => 'required|integer|min:1',
            'foto_treatment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            'foto_treatment.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'foto_treatment.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            // Handle file upload jika ada
            $fotoPath = $treatment->foto_treatment;
            
            if ($request->hasFile('foto_treatment')) {
                // Delete old image if exists
                if ($treatment->foto_treatment && Storage::disk('public')->exists($treatment->foto_treatment)) {
                    Storage::disk('public')->delete($treatment->foto_treatment);
                }
                
                // Upload new image
                $fotoPath = $request->file('foto_treatment')->store('treatments', 'public');
            }

            // Prepare data for stored procedure
            $dataForSP = [
                'nama_treatment' => $validated['nama_treatment'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga' => $validated['harga'],
                'durasi' => $validated['durasi'],
                'foto_treatment' => $fotoPath
            ];

            // Call stored procedure UpdateTreatment
            // SP ini akan handle validasi promo, logging, dll
            Treatment::updateWithProcedure($id, $dataForSP);

            return redirect()
                ->route('admin.treatment.index')
                ->with('success', 'Treatment berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete newly uploaded file if error occurs
            if ($request->hasFile('foto_treatment') && isset($fotoPath) && $fotoPath != $treatment->foto_treatment) {
                Storage::disk('public')->delete($fotoPath);
            }

            \Log::error('Error updating treatment: ' . $e->getMessage());

            // Check if error is from stored procedure validation
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'Tidak bisa menaikkan harga')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Tidak bisa menaikkan harga karena treatment sedang promo!');
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate treatment: ' . $errorMessage);
        }
    }

    /**
     * Remove the specified treatment (soft delete).
     */
    public function destroy($id)
    {
        try {
            

            $treatment = Treatment::findOrFail($id);

            // Cek apakah ada reservasi aktif dengan treatment ini
            $activeReservations = DB::table('detail_reservasi')
                ->join('reservasi', 'detail_reservasi.id_reservasi', '=', 'reservasi.id_reservasi')
                ->where('detail_reservasi.id_treatment', $id)
                ->whereIn('reservasi.status', ['requested', 'confirmed'])
                ->whereNull('detail_reservasi.deleted_at')
                ->count();

            if ($activeReservations > 0) {
                return redirect()
                    ->route('admin.treatment.index')
                    ->with('error', 'Tidak bisa menghapus treatment yang sedang ada di reservasi aktif!');
            }

            // Delete image if exists
            if ($treatment->foto_treatment && Storage::disk('public')->exists($treatment->foto_treatment)) {
                Storage::disk('public')->delete($treatment->foto_treatment);
            }

            // Soft delete
            $treatment->delete();

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menghapus treatment: ' . $treatment->nama_treatment . ' (ID: ' . $id . ')',
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('admin.treatment')
                ->with('success', 'Treatment berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error deleting treatment: ' . $e->getMessage());

            return redirect()
                ->route('admin.treatment')
                ->with('error', 'Gagal menghapus treatment: ' . $e->getMessage());
        }
    }

    /**
     * Export treatments to PDF.
     */
    public function export(Request $request)
    {
        try {
            $query = Treatment::query();

            // Apply search filter if exists (sama seperti di index)
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_treatment', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            // Order by nama treatment
            $treatments = $query->orderBy('nama_treatment', 'asc')->get();

            // Calculate summary data
            $totalTreatments = $treatments->count();
            $avgHarga = $treatments->avg('harga');
            $totalHarga = $treatments->sum('harga');
            $avgDurasi = $treatments->avg('durasi');

            // Generate PDF
            $pdf = Pdf::loadView('admin.treatment.export-pdf', [
                'treatments' => $treatments,
                'tanggal_export' => now()->format('d F Y'),
                'total_treatments' => $totalTreatments,
                'avg_harga' => $avgHarga,
                'total_harga' => $totalHarga,
                'avg_durasi' => $avgDurasi,
                'search_query' => $request->search
            ]);

            // Set paper size portrait (karena kolom tidak terlalu banyak)
            $pdf->setPaper('a4', 'portrait');

            $filename = 'daftar_treatment_' . date('Y-m-d_His') . '.pdf';

            // Download PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error exporting treatments: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal export data treatment: ' . $e->getMessage());
        }
    }
}