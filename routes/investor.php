<?php

use App\Http\Controllers\Investor\InvestmentController;
use App\Http\Controllers\Investor\OfferController;
use Illuminate\Support\Facades\Route;


Route::prefix('/investor')->middleware(['auth', 'verified', 'investor'])->group(function () {

    Route::redirect('/', '/investor/offers')->name('investor.dashboard');

    // offers
    Route::prefix('offers')->name('investor.offers.')->controller(OfferController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Investment
    Route::prefix('investment')->name('investor.investment.')->controller(InvestmentController::class)->group(function () {
        Route::get('/', 'index')->name('index'); 
       
    });

   
});