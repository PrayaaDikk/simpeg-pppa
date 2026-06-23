<?php

use App\Http\Controllers\Admin\BidangController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PangkatController;
use App\Http\Controllers\Admin\RiwayatJabatanController;
use App\Http\Controllers\Admin\RiwayatPangkatController;
use App\Http\Controllers\Admin\RiwayatPendidikanController;
use App\Http\Controllers\AdminDelegationController; // Controller Baru untuk Delegasi
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KgbController;
use App\Http\Controllers\Pegawai\CutiPegawaiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PegawaiPasswordController;
use App\Http\Controllers\RiwayatPegawaiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'index'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Khusus Akun Non-Personil: Superadmin Only
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:superadmin')->prefix('/admin')->group(function () {
        Route::prefix('master/delegasi-akses')->name('admin.delegasi.')->group(function () {
            Route::get('/', [AdminDelegationController::class, 'index'])->name('index');
            Route::post('/', [AdminDelegationController::class, 'store'])->name('store');
            Route::post('/revoke', [AdminDelegationController::class, 'revoke'])->name('revoke');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Administrator Area (Admin & Superadmin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin|superadmin')->prefix('/admin')->group(function () {
        Route::prefix('/atur-ulang-sandi')->name('reset-password.')->group(function () {
            Route::get('/', [PegawaiPasswordController::class, 'index'])->name('index');
            Route::post('/{id}', [PegawaiPasswordController::class, 'reset'])
                ->name('reset');
        });

        // Sub-Group: Manajemen Utama Pegawai
        Route::prefix('/pegawai')->group(function () {

            Route::get('/', [PegawaiController::class, 'index'])->name('pegawai');
            Route::post('/store', [PegawaiController::class, 'store'])->name('pegawai.store');
            Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
            Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

            // Sub-Group internal: Riwayat/Berkas Pegawai
            Route::prefix('/{pegawai_id}/riwayat')->name('pegawai.riwayat.')->group(function () {
                Route::get('/', [RiwayatPegawaiController::class, 'index']);

                Route::prefix('riwayat-pendidikan')->name('riwayatPendidikan.')->group(function () {
                    Route::post('/', [RiwayatPendidikanController::class, 'store'])->name('store');
                    Route::post('/{id}', [RiwayatPendidikanController::class, 'update'])->name('update');
                    Route::delete('/{id}/hapus-ijazah', [RiwayatPendidikanController::class, 'deleteIjazah'])->name('deleteIjazah');
                    Route::delete('/{id}', [RiwayatPendidikanController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('riwayat-pangkat')->name('riwayatPangkat.')->group(function () {
                    Route::post('/', [RiwayatPangkatController::class, 'store'])->name('store');
                    Route::put('/{id}', [RiwayatPangkatController::class, 'update'])->name('update');
                    Route::delete('/{id}/hapus-sk', [RiwayatPangkatController::class, 'deleteSkFile'])->name('deleteSk');
                    Route::delete('/{id}', [RiwayatPangkatController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('riwayat-jabatan')->name('riwayatJabatan.')->group(function () {
                    Route::post('/', [RiwayatJabatanController::class, 'store'])->name('store');
                    Route::put('/{id}', [RiwayatJabatanController::class, 'update'])->name('update');
                    Route::delete('/{id}', [RiwayatJabatanController::class, 'destroy'])->name('destroy');
                });
            });
        });

        // Sub-Group: Manajemen Operasional Cuti
        Route::prefix('/cuti')->name('cuti.')->group(function () {
            Route::get('/', [CutiController::class, 'index'])->name('index');
            Route::post('/', [CutiController::class, 'store'])->name('store');
            Route::put('/{id}', [CutiController::class, 'update'])->name('update');
            Route::patch('/{id}', [CutiController::class, 'updateStatus'])->name('update_status');
            Route::delete('/{id}', [CutiController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/cetak', [CutiController::class, 'cetakDokumen'])->name('cetak');
        });

        // Sub-Group: Manajemen Kenaikan Gaji Berkala (KGB)
        Route::prefix('/kgb')->name('kgb.')->group(function () {
            Route::get('/', [KgbController::class, 'index'])->name('index');
            Route::post('/store', [KgbController::class, 'store'])->name('store');
            Route::put('/{id}', [KgbController::class, 'update'])->name('update');
            Route::put('/{id}/status', [KgbController::class, 'updateStatus'])->name('update_status');
            Route::delete('/{id}', [KgbController::class, 'destroy'])->name('destroy');
        });

        // Sub-Group: Master Data Instansi
        Route::prefix('master')->group(function () {
            Route::prefix('pangkat')->name('masterPangkat')->group(function () {
                Route::get('/', [PangkatController::class, 'index']);
                Route::post('/store', [PangkatController::class, 'store'])->name('store');
                Route::put('/{id}', [PangkatController::class, 'update'])->name('update');
                Route::delete('/{id}', [PangkatController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('bidang')->name('masterBidang')->group(function () {
                Route::get('/', [BidangController::class, 'index']);
                Route::post('/store', [BidangController::class, 'store'])->name('store');
                Route::put('/{id}', [BidangController::class, 'update'])->name('update');
                Route::delete('/{id}', [BidangController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('jabatan')->name('masterJabatan')->group(function () {
                Route::get('/', [JabatanController::class, 'index']);
                Route::post('/store', [JabatanController::class, 'store'])->name('store');
                Route::put('/{id}', [JabatanController::class, 'update'])->name('update');
                Route::delete('/{id}', [JabatanController::class, 'destroy'])->name('destroy');
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Pegawai/Staff Self-Service Area
    |--------------------------------------------------------------------------
    */
    Route::prefix('/pegawai')->group(function () {
        Route::get('/cuti', [CutiPegawaiController::class, 'index'])->name('pegawaiCuti');
        Route::post('/cuti/store', [CutiPegawaiController::class, 'store'])->name('pegawaiCuti.store');
    });
});

require __DIR__ . '/settings.php';
