<?php

use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('pengaturan', '/pengaturan/profil');

    Route::get('pengaturan/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('pengaturan/profil', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('pengaturan/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('pengaturan/keamanan', [SecurityController::class, 'edit'])
        ->middleware(RequirePassword::class)
        ->name('security.edit');

    Route::put('pengaturan/password', [SecurityController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');
});
