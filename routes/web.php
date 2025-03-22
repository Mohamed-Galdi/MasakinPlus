<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TempFileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'));

// file upload
Route::post('/upload', [TempFileController::class, 'upload'])->name('file.upload');
Route::post('/revert/{id}', [TempFileController::class, 'revert'])->name('file.revert');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
