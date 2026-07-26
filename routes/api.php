<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. GET: List Semua Projects Portofolio
Route::get('/projects', function () {
    return response()->json([
        'success' => true,
        'message' => 'List data projek portofolio',
        'data'    => Project::all()
    ]);
});

// 2. GET: Detail Project Berdasarkan Slug
Route::get('/projects/{slug}', function ($slug) {
    $project = Project::where('slug', $slug)->first();

    if (!$project) {
        return response()->json([
            'success' => false,
            'message' => 'Projek tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data'    => $project
    ]);
});

// 3. POST: Form Kontak via API
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|email',
        'message'    => 'required|string',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pesan kontak berhasil diterima!',
        'data'    => $validated
    ], 201);
});
