<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Home'))->name('home');



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
