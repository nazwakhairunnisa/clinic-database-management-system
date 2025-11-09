<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/navbar', function () {
    return view('layouts.navbar');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/treatment', function () {
    return view('treatment'); 
})->name('treatment');

Route::get('/treatment/all', function () {
    return view('treatment-all');
})->name('treatment.all');

Route::get('/promo', function () {
    return view('promo');
})->name('promo');

Route::get('/allpromo', function () {
    return view('allpromo');
})->name('allpromo');

Route::get('/Contact', function () {
    return view('Contact');
})->name('Contact');

Route::prefix('owner')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.owner.dashboard_owner');
    })->name('owner.dashboard');

    Route::get('/jadwal', function () {
        return view('layouts.owner.jadwal');
    })->name('owner.jadwal');

    // 🧾 Route baru untuk Daftar Pasien
    Route::get('/pasien', function () {
        return view('layouts.owner.pasien');
    })->name('owner.pasien');

     Route::get('/pasien/add', function () {
        return view('layouts.owner.add_pasien');
    })->name('owner.pasien.add');

     Route::get('/pasien/edit-rekam', function () {
        return view('layouts.owner.edit_rekam_medis');
    })->name('owner.pasien.editRekam');

     Route::get('/pasien/detail', function () {
        return view('layouts.owner.detail_pasien');
    })->name('owner.pasien.detail');

    Route::get('/treatment', function () {
        return view('layouts.owner.treatment');
    })->name('owner.treatment');

    Route::get('/treatment/add', function () {
        return view('layouts.owner.add_treatment');
    })->name('owner.treatment.add');

    Route::get('/treatment/edit', function () {
    return view('layouts.owner.edit_treatment');
    })->name('owner.treatment.edit');

    Route::get('/obat', function () {
    return view('layouts.owner.obat');
    })->name('owner.obat');

    Route::get('/obat/add', function () {
    return view('layouts.owner.add_obat');
    })->name('owner.obat.add');

    Route::get('/obat/edit', function () {
        return view('layouts.owner.edit_obat');
    })->name('owner.obat.edit');

    Route::get('/pengeluaran', function () {
    return view('layouts.owner.pengeluaran');
    })->name('owner.pengeluaran');

    Route::get('/pendapatan', function () {
    return view('layouts.owner.pendapatan');
    })->name('owner.pendapatan');

    Route::get('/laporan-penjualan', function () {
    return view('layouts.owner.laporan_penjualan');
    })->name('owner.laporan.penjualan');

});




require __DIR__.'/auth.php';
