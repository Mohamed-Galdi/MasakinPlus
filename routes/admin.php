<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvestmentRequestController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Settings\ContentManagementController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\TempFileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::redirect('/', '/admin/dashboard')->name('admin.dashboard');

    // Dashboard
    Route::prefix('dashboard')->name('admin.dashboard.')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Users
    Route::prefix('users')->name('admin.users.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/suspended', 'suspended')->name('suspended');
        Route::get('/{user}', 'view')->name('view');
        Route::post('/{user}/suspend', 'suspend')->name('suspend');
        Route::post('/{user}/reactivate', 'reactivate')->name('reactivate');
    });

    // Properties
    Route::prefix('properties')->name('admin.properties.')->controller(PropertyController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{property}', 'view')->name('view');
    });

    // Investment Requests
    Route::prefix('investment-requests')->name('admin.investment-requests.')->controller(InvestmentRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{investmentRequest}', 'show')->name('show');
        Route::post('createOffer', 'createOffer')->name('createOffer');
        Route::put('/updateOffer', 'updateOffer')->name('updateOffer');
    });

    // Support
    Route::prefix('support')->name('admin.support.')->controller(SupportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::post('/reply/{ticketId}', 'reply')->name('reply');
        Route::post('/resetUnreadCount/{ticketId}', 'resetUnreadCount')->name('resetUnreadCount');
    });

    // Account
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('admin.account.index');
        Route::post('/change-image', [AccountController::class, 'changeImage'])->name('admin.account.changeImage');
        Route::post('/edit-account', [AccountController::class, 'editAccount'])->name('admin.account.editAccount');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('admin.account.updatePassword');
    });

    // Settings
    Route::prefix('settings')->name('admin.settings.')->group(function () {
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            // Property Status Guide
            Route::get('/property_status_guide',  'propertyStatusGuide')->name('property_status_guide');
            // setting tab
            Route::get('/setting_tab',  'settingTab')->name('setting_tab');
        });
        // CMS
        Route::prefix('content')->name('content.')->controller(ContentManagementController::class)->group(function () {
            Route::get('/home',  'home')->name('home');
            Route::get('/about',  'about')->name('about');
            Route::get('/privacy',  'privacy')->name('privacy');
            Route::put('/update-page-content',  'updatePageContent')->name('updatePageContent');
            Route::put('/update-section-content',  'updateSectionContent')->name('updateSectionContent');
        });
       
    });
});

// admin auth
Route::middleware('guest')->group(function () {
    Route::get('masakin-secret-center', [AdminAuthenticatedSessionController::class, 'adminLogin']);
    Route::get('masakin-secret-center/otp', [AdminAuthenticatedSessionController::class, 'adminOTP'])->name('admin.otp');
});

// file upload
Route::post('/upload', [TempFileController::class, 'upload'])->name('file.upload');
Route::post('/revert/{id}', [TempFileController::class, 'revert'])->name('file.revert');
