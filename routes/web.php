<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PegawaiController;

Route::Get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
