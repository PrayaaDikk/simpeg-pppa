<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\RiwayatKepangkatanController;
use App\Http\Controllers\RiwayatPendidikanController;
use App\Http\Controllers\CutiController;

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::prefix('pegawai')->as('admin.')->group(function () {
        Route::controller(PegawaiController::class)->group(function () {
            Route::get('/', 'index')->name('pegawai.index');
            Route::get('/create', 'create')->name('pegawai.create');
            Route::post('/', 'store')->name('pegawai.store');

            Route::get('/{id}', 'show')->name('pegawai.show');
            Route::get('/{id}/edit', 'edit')->name('pegawai.edit');
            Route::put('/{id}', 'update')->name('pegawai.update');
            Route::delete('/{id}', 'destroy')->name('pegawai.destroy');
        });

        Route::controller(RiwayatPendidikanController::class)->as('riwayat-pendidikan.')->group(function () {
            Route::get('/{pegawaiId}/riwayat-pendidikan', 'index')->name('index');
            Route::get('/{pegawaiId}/riwayat-pendidikan/create', 'create')->name('create');
            Route::post('/{pegawaiId}/riwayat-pendidikan', 'store')->name('store');

            Route::get('/riwayat-pendidikan/{id}/edit', 'edit')->name('edit');
            Route::get('/riwayat-pendidikan/{id}', 'show')->name('show');
            Route::put('/riwayat-pendidikan/{id}', 'update')->name('update');
            Route::delete('/riwayat-pendidikan/{id}', 'destroy')->name('destroy');
        });

        Route::controller(RiwayatKepangkatanController::class)->as('riwayat-pangkat.')->group(function () {
            Route::get('/{pegawaiId}/riwayat-pangkat', 'index')->name('index');
            Route::get('/{pegawaiId}/riwayat-pangkat/create', 'create')->name('create');
            Route::post('/{pegawaiId}/riwayat-pangkat', 'store')->name('store');

            Route::get('/riwayat-pangkat/{id}/edit', 'edit')->name('edit');
            Route::get('/riwayat-pangkat/{id}', 'show')->name('show');
            Route::put('/riwayat-pangkat/{id}', 'update')->name('update');
            Route::delete('/riwayat-pangkat/{id}', 'destroy')->name('destroy');
        });

        Route::controller(RiwayatJabatanController::class)->as('riwayat-jabatan.')->group(function () {
            Route::get('/{pegawaiId}/riwayat-jabatan', 'index')->name('index');
            Route::get('/{pegawaiId}/riwayat-jabatan/create', 'create')->name('create');
            Route::post('/{pegawaiId}/riwayat-jabatan', 'store')->name('store');

            Route::get('/riwayat-jabatan/{id}/edit', 'edit')->name('edit');
            Route::get('/riwayat-jabatan/{id}', 'show')->name('show');
            Route::put('/riwayat-jabatan/{id}', 'update')->name('update');
            Route::delete('/riwayat-jabatan/{id}', 'destroy')->name('destroy');
        });
    });

    Route::prefix('cuti')->group(function () {
        Route::get('/', [CutiController::class, 'index'])->name('admin.cuti.index');
        Route::get('/create', [CutiController::class, 'create'])->name('admin.cuti.create');
        Route::post('/store', [CutiController::class, 'store'])->name('admin.cuti.store');

        Route::get('/{id}', [CutiController::class, 'show'])->name('admin.cuti.show');
        Route::delete('/{id}', [CutiController::class, 'destroy'])->name('admin.cuti.destroy');
    });
});

Route::prefix('storage')->group(function () {
    Route::get('foto-pegawai/{$filename}', [PegawaiController::class, 'showFile'])->name('pegawai.showFile');
    Route::get('ijazah/{$filename}', [RiwayatPendidikanController::class, 'showFile'])->name('riwayat-pendidikan.showFile');
    Route::get('sk-pangkat/{$filename}', [RiwayatKepangkatanController::class, 'showFile'])->name('riwayat-pangkat.showFile');
    Route::get('sk-jabatan/{$filename}', [RiwayatKepangkatanController::class, 'showFile'])->name('riwayat-jabatan.showFile');
});
