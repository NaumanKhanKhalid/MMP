<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\CarMakeController;
use App\Http\Controllers\CarModelController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModelEngineController;
use App\Http\Controllers\ProductFitmentController;
    use App\Http\Controllers\ProductController;

Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
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


Route::middleware(['auth', 'security'])->group(function () {
    // Suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::patch('/toggle-supplier-status/{supplier}', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle.status');
});




Route::middleware(['auth', 'security'])->group(function () {

    // 🔹 Car Makes
    Route::get('/car-makes', [CarMakeController::class, 'index'])->name('car-makes.index');
    Route::post('/car-makes', [CarMakeController::class, 'store'])->name('car-makes.store');
    Route::put('/car-makes/{make}', [CarMakeController::class, 'update'])->name('car-makes.update');
    Route::delete('/car-makes/{make}', [CarMakeController::class, 'destroy'])->name('car-makes.destroy');
    Route::patch('/toggle-car-make-status/{make}', [CarMakeController::class, 'toggleStatus'])->name('toggle.car-make.status');

    // 🔹 Car Models
    Route::get('/car-models', [CarModelController::class, 'index'])->name('car-models.index');
    Route::post('/car-models', [CarModelController::class, 'store'])->name('car-models.store');
    Route::put('/car-models/{model}', [CarModelController::class, 'update'])->name('car-models.update');
    Route::delete('/car-models/{model}', [CarModelController::class, 'destroy'])->name('car-models.destroy');
    Route::patch('/toggle-car-model-status/{model}', [CarModelController::class, 'toggleStatus'])->name('toggle.car-model.status');

});

Route::middleware(['auth', 'security'])->group(function () {

    // Engines
    Route::get('/engines', [EngineController::class, 'index'])->name('engines.index');
    Route::post('/engines', [EngineController::class, 'store'])->name('engines.store');
    Route::get('/engines/{engine}/edit', [EngineController::class, 'edit'])->name('engines.edit');
    Route::put('/engines/{engine}', [EngineController::class, 'update'])->name('engines.update');
    Route::delete('/engines/{engine}', [EngineController::class, 'destroy'])->name('engines.destroy');

    Route::patch('engines/{engine}/toggle-status', [EngineController::class, 'toggleStatus'])->name('toggle.engine.status');
    // Model Engines
    Route::get('/model-engines', [ModelEngineController::class, 'index'])->name('model.engines.index');
    Route::post('/model-engines', [ModelEngineController::class, 'store'])->name('model.engines.store');
    Route::get('/model-engines/{modelEngine}/edit', [ModelEngineController::class, 'edit'])->name('model.engines.edit');
    Route::put('/model-engines/{modelEngine}', [ModelEngineController::class, 'update'])->name('model.engines.update');
    Route::delete('/model-engines/{modelEngine}', [ModelEngineController::class, 'destroy'])->name('model.engines.destroy');
    // Update

    // Toggle Status (PATCH instead of PUT)
    Route::patch('/model-engines/{modelEngine}/toggle-status', [ModelEngineController::class, 'toggleStatus'])
        ->name('toggle.model-engine.status');

    // Product Fitments
    Route::get('/product-fitments', [ProductFitmentController::class, 'index'])->name('product.fitments.index');
    Route::post('/product-fitments', [ProductFitmentController::class, 'store'])->name('product.fitments.store');
    Route::get('/product-fitments/{fitment}/edit', [ProductFitmentController::class, 'edit'])->name('product.fitments.edit');
    Route::put('/product-fitments/{fitment}', [ProductFitmentController::class, 'update'])->name('product.fitments.update');
    Route::delete('/product-fitments/{fitment}', [ProductFitmentController::class, 'destroy'])->name('product.fitments.destroy');



Route::middleware(['auth','security'])->group(function () {
    Route::get('/products', [ProductController::class,'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class,'show'])->name('products.show'); // detail / history
    Route::post('/products', [ProductController::class,'store'])->name('products.store');
    Route::post('/products/quick-add', [ProductController::class,'quickAdd'])->name('products.quickAdd');
    Route::put('/products/{product}', [ProductController::class,'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class,'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle-status', [ProductController::class,'toggleStatus'])->name('products.toggleStatus');
});


});
