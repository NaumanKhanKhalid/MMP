<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 🔹 Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.get');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.get');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Force Change Password
Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change.password.get');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password.post');

// 🔹 Two Factor
Route::get('/twofactor', [AuthController::class, 'showTwoFactor'])->name('twofactor.get');
Route::post('/twofactor', [AuthController::class, 'verifyTwoFactor'])->name('twofactor.post');

Route::middleware(['auth', 'security'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Owner')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    });
});



// Route::middleware(['auth', 'role:Owner'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
// }); 