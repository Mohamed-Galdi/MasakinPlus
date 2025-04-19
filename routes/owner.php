<?php

use App\Http\Controllers\Owner\PropertyController;
use Illuminate\Support\Facades\Route;


Route::prefix('/owner')->middleware(['auth', 'verified', 'owner'])->group(function () {
    
    Route::redirect('/', '/owner/properties')->name('owner.dashboard');

    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('owner.properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('owner.properties.create');
    Route::post('/properties/create', [PropertyController::class, 'store'])->name('owner.properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('owner.properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('owner.properties.update');
});