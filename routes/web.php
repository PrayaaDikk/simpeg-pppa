<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PegawaiController;

Route::prefix('admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');

    Route::prefix('/pegawai')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('admin.pegawai.index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('admin.pegawai.create');
        Route::post('/store', [PegawaiController::class, 'store'])->name('admin.pegawai.store');

        Route::get('/{id}', [PegawaiController::class, 'show'])->name('admin.pegawai.show');
    });
});
