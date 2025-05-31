<?php

use App\Http\Controllers\Common\SupportController;
use App\Http\Controllers\Common\UserAccountController;
use App\Http\Controllers\Common\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('/user')->middleware(['auth', 'phone_verified', 'not-admin'])->group(function () {

    // Users Account Management
    Route::prefix('/account')->controller(UserAccountController::class)->name('account.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/change-image', 'changeImage')->name('changeImage');
        Route::post('/edit-username', 'editUsername')->name('editUsername');
        Route::post('/edit-email', 'editEmail')->name('editEmail');
        Route::post('/update-password', 'updatePassword')->name('updatePassword');
    });

    // Users Support
    Route::prefix('/support')->controller(SupportController::class)->name('support.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::post('/reset-user-unread-count/{ticketId}', 'resetUserUnreadCount')->name('resetUserUnreadCount');
        Route::post('/reply/{ticketId}', 'reply')->name('reply');
    });

    // Wallet
    Route::prefix('/wallet')->controller(WalletController::class)->name('wallet.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

});
