<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/owner')->middleware(['auth', 'verified'])->group(function () {
    
    Route::redirect('/', '/owner/dashboard')->name('owner.dashboard');
    
    Route::get('/dashboard', fn() => inertia('Owner/Dashboard'))->name('owner.dashboard');
    Route::get('/properties', fn() => inertia('Owner/Properties'))->name('owner.properties');

});