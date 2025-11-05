<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
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


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

require __DIR__.'/auth.php';
