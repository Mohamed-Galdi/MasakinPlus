<?php

use App\Http\Controllers\Owner\InvestmentRequestController;
use App\Http\Controllers\Owner\PropertyController;
use Illuminate\Support\Facades\Route;

Route::prefix('/owner')->middleware(['auth', 'owner'])->group(function () {

    Route::redirect('/', '/owner/properties')->name('owner.dashboard');

    // Properties
    Route::prefix('properties')->name('owner.properties.')->controller(PropertyController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/{property}', 'show')->name('show');
        Route::get('/{property}/edit', 'edit')->name('edit');
        Route::put('/{property}', 'update')->name('update');
    });

    // Investment Requests
    Route::prefix('investment-requests')->name('owner.investment-requests.')->controller(InvestmentRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::get('/{investmentRequest}', 'show')->name('show');
        Route::post('/{investmentRequest}/accept-offer', 'acceptOffer')->name('acceptOffer');
        Route::post('/{investmentRequest}/reject-offer', 'rejectOffer')->name('rejectOffer');
    });
});
