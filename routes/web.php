<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Home/Home'))->name('home');


Route::get('/test', fn () => inertia('Home/Test'))->name('test');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
