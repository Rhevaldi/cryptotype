<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/live-prices', [HomeController::class, 'getPricesApi'])->name('api.live-prices');