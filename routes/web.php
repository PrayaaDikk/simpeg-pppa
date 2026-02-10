<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PegawaiController;

Route::prefix('admin')->group(function () {
    Route::Get('/', [HomeController::class, 'index'])->name('dashboard');
});
