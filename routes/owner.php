<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/owner')->middleware([])->group(function () {
    
    Route::redirect('/', '/owner/dashboard');
    
    Route::get('/dashboard', fn() => inertia('Owner/Dashboard'))->name('owner.dashboard');
    Route::get('/properties', fn() => inertia('Owner/Properties'))->name('owner.properties');

});