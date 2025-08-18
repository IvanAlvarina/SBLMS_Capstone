<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\UserManagement\UserManagementController;

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

Route::group(['prefix' => 'user-management'], function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
        Route::get('/json', [UserManagementController::class, 'getUsers'])->name('user-management.json');
        Route::get('/json-approval', [UserManagementController::class, 'getUsersForApproval'])->name('user-management.json.approval');
        Route::get('/pending-approval', [UserManagementController::class, 'forApprovalIndex'])->name('user-management.pending-approval');
        Route::get('/users/approve/{id}', [UserManagementController::class, 'approveUser'])->name('user-management.approve');

    });
});