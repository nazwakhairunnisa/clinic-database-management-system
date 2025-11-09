<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PatientRegisterController;
use App\Http\Controllers\PatientLoginController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/navbar', function () {
//     return view('layouts.navbar');
// });

// Route::get('/home', function () {
//     return view('home');
// })->name('home');

// Route::get('/about', function () {
//     return view('about');
// })->name('about');

// Route::get('/treatment', function () {
//     return view('treatment'); 
// })->name('treatment');

// Route::get('/treatment/all', function () {
//     return view('treatment-all');
// })->name('treatment.all');

// Route::get('/promo', function () {
//     return view('promo');
// })->name('promo');

// Route::get('/allpromo', function () {
//     return view('allpromo');
// })->name('allpromo');

// Route::get('/Contact', function () {
//     return view('Contact');
// })->name('Contact');


require __DIR__.'/auth.php';
