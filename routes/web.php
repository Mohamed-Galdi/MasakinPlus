<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Home/Home'))->name('home');
Route::get('/privacy-policy', fn () => inertia('Home/PrivacyPolicy'))->name('privacy-policy');
Route::get('/about-us', fn () => inertia('Home/AboutUs'))->name('about-us');


Route::get('/test', fn () => inertia('Home/Test'))->name('test');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/owner.php';

