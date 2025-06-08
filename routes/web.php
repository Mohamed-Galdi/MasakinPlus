<?php

use App\Enums\PropertyStatus;
use App\Http\Controllers\PageController;
use App\Models\Property;
use Illuminate\Support\Facades\Route;

// Home Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy-policy');
Route::get('/about-us', [PageController::class, 'about'])->name('about-us');

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
})->middleware(['auth'])->name('dashboard');

Route::get('/test', function () {
   

    return inertia('Home/Test');
})->name('test');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/owner.php';
require __DIR__.'/investor.php';
require __DIR__.'/tenant.php';
require __DIR__.'/common.php';
