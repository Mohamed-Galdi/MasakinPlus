<?php

use App\Http\Controllers\TempFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Home'))->name('home');

// file upload
Route::post('/upload', [TempFileController::class, 'upload'])->name('file.upload');
Route::post('/revert/{id}', [TempFileController::class, 'revert'])->name('file.revert');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
