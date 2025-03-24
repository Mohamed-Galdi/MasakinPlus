<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::redirect('/', '/admin/products/dialog-crud')->name('dashboard');

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/dialog-crud', [ProductController::class, 'dialogCrud'])->name('admin.products.dialog-crud');
        Route::get('/drawer-crud', [ProductController::class, 'drawerCrud'])->name('admin.products.drawer-crud');

        Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::post('/delete/{id}', [ProductController::class, 'delete'])->name('admin.products.delete');
    });

    // Account
    Route::prefix('account')->group(function () {
        Route::get('/', [ProductController::class, 'account'])->name('admin.account.index');
    });

});