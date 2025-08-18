<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;

//  Root route - redirect to login
Route::get('/', function () {
    return redirect()->route('login.index');
});

//  Login routes (with guest middleware to prevent authenticated users)
Route::middleware('guest')->group(function () {
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', [LoginController::class, 'index'])->name('login.index');
        Route::get('/register', [LoginController::class, 'register'])->name('login.register');
        Route::post('/store', [LoginController::class, 'store'])->name('login.store');
        Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
    });
});

//  Logout route (for authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

//  Dashboard routes (protected with auth middleware)
Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        // Add more dashboard routes here
    });
});