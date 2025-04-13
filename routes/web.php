<?php

use App\Enums\PropertyStatus;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

// Home Pages
Route::get('/', fn () => inertia('Home/Home'))->name('home');
Route::get('/privacy-policy', fn () => inertia('Home/PrivacyPolicy'))->name('privacy-policy');
Route::get('/about-us', fn () => inertia('Home/AboutUs'))->name('about-us');

// Dashboard Redirection
Route::get('/dashboard', function () {
    $userType = auth()->user()->type;

    return match ($userType) {
        'admin' => redirect()->route('admin.dashboard'),
        'tenant' => redirect()->route('tenant.dashboard'),
        'investor' => redirect()->route('investor.dashboard'),
        'owner' => redirect()->route('owner.dashboard'),
        default => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Users Account Management
Route::prefix('/account')->middleware(['auth', 'verified', 'not-admin'])->group(function () {
    Route::get('/', [UserAccountController::class, 'index'])->name('account.index');
    Route::post('/change-image', [UserAccountController::class, 'changeImage'])->name('account.changeImage');
    Route::post('/edit-username', [UserAccountController::class, 'editUsername'])->name('account.editUsername');
    Route::post('/edit-email', [UserAccountController::class, 'editEmail'])->name('account.editEmail');
    Route::post('/update-password', [UserAccountController::class, 'updatePassword'])->name('account.updatePassword');
});


Route::get('/test', function () {
    return inertia('Home/Test');
})->name('test');




require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/owner.php';
require __DIR__ . '/investor.php';
require __DIR__ . '/tenant.php';
