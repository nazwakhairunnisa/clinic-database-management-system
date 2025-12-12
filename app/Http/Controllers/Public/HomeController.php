<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\Promo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman landing utama
     */
    public function index()
    {
        // Ambil 3 treatment terbaru untuk di landing page
        $treatments = Treatment::whereNull('deleted_at')
            ->latest()
            ->take(3)
            ->get();

        // Ambil 3 promo aktif
        $promos = Promo::whereNull('deleted_at')
            ->where('periode_selesai', '>=', now())
            ->latest()
            ->take(3)
            ->get();

        return view('landing', compact('treatments', 'promos'));
    }

    /**
     * Section About (jika terpisah dari landing)
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Halaman treatments (section di landing atau terpisah)
     */
    public function treatments()
    {
        $treatments = Treatment::whereNull('deleted_at')
            ->latest()
            ->take(6)
            ->get();

        return view('treatment', compact('treatments'));
    }

    /**
     * Halaman semua treatment (dengan pagination)
     */
    public function treatmentsAll()
    {
        $treatments = Treatment::whereNull('deleted_at')
            ->latest()
            ->paginate(9);

        return view('treatment-all', compact('treatments'));
    }

    /**
     * Halaman promo (section di landing atau terpisah)
     */
    public function promo()
    {
        $promos = Promo::whereNull('deleted_at')
            ->where('periode_selesai', '>=', now())
            ->latest()
            ->take(6)
            ->get();

        return view('promo', compact('promos'));
    }

    /**
     * Halaman semua promo (dengan pagination)
     */
    public function allPromo()
    {
        $promos = Promo::whereNull('deleted_at')
            ->where('periode_selesai', '>=', now())
            ->latest()
            ->paginate(9);

        return view('allpromo', compact('promos'));
    }

    /**
     * Halaman contact
     */
    public function contact()
    {
        return view('contact');
    }
}