<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 🔹 Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
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
Route::get('/users-list', [UserController::class, 'index'])->name('users.index');
Route::post('/add-users', [UserController::class, 'store'])->name('users.store');
Route::put('/update-users/{user}', [UserController::class, 'update'])->name('users.update');
Route::post('/user-password-update', [UserController::class, 'userPasswordUpdate'])->name('user.password.update');
Route::delete('/delete-user/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/user-profile-settings', [UserController::class, 'userProfileSettings'])->name('users.profile.settings');
Route::put('/user-profile-update', [UserController::class, 'userProfileUpdate'])->name('user.profile.update');
Route::get('/user-avatar-remove', [UserController::class, 'removeAvatar'])->name('user.avatar.remove');
Route::patch('/toggle-user-status/{user}/', [UserController::class, 'toggleUserStatus'])->name('toggle.user.status');
Route::post('/two-factor-enable', [UserController::class, 'twoFactorEnable'])->name('two.factor.enable');
Route::post('/two-factor-disable', [UserController::class, 'twoFactorDisable'])->name('two.factor.disable');

// }); 

Route::middleware(['auth', 'security'])->group(function () {

    // Parent Categories
    Route::get('/categories', [CategoryController::class, 'parentCategories'])->name('categories.parents');
    Route::get('/categories/create', [CategoryController::class, 'createParent'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'storeParent'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'editParent'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'updateParent'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroyParent'])->name('categories.destroy');
    Route::patch('/toggle-category-status/{category}', [CategoryController::class, 'toggleStatus'])->name('toggle.category.status');

    // Subcategories
    Route::get('/subcategories', [CategoryController::class, 'subCategories'])->name('categories.subcategories');
    Route::get('/subcategories/create', [CategoryController::class, 'createSub'])->name('subcategories.create');
    Route::post('/subcategories', [CategoryController::class, 'storeSub'])->name('subcategories.store');
    Route::get('/subcategories/{category}/edit', [CategoryController::class, 'editSub'])->name('subcategories.edit');
    Route::put('/subcategories/{category}', [CategoryController::class, 'updateSub'])->name('subcategories.update');
    Route::delete('/subcategories/{category}', [CategoryController::class, 'destroySub'])->name('subcategories.destroy');
    Route::patch('/toggle-subcategory-status/{category}', [CategoryController::class, 'toggleStatusSub'])->name('toggle.subcategory.status');

    
});


Route::resource('brands', BrandController::class);
Route::patch('/toggle-brand-status/{brand}', [BrandController::class, 'toggleStatus'])->name('toggle.brand.status');
