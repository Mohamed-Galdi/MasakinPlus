<?php

use App\Mail\EmailVerif;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', fn () => inertia('Home/Home'))->name('home');
Route::get('/privacy-policy', fn () => inertia('Home/PrivacyPolicy'))->name('privacy-policy');
Route::get('/about-us', fn () => inertia('Home/AboutUs'))->name('about-us');


// Route::get('/test', fn () => inertia('Home/Test'))->name('test');


Route::get('/test', function () {
    Mail::to('contact.galdi@gmail.com')->send(new EmailVerif());
    return 'email sent';
})->name('test');

Route::get('/test-email', function () {
    return new EmailVerif();
})->name('test-email');


require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/owner.php';

