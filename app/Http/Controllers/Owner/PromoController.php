<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PromoController extends Controller
{
    /**
     * Display a listing of promos.
     */
    public function index(Request $request)
    {
        $query = Promo::with('treatment');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_promo', 'like', "%{$search}%")
                  ->orWhereHas('treatment', function($q2) use ($search) {
                      $q2->where('nama_treatment', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date jika ada
        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $query->where('periode_mulai', '<=', $date)
                  ->where('periode_selesai', '>=', $date);
        }

        // Order by periode_mulai descending
        $query->orderBy('periode_mulai', 'desc');

        // Paginate
        $promos = $query->paginate(10);

        return view('owner.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new promo.
     */
    public function create()
    {
        // Ambil semua treatment yang aktif untuk dropdown
        $treatments = Treatment::orderBy('nama_treatment')->get();
        
        return view('owner.promo.create', compact('treatments'));
    }

    /**
     * Store a newly created promo.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_treatment' => 'required|exists:treatment,id_treatment',
                'nama_promo' => 'required|string|max:255',
                'harga_promo' => 'required|numeric|min:0.01',
                'periode_mulai' => 'required|date',
                'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
                'gambar_promo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ], [
                'id_treatment.required' => 'Treatment wajib dipilih',
                'id_treatment.exists' => 'Treatment tidak ditemukan',
                'nama_promo.required' => 'Nama promo wajib diisi',
                'harga_promo.required' => 'Harga promo wajib diisi',
                'harga_promo.numeric' => 'Harga promo harus berupa angka',
                'harga_promo.min' => 'Harga promo minimal Rp 0.01',
                'periode_mulai.required' => 'Periode mulai wajib diisi',
                'periode_selesai.required' => 'Periode selesai wajib diisi',
                'periode_selesai.after_or_equal' => 'Periode selesai harus setelah atau sama dengan periode mulai',
                'gambar_promo.required' => 'Gambar promo wajib diupload',
                'gambar_promo.image' => 'File harus berupa gambar',
                'gambar_promo.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'gambar_promo.max' => 'Ukuran gambar maksimal 2MB',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        try {
            

            // Validasi: Harga promo harus lebih kecil dari harga normal
            $treatment = Treatment::findOrFail($validated['id_treatment']);
            if ($validated['harga_promo'] >= $treatment->harga) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Harga promo harus lebih kecil dari harga normal treatment (Rp ' . number_format($treatment->harga, 0, ',', '.') . ')');
            }

            // Validasi: Cek apakah ada promo yang overlap untuk treatment yang sama
            $overlap = Promo::where('id_treatment', $validated['id_treatment'])
                ->where(function($q) use ($validated) {
                    $q->whereBetween('periode_mulai', [$validated['periode_mulai'], $validated['periode_selesai']])
                      ->orWhereBetween('periode_selesai', [$validated['periode_mulai'], $validated['periode_selesai']])
                      ->orWhere(function($q2) use ($validated) {
                          $q2->where('periode_mulai', '<=', $validated['periode_mulai'])
                             ->where('periode_selesai', '>=', $validated['periode_selesai']);
                      });
                })
                ->whereNull('deleted_at')
                ->exists();

            if ($overlap) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Sudah ada promo lain untuk treatment ini pada periode tersebut!');
            }

            // Handle file upload
            $validated['gambar_promo'] = $request->file('gambar_promo')
                ->store('promos', 'public');

            // Create promo
            $promo = Promo::create($validated);

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menambahkan promo: ' . $validated['nama_promo'] . 
                                 ' untuk treatment ' . $treatment->nama_treatment .
                                 ' dengan harga Rp ' . number_format($validated['harga_promo'], 0, ',', '.'),
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.promo.index')
                ->with('success', 'Promo berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file if exists
            if (isset($validated['gambar_promo'])) {
                Storage::disk('public')->delete($validated['gambar_promo']);
            }

            \Log::error('Error creating promo: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan promo: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the promo.
     */
    public function edit($id)
    {
        $promo = Promo::with('treatment')->findOrFail($id);
        $treatments = Treatment::orderBy('nama_treatment')->get();
        
        return view('owner.promo.edit', compact('promo', 'treatments'));
    }

    /**
     * Update the specified promo.
     */
    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $validated = $request->validate([
            'id_treatment' => 'required|exists:treatment,id_treatment',
            'nama_promo' => 'required|string|max:255',
            'harga_promo' => 'required|numeric|min:0.01',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'gambar_promo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'id_treatment.required' => 'Treatment wajib dipilih',
            'nama_promo.required' => 'Nama promo wajib diisi',
            'harga_promo.required' => 'Harga promo wajib diisi',
            'harga_promo.numeric' => 'Harga promo harus berupa angka',
            'harga_promo.min' => 'Harga promo minimal Rp 0.01',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_selesai.required' => 'Periode selesai wajib diisi',
            'periode_selesai.after_or_equal' => 'Periode selesai harus setelah periode mulai',
            'gambar_promo.image' => 'File harus berupa gambar',
            'gambar_promo.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'gambar_promo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            

            // Validasi harga promo
            $treatment = Treatment::findOrFail($validated['id_treatment']);
            if ($validated['harga_promo'] >= $treatment->harga) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Harga promo harus lebih kecil dari harga normal treatment (Rp ' . number_format($treatment->harga, 0, ',', '.') . ')');
            }

            // Validasi overlap (exclude promo yang sedang di-edit)
            $overlap = Promo::where('id_treatment', $validated['id_treatment'])
                ->where('promo_id', '!=', $id)
                ->where(function($q) use ($validated) {
                    $q->whereBetween('periode_mulai', [$validated['periode_mulai'], $validated['periode_selesai']])
                      ->orWhereBetween('periode_selesai', [$validated['periode_mulai'], $validated['periode_selesai']])
                      ->orWhere(function($q2) use ($validated) {
                          $q2->where('periode_mulai', '<=', $validated['periode_mulai'])
                             ->where('periode_selesai', '>=', $validated['periode_selesai']);
                      });
                })
                ->whereNull('deleted_at')
                ->exists();

            if ($overlap) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Sudah ada promo lain untuk treatment ini pada periode tersebut!');
            }

            // Handle file upload jika ada
            if ($request->hasFile('gambar_promo')) {
                // Delete old image
                if ($promo->gambar_promo && Storage::disk('public')->exists($promo->gambar_promo)) {
                    Storage::disk('public')->delete($promo->gambar_promo);
                }
                
                // Upload new image
                $validated['gambar_promo'] = $request->file('gambar_promo')->store('promos', 'public');
            } else {
                unset($validated['gambar_promo']);
            }

            // Update promo
            $promo->update($validated);

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Mengupdate promo: ' . $validated['nama_promo'],
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.promo.index')
                ->with('success', 'Promo berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->hasFile('gambar_promo') && isset($validated['gambar_promo'])) {
                Storage::disk('public')->delete($validated['gambar_promo']);
            }

            \Log::error('Error updating promo: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate promo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified promo.
     */
    public function destroy($id)
    {
        try {
            

            $promo = Promo::findOrFail($id);

            // Delete image if exists
            if ($promo->gambar_promo && Storage::disk('public')->exists($promo->gambar_promo)) {
                Storage::disk('public')->delete($promo->gambar_promo);
            }

            // Soft delete
            $promo->delete();

            // Log activity
            if (Auth::check()) {
                DB::table('log_activity')->insert([
                    'id_user' => Auth::id(),
                    'activity' => 'Menghapus promo: ' . $promo->nama_promo . ' (ID: ' . $id . ')',
                    'created_at' => now()
                ]);
            }

            

            return redirect()
                ->route('owner.promo.index')
                ->with('success', 'Promo berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error deleting promo: ' . $e->getMessage());

            return redirect()
                ->route('owner.promo.index')
                ->with('error', 'Gagal menghapus promo: ' . $e->getMessage());
        }
    }

    /**
     * Export promos to PDF.
     */
    public function export(Request $request)
    {
        try {
            $query = Promo::with('treatment');

            // Apply search filter if exists
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_promo', 'like', "%{$search}%")
                    ->orWhereHas('treatment', function($q2) use ($search) {
                        $q2->where('nama_treatment', 'like', "%{$search}%");
                    });
                });
            }

            // Filter by date jika ada
            if ($request->has('date') && $request->date != '') {
                $date = $request->date;
                $query->where('periode_mulai', '<=', $date)
                    ->where('periode_selesai', '>=', $date);
            }

            // Order by periode_mulai descending
            $promos = $query->orderBy('periode_mulai', 'desc')->get();

            // Calculate summary data
            $totalPromos = $promos->count();
            $activePromos = $promos->filter(function($promo) {
                return $promo->isActive();
            })->count();
            $totalHemat = $promos->sum('hemat');
            $avgDiskon = $promos->avg('persen_diskon');

            // Generate PDF
            $pdf = Pdf::loadView('owner.promo.export-pdf', [
                'promos' => $promos,
                'tanggal_export' => now()->format('d F Y'),
                'total_promos' => $totalPromos,
                'active_promos' => $activePromos,
                'total_hemat' => $totalHemat,
                'avg_diskon' => $avgDiskon,
                'search_query' => $request->search,
                'date_filter' => $request->date
            ]);

            // Set paper size landscape (karena banyak kolom)
            $pdf->setPaper('a4', 'landscape');

            $filename = 'daftar_promo_' . date('Y-m-d_His') . '.pdf';

            // Download PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error exporting promos: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal export data promo: ' . $e->getMessage());
        }
    }
}