<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PatientRegisterController;
use App\Http\Controllers\PatientLoginController;
use App\Http\Controllers\Owner\TreatmentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// web public routes (tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/treatment', [HomeController::class, 'treatments'])->name('treatment');
Route::get('/treatment/all', [HomeController::class, 'treatmentsAll'])->name('treatment.all');
Route::get('/promo', [HomeController::class, 'promo'])->name('promo');
Route::get('/allpromo', [HomeController::class, 'allPromo'])->name('allpromo');
Route::get('/contact', [HomeController::class, 'contact'])->name('Contact');

// registrasi dan login pasien
Route::get('/register', [PatientRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PatientRegisterController::class, 'register']);

Route::get('/login', [PatientLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PatientLoginController::class, 'login']);
Route::post('/logout', [PatientLoginController::class, 'logout'])->name('logout');

// reservasi routes (untuk pasien yang sudah login)
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/saya', [ReservationController::class, 'myReservations'])->name('reservasi.my');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/data-pasien', function () {
    return view('data');
})->middleware('auth')->name('patient.data');

Route::get('/navbar-auth', function () {
    return view('layouts.navbar-auth');
});

Route::get('/reservation', function () {
    return view('reservation');
})->name('reservation');

Route::get('/profile', function () {
    return view('profile.show');
})->name('profile.show');

Route::get('/profile/edit', function () {
    return view('profile.edit');
})->name('profile.edit');


Route::get('/reservation', [ReservationController::class, 'create'])->name('reservation');

Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.create');
});


Route::prefix('owner')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.owner.dashboard_owner');
    })->name('owner.dashboard');

    Route::get('/jadwal', function () {
        return view('layouts.owner.jadwal');
    })->name('owner.jadwal');

    // Route baru untuk Daftar Pasien
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

    Route::get('/treatment', [TreatmentController::class, 'index'])->name('owner.treatment');
    Route::get('/treatment/create', [TreatmentController::class, 'create'])->name('owner.treatment.add');
    Route::post('/treatment', [TreatmentController::class, 'store'])->name('owner.treatment.store');
    Route::get('/treatment/{id}/edit', [TreatmentController::class, 'edit'])->name('owner.treatment.edit');
    Route::put('/treatment/{id}', [TreatmentController::class, 'update'])->name('owner.treatment.update');
    Route::delete('/treatment/{id}', [TreatmentController::class, 'destroy'])->name('owner.treatment.destroy');

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




Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// require __DIR__.'/auth.php';
