<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarMakeController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\JobCardController;
use App\Http\Controllers\ModelEngineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductFitmentController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Engine;
use Illuminate\Support\Facades\Route;

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

    Route::middleware('role:Owner')->group(function () {});
});

// Route::middleware(['auth', 'role:Owner'])->group(function () {
Route::middleware(['auth', 'security'])->group(function () {
    Route::get('/users-list', [UserController::class, 'index'])->name('users.index');
    Route::post('/add-users', [UserController::class, 'store'])->name('users.store');
    Route::put('/update-users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/user-password-update', [UserController::class, 'userPasswordUpdate'])->name('user.password.update');
    Route::delete('/delete-user/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/restore-user/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/force-delete-user/{id}', [UserController::class, 'forceDestroy'])->name('users.force-destroy');
    
    // User Modals
    Route::get('/users/create-modal', [UserController::class, 'createModal'])->name('users.create-modal');
    Route::get('/users/{user}/view-modal', [UserController::class, 'viewModal'])->name('users.view-modal');
    Route::get('/users/{user}/edit-modal', [UserController::class, 'editModal'])->name('users.edit-modal');

    Route::get('/user-profile-settings', [UserController::class, 'userProfileSettings'])->name('users.profile.settings');
    Route::put('/user-profile-update', [UserController::class, 'userProfileUpdate'])->name('user.profile.update');
    Route::get('/user-avatar-remove', [UserController::class, 'removeAvatar'])->name('user.avatar.remove');
    Route::patch('/toggle-user-status/{user}/', [UserController::class, 'toggleUserStatus'])->name('toggle.user.status');
    Route::post('/two-factor-enable', [UserController::class, 'twoFactorEnable'])->name('two.factor.enable');
    Route::post('/two-factor-disable', [UserController::class, 'twoFactorDisable'])->name('two.factor.disable');

});

Route::middleware(['auth', 'security'])->group(function () {

    // Main Categories
    Route::get('/categories', [CategoryController::class, 'parentCategories'])->name('categories.index');
    
    // Sub-Categories
    Route::get('/subcategories', [CategoryController::class, 'subCategories'])->name('categories.subcategories');
    
    // Category Actions (Both Main & Sub)
    Route::post('/categories', [CategoryController::class, 'storeParent'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'updateParent'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroyParent'])->name('categories.destroy');
    Route::patch('/toggle-category-status/{category}', [CategoryController::class, 'toggleStatus'])->name('toggle.category.status');
    
    // Category Modals
    Route::get('/categories/create-category-modal', [CategoryController::class, 'createCategoryModal'])->name('categories.create-category-modal');
    Route::get('/categories/create-subcategory-modal', [CategoryController::class, 'createSubCategoryModal'])->name('categories.create-subcategory-modal');
    Route::get('/categories/{category}/view-modal', [CategoryController::class, 'viewModal'])->name('categories.view-modal');
    Route::get('/categories/{category}/edit-modal', [CategoryController::class, 'editModal'])->name('categories.edit-modal');

    // Brand Modals
    Route::get('/brands/create-modal', [BrandController::class, 'createModal'])->name('brands.create-modal');
    Route::get('/brands/{brand}/view-modal', [BrandController::class, 'viewModal'])->name('brands.view-modal');
    Route::get('/brands/{brand}/edit-modal', [BrandController::class, 'editModal'])->name('brands.edit-modal');
    Route::patch('/brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    Route::patch('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');

});

Route::resource('brands', BrandController::class);
Route::patch('/toggle-brand-status/{brand}', [BrandController::class, 'toggleStatus'])->name('toggle.brand.status');

Route::middleware(['auth', 'security'])->group(function () {
    // Suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    // Route::patch('/suppliers/{id}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');
    // Route::delete('/suppliers/{id}/force-delete', [SupplierController::class, 'forceDelete'])->name('suppliers.force-delete');
    Route::patch('/toggle-supplier-status/{supplier}', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle.status');
    
    // Supplier Modals
    Route::get('/suppliers/create-modal', [SupplierController::class, 'createModal'])->name('suppliers.create-modal');
    Route::get('/suppliers/{supplier}/view-modal', [SupplierController::class, 'viewModal'])->name('suppliers.view-modal');
    Route::get('/suppliers/{supplier}/edit-modal', [SupplierController::class, 'editModal'])->name('suppliers.edit-modal');
});

Route::middleware(['auth', 'security'])->group(function () {

    // 🔹 Car Makes
    Route::get('/car-makes', [CarMakeController::class, 'index'])->name('car-makes.index');
    Route::post('/car-makes', [CarMakeController::class, 'store'])->name('car-makes.store');
    Route::post('/car-makes/quick-add', [CarMakeController::class, 'quickAdd'])->name('car-makes.quick-add');
    Route::put('/car-makes/{make}', [CarMakeController::class, 'update'])->name('car-makes.update');
    Route::delete('/car-makes/{make}', [CarMakeController::class, 'destroy'])->name('car-makes.destroy');
    Route::patch('/toggle-car-make-status/{make}', [CarMakeController::class, 'toggleStatus'])->name('toggle.car-make.status');

    // 🔹 Car Models
    Route::get('/car-models', [CarModelController::class, 'index'])->name('car-models.index');
    Route::post('/car-models', [CarModelController::class, 'store'])->name('car-models.store');
    Route::post('/car-models/quick-add', [CarModelController::class, 'quickAdd'])->name('car-models.quick-add');
    Route::put('/car-models/{model}', [CarModelController::class, 'update'])->name('car-models.update');
    Route::delete('/car-models/{model}', [CarModelController::class, 'destroy'])->name('car-models.destroy');
    Route::patch('/toggle-car-model-status/{model}', [CarModelController::class, 'toggleStatus'])->name('toggle.car-model.status');

});

Route::middleware(['auth', 'security'])->group(function () {

    // Engines
    Route::get('/engines', [EngineController::class, 'index'])->name('engines.index');
    Route::post('/engines', [EngineController::class, 'store'])->name('engines.store');
    Route::post('/car-engines/quick-add', [EngineController::class, 'quickAdd'])->name('car-engines.quick-add');
    Route::get('/engines/{engine}/edit', [EngineController::class, 'edit'])->name('engines.edit');
    Route::put('/engines/{engine}', [EngineController::class, 'update'])->name('engines.update');
    Route::delete('/engines/{engine}', [EngineController::class, 'destroy'])->name('engines.destroy');

    Route::patch('engines/{engine}/toggle-status', [EngineController::class, 'toggleStatus'])->name('toggle.engine.status');
    // // Model Engines
    // Route::get('/model-engines', [ModelEngineController::class, 'index'])->name('model.engines.index');
    // Route::post('/model-engines', [ModelEngineController::class, 'store'])->name('model.engines.store');
    // Route::get('/model-engines/{modelEngine}/edit', [ModelEngineController::class, 'edit'])->name('model.engines.edit');
    // Route::put('/model-engines/{modelEngine}', [ModelEngineController::class, 'update'])->name('model.engines.update');
    // Route::delete('/model-engines/{modelEngine}', [ModelEngineController::class, 'destroy'])->name('model.engines.destroy');
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

    Route::middleware(['auth', 'security'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/quick-add', [ProductController::class, 'quickAdd'])->name('products.quickAdd');
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/sample-import', function () {
        return response()->download(public_path('sample_products_import.csv'), 'sample_products_import.csv');
    })->name('products.sample-import');
        Route::get('/products/test-pdf', [ProductController::class, 'testPdf'])->name('products.testPdf');
        
        // These routes with {product} parameter must come AFTER specific routes
        Route::get('/products/{product}', function($id) {
            return redirect()->route('products.index');
        })->name('products.show'); // Redirect old product URLs to products index
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); // ✅ Changed to PUT
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        
        // Customer Vehicle API Routes
        Route::get('/api/car-makes', [App\Http\Controllers\CustomerController::class, 'getMakes'])->name('api.car-makes');
        Route::get('/api/car-models', [App\Http\Controllers\CustomerController::class, 'getModels'])->name('api.car-models');
        Route::get('/api/engines', [App\Http\Controllers\CustomerController::class, 'getEngines'])->name('api.engines');
        
        // API-style customer vehicles routes
        Route::get('/api/customers/{customer}/vehicles', [App\Http\Controllers\CustomerController::class, 'getVehicles'])->name('api.customers.vehicles');
        Route::get('/api/vehicle-makes/{make}/models', [App\Http\Controllers\CustomerController::class, 'getModelsByMake'])->name('api.vehicle-makes.models');
        
        // Legacy routes (keep for backward compatibility)
        Route::get('/customers/{customer}/vehicles', [App\Http\Controllers\CustomerController::class, 'getVehicles'])->name('customers.vehicles.get');
        Route::post('/customers/{customer}/vehicles', [App\Http\Controllers\CustomerController::class, 'storeVehicle'])->name('customers.vehicles.store');
        Route::delete('/customers/{customer}/vehicles/{vehicle}', [App\Http\Controllers\CustomerController::class, 'deleteVehicle'])->name('customers.vehicles.delete');
    }); 

    // Goods Receipts - Specific routes MUST come BEFORE resource route
    Route::get('/goods-receipts/po/{poId}/items', [GoodsReceiptController::class, 'getPOItems'])
        ->name('goods-receipts.get-po-items');
    
    Route::get('/goods-receipts/po/{poId}/details', [GoodsReceiptController::class, 'getPODetails'])
        ->name('goods-receipts.get-po-details');
    
    Route::get('/goods-receipts/po/{poId}/receipts', [GoodsReceiptController::class, 'getPOReceipts'])
        ->name('goods-receipts.get-po-receipts');
    
    Route::get('/goods-receipts/export', [GoodsReceiptController::class, 'export'])
        ->name('goods-receipts.export');
    
    Route::get('/goods-receipts/search-products', [GoodsReceiptController::class, 'searchProducts'])
        ->name('goods-receipts.search-products');
    
    Route::get('/goods-receipts/{grn}/print', [GoodsReceiptController::class, 'print'])
        ->name('goods-receipts.print');
    
    Route::resource('goods-receipts', GoodsReceiptController::class);
    
    // Purchase Orders - Specific routes MUST come BEFORE resource route
    Route::get('/purchase-orders/create-modal', [PurchaseOrderController::class, 'createModal'])
        ->name('purchase-orders.create-modal');
    Route::get('/purchase-orders/export', [PurchaseOrderController::class, 'export'])
        ->name('purchase-orders.export');
    Route::get('/purchase-orders/search-products', [PurchaseOrderController::class, 'searchProducts'])
        ->name('purchase-orders.search-products');
    
    Route::resource('purchase-orders', PurchaseOrderController::class);
    
    // Purchase Order Status Change (PATCH)
    Route::patch('/purchase-orders/{purchaseOrder}/change-status', [PurchaseOrderController::class, 'changeStatus'])
        ->name('purchase-orders.change-status');

    // AJAX: PO modals
    Route::get('/purchase-orders/{id}/view-modal', [PurchaseOrderController::class, 'viewModal'])
        ->name('purchase-orders.view-modal');
    Route::get('/purchase-orders/{id}/edit-modal', [PurchaseOrderController::class, 'editModal'])
        ->name('purchase-orders.edit-modal');

    // Purchase Order actions
    Route::get('/purchase-orders/{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])
        ->name('purchase-orders.print');

    // PO Approval and Closing
    Route::post('/purchase-orders/{id}/approve', [PurchaseOrderController::class, 'approve'])
        ->name('purchase-orders.approve');
    Route::post('/purchase-orders/{id}/close', [PurchaseOrderController::class, 'close'])
        ->name('purchase-orders.close');

    // Goods Receipts modals
    Route::get('/goods-receipts/{id}/view-modal', [GoodsReceiptController::class, 'viewModal'])
        ->name('goods-receipts.view-modal');
    Route::get('/goods-receipts/{id}/edit-modal', [GoodsReceiptController::class, 'editModal'])
        ->name('goods-receipts.edit-modal');

    // GRN Posting
    Route::post('/goods-receipts/{id}/post', [GoodsReceiptController::class, 'post'])
        ->name('goods-receipts.post');

// Quotes CRUD + modals
// Print Quotation
Route::get('/quotes/{quote}/print', [App\Http\Controllers\QuoteController::class, 'print'])->name('quotes.print');
// PDF Quotation
Route::get('/quotes/{quote}/pdf', [App\Http\Controllers\QuoteController::class, 'pdf'])->name('quotes.pdf');
// Export Quotations
Route::get('/quotes/export', [App\Http\Controllers\QuoteController::class, 'export'])->name('quotes.export');

Route::get('/quotes/create', function () {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $makes = CarMake::where('status', 'active')->orderBy('name')->get();
        $models = CarModel::where('status', 'active')->orderBy('name')->get();
        $engines = Engine::where('status', 'active')->orderBy('code')->get();
        
        // Get VAT settings from database
        $vatSettings = [
            'enabled' => App\Models\Setting::vatEnabled(),
            'rate' => App\Models\Setting::vatRate(),
            'inclusive' => App\Models\Setting::vatInclusive(),
        ];

    return view('quotes.partials.create_modal', compact('customers', 'products', 'makes', 'models', 'engines', 'vatSettings'))->render();
})->name('quotes.create');

Route::get('/quotes/{id}/view-modal', [App\Http\Controllers\QuoteController::class, 'viewModal'])
    ->name('quotes.view-modal');
Route::get('/quotes/{id}/edit-modal', [App\Http\Controllers\QuoteController::class, 'editModal'])
    ->name('quotes.edit-modal');

// Get Customer Info for Payment Options
Route::get('/quotes/{id}/customer-info', [App\Http\Controllers\QuoteController::class, 'getCustomerInfo'])->name('quotes.customer-info');
// Convert to Invoice
Route::post('/quotes/{id}/convert-to-invoice', [App\Http\Controllers\QuoteController::class, 'convertToInvoice'])->name('quotes.convert-to-invoice');
// Duplicate Quote
Route::post('/quotes/{id}/duplicate', [App\Http\Controllers\QuoteController::class, 'duplicate'])->name('quotes.duplicate');
// Search Products for Quote
Route::get('/quotes/search-products', [App\Http\Controllers\QuoteController::class, 'searchProducts'])->name('quotes.search-products');
// Search Customers for Quote
Route::get('/customers/search', [App\Http\Controllers\CustomerController::class, 'searchCustomers'])->name('customers.search');

Route::resource('quotes', App\Http\Controllers\QuoteController::class)->except(['create']);
Route::post('quotes/{quote}/send-whatsapp', [App\Http\Controllers\QuoteController::class, 'sendWhatsApp'])->name('quotes.send-whatsapp');
Route::post('quotes/{quote}/send-email', [App\Http\Controllers\QuoteController::class, 'sendEmail'])->name('quotes.send-email');

// Customers modals
Route::get('/customers/create', function () {
    return view('customers.partials.create_modal')->render();
})->name('customers.create');

Route::get('/customers/{id}/view-modal', function ($id) {
        $customer = Customer::with(['vehicles.make', 'vehicles.model', 'vehicles.engine'])->findOrFail($id);

    return view('customers.partials.view_modal', compact('customer'))->render();
})->name('customers.view-modal');

Route::get('/customers/{id}/edit-modal', function ($id) {
        $customer = Customer::with(['vehicles.make', 'vehicles.model', 'vehicles.engine'])->findOrFail($id);

    return view('customers.partials.edit_modal', compact('customer'))->render();
})->name('customers.edit-modal');


Route::resource('customers', App\Http\Controllers\CustomerController::class)->except(['create', 'edit', 'show']);

// Customer toggle status route
Route::patch('/customers/{customer}/toggle-status', [App\Http\Controllers\CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

Route::get('/quotes/{id}/view-modal', [QuoteController::class, 'viewModal'])->name('quotes.view-modal');
Route::get('/quotes/{id}/edit-modal', [QuoteController::class, 'editModal'])->name('quotes.edit-modal');

    // Invoices CRUD + modals
    Route::get('/invoices/create', function () {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('invoices.partials.create_modal', compact('customers', 'products'))->render();
    })->name('invoices.create');

    Route::get('/invoices/{id}/view-modal', [App\Http\Controllers\InvoiceController::class, 'viewModal'])
        ->name('invoices.view-modal');
    Route::get('/invoices/{id}/edit-modal', [App\Http\Controllers\InvoiceController::class, 'editModal'])
        ->name('invoices.edit-modal');

    // Print Invoice
    Route::get('/invoices/{invoice}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoices.print');
    
    // PDF Invoice
    Route::get('/invoices/{invoice}/pdf', [App\Http\Controllers\InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    
    // WhatsApp Invoice
    Route::post('/invoices/{invoice}/whatsapp', [App\Http\Controllers\InvoiceController::class, 'sendWhatsApp'])->name('invoices.whatsapp');
    
    // Email Invoice
    Route::post('/invoices/{invoice}/email', [App\Http\Controllers\InvoiceController::class, 'sendEmail'])->name('invoices.email');
    
    // Picking List
    Route::get('/invoices/{invoice}/picking-list', [App\Http\Controllers\InvoiceController::class, 'pickingList'])->name('invoices.picking-list');

    // Export Invoices
    Route::get('/invoices/export', [App\Http\Controllers\InvoiceController::class, 'export'])->name('invoices.export');

    // Post Invoice (update stock)
    Route::post('/invoices/{invoice}/post', [App\Http\Controllers\InvoiceController::class, 'post'])->name('invoices.post');

    Route::resource('invoices', App\Http\Controllers\InvoiceController::class)->except(['create', 'edit', 'show']);

    // Credit Notes Routes
    Route::post('/credit-notes/get-invoice-details', [App\Http\Controllers\CreditNoteController::class, 'getInvoiceDetails'])->name('credit-notes.get-invoice-details');
    Route::post('/credit-notes/{id}/post', [App\Http\Controllers\CreditNoteController::class, 'post'])->name('credit-notes.post');
    Route::post('/credit-notes/{id}/void', [App\Http\Controllers\CreditNoteController::class, 'void'])->name('credit-notes.void');
    Route::get('/credit-notes/{id}/pdf', [App\Http\Controllers\CreditNoteController::class, 'pdf'])->name('credit-notes.pdf');
    Route::post('/credit-notes/{id}/email', [App\Http\Controllers\CreditNoteController::class, 'email'])->name('credit-notes.email');
    Route::post('/credit-notes/{id}/whatsapp', [App\Http\Controllers\CreditNoteController::class, 'whatsapp'])->name('credit-notes.whatsapp');
    Route::resource('credit-notes', App\Http\Controllers\CreditNoteController::class);

    // Returns & Credit Notes Routes
    Route::get('/returns/create', [App\Http\Controllers\ReturnsController::class, 'create'])->name('returns.create');
    Route::get('/returns/{return}', [App\Http\Controllers\ReturnsController::class, 'show'])->name('returns.show');
    Route::get('/returns/{return}/edit', [App\Http\Controllers\ReturnsController::class, 'edit'])->name('returns.edit');
    Route::post('/returns', [App\Http\Controllers\ReturnsController::class, 'store'])->name('returns.store');
    Route::put('/returns/{return}', [App\Http\Controllers\ReturnsController::class, 'update'])->name('returns.update');
    Route::delete('/returns/{return}', [App\Http\Controllers\ReturnsController::class, 'destroy'])->name('returns.destroy');
    Route::post('/returns/{return}/approve', [App\Http\Controllers\ReturnsController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{return}/reject', [App\Http\Controllers\ReturnsController::class, 'reject'])->name('returns.reject');
    Route::post('/returns/{return}/complete', [App\Http\Controllers\ReturnsController::class, 'complete'])->name('returns.complete');
    Route::get('/returns/invoice/{invoice}/items', [App\Http\Controllers\ReturnsController::class, 'getInvoiceItems'])->name('returns.invoice-items');
    Route::get('/returns/credit-note/{creditNote}/pdf', [App\Http\Controllers\ReturnsController::class, 'generateCreditNotePDF'])->name('returns.credit-note-pdf');
    Route::resource('returns', App\Http\Controllers\ReturnsController::class)->only(['index']);

    // Workshop Job Cards Routes
    Route::get('/job-cards/create', [JobCardController::class, 'create'])->name('job-cards.create');
    Route::get('/job-cards/{jobCard}', [JobCardController::class, 'show'])->name('job-cards.show');
    Route::get('/job-cards/{jobCard}/edit', [JobCardController::class, 'edit'])->name('job-cards.edit');
    Route::post('/job-cards', [JobCardController::class, 'store'])->name('job-cards.store');
    Route::put('/job-cards/{jobCard}', [JobCardController::class, 'update'])->name('job-cards.update');
    Route::delete('/job-cards/{jobCard}', [JobCardController::class, 'destroy'])->name('job-cards.destroy');
    Route::patch('/job-cards/{jobCard}/change-status', [JobCardController::class, 'changeStatus'])->name('job-cards.change-status');
    Route::post('/job-cards/{jobCard}/convert-to-invoice', [JobCardController::class, 'convertToInvoice'])->name('job-cards.convert-to-invoice');
    Route::post('/job-cards/{jobCard}/add-item', [JobCardController::class, 'addItem'])->name('job-cards.add-item');
    Route::post('/job-cards/{jobCard}/add-labour', [JobCardController::class, 'addLabour'])->name('job-cards.add-labour');
    Route::get('/job-cards/{jobCard}/pdf', [JobCardController::class, 'generatePDF'])->name('job-cards.pdf');
    Route::get('/job-cards/export/{format}', [JobCardController::class, 'export'])->name('job-cards.export');
    Route::resource('job-cards', JobCardController::class)->only(['index']);

    // POS Routes
    Route::prefix('pos')->group(function () {
        Route::get('/', [App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
        Route::get('/products', [App\Http\Controllers\POSController::class, 'getProducts'])->name('pos.products');
        Route::get('/customers', [App\Http\Controllers\POSController::class, 'getCustomers'])->name('pos.customers');
        Route::get('/categories', [App\Http\Controllers\POSController::class, 'getCategories'])->name('pos.categories');
        Route::post('/process-sale', [App\Http\Controllers\POSController::class, 'processSale'])->name('pos.process-sale');
        Route::get('/search-products', [App\Http\Controllers\POSController::class, 'searchProducts'])->name('pos.search-products');
        Route::get('/product-by-barcode', [App\Http\Controllers\POSController::class, 'getProductByBarcode'])->name('pos.product-by-barcode');
        
        // Enhanced customer experience routes
        Route::get('/invoice-pdf/{invoice}', [App\Http\Controllers\POSController::class, 'generateInvoicePDF'])->name('pos.invoice-pdf');
        Route::get('/picking-list/{invoice}', [App\Http\Controllers\POSController::class, 'generatePickingList'])->name('pos.picking-list');
        Route::post('/send-invoice/{invoice}', [App\Http\Controllers\POSController::class, 'sendInvoice'])->name('pos.send-invoice');
    });

    // Payments Routes
    Route::prefix('payments')->group(function () {
        Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment}/view-modal', [App\Http\Controllers\PaymentController::class, 'viewModal'])->name('payments.view-modal');
        Route::get('/{payment}/allocate-modal', [App\Http\Controllers\PaymentController::class, 'allocateModal'])->name('payments.allocate-modal');
        Route::post('/{payment}/allocate', [App\Http\Controllers\PaymentController::class, 'allocate'])->name('payments.allocate');
        Route::post('/{payment}/void', [App\Http\Controllers\PaymentController::class, 'void'])->name('payments.void');
        Route::get('/outstanding-invoices', [App\Http\Controllers\PaymentController::class, 'getOutstandingInvoices'])->name('payments.outstanding-invoices');
    });

    // Stock Count Routes
    Route::prefix('stock-counts')->group(function () {
        Route::get('/', [App\Http\Controllers\StockCountController::class, 'index'])->name('stock-counts.index');
        Route::get('/create', [App\Http\Controllers\StockCountController::class, 'create'])->name('stock-counts.create');
        Route::post('/', [App\Http\Controllers\StockCountController::class, 'store'])->name('stock-counts.store');
        Route::get('/{id}/count', [App\Http\Controllers\StockCountController::class, 'count'])->name('stock-counts.count');
        Route::get('/{id}/stats', [App\Http\Controllers\StockCountController::class, 'getStats'])->name('stock-counts.stats');
        Route::post('/{countId}/items/{itemId}', [App\Http\Controllers\StockCountController::class, 'updateItem'])->name('stock-counts.update-item');
        Route::post('/{id}/start', [App\Http\Controllers\StockCountController::class, 'startCounting'])->name('stock-counts.start');
        Route::post('/{id}/complete', [App\Http\Controllers\StockCountController::class, 'completeCounting'])->name('stock-counts.complete');
        Route::post('/{id}/post', [App\Http\Controllers\StockCountController::class, 'post'])->name('stock-counts.post');
        Route::get('/{id}/variance-report', [App\Http\Controllers\StockCountController::class, 'varianceReport'])->name('stock-counts.variance-report');
        Route::post('/{id}/cancel', [App\Http\Controllers\StockCountController::class, 'cancel'])->name('stock-counts.cancel');
        Route::delete('/{id}', [App\Http\Controllers\StockCountController::class, 'destroy'])->name('stock-counts.destroy');
    });

    // Stock Adjustment Routes
    Route::prefix('stock-adjustments')->group(function () {
        Route::get('/', [App\Http\Controllers\StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
        Route::get('/create', [App\Http\Controllers\StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
        Route::post('/', [App\Http\Controllers\StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
        Route::get('/product/{productId}', [App\Http\Controllers\StockAdjustmentController::class, 'getProductStock'])->name('stock-adjustments.product-stock');
    });

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/sales-by-category', [App\Http\Controllers\ReportController::class, 'salesByCategory'])->name('reports.sales-by-category');
        Route::get('/timed-sales', [App\Http\Controllers\ReportController::class, 'timedSales'])->name('reports.timed-sales');
        Route::get('/discount-matrix', [App\Http\Controllers\ReportController::class, 'discountMatrix'])->name('reports.discount-matrix');
        Route::get('/replenishment', [App\Http\Controllers\ReportController::class, 'replenishment'])->name('reports.replenishment');
        Route::get('/new-items', [App\Http\Controllers\ReportController::class, 'newItems'])->name('reports.new-items');
        Route::get('/profit-loss', [App\Http\Controllers\ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/tax-report', [App\Http\Controllers\ReportController::class, 'taxReport'])->name('reports.tax-report');
        Route::get('/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('reports.expenses');
        Route::get('/customer-sales', [App\Http\Controllers\ReportController::class, 'customerSales'])->name('reports.customer-sales');
        Route::get('/loyalty', [App\Http\Controllers\ReportController::class, 'loyalty'])->name('reports.loyalty');
        Route::get('/supplier-buying', [App\Http\Controllers\ReportController::class, 'supplierBuying'])->name('reports.supplier-buying');
        Route::get('/items-sales', [App\Http\Controllers\ReportController::class, 'itemsSales'])->name('reports.items-sales');
        Route::get('/transactions', [App\Http\Controllers\ReportController::class, 'transactions'])->name('reports.transactions');
        Route::get('/employee-performance', [App\Http\Controllers\ReportController::class, 'employeePerformance'])->name('reports.employee-performance');
        Route::get('/day-end-detailed', [App\Http\Controllers\ReportController::class, 'dayEndDetailed'])->name('reports.day-end-detailed');
        Route::get('/lost-sales', [App\Http\Controllers\ReportController::class, 'lostSales'])->name('reports.lost-sales');
        Route::get('/debtors-ageing', [App\Http\Controllers\ReportController::class, 'debtorsAgeing'])->name('reports.debtors-ageing');
        Route::get('/creditors-ageing', [App\Http\Controllers\ReportController::class, 'creditorsAgeing'])->name('reports.creditors-ageing');
        Route::get('/negative-stock', [App\Http\Controllers\ReportController::class, 'negativeStock'])->name('reports.negative-stock');
        Route::get('/inventory-valuation', [App\Http\Controllers\ReportController::class, 'inventoryValuation'])->name('reports.inventory-valuation');
        Route::get('/stock-movement', [App\Http\Controllers\ReportController::class, 'stockMovement'])->name('reports.stock-movement');
    });

    // Settings Routes (Owner only)
    Route::middleware('role:Owner')->prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/company', [App\Http\Controllers\SettingsController::class, 'updateCompany'])->name('settings.update-company');
        Route::post('/vat', [App\Http\Controllers\SettingsController::class, 'updateVat'])->name('settings.update-vat');
        Route::post('/fees', [App\Http\Controllers\SettingsController::class, 'updateFees'])->name('settings.update-fees');
        Route::post('/numbering', [App\Http\Controllers\SettingsController::class, 'updateNumbering'])->name('settings.update-numbering');
        Route::post('/pos', [App\Http\Controllers\SettingsController::class, 'updatePos'])->name('settings.update-pos');
        Route::post('/email', [App\Http\Controllers\SettingsController::class, 'updateEmail'])->name('settings.update-email');
        Route::post('/whatsapp', [App\Http\Controllers\SettingsController::class, 'updateWhatsApp'])->name('settings.update-whatsapp');
        Route::post('/security', [App\Http\Controllers\SettingsController::class, 'updateSecurity'])->name('settings.update-security');
        Route::post('/remove-logo', [App\Http\Controllers\SettingsController::class, 'removeLogo'])->name('settings.remove-logo');
        Route::post('/remove-terms-pdf', [App\Http\Controllers\SettingsController::class, 'removeTermsPdf'])->name('settings.remove-terms-pdf');
    });

    // Statements Routes
    Route::prefix('statements')->group(function () {
        // Customer Statements
        Route::get('/customer/{customer}', [App\Http\Controllers\StatementController::class, 'customerStatement'])->name('statements.customer');
        Route::get('/customer/{customer}/form', [App\Http\Controllers\StatementController::class, 'customerStatementForm'])->name('statements.customer-form');
        
        // Supplier Statements (Owner only)
        Route::middleware('role:Owner')->group(function () {
            Route::get('/supplier/{supplier}', [App\Http\Controllers\StatementController::class, 'supplierStatement'])->name('statements.supplier');
            Route::get('/supplier/{supplier}/form', [App\Http\Controllers\StatementController::class, 'supplierStatementForm'])->name('statements.supplier-form');
        });
    });

});