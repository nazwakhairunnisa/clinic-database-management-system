<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/treatment/all', function () {
    return view('treatment-all');
})->name('treatment.all');

Route::get('/allpromo', function () {
    return view('allpromo');
})->name('allpromo');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::get('/Certificate', function () {
    return view('Certificate');
})->name('Certificate');

Route::get('/footer', function () {
    return view('footer');
})->name('footer');

require __DIR__.'/auth.php';
