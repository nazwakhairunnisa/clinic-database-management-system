<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\Promo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $treatments = Treatment::take(3)->get();
        $promos = Promo::with('treatment')
            ->whereDate('periode_mulai', '<=', now())
            ->whereDate('periode_selesai', '>=', now())
            ->take(2)
            ->get();

        return view('home', compact('treatments', 'promos'));
    }

    public function about()
    {
        return view('about');
    }

    public function treatments()
    {
        $treatments = Treatment::take(3)->get();
        return view('treatment', compact('treatments'));
    }

    public function treatmentsAll()
    {
        $treatments = Treatment::all();
        return view('treatment-all', compact('treatments'));
    }

    public function promo()
    {
        $promos = Promo::with('treatment')
            ->whereDate('periode_mulai', '<=', now())
            ->whereDate('periode_selesai', '>=', now())
            ->get();
        return view('promo', compact('promos'));
    }

    public function allPromo()
    {
        $promos = Promo::with('treatment')->get();
        return view('allpromo', compact('promos'));
    }

    public function contact()
    {
        return view('Contact');
    }
}
