<?php

use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\TempFileController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::redirect('/', '/admin/users')->name('admin.dashboard');

    Route::get('/users', function () {
        return inertia('Admin/Users');
    })->name('admin.users');

    Route::get('/properties', function () {
        return inertia('Admin/Properties');
    })->name('admin.properties');

    // Account
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('admin.account.index');
        Route::post('/change-image', [AccountController::class, 'changeImage'])->name('admin.account.changeImage');
        Route::post('/edit-account', [AccountController::class, 'editAccount'])->name('admin.account.editAccount');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('admin.account.updatePassword');
    });

});

// file upload
Route::post('/upload', [TempFileController::class, 'upload'])->name('file.upload');
Route::post('/revert/{id}', [TempFileController::class, 'revert'])->name('file.revert');

// admin auth

Route::middleware('guest')->group(function () {

    Route::get('masakin-secret-center', [AdminAuthenticatedSessionController::class, 'adminLogin']);
    Route::get('masakin-secret-center/otp', [AdminAuthenticatedSessionController::class, 'adminOTP']);

});