@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-3" style="background: #f5f7fa; min-height: 100vh;">
    <!-- POS Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="ri-shopping-bag-3-line me-2 text-primary"></i>Point of Sale
            </h4>
            <p class="text-muted mb-0">
                <i class="ri-time-line me-1"></i>{{ now()->format('d M Y, h:i A') }} | 
                <i class="ri-user-line me-1"></i>{{ auth()->user()->name }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-light" onclick="clearCart()">
                <i class="ri-refresh-line me-1"></i> Clear
            </button>
            <button type="button" class="btn btn-light" onclick="loadRecentSales()">
                <i class="ri-history-line me-1"></i> Recent
            </button>
        </div>
    </div>

    <div class="row g-3">
        <!-- Left Column - Product Search & Selection -->
        <div class="col-lg-8">
            <!-- Product Search Bar -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                        <div class="input-group input-group-lg">
                        <span class="input-group-text bg-primary text-white">
                                <i class="ri-barcode-line"></i>
                            </span>
                            <input type="text" 
                               class="form-control border-0" 
                                   id="productSearch" 
                               placeholder="Scan barcode or search by name, SKU..."
                                   autocomplete="off"
                                   autofocus>
                            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        
                        <!-- Search Results Dropdown -->
                    <div id="searchResults" class="search-results-dropdown mt-2" style="display: none;">
                            <!-- Results will appear here -->
                    </div>
                </div>
            </div>

            <!-- Cart Items Display -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="ri-shopping-cart-line me-2"></i>Cart Items
                        </h6>
                    <span class="badge bg-white text-success fw-bold" id="cartItemCountLeft">0</span>
                        </div>
                <div class="card-body p-0">
                    <div id="cartItemsLeft" style="max-height: 580px; overflow-y: auto;">
                        <div class="text-center text-muted py-5">
                            <i class="ri-shopping-cart-line fs-48 mb-3 d-block text-muted"></i>
                            <h6 class="text-muted">Cart is empty</h6>
                            <p class="mb-0 small text-muted">Search and add products to cart</p>
                    </div>
                </div>
                    </div>
                </div>
            </div>

        <!-- Right Column - Customer & Summary -->
        <div class="col-lg-4">
            <!-- Customer Selection -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="ri-user-line me-2"></i>Customer
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <div class="input-group">
                            <select class="form-select form-select-sm" id="customerSelect" onchange="selectCustomer()">
                                <option value="">Walk-in Customer</option>
                            </select>
                            <button class="btn btn-sm btn-outline-primary" onclick="showAddCustomerModal()" title="Add New Customer">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="customerInfo" class="d-none">
                        <!-- Customer details will be shown here -->
                    </div>

                    <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="vatEnabled" onchange="toggleVAT()">
                            <label class="form-check-label" for="vatEnabled">
                            <small>Enable VAT (15%)</small>
                            </label>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="ri-car-line me-2"></i>Vehicle Details
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">Make</label>
                            <select class="form-select form-select-sm select2-vehicle-make" id="vehicleMake">
                                <option value="">Select Make</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Model</label>
                            <select class="form-select form-select-sm select2-vehicle-model" id="vehicleModel">
                                <option value="">Select Model</option>
                            </select>
                    </div>
                        <div class="col-md-6">
                            <label class="form-label small">Engine</label>
                            <select class="form-select form-select-sm select2-vehicle-engine" id="vehicleEngine">
                                <option value="">Select Engine</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Year</label>
                            <input type="number" class="form-control form-control-sm" id="vehicleYear" placeholder="e.g., 2020">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">VIN Number</label>
                            <input type="text" class="form-control form-control-sm" id="vehicleVin" placeholder="Vehicle Identification Number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Registration</label>
                            <input type="text" class="form-control form-control-sm" id="vehicleReg" placeholder="e.g., ABC123GP">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small">Mileage</label>
                            <input type="number" class="form-control form-control-sm" id="vehicleMileage" placeholder="e.g., 50000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-warning text-dark py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="ri-calculator-line me-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">Subtotal</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="subtotalDisplay" value="R 0.00" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                Discount
                                <i class="ri-information-line text-info ms-1" data-bs-toggle="tooltip" title="Enter discount amount"></i>
                            </label>
                            <input type="number" class="form-control form-control-sm" id="discountInput" value="0.00" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">Shipping</label>
                            <input type="number" class="form-control form-control-sm" id="shippingInput" value="0.00" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                VAT (15%)
                                <span class="badge bg-info badge-sm">Inc</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-text">
                                    <input type="checkbox" class="form-check-input" id="vatEnabled" onchange="toggleVAT()">
                        </div>
                                <input type="text" class="form-control bg-light" id="vatAmountDisplay" value="R 0.00" readonly>
                    </div>
                        </div>
                    </div>

                    <hr class="my-2">
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">GRAND TOTAL:</span>
                        <span class="fw-bold fs-5 text-primary" id="grandTotal">R 0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Amount Paid:</span>
                        <span class="fw-bold text-success" id="amountPaidDisplay">R 0.00</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">BALANCE DUE:</span>
                        <span class="fw-bold fs-5 text-danger" id="balanceDue">R 0.00</span>
                </div>
            </div>
            </div>

            <!-- Checkout Button -->
            <button type="button" class="btn btn-primary btn-lg w-100 shadow-sm" onclick="processSale()" id="checkoutBtn">
                <i class="ri-shopping-cart-check-line me-2"></i>Checkout
            </button>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-user-add-line me-2"></i>Add New Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCustomerForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Customer Name *</label>
                        <input type="text" class="form-control" id="newCustomerName" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Customer Type *</label>
                        <select class="form-select" id="newCustomerType" required>
                            <option value="cash">Cash Customer</option>
                            <option value="credit">Credit Customer</option>
                            </select>
                        </div>
                    
                    <div class="mb-3" id="creditLimitRow" style="display: none;">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <input type="number" class="form-control" id="newCustomerCreditLimit" value="0" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="newCustomerEmail">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="newCustomerPhone">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" id="newCustomerAddress" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addNewCustomer()">
                    <i class="ri-check-line me-1"></i>Add Customer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="customerTypeAlert"></div>
                
                    <div class="mb-3">
                    <label class="form-label fw-bold">Payment Method</label>
                    <select class="form-select" id="paymentMethod" onchange="updatePaymentFields()">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="eft">EFT</option>
                        <option value="on_account" id="onAccountOption" style="display: none;">On Account</option>
                    </select>
                    </div>

                <div class="mb-3" id="amountPaidRow">
                    <label class="form-label fw-bold">Amount Paid</label>
                    <input type="number" class="form-control form-control-lg" id="amountPaid" step="0.01" min="0">
                    </div>

                <div class="mb-3" id="paymentReferenceRow">
                    <label class="form-label">Reference</label>
                    <input type="text" class="form-control" id="paymentReference" placeholder="Optional">
                    </div>

                <div class="alert alert-info mb-0" id="changeRow">
                    <div class="d-flex justify-content-between">
                        <span>Change:</span>
                        <span class="fw-bold" id="changeAmount">R 0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmPayment()">
                    <i class="ri-check-line me-1"></i>Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        max-height: 500px;
    overflow-y: auto;
        margin-top: 5px;
}

.search-result-item {
        padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
}

.search-result-item:hover {
        background: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.product-card {
    cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
}

.product-card:hover {
    transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: #0d6efd;
}

.cart-item {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .cart-item:last-child {
        border-bottom: none;
}

.quantity-controls {
    display: flex;
    align-items: center;
        gap: 8px;
        justify-content: center;
}

.quantity-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
        padding: 0;
}

.quantity-btn:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
    }

    .quantity-btn.btn-sm {
        width: 24px;
        height: 24px;
}

.quantity-input {
        width: 50px;
    text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px;
}

.stock-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    #productTableBody tr {
        cursor: pointer;
    }

    #productTableBody tr:hover {
        background: #f8f9fa;
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .product-image-placeholder {
        width: 50px;
        height: 50px;
        background: #f0f0f0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
    }

    .product-image-placeholder i {
        font-size: 24px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let products = [];
let customers = [];
let categories = [];
let vehicleMakes = [];
let vehicleModels = [];
let vehicleEngines = [];
let cart = [];
let currentCustomer = null;
let vatEnabled = false;
let vatRate = 15;
let discountAmount = 0;
let discountType = 'amount';
let shippingAmount = 0;

// Initialize
$(document).ready(function() {
    loadProducts();
    loadCustomers();
    loadCategories();
    // Load data first, then initialize Select2
    loadVehicleMakes();
    loadVehicleModels();
    loadVehicleEngines();
    
    // Product search with debounce
    let searchTimeout;
    $('#productSearch').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchProducts($(this).val());
        }, 300);
    });

    // Enter key to quick add
    $('#productSearch').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            quickAddFirstResult($(this).val());
        }
    });

    // Amount paid change
    $('#amountPaid').on('input', function() {
        calculateChange();
    });
});

// Load products
function loadProducts() {
    fetch('{{ route('pos.products') }}')
        .then(response => response.json())
        .then(data => {
            products = data;
            displayProducts();
        })
        .catch(error => {
            console.error('Error loading products:', error);
            toastr.error('Error loading products');
        });
}

// Load customers
function loadCustomers() {
    fetch('{{ route('pos.customers') }}')
        .then(response => response.json())
        .then(data => {
            customers = data.map(customer => ({
                ...customer,
                customer_type: customer.customer_type || 'cash',
                credit_limit: parseFloat(customer.credit_limit) || 0,
                balance: parseFloat(customer.balance) || 0
            }));
            populateCustomerDropdown();
        })
        .catch(error => {
            console.error('Error loading customers:', error);
        });
}

// Load categories
function loadCategories() {
    fetch('{{ route('pos.categories') }}')
        .then(response => response.json())
        .then(data => {
            categories = data;
            populateCategoryFilter();
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });
}

// Load vehicle makes
function loadVehicleMakes() {
    fetch('{{ route('car-makes.index') }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            vehicleMakes = data;
            populateVehicleMakes();
        })
        .catch(error => {
            console.error('Error loading vehicle makes:', error);
        });
}

// Load vehicle models
function loadVehicleModels() {
    fetch('{{ route('car-models.index') }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            vehicleModels = data;
            populateVehicleModels();
        })
        .catch(error => {
            console.error('Error loading vehicle models:', error);
        });
}

// Load vehicle engines
function loadVehicleEngines() {
    fetch('{{ route('engines.index') }}', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            vehicleEngines = data;
            populateVehicleEngines();
            // Initialize Select2 after all data is loaded
            initVehicleSelect2();
        })
        .catch(error => {
            console.error('Error loading vehicle engines:', error);
        });
}

// Populate vehicle makes dropdown
function populateVehicleMakes() {
    const select = $('#vehicleMake');
    
    // Destroy Select2 if initialized
    if (select.hasClass('select2-hidden-accessible')) {
        select.select2('destroy');
    }
    
    // Clear existing options except first
    select.find('option:not(:first)').remove();
    
    // Add new options
    vehicleMakes.forEach(make => {
        select.append(`<option value="${make.id}" data-name="${make.name}">${make.name}</option>`);
    });
    
    // Re-initialize Select2
    initVehicleSelect2();
}

// Populate vehicle models dropdown
function populateVehicleModels() {
    const select = $('#vehicleModel');
    
    // Destroy Select2 if initialized
    if (select.hasClass('select2-hidden-accessible')) {
        select.select2('destroy');
    }
    
    // Clear existing options except first
    select.find('option:not(:first)').remove();
    
    // Add new options
    vehicleModels.forEach(model => {
        select.append(`<option value="${model.id}" data-name="${model.name}" data-make-id="${model.make_id}">${model.name}</option>`);
    });
    
    // Re-initialize Select2
    initVehicleSelect2();
}

// Populate vehicle engines dropdown
function populateVehicleEngines() {
    const select = $('#vehicleEngine');
    
    // Destroy Select2 if initialized
    if (select.hasClass('select2-hidden-accessible')) {
        select.select2('destroy');
    }
    
    // Clear existing options except first
    select.find('option:not(:first)').remove();
    
    // Add new options
    vehicleEngines.forEach(engine => {
        select.append(`<option value="${engine.id}" data-code="${engine.code}">${engine.code}</option>`);
    });
    
    // Re-initialize Select2
    initVehicleSelect2();
}

// Initialize Select2 for vehicle dropdowns (only once per element)
function initVehicleSelect2() {
    $('.select2-vehicle-make, .select2-vehicle-model, .select2-vehicle-engine').each(function() {
        // Skip if already initialized
        if ($(this).hasClass('select2-hidden-accessible')) {
        return;
    }
    
        const $element = $(this);
        $element.select2({
            width: '100%',
            placeholder: $element.find('option:first').text(),
            allowClear: true,
            tags: true,
            dropdownParent: $element.closest('.modal').length ? $element.closest('.modal') : $('body'),
            createTag: function(params) {
                const term = $.trim(params.term);
                if (term === '') return null;
                return {
                    id: 'new:' + term,
                    text: term + ' (Press Enter to add)',
                    newTag: true
                };
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            if (data.newTag) {
                const newName = data.text.replace(' (Press Enter to add)', '');
                const $select = $(this);
                let endpoint = '';
                let makeId = null;

                // Determine endpoint based on field type
                if ($select.hasClass('select2-vehicle-make')) {
                    endpoint = '{{ route('car-makes.quick-add') }}';
                } else if ($select.hasClass('select2-vehicle-model')) {
                    endpoint = '{{ route('car-models.quick-add') }}';
                    // Get selected make ID
                    const selectedMake = $('#vehicleMake').val();
                    if (selectedMake && !selectedMake.startsWith('new:')) {
                        makeId = selectedMake;
                    }
                } else if ($select.hasClass('select2-vehicle-engine')) {
                    endpoint = '{{ route('car-engines.quick-add') }}';
                }

                // AJAX call to save
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        name: newName,
                        make_id: makeId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const newId = response.data.id;
                            const newName = response.data.name;
                            
                            if ($select.hasClass('select2-vehicle-make')) {
                                vehicleMakes.push({ id: newId, name: newName });
                            } else if ($select.hasClass('select2-vehicle-model')) {
                                vehicleModels.push({ id: newId, name: newName, make_id: makeId });
                            } else if ($select.hasClass('select2-vehicle-engine')) {
                                vehicleEngines.push({ id: newId, code: newName });
                            }

                            const newOption = new Option(newName, newId, true, true);
                            $select.append(newOption);
                            $select.val(newId).trigger('change');

                            toastr.success(newName + ' added successfully!');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add. Please try again.');
                        $select.val('').trigger('change');
                    }
                });
            }
        });
    });
}


// Make selection handler - filter models by selected make (only once, outside initVehicleSelect2)
$(document).on('change', '#vehicleMake', function() {
    const makeId = $(this).val();
    const modelSelect = $('#vehicleModel');
    const engineSelect = $('#vehicleEngine');

    // Filter models by selected make
    modelSelect.find('option').each(function() {
        const option = $(this);
        const makeIdAttr = option.data('make-id');

        if (option.val() === '' || makeIdAttr == makeId || makeId === '') {
            option.show();
        } else {
            option.hide();
        }
    });

    // Reset model and engine selections
    modelSelect.val('').trigger('change');
    engineSelect.val('').trigger('change');
});

// Populate customer dropdown
function populateCustomerDropdown() {
    const select = $('#customerSelect');
    customers.forEach(customer => {
        select.append(`<option value="${customer.id}">${customer.name}</option>`);
    });
}

// Populate category filter
function populateCategoryFilter() {
    const select = $('#categoryFilter');
    categories.forEach(category => {
        select.append(`<option value="${category.id}">${category.name}</option>`);
    });
}

// Search products
function searchProducts(query) {
    if (!query) {
        $('#searchResults').hide();
        return;
    }

    const filtered = products.filter(p =>
        p.name.toLowerCase().includes(query.toLowerCase()) ||
        p.sku.toLowerCase().includes(query.toLowerCase()) ||
        (p.barcode_primary && p.barcode_primary.toLowerCase().includes(query.toLowerCase()))
    );

    if (filtered.length === 0) {
        $('#searchResults').html('<div class="p-3 text-center text-muted">No products found</div>').show();
        return;
    }
    
    let html = '';
    filtered.slice(0, 10).forEach(product => {
        const onHand = product.on_hand || 0;
        const reserved = product.reserved || 0;
        const available = onHand - reserved;
        
        // Determine stock badge
        let stockBadge = '';
        if (available < 0) {
            stockBadge = `<span class="badge bg-danger stock-badge" title="Negative Stock">Out of Stock</span>`;
        } else if (available === 0) {
            stockBadge = `<span class="badge bg-warning text-dark stock-badge" title="No Stock Available">No Stock</span>`;
        } else if (available <= 10) {
            stockBadge = `<span class="badge bg-warning text-dark stock-badge" title="Low Stock">Low: ${available}</span>`;
        } else {
            stockBadge = `<span class="badge bg-success stock-badge" title="In Stock">Available: ${available}</span>`;
        }
        
        // Add reserved info if reserved > 0
        let reservedInfo = '';
        if (reserved > 0) {
            reservedInfo = `<small class="text-warning d-block mt-1" title="Reserved for jobs">Reserved: ${reserved}</small>`;
        }
        
        html += `
            <div class="search-result-item">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                        ${product.image ? 
                                `<img src="${product.image}" class="product-image">` :
                                `<div class="product-image-placeholder">
                                <i class="ri-image-line text-muted"></i>
                            </div>`
                        }
                            <div>
                                <div class="fw-bold">${product.name}</div>
                        <small class="text-muted">SKU: ${product.sku}</small>
                    </div>
                    </div>
                    </div>
                    <div class="text-end me-3">
                        <div class="fw-bold text-primary mb-1">R ${parseFloat(product.price_normal).toFixed(2)}</div>
                        ${stockBadge}
                        ${reservedInfo}
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                        <i class="ri-add-line me-1"></i>Add
                    </button>
                </div>
            </div>
        `;
    });
    
    $('#searchResults').html(html).show();
}

// Quick add first result
function quickAddFirstResult(query) {
    const filtered = products.filter(p =>
        p.name.toLowerCase().includes(query.toLowerCase()) ||
        p.sku.toLowerCase().includes(query.toLowerCase())
    );
    
    if (filtered.length > 0) {
        addToCart(filtered[0]);
        $('#productSearch').val('');
        $('#searchResults').hide();
    }
}

// Display products (not used - keeping for compatibility)
function displayProducts() {
    // Products are now shown only in search results
}

// Add to cart
function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price_normal),
            quantity: 1,
            discount: 0,
            stock: product.on_hand || 0
        });
    }
    
    updateCartDisplay();
    toastr.success(`${product.name} added to cart`);
    $('#productSearch').val('');
    $('#searchResults').hide();
}

// Update cart display
function updateCartDisplay() {
    const container = $('#cartItemsLeft');
    const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    $('#cartItemCountLeft').text(itemCount);
    
    if (cart.length === 0) {
        container.html(`
            <div class="text-center text-muted py-5">
                <i class="ri-shopping-cart-line fs-48 mb-3 d-block text-muted"></i>
                <h6 class="text-muted">Cart is empty</h6>
                <p class="mb-0 small text-muted">Search and add products to cart</p>
            </div>
        `);
        updateCartTotals();
        return;
    }

    let html = '<div class="table-responsive"><table class="table table-hover align-middle mb-0">';
            html += `
        <thead class="table-light sticky-top">
            <tr>
                <th style="width: 50px;">Image</th>
                <th>Product</th>
                <th style="width: 100px;">SKU</th>
                <th style="width: 80px;" class="text-end">Price</th>
                <th style="width: 100px;" class="text-center">Qty</th>
                <th style="width: 80px;" class="text-end">
                    Discount
                    <i class="ri-information-line text-warning ms-1" data-bs-toggle="tooltip" title="Max {{ auth()->user()->max_discount_allowed ?? 10 }}% per line"></i>
                </th>
                <th style="width: 100px;" class="text-end">Total</th>
                <th style="width: 60px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
    `;
    
    cart.forEach(item => {
        html += `
            <tr>
                <td>
                    <div class="product-image-placeholder">
                        <i class="ri-image-line text-muted"></i>
                        </div>
                </td>
                <td>
                    <div class="fw-bold">${item.name}</div>
                </td>
                <td><small class="text-muted">${item.sku}</small></td>
                <td class="text-end">R ${item.price.toFixed(2)}</td>
                <td class="text-center">
                        <div class="quantity-controls">
                        <button class="quantity-btn btn-sm" onclick="decreaseQuantity(${item.id})">
                                <i class="ri-subtract-line"></i>
                            </button>
                        <input type="number" class="quantity-input" value="${item.quantity}" 
                               onchange="updateQuantity(${item.id}, this.value)" min="1">
                        <button class="quantity-btn btn-sm" onclick="increaseQuantity(${item.id})">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm item-discount" 
                           data-item-id="${item.id}" 
                           value="${item.discount || 0}" 
                           step="0.01" min="0" 
                           placeholder="0.00"
                           style="width: 80px;">
                </td>
                <td class="text-end fw-bold text-primary">R ${((item.price * item.quantity) - (item.discount || 0)).toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-link text-danger p-0" onclick="removeFromCart(${item.id})" title="Remove">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
            `;
        });
        
    html += '</tbody></table></div>';
        container.html(html);
    updateCartTotals();
}

// Update cart totals
function updateCartTotals() {
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get additional discount and shipping from input fields
    discountAmount = parseFloat($('#discountInput').val()) || 0;
    shippingAmount = parseFloat($('#shippingInput').val()) || 0;
    
    // Total after additional discount and shipping
    const totalAfterDiscount = subtotal - discountAmount + shippingAmount;
    
    // Calculate VAT
    let vatAmount = 0;
    if (vatEnabled) {
        vatAmount = totalAfterDiscount * (vatRate / 100);
    }

    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Update display
    $('#subtotalDisplay').val('R ' + subtotal.toFixed(2));
    $('#vatAmountDisplay').val('R ' + vatAmount.toFixed(2));
    $('#grandTotal').text('R ' + grandTotal.toFixed(2));
    $('#amountPaidDisplay').text('R 0.00');
    $('#balanceDue').text('R ' + grandTotal.toFixed(2));
}

// Increase quantity
function increaseQuantity(productId) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += 1;
        updateCartDisplay();
    }
}

// Decrease quantity
function decreaseQuantity(productId) {
    const item = cart.find(item => item.id === productId);
    if (item && item.quantity > 1) {
        item.quantity -= 1;
            updateCartDisplay();
        }
}

// Update quantity
function updateQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        const qty = parseInt(quantity) || 1;
        item.quantity = qty;
        updateCartDisplay();
    }
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartDisplay();
    toastr.info('Item removed from cart');
}

// Select customer
function selectCustomer() {
    const customerId = $('#customerSelect').val();
    
    if (customerId) {
        currentCustomer = customers.find(c => c.id == customerId);
        displayCustomerInfo();
        
        // Auto-enable VAT for credit customers
        if (currentCustomer && currentCustomer.customer_type === 'credit') {
            $('#vatEnabled').prop('checked', true);
            vatEnabled = true;
            updateCartTotals();
    } else {
            $('#vatEnabled').prop('checked', false);
            vatEnabled = false;
            updateCartTotals();
        }
    } else {
        currentCustomer = null;
        $('#customerInfo').addClass('d-none');
        $('#vatEnabled').prop('checked', false);
        vatEnabled = false;
        updateCartTotals();
    }
    
    updatePaymentMethods();
}

// Display customer info
function displayCustomerInfo() {
    if (!currentCustomer) return;
    
    const html = `
        <div class="alert alert-info py-2 mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="fw-bold">${currentCustomer.name}</small><br>
                    <small class="text-muted">${currentCustomer.email || currentCustomer.phone || ''}</small>
                        </div>
                        <div class="text-end">
                    <small class="text-muted">Credit Limit</small><br>
                    <small class="fw-bold">R ${(currentCustomer.credit_limit || 0).toFixed(2)}</small>
                        </div>
                    </div>
                </div>
            `;
        
    $('#customerInfo').removeClass('d-none').html(html);
    }
    
// Toggle VAT
function toggleVAT() {
    vatEnabled = $('#vatEnabled').is(':checked');
    updateCartTotals();
}

// Update payment methods
function updatePaymentMethods() {
    const select = $('#paymentMethod');
    const currentValue = select.val();
    
    select.find('option:not(:first)').remove();
    
    if (currentCustomer && currentCustomer.customer_type === 'credit') {
        select.append('<option value="on_account">On Account</option>');
        $('#onAccountOption').show();
        } else {
        $('#onAccountOption').hide();
    }
    
    select.append('<option value="cash">Cash</option>');
    select.append('<option value="card">Card</option>');
    select.append('<option value="eft">EFT</option>');
    
    if (currentValue) select.val(currentValue);
}

// Calculate change
function calculateChange() {
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get additional discount and shipping from input fields
    const discount = parseFloat($('#discountInput').val()) || 0;
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    const change = amountPaid - grandTotal;
    $('#changeAmount').text('R ' + change.toFixed(2));
}

// Clear cart
function clearCart() {
    if (cart.length > 0) {
        if (confirm('Are you sure you want to clear the cart?')) {
            cart = [];
            discountAmount = 0;
            shippingAmount = 0;
            $('#discountInput').val('0.00');
            $('#shippingInput').val('0.00');
            $('#vehicleMake').val('').trigger('change');
            $('#vehicleModel').val('').trigger('change');
            $('#vehicleEngine').val('').trigger('change');
            $('#vehicleYear').val('');
            $('#vehicleReg').val('');
            $('#vehicleVin').val('');
            $('#vehicleMileage').val('');
            $('#customerSelect').val('');
            currentCustomer = null;
            $('#customerInfo').addClass('d-none');
            $('#vatEnabled').prop('checked', false);
            vatEnabled = false;
            updateCartDisplay();
            toastr.info('Cart cleared');
        }
    }
}

// Clear search
function clearSearch() {
    $('#productSearch').val('');
    $('#searchResults').hide();
}

// Sort products (not used - keeping for compatibility)
function sortProductList() {
    // Products are now shown only in search results
}

// Filter by category (not used - keeping for compatibility)
function filterByCategory() {
    // Products are now shown only in search results
}

// Load recent sales
function loadRecentSales() {
    toastr.info('Recent sales feature coming soon');
}

// Discount and shipping input handlers
$(document).on('input', '#discountInput', function() {
    let discountValue = parseFloat($(this).val()) || 0;
    
    // Validate discount
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Prevent negative values
    if (discountValue < 0) {
        $(this).val('0.00');
        discountValue = 0;
    }
    
    // Prevent discount greater than subtotal
    if (discountValue > subtotal && subtotal > 0) {
        $(this).val(subtotal.toFixed(2));
        discountValue = subtotal;
        toastr.error('Discount cannot exceed subtotal');
    }
    
    // Prevent extremely large numbers
    if (discountValue > 999999.99) {
        $(this).val('999999.99');
        discountValue = 999999.99;
        toastr.error('Discount too large. Maximum is R999,999.99');
    }
    
    discountAmount = discountValue;
    updateCartTotals();
});

$(document).on('input', '#shippingInput', function() {
    let shippingValue = parseFloat($(this).val()) || 0;
    
    // Prevent negative values
    if (shippingValue < 0) {
        $(this).val('0.00');
        shippingValue = 0;
    }
    
    // Prevent extremely large numbers
    if (shippingValue > 999999.99) {
        $(this).val('999999.99');
        shippingValue = 999999.99;
        toastr.error('Shipping too large. Maximum is R999,999.99');
    }
    
    shippingAmount = shippingValue;
    updateCartTotals();
});

// Item discount input handler
$(document).on('input', '.item-discount', function() {
    const itemId = parseInt($(this).data('item-id'));
    let discountValue = parseFloat($(this).val()) || 0;
    
    const item = cart.find(item => item.id === itemId);
    if (item) {
        // Prevent negative values
        if (discountValue < 0) {
            $(this).val('0.00');
            discountValue = 0;
        }
        
        // Validate discount limit
        const maxDiscountAllowed = {{ (auth()->user()->max_discount_allowed ?? 10) }};
        const lineTotal = item.price * item.quantity;
        const maxDiscountAmount = (lineTotal * maxDiscountAllowed) / 100;
        
        if (discountValue > maxDiscountAmount) {
            toastr.warning(`Discount cannot exceed ${maxDiscountAllowed}% (R${maxDiscountAmount.toFixed(2)}) for your role`);
            $(this).val(maxDiscountAmount.toFixed(2));
            discountValue = maxDiscountAmount;
        }
        
        // Prevent discount greater than line total
        if (discountValue > lineTotal) {
            $(this).val(lineTotal.toFixed(2));
            discountValue = lineTotal;
            toastr.error('Discount cannot exceed line total');
        }
        
        item.discount = discountValue;
        updateCartDisplay();
    }
});

// Show add customer modal
function showAddCustomerModal() {
    const modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
    modal.show();
    
    // Reset form
    $('#addCustomerForm')[0].reset();
    $('#creditLimitRow').hide();
    
    // Customer type change handler
    $('#newCustomerType').off('change').on('change', function() {
        if ($(this).val() === 'credit') {
            $('#creditLimitRow').show();
    } else {
            $('#creditLimitRow').hide();
        }
    });
}

// Add new customer
function addNewCustomer() {
    const name = $('#newCustomerName').val();
    const type = $('#newCustomerType').val();
    const creditLimit = $('#newCustomerCreditLimit').val() || 0;
    const email = $('#newCustomerEmail').val();
    const phone = $('#newCustomerPhone').val();
    const address = $('#newCustomerAddress').val();
    
    if (!name) {
        toastr.error('Customer name is required');
        return;
    }
    
    // Create customer via AJAX
    fetch('{{ route('customers.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: name,
            customer_type: type,
            credit_limit: creditLimit,
            email: email,
            phone: phone,
            address: address
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                toastr.success('Customer added successfully!');
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('addCustomerModal')).hide();
            
            // Reload customers
                loadCustomers();
            
            // Select the new customer
            setTimeout(() => {
                $('#customerSelect').val(data.customer.id);
                selectCustomer();
            }, 500);
            } else {
            toastr.error(data.message || 'Error adding customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error adding customer');
    });
}

// Update payment fields based on customer type and payment method
function updatePaymentFields() {
    const paymentMethod = $('#paymentMethod').val();
    const isCreditCustomer = currentCustomer && currentCustomer.customer_type === 'credit';
    
    if (paymentMethod === 'on_account') {
        // On account - hide amount paid and change
        $('#amountPaidRow').hide();
        $('#changeRow').hide();
        $('#amountPaid').val(0);
            } else {
        // Cash/Card/EFT - show amount paid and change
        $('#amountPaidRow').show();
        $('#changeRow').show();
        calculateChange();
    }
}

// Process sale
function processSale() {
    if (cart.length === 0) {
        toastr.error('Cart is empty');
        return;
    }
    
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get additional discount and shipping from input fields
    const discount = parseFloat($('#discountInput').val()) || 0;
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Check if credit customer - skip payment modal, create invoice directly
    if (currentCustomer && currentCustomer.customer_type === 'credit') {
        // Validate credit limit
        const availableCredit = (currentCustomer.credit_limit || 0) - Math.abs(currentCustomer.balance || 0);
        
        if (grandTotal > availableCredit) {
            toastr.error(`Insufficient credit limit. Available: R ${availableCredit.toFixed(2)}, Required: R ${grandTotal.toFixed(2)}`);
            return;
    }
    
        // Directly process sale with on_account payment (no confirmation)
        processSaleDirect('on_account', 0, '');
        return;
    }
    
    // Cash customer - show payment modal
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    
    // Set default amount paid
    $('#amountPaid').val(grandTotal.toFixed(2));
    
    // Customer type alert
    let alertHtml = `
        <div class="alert alert-warning">
            <i class="ri-alert-line me-2"></i><strong>Cash Customer</strong><br>
            <small>Cash customers must pay immediately.</small>
        </div>
    `;
    
    $('#customerTypeAlert').html(alertHtml);
    
    // Update payment fields
    updatePaymentFields();
    
    paymentModal.show();
}

// Process sale directly (for credit customers)
function processSaleDirect(paymentMethod, amountPaid, paymentReference) {
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get additional discount and shipping from input fields
    const discount = parseFloat($('#discountInput').val()) || 0;
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Prepare sale data
    const saleData = {
        cart: cart,
        customer_id: currentCustomer ? currentCustomer.id : null,
        payment_method: paymentMethod,
        amount_paid: amountPaid,
        payment_reference: paymentReference,
        vat_enabled: vatEnabled,
        vat_rate: vatRate,
        discount_amount: discount,
        discount_type: 'amount',
        shipping: shipping,
        vehicle_make: $('#vehicleMake').val(),
        vehicle_model: $('#vehicleModel').val(),
        vehicle_engine: $('#vehicleEngine').val(),
        vehicle_year: $('#vehicleYear').val(),
        vehicle_reg: $('#vehicleReg').val(),
        vehicle_vin: $('#vehicleVin').val(),
        vehicle_mileage: $('#vehicleMileage').val(),
        _token: '{{ csrf_token() }}'
    };
    
    // Process sale
    fetch('{{ route('pos.process-sale') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(saleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Invoice created successfully!');
            
            // Show stock warning if exists
            if (data.stock_warning) {
                toastr.warning(data.stock_warning, 'Stock Warning', {
                    timeOut: 10000,
                    extendedTimeOut: 5000
                });
            }
            
            // Clear cart and reset
            cart = [];
            discountAmount = 0;
            shippingAmount = 0;
            $('#discountInput').val('0.00');
            $('#shippingInput').val('0.00');
            $('#vehicleMake').val('').trigger('change');
            $('#vehicleModel').val('').trigger('change');
            $('#vehicleEngine').val('').trigger('change');
            $('#vehicleYear').val('');
            $('#vehicleReg').val('');
            $('#vehicleVin').val('');
            $('#vehicleMileage').val('');
                $('#customerSelect').val('');
            currentCustomer = null;
                $('#customerInfo').addClass('d-none');
                $('#vatEnabled').prop('checked', false);
                vatEnabled = false;
            updateCartDisplay();
            } else {
            toastr.error(data.message || 'Error creating invoice');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error creating invoice');
    });
}

// Confirm payment
function confirmPayment() {
    const paymentMethod = $('#paymentMethod').val();
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const paymentReference = $('#paymentReference').val();
    
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get additional discount and shipping from input fields
    const discount = parseFloat($('#discountInput').val()) || 0;
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Validation
    if (paymentMethod === 'on_account') {
        if (!currentCustomer || currentCustomer.customer_type !== 'credit') {
            toastr.error('Only credit customers can use on account payment');
            return;
        }
        
        // Check credit limit
        const availableCredit = (currentCustomer.credit_limit || 0) - Math.abs(currentCustomer.balance || 0);
        if (grandTotal > availableCredit) {
            toastr.error(`Insufficient credit limit. Available: R ${availableCredit.toFixed(2)}, Required: R ${grandTotal.toFixed(2)}`);
            return;
        }
    } else {
        // Cash/Card/EFT - must pay full amount
        if (amountPaid < grandTotal) {
            toastr.error('Amount paid must be equal to or greater than total');
            return;
        }
    }
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
    
    // Process sale
    processSaleDirect(paymentMethod, amountPaid, paymentReference);
}
</script>
@endpush
