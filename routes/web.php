<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;

Route::get('/', function () {
    return view('Login.login');
});

Route::group(['prefix' => 'login'], function () {
    Route::get('/', [LoginController::class, 'index'])->name('login.index');
    Route::get('/register', [LoginController::class, 'register'])->name('login.register');
    Route::post('/store', [LoginController::class, 'store'])->name('login.store');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('dashboard.logout');
});

