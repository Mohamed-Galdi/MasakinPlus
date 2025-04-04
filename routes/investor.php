<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/investor')->middleware([])->group(function () {

    Route::redirect('/', '/investor/dashboard');

    Route::get('/dashboard', fn() => inertia('Owner/Dashboard'))->name('investor.dashboard');
    Route::get('/properties', fn() => inertia('Owner/Properties'))->name('investor.properties');
});
