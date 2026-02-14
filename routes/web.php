<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// Route untuk halaman home
Route::get('/', function () {
    return view('pages.home');
});

// Route untuk contact form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');