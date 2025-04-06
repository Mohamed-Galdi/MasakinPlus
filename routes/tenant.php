<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/tenant')->middleware(['auth', 'verified'])->group(function () {

    Route::redirect('/', '/tenant/dashboard')->name('tenant.dashboard');

    Route::get('/dashboard', fn() => inertia('Tenant/Dashboard'))->name('tenant.dashboard');
    Route::get('/properties', fn() => inertia('Tenant/Properties'))->name('tenant.properties');
});
