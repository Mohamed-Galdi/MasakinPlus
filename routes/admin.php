<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('/abc', [ProductController::class, 'abc'])->name('admin.products.abc');
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [ProductController::class, 'orders'])->name('admin.orders.index');
    });

    // Packages
    Route::prefix('packages')->group(function () {
        Route::get('/', [ProductController::class, 'packages'])->name('admin.packages.index');
    });

});