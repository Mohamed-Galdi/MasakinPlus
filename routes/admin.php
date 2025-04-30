<?php

use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\InvestmentRequestController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TempFileController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::redirect('/', '/admin/users')->name('admin.dashboard');

    // Users
    Route::prefix('users')->name('admin.users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/suspended', 'suspended')->name('suspended');
        Route::get('/{user}', 'view')->name('view');
        Route::post('/{user}/suspend', 'suspend')->name('suspend');
        Route::post('/{user}/reactivate', 'reactivate')->name('reactivate');
    });

    // Support
    Route::prefix('support')->name('admin.support.')->controller(SupportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::post('/reply/{ticketId}', 'reply')->name('reply');
        Route::post('/resetUnreadCount/{ticketId}', 'resetUnreadCount')->name('resetUnreadCount');
    });

    // Investment Requests
    Route::prefix('investment-requests')->name('admin.investment-requests.')->controller(InvestmentRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/reply', 'reply')->name('reply');
        Route::post('/change-status', 'changeStatus')->name('changeStatus');
    });


    Route::get('/properties', function () {
        return inertia('Admin/Properties');
    })->name('admin.properties');

    // Account
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('admin.account.index');
        Route::post('/change-image', [AccountController::class, 'changeImage'])->name('admin.account.changeImage');
        Route::post('/edit-account', [AccountController::class, 'editAccount'])->name('admin.account.editAccount');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('admin.account.updatePassword');
    });
});

// file upload
Route::post('/upload', [TempFileController::class, 'upload'])->name('file.upload');
Route::post('/revert/{id}', [TempFileController::class, 'revert'])->name('file.revert');

// admin auth

Route::middleware('guest')->group(function () {

    Route::get('masakin-secret-center', [AdminAuthenticatedSessionController::class, 'adminLogin']);
    Route::get('masakin-secret-center/otp', [AdminAuthenticatedSessionController::class, 'adminOTP'])->name('admin.otp');
});
