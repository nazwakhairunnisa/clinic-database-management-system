<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create()
{
    // Ambil semua treatment dari database
    $treatments = \App\Models\Treatment::all();

    // arahkan ke halaman reservation.blade.php
    return view('reservation', compact('treatments'));
}

}
