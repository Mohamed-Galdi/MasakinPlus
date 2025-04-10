<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/investor')->middleware(['auth', 'verified', 'investor'])->group(function () {

    Route::redirect('/', '/investor/dashboard')->name('investor.dashboard');
    Route::get('/dashboard', fn() => inertia('Investor/Dashboard'))->name('investor.dashboard');
    Route::get('/properties', fn() => inertia('Investor/Properties'))->name('investor.properties');
});
