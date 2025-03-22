<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
    });

});