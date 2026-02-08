<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::Get('/', [HomeController::class, 'index'])->name('dashboard');
