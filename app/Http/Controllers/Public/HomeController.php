<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\MonthlyPrice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $treatments = Treatment::with('currentPrice')->take(6)->get();
        $promo = MonthlyPrice::where('is_promo', true)
            ->with('treatment')
            ->whereYear('year', now()->year)
            ->where('month', now()->month)
            ->get();

        return view('home', compact('treatments', 'promo'));
    }

    public function about()
    {
        return view('about');
    }

    public function treatments()
    {
        $treatments = Treatment::with('currentPrice')->get();
        return view('treatment', compact('treatments'));
    }

    public function treatmentsAll()
    {
        $treatments = Treatment::with('currentPrice')->get();
        return view('treatment-all', compact('treatments'));
    }

    public function promo()
    {
        $promos = MonthlyPrice::where('is_promo', true)
            ->with('treatment')
            ->whereYear('year', now()->year)
            ->where('month', now()->month)
            ->get();
        return view('promo', compact('promos'));
    }

    public function allPromo()
    {
        $promos = MonthlyPrice::where('is_promo', true)
            ->with('treatment')
            ->get();
        return view('allpromo', compact('promos'));
    }

    public function contact()
    {
        return view('Contact');
    }
}
