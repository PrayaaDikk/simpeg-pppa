<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\RiwayatKepangkatanController;
use App\Http\Controllers\RiwayatPendidikanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'user', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::prefix('pegawai')->group(function () {
            Route::controller(PegawaiController::class)->as('pegawai.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
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
            Route::controller(CutiController::class)->as('cuti.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create/{pegawaiId}', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::get('/{id}', 'show')->name('show');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        });
    });

    Route::prefix('storage')->group(function () {
        Route::get('foto-pegawai/{filename}', [PegawaiController::class, 'showFile'])->name('pegawai.showFile');
        Route::get('ijazah/{filename}', [RiwayatPendidikanController::class, 'showFile'])->name('riwayat-pendidikan.showFile');
        Route::get('sk-pangkat/{filename}', [RiwayatKepangkatanController::class, 'showFile'])->name('riwayat-pangkat.showFile');
        Route::get('sk-jabatan/{filename}', [RiwayatKepangkatanController::class, 'showFile'])->name('riwayat-jabatan.showFile');
    })->middleware('auth');
});


require __DIR__ . '/auth.php';
