<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/tenant')->middleware([])->group(function () {

    Route::redirect('/', '/tenant/dashboard');

    Route::get('/dashboard', fn() => inertia('Owner/Dashboard'))->name('tenant.dashboard');
    Route::get('/properties', fn() => inertia('Owner/Properties'))->name('tenant.properties');
});
