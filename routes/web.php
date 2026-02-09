<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

use App\Http\Controllers\ContactController;

// Route yang sudah ada
Route::get('/', function () {
    return view('pages.home');
});

// Route baru untuk contact
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');