<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KgbController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');

    Route::prefix('kgb')->group(function () {
        Route::get('/', [KgbController::class, 'index'])->name('admin.kgb.index');
    });
});
