<?php

use App\Http\Controllers\CutiController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');

    Route::prefix('cuti')->group(function () {
        Route::get('/', [CutiController::class, 'index'])->name('admin.cuti.index');
        Route::get('/create', [CutiController::class, 'create'])->name('admin.cuti.create');
        Route::post('/store', [CutiController::class, 'store'])->name('admin.cuti.store');

        Route::get('/{id}', [CutiController::class, 'show'])->name('admin.cuti.show');
        Route::delete('/{id}', [CutiController::class, 'destroy'])->name('admin.cuti.destroy');
    });
});
