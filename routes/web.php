<?php

use App\Enums\PropertyStatus;
use Illuminate\Support\Facades\Route;

// Home Pages
Route::get('/', fn() => inertia('Home/Home'))->name('home');
Route::get('/privacy-policy', fn() => inertia('Home/PrivacyPolicy'))->name('privacy-policy');
Route::get('/about-us', fn() => inertia('Home/AboutUs'))->name('about-us');

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


Route::get('/test', function () {
    $statusOptions = PropertyStatus::options();
    return inertia('Home/Test', compact('statusOptions'));
})->name('test');


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/owner.php';
require __DIR__ . '/investor.php';
require __DIR__ . '/tenant.php';
require __DIR__ . '/common.php';
