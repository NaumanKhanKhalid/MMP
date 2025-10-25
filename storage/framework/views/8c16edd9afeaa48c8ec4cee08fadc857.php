<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 py-3" style="background: #f5f7fa; min-height: 100vh;">
    <!-- POS Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="ri-shopping-bag-3-line me-2 text-primary"></i>Point of Sale
            </h4>
            <p class="text-muted mb-0">
                <i class="ri-time-line me-1"></i><?php echo e(now()->format('d M Y, h:i A')); ?> | 
                <i class="ri-user-line me-1"></i><?php echo e(auth()->user()->name); ?>

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
                        <!-- Enhanced Search Bar -->
                        <div class="input-group input-group-lg mb-3 shadow-sm" style="overflow: hidden;">
                            <span class="input-group-text bg-primary text-white border-0" style="padding: 0 15px;">
                                <i class="ri-barcode-line fs-4"></i>
                            </span>
                            <input type="text" 
                               class="form-control border-0" 
                                   id="productSearch" 
                                placeholder="Scan barcode or search by name, SKU, OE Number..."
                                   autocomplete="off"
                                autofocus
                                style="font-size: 1.1rem; background: #f8f9fa; border-left: 3px solid #007bff;">
                        </div>
                        
                        <!-- Quick Action Buttons -->
                        
                        
                        <!-- Search Results Dropdown -->
                    <div id="searchResults" class="search-results-dropdown mt-2" style="display: none;">
                            <!-- Results will appear here -->
                    </div>
                    
                    <!-- Quick Add Product -->
                    <div id="quickAddSection" class="mt-3" style="display: none;">
                        <div class="card border-success shadow-lg">
                            <div class="card-header bg-success text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="ri-add-circle-fill me-2"></i>Quick Add New Product
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white" onclick="$('#quickAddSection').slideUp();"></button>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="alert alert-light border-success mb-3 py-2">
                                    <small class="text-muted">
                                        <i class="ri-information-line text-success"></i>
                                        Product will be saved to inventory and added to cart
                                    </small>
                                </div>
                                <div class="row g-3">
                                <div class="col-md-6">
                                        <label class="form-label fw-bold mb-1">
                                            <i class="ri-product-hunt-line me-1 text-primary"></i>Product Name *
                                        </label>
                                        <input type="text" class="form-control shadow-sm" id="quickAddName" placeholder="Enter product name..." required>
                                </div>
                                <div class="col-md-3">
                                        <label class="form-label fw-bold mb-1">
                                            <i class="ri-price-tag-3-line me-1 text-success"></i>Price *
                                        </label>
                                        <input type="number" class="form-control shadow-sm" id="quickAddPrice" placeholder="R 0.00" step="0.01" required>
                                </div>
                                <div class="col-md-3">
                                        <label class="form-label fw-bold mb-1">
                                            <i class="ri-stack-line me-1 text-warning"></i>Qty *
                                        </label>
                                        <input type="number" class="form-control shadow-sm" id="quickAddQty" placeholder="1" value="1" min="0" step="1" required>
                                </div>
                            </div>
                            </div>
                            <div class="card-footer bg-light border-top-0">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success shadow-sm flex-grow-1" onclick="addQuickProduct()">
                                        <i class="ri-check-line me-1"></i>Create & Add to Cart
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="$('#quickAddSection').slideUp();">
                                        <i class="ri-close-line me-1"></i>Cancel
                            </button>
                                </div>
                            </div>
                        </div>
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
            <!-- Customer & Vehicle - Combined -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="ri-user-line me-2"></i>Customer & Vehicle
                    </h6>
                </div>
                <div class="card-body p-3">
                    <!-- Customer Search -->
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="ri-search-line me-1 text-primary"></i>Search Customer
                        </label>
                        <div class="input-group shadow-sm" style="overflow: hidden;">
                            <span class="input-group-text bg-primary text-white border-0" style="padding: 0 12px;">
                                <i class="ri-user-search-line"></i>
                            </span>
                            <input type="text" 
                                class="form-control border-0" 
                                id="customerSearch" 
                                placeholder="Type name, code, phone..."
                                style="font-size: 1rem; background: #f8f9fa; border-left: 3px solid #007bff;">
                            <button class="btn btn-warning border-0" onclick="clearCustomer()" title="Switch to Walk-in" style="display: none; padding: 0 12px;" id="clearCustomerBtn">
                                <i class="ri-refresh-line"></i>
                            </button>
                            <button class="btn btn-success border-0" onclick="showAddCustomerModal()" title="Quick Add Customer" style="padding: 0 15px;">
                                <i class="ri-user-add-line me-1"></i>Add
                            </button>
                        </div>
                        <div id="customerSearchResults" class="list-group mt-2 shadow-sm" style="display: none; max-height: 250px; overflow-y: auto; position: absolute; z-index: 1050; width: calc(100% - 30px); border-radius: 0.375rem;"></div>
                        <small class="text-muted d-block mt-1">
                            <i class="ri-information-line"></i> Leave empty for Walk-in Customer
                        </small>
                    </div>
                    
                    <!-- Customer Info Form -->
                    <div id="customerInfoForm" class="mb-3" style="display: none;">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Name</label>
                                <input type="text" class="form-control form-control-sm" id="customerName" readonly>
                    </div>
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Phone</label>
                                <input type="text" class="form-control form-control-sm" id="customerPhone" readonly>
                        </div>
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Email</label>
                                <input type="email" class="form-control form-control-sm" id="customerEmail" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Address</label>
                                <input type="text" class="form-control form-control-sm" id="customerAddress" readonly>
                    </div>
                </div>
            </div>

                    <!-- Walk-in Customer Form -->
                    <div id="walkInCustomerForm" class="mb-3">
                        <div class="alert alert-info border-info shadow-sm py-2 mb-3" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);">
                            <div class="d-flex align-items-center">
                                <i class="ri-user-smile-line fs-4 me-2 text-info"></i>
                                <div>
                                    <strong class="text-info">Walk-in Customer</strong>
                                    <small class="d-block text-muted">Add details for invoice (optional)</small>
                </div>
                            </div>
                        </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-user-line me-1 text-primary"></i>Name
                                </label>
                                <input type="text" class="form-control form-control-sm shadow-sm" id="walkInName" placeholder="Enter name...">
                        </div>
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-phone-line me-1 text-success"></i>Phone
                                </label>
                                <input type="text" class="form-control form-control-sm shadow-sm" id="walkInPhone" placeholder="Enter phone...">
                    </div>
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-mail-line me-1 text-info"></i>Email
                                </label>
                                <input type="email" class="form-control form-control-sm shadow-sm" id="walkInEmail" placeholder="Optional">
                        </div>
                        <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-map-pin-line me-1 text-danger"></i>Address
                                </label>
                                <input type="text" class="form-control form-control-sm shadow-sm" id="walkInAddress" placeholder="Optional">
                        </div>
                        </div>
                        </div>
                    
                    <!-- Vehicle Selection (Dynamic based on customer) -->
                    <div id="vehicleSection" class="mb-3" style="display: none;">
                        <label class="form-label small fw-bold mb-1"><i class="ri-car-line me-1"></i>Vehicle</label>
                        <div class="input-group input-group-sm">
                            <select class="form-select" id="vehicleSelect" onchange="selectVehicle()">
                                <option value="">Select Vehicle</option>
                            </select>
                            <button class="btn btn-success" onclick="showAddVehicleModal()" title="Add Vehicle">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                        <div id="vehicleInfo" class="mt-2" style="display: none;">
                            <!-- Vehicle details shown here -->
                        </div>
                    </div>
                    
                    <!-- Price Tier -->
                    <div class="mb-3">
                        <label class="form-label small mb-1">Price Tier</label>
                        <select class="form-select form-select-sm" id="priceTier" onchange="updatePriceTier()">
                            <option value="normal">Normal</option>
                            <option value="online">Online</option>
                            <option value="workshop">Workshop</option>
                        </select>
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
                                <?php if(\App\Models\Setting::discountType() == 'percentage'): ?>
                                    <span class="badge bg-info-transparent">%</span>
                                <?php else: ?>
                                    <span class="badge bg-success-transparent">R</span>
                                <?php endif; ?>
                                <i class="ri-information-line text-info ms-1" data-bs-toggle="tooltip" data-bs-html="true" id="discountTooltip"></i>
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

                    <!-- Amount Paid and Balance Due removed - payment happens at checkout -->
                    <!-- <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Amount Paid:</span>
                        <span class="fw-bold text-success" id="amountPaidDisplay">R 0.00</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">BALANCE DUE:</span>
                        <span class="fw-bold fs-5 text-danger" id="balanceDue">R 0.00</span>
                    </div> -->
            </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="processSale()" id="checkoutBtn">
                <i class="ri-shopping-cart-check-line me-2"></i>Checkout
            </button>
                <button type="button" class="btn btn-outline-secondary" onclick="saveAsQuotation()" id="saveQuoteBtn">
                    <i class="ri-file-list-line me-2"></i>Save as Quotation
            </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="ri-car-add-line me-2"></i>Add Vehicle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addVehicleForm">
                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small">Make *</label>
                            <select class="form-select form-select-sm select2-add-vehicle-make" id="newVehicleMake" required>
                                <option value="">Select Make</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small">Model *</label>
                            <select class="form-select form-select-sm select2-add-vehicle-model" id="newVehicleModel" required disabled>
                                <option value="">Select Model</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Engine</label>
                            <input type="text" class="form-control form-control-sm" id="newVehicleEngine" placeholder="e.g., 2.0T">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Year</label>
                            <input type="number" class="form-control form-control-sm" id="newVehicleYear" placeholder="e.g., 2020">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold small">Registration *</label>
                            <input type="text" class="form-control form-control-sm" id="newVehicleReg" placeholder="e.g., ABC123GP" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Mileage</label>
                            <input type="number" class="form-control form-control-sm" id="newVehicleMileage" placeholder="e.g., 50000">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newVehiclePrimary" checked>
                                <label class="form-check-label small" for="newVehiclePrimary">
                                    Set as primary vehicle
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" onclick="addNewVehicle()">
                    <i class="ri-check-line me-1"></i>Add Vehicle
                </button>
            </div>
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
                        <label class="form-label fw-bold">Payment Terms *</label>
                        <select class="form-select" id="newCustomerType" required>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
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

<!-- Post-Sale Actions Modal -->
<div class="modal fade" id="postSaleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <div class="w-100 text-center">
                    <h5 class="modal-title mb-0">
                        <i class="ri-checkbox-circle-line me-2"></i>Sale Completed Successfully
                    </h5>
                </div>
            </div>
            
            <!-- Body -->
            <div class="modal-body text-center py-4">
                <!-- Invoice Info -->
                <div class="card border-primary mb-3">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block mb-1">Invoice Number</small>
                                <h5 id="postSaleInvoiceNumber" class="text-primary mb-0 fw-bold"></h5>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Total Amount</small>
                                <h5 class="text-success mb-0 fw-bold">R <span id="postSaleTotal">0.00</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" onclick="downloadInvoicePDF()">
                        <i class="ri-file-pdf-line me-2"></i>Download PDF
                    </button>
                    <button type="button" class="btn btn-danger" onclick="printInvoiceInline()">
                        <i class="ri-printer-line me-2"></i>Print Invoice
                    </button>
                    <button type="button" class="btn btn-success" onclick="sendWhatsApp()">
                        <i class="ri-whatsapp-line me-2"></i>Send via WhatsApp
                    </button>
                    <button type="button" class="btn btn-info" onclick="sendEmail()">
                        <i class="ri-mail-line me-2"></i>Send via Email
                    </button>
                    <button type="button" class="btn btn-warning" onclick="downloadPickingList()">
                        <i class="ri-file-list-3-line me-2"></i>Download Picking List
                    </button>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer justify-content-center border-top">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Close
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
                        <option value="credit" id="onAccountOption" style="display: none;">Credit</option>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let products = [];
let customers = [];
let categories = [];
let customerVehicles = [];
let cart = [];
let currentCustomer = null;
let currentVehicle = null;
let vatEnabled = <?php echo e($vatSettings['enabled'] ? 'true' : 'false'); ?>;
let vatRate = <?php echo e($vatSettings['rate']); ?>;
let discountAmount = 0;
let discountType = 'amount';
let shippingAmount = 0;

// Discount Settings from database
let discountTypeSettings = '<?php echo e(\App\Models\Setting::discountType()); ?>'; // 'flat' or 'percentage'
<?php
    $userRoleName = 'staff'; // default
    if (auth()->check()) {
        $role = auth()->user()->role;
        if (is_object($role) && isset($role->name)) {
            $userRoleName = strtolower($role->name);
        } elseif (is_string($role)) {
            $userRoleName = strtolower($role);
        }
    }
    // Map common role names to our discount categories
    $roleMap = [
        'owner' => 'admin',
        'administrator' => 'admin',
        'admin' => 'admin',
        'manager' => 'manager',
        'staff' => 'staff',
        'employee' => 'staff'
    ];
    $userRoleName = $roleMap[$userRoleName] ?? 'staff';
?>
let userRole = '<?php echo e($userRoleName); ?>'; // admin, manager, staff
let maxDiscountPercentage = {
    'admin': <?php echo e(\App\Models\Setting::adminMaxDiscount()); ?>,
    'manager': <?php echo e(\App\Models\Setting::managerMaxDiscount()); ?>,
    'staff': <?php echo e(\App\Models\Setting::staffMaxDiscount()); ?>

};
let maxDiscountLimit = maxDiscountPercentage[userRole] || 10;

// Initialize
$(document).ready(function() {
    // Initialize VAT checkbox from settings
    $('#vatEnabled').prop('checked', vatEnabled);
    
    // Initialize all tooltips first
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true
        });
    });
    
    // Set dynamic tooltip for discount field after initialization
    const discountTypeText = discountTypeSettings === 'percentage' ? 'Percentage (%)' : 'Flat Amount (R)';
    const roleText = userRole.charAt(0).toUpperCase() + userRole.slice(1);
    const tooltipText = '<strong>Discount Type:</strong> ' + discountTypeText + '<br><strong>Your Role:</strong> ' + roleText + '<br><strong>Max Discount:</strong> ' + maxDiscountLimit + '%';
    
    // Get tooltip instance and update
    const discountTooltipEl = document.getElementById('discountTooltip');
    if (discountTooltipEl) {
        const tooltipInstance = bootstrap.Tooltip.getInstance(discountTooltipEl);
        if (tooltipInstance) {
            tooltipInstance.dispose();
        }
        new bootstrap.Tooltip(discountTooltipEl, {
            html: true,
            title: tooltipText
        });
    }
    
    // Initialize walk-in customer form on page load
    $('#walkInCustomerForm').show();
    $('#customerInfoForm').hide();
    
    loadProducts();
    loadCustomers();
    loadCategories();
    
    // Close customer search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#customerSearch, #customerSearchResults').length) {
            $('#customerSearchResults').hide();
        }
    });
    
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
    fetch('<?php echo e(route('pos.products')); ?>')
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
    fetch('<?php echo e(route('pos.customers')); ?>')
        .then(response => response.json())
        .then(data => {
            customers = data.map(customer => ({
                ...customer,
                credit_limit: parseFloat(customer.credit_limit) || 0,
                balance: parseFloat(customer.balance) || 0
            }));
            // Customers loaded and ready for search
        })
        .catch(error => {
            console.error('Error loading customers:', error);
        });
}

// Load categories
function loadCategories() {
    fetch('<?php echo e(route('pos.categories')); ?>')
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
    fetch('<?php echo e(route('car-makes.index')); ?>', {
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
    fetch('<?php echo e(route('car-models.index')); ?>', {
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
    fetch('<?php echo e(route('engines.index')); ?>', {
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
            dropdownParent: $('body'),
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
                    endpoint = '<?php echo e(route('car-makes.quick-add')); ?>';
                } else if ($select.hasClass('select2-vehicle-model')) {
                    endpoint = '<?php echo e(route('car-models.quick-add')); ?>';
                    // Get selected make ID
                    const selectedMake = $('#vehicleMake').val();
                    if (selectedMake && !selectedMake.startsWith('new:')) {
                        makeId = selectedMake;
                    }
                } else if ($select.hasClass('select2-vehicle-engine')) {
                    endpoint = '<?php echo e(route('car-engines.quick-add')); ?>';
                }

                // AJAX call to save
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        name: newName,
                        make_id: makeId,
                        _token: '<?php echo e(csrf_token()); ?>'
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
// Customer search with live results
let customerSearchTimeout;
$('#customerSearch').on('input', function() {
    const searchTerm = $(this).val().toLowerCase().trim();
    
    clearTimeout(customerSearchTimeout);
    
    if (searchTerm.length === 0) {
        // Walk-in customer (no customer selected)
        $('#customerSearchResults').hide();
        currentCustomer = null;
        $('#customerInfo').addClass('d-none');
        $('#vehicleSection').hide();
        $('#priceTier').closest('.mb-3').show();
        
        // Show walk-in customer form
        $('#customerInfoForm').hide();
        $('#walkInCustomerForm').show();
        $('#clearCustomerBtn').hide();
        return;
    }
    
    if (searchTerm.length < 2) {
        $('#customerSearchResults').hide();
        return;
    }
    
    customerSearchTimeout = setTimeout(() => {
        searchCustomers(searchTerm);
    }, 300);
});

function searchCustomers(searchTerm) {
    const results = customers.filter(customer => {
        return customer.name.toLowerCase().includes(searchTerm) ||
               (customer.customer_code && customer.customer_code.toLowerCase().includes(searchTerm)) ||
               (customer.phone && customer.phone.includes(searchTerm)) ||
               (customer.email && customer.email.toLowerCase().includes(searchTerm));
    });
    
    displayCustomerResults(results);
}

function displayCustomerResults(results) {
    const resultsDiv = $('#customerSearchResults');
    resultsDiv.empty();
    
    if (results.length === 0) {
        resultsDiv.html('<div class="list-group-item text-muted">No customers found</div>');
        resultsDiv.show();
        return;
    }
    
    results.forEach(customer => {
        const balance = customer.outstanding_balance || customer.balance || 0;
        const balanceClass = balance > 0 ? 'text-danger' : 'text-success';
        const termsText = customer.terms === 'credit' ? 'Credit' : 'Cash';
        
        const item = $(`
            <a href="#" class="list-group-item list-group-item-action" data-customer-id="${customer.id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${customer.name}</strong>
                        <br>
                        <small class="text-muted">${customer.customer_code || ''}</small>
                        ${customer.phone ? `<small class="text-muted"> • ${customer.phone}</small>` : ''}
                    </div>
                    <div class="text-end">
                        <span class="badge bg-${customer.terms === 'credit' ? 'warning' : 'success'}">${termsText}</span>
                        ${balance !== 0 ? `<br><small class="${balanceClass}">Bal: R ${Math.abs(balance).toFixed(2)}</small>` : ''}
                    </div>
                </div>
            </a>
        `);
        
        item.on('click', function(e) {
            e.preventDefault();
            selectCustomerFromSearch(customer);
        });
        
        resultsDiv.append(item);
    });
    
    resultsDiv.show();
}

function selectCustomerFromSearch(customer) {
    currentCustomer = customer;
    $('#customerSearch').val(customer.name);
    $('#customerSearchResults').hide();
    $('#clearCustomerBtn').show(); // Show clear button
    
    // Show customer info form (readonly)
    $('#customerInfoForm').show();
    $('#walkInCustomerForm').hide();
    
    // Populate customer details
    $('#customerName').val(customer.name || '');
    $('#customerPhone').val(customer.phone || '');
    $('#customerEmail').val(customer.email || '');
    $('#customerAddress').val(customer.address || '');
    
    displayCustomerInfo();
    loadCustomerVehicles(customer.id);
    applyPriceTierToCart();
    updatePaymentMethods();
}

// Clear customer selection (Walk-in)
function clearCustomer() {
    currentCustomer = null;
    currentVehicle = null;
    $('#customerSearch').val('');
    $('#customerInfo').addClass('d-none');
    $('#vehicleSection').hide();
    $('#vehicleSelect').html('<option value="">Select Vehicle</option>');
    $('#vehicleInfo').hide();
    $('#clearCustomerBtn').hide();
    
    // Show walk-in customer form
    $('#customerInfoForm').hide();
    $('#walkInCustomerForm').show();
    
    // Clear walk-in form
    $('#walkInName').val('');
    $('#walkInPhone').val('');
    $('#walkInEmail').val('');
    $('#walkInAddress').val('');
    
    // Show price tier dropdown for walk-in customers
    $('#priceTier').closest('.mb-3').show();
    
    updatePaymentMethods();
    updateCartTotals();
    
    toastr.info('Switched to Walk-in Customer');
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

    const searchTerm = query.toLowerCase();
    
    const filtered = products.filter(p => {
        // Search by Product Name
        if (p.name && p.name.toLowerCase().includes(searchTerm)) return true;
        
        // Search by SKU
        if (p.sku && p.sku.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Barcode
        if (p.barcode_primary && p.barcode_primary.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Description
        if (p.description && p.description.toLowerCase().includes(searchTerm)) return true;
        
        // Search by OE Numbers
        if (p.oeNumbers && p.oeNumbers.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Supplier Code
        if (p.supplier_code && p.supplier_code.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Brand Code
        if (p.brand_code && p.brand_code.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Bin Location
        if (p.bin_location && p.bin_location.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Brand Name
        if (p.brand_name && p.brand_name.toLowerCase().includes(searchTerm)) return true;
        
        // Search by Category Name
        if (p.category_name && p.category_name.toLowerCase().includes(searchTerm)) return true;
        
        return false;
    });

    if (filtered.length === 0) {
        $('#searchResults').html('<div class="p-3 text-center text-muted">No products found</div>').show();
        
        // Show Quick Add section
        $('#quickAddSection').show();
        $('#quickAddName').val(query); // Pre-fill with search term
        return;
    }
    
    // Hide Quick Add section when products are found
    $('#quickAddSection').hide();
    
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
        
        // Build OE numbers and supplier code display
        const additionalInfo = [];
        if (product.supplier_code) {
            additionalInfo.push(`<span class="badge bg-info-transparent">Supplier: ${product.supplier_code}</span>`);
        }
        if (product.oe_numbers) {
            additionalInfo.push(`<span class="badge bg-secondary-transparent">OE: ${product.oe_numbers}</span>`);
        }
        if (product.bin_location) {
            additionalInfo.push(`<span class="badge bg-primary-transparent">Bin: ${product.bin_location}</span>`);
        }
        const additionalInfoHtml = additionalInfo.length > 0 ? `<div class="mt-1">${additionalInfo.join(' ')}</div>` : '';
        
        html += `
            <div class="search-result-item" onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})" style="cursor: pointer;">
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
                        ${additionalInfoHtml}
                    </div>
                    </div>
                    </div>
                    <div class="text-end me-3">
                        <div class="fw-bold text-primary mb-1">R ${parseFloat(product.price_normal).toFixed(2)}</div>
                        ${stockBadge}
                        ${reservedInfo}
                    </div>
                    
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
function addToCart(product, quantity = 1) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price_normal),
            price_normal: parseFloat(product.price_normal),
            price_online: parseFloat(product.price_online),
            price_workshop: parseFloat(product.price_workshop),
            quantity: quantity,
            discount: 0,
            stock: product.on_hand || 0,
            available: product.on_hand || 0
        });
    }
    
    updateCartDisplay();
    toastr.success(`${product.name} added to cart`);
    $('#productSearch').val('');
    $('#searchResults').hide();
}

// Add to cart silently (no toast)
function addToCartSilent(product, quantity = 1) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price_normal),
            price_normal: parseFloat(product.price_normal),
            price_online: parseFloat(product.price_online),
            price_workshop: parseFloat(product.price_workshop),
            quantity: quantity,
            discount: 0,
            stock: product.on_hand || 0,
            available: product.on_hand || 0
        });
    }
    
    updateCartDisplay();
    $('#productSearch').val('');
    $('#searchResults').hide();
}

// Update cart display
function updateCartDisplay() {
    const container = $('#cartItemsLeft');
    const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Save currently focused element
    const focusedElement = document.activeElement;
    const isFocusedOnDiscount = focusedElement && focusedElement.classList.contains('item-discount');
    const focusedItemId = isFocusedOnDiscount ? parseInt(focusedElement.getAttribute('data-item-id')) : null;
    
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

    // Check for negative stock warnings
    let hasNegativeStock = false;
    cart.forEach(item => {
        if (item.available !== undefined && item.quantity > item.available) {
            hasNegativeStock = true;
        }
    });

    let html = '';
    
    // Add negative stock warning if needed
    if (hasNegativeStock) {
        html += `
            <div class="alert alert-warning alert-sm mb-2">
                <i class="ri-alert-line me-1"></i>
                <strong>Negative Stock Warning:</strong> Some items will go into negative stock.
            </div>
        `;
    }
    
    html += '<div class="table-responsive"><table class="table table-hover align-middle mb-0">';
            html += `
        <thead class="table-light sticky-top">
            <tr>
                <th style="width: 50px;">Image</th>
                <th>Product</th>
                <th style="width: 100px;">SKU</th>
                <th style="width: 80px;" class="text-end">Price</th>
                <th style="width: 100px;" class="text-center">Qty</th>
                <th style="width: 80px;" class="text-end">
                    Discount (R)
                    <i class="ri-information-line text-warning ms-1" data-bs-toggle="tooltip" data-bs-html="true" title="<strong>Line Item Discount</strong><br>Max: ` + maxDiscountLimit + `% (` + userRole + `)"></i>
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
                    ${item.available !== undefined && item.quantity > item.available ? 
                        '<span class="badge bg-danger-transparent badge-sm">Will go negative</span>' : 
                        item.available !== undefined && item.available <= 0 ? 
                        '<span class="badge bg-warning-transparent badge-sm">Out of stock</span>' : 
                        ''
                    }
                </td>
                <td><small class="text-muted">${item.sku}</small></td>
                <td class="text-end">
                    <?php if(in_array(auth()->user()->role, ['owner', 'manager'])): ?>
                        <input type="number" class="form-control form-control-sm text-end" 
                               value="${(item.price || 0).toFixed(2)}" 
                               onchange="updateItemPrice(${item.id}, this.value)" 
                               step="0.01" min="0" 
                               style="width: 90px;">
                    <?php else: ?>
                        R ${(item.price || 0).toFixed(2)}
                    <?php endif; ?>
                </td>
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
                <td class="text-end">
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <span class="input-group-text bg-light px-2">R</span>
                    <input type="number" class="form-control form-control-sm item-discount" 
                           data-item-id="${item.id}" 
                           value="${item.discount || 0}" 
                           step="0.01" min="0" 
                               placeholder="0"
                               title="Max: R${((item.price * item.quantity * maxDiscountLimit) / 100).toFixed(2)}"
                               style="text-align: right;">
                    </div>
                </td>
                <td class="text-end fw-bold text-primary">R ${(((item.price || 0) * (item.quantity || 0)) - (item.discount || 0)).toFixed(2)}</td>
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
    
    // Reinitialize tooltips for cart items
    const tooltipTriggerList = [].slice.call(container[0].querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true
        });
    });
    
    // Restore focus to the discount input if it was focused before
    if (focusedItemId) {
        setTimeout(() => {
            const inputToFocus = $(`.item-discount[data-item-id="${focusedItemId}"]`);
            if (inputToFocus.length) {
                inputToFocus.focus();
                // Move cursor to end of input
                const input = inputToFocus[0];
                if (input.setSelectionRange) {
                    const len = input.value.length;
                    input.setSelectionRange(len, len);
                }
            }
        }, 0);
    }
    
    updateCartTotals();
}

// Update cart totals
function updateCartTotals() {
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const price = parseFloat(item.price) || 0;
        const quantity = parseFloat(item.quantity) || 0;
        const discount = parseFloat(item.discount) || 0;
        const itemTotal = (price * quantity) - discount;
        return sum + itemTotal;
    }, 0);
    
    // Get discount amount based on settings type
    discountAmount = getDiscountAmount(subtotal);
    shippingAmount = parseFloat($('#shippingInput').val()) || 0;
    
    // Total after additional discount and shipping
    const totalAfterDiscount = subtotal - discountAmount + shippingAmount;
    
    // Calculate VAT - ensure vatRate is valid
    let vatAmount = 0;
    if (vatEnabled && vatRate && !isNaN(vatRate)) {
        vatAmount = totalAfterDiscount * (vatRate / 100);
    }

    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Ensure all values are valid numbers
    const safeSubtotal = isNaN(subtotal) ? 0 : subtotal;
    const safeVatAmount = isNaN(vatAmount) ? 0 : vatAmount;
    const safeGrandTotal = isNaN(grandTotal) ? 0 : grandTotal;
    
    // Update display
    $('#subtotalDisplay').val('R ' + safeSubtotal.toFixed(2));
    $('#vatAmountDisplay').val('R ' + safeVatAmount.toFixed(2));
    $('#grandTotal').text('R ' + safeGrandTotal.toFixed(2));
    // Amount Paid and Balance Due removed from summary
    // $('#amountPaidDisplay').text('R 0.00');
    // $('#balanceDue').text('R ' + safeGrandTotal.toFixed(2));
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

// Update item price (admin/manager only)
function updateItemPrice(productId, newPrice) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        const price = parseFloat(newPrice) || 0;
        if (price < 0) {
            toastr.error('Price cannot be negative');
            updateCartDisplay();
            return;
        }
        item.price = price;
        updateCartDisplay();
        toastr.success('Price updated');
    }
}

// Remove from cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartDisplay();
    toastr.info('Item removed from cart');
}

// Select customer
// Select customer (legacy function - kept for compatibility)
function selectCustomer() {
    // Now handled by selectCustomerFromSearch()
    // This function kept for any legacy calls
}

// Display customer info
function displayCustomerInfo() {
    if (!currentCustomer) return;
    
    const balance = currentCustomer.outstanding_balance || currentCustomer.balance || 0;
    const creditLimit = currentCustomer.credit_limit || 0;
    const availableCredit = currentCustomer.available_credit || (creditLimit - Math.abs(balance));
    const priceTier = currentCustomer.price_tier || 'normal';
    const terms = currentCustomer.terms || 'cash';
    
    // Auto-set price tier based on customer
    $('#priceTier').val(priceTier);
    updatePriceTier();
    
    // Hide price tier dropdown (customer's tier is locked)
    $('#priceTier').closest('.mb-3').hide();
    
    // Get terms display text
    let termsText = 'Cash';
    if (terms === 'credit') {
        termsText = 'On Account';
    } else if (terms === 'mixed') {
        termsText = 'Mixed';
    }
    
    const html = `
        <div class="alert alert-primary py-2 mb-0" style="border-left: 4px solid #0d6efd;">
            <div class="mb-2 d-flex justify-content-between align-items-start">
                <div>
                    <strong class="d-block">${currentCustomer.name}</strong>
                    <small class="text-muted">${currentCustomer.customer_code || ''}</small>
                        </div>
                        <div class="text-end">
                    <span class="badge bg-info">${termsText}</span>
                        </div>
                    </div>
            
            ${creditLimit > 0 ? `
            <div class="row g-2 small mb-2">
                <div class="col-4">
                    <span class="text-muted">Outstanding:</span><br>
                    <strong class="${balance > 0 ? 'text-danger' : 'text-success'}">R ${Math.abs(balance).toFixed(2)}</strong>
                </div>
                <div class="col-4">
                    <span class="text-muted">Credit Limit:</span><br>
                    <strong class="text-info">R ${creditLimit.toFixed(2)}</strong>
                </div>
                <div class="col-4">
                    <span class="text-muted">Available:</span><br>
                    <strong class="${availableCredit > 0 ? 'text-success' : 'text-danger'}">R ${availableCredit.toFixed(2)}</strong>
                </div>
            </div>
            ` : ''}
            
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-primary-transparent">${priceTier.charAt(0).toUpperCase() + priceTier.slice(1)} Price</span>
                ${currentCustomer.phone ? `<small class="text-muted"><i class="ri-phone-line"></i> ${currentCustomer.phone}</small>` : ''}
                ${currentCustomer.email ? `<small class="text-muted"><i class="ri-mail-line"></i> ${currentCustomer.email}</small>` : ''}
                    </div>
                </div>
            `;
        
    $('#customerInfo').removeClass('d-none').html(html);
    }
    
// Toggle VAT
function toggleVAT() {
    vatEnabled = $('#vatEnabled').is(':checked');
    
    // Debug logging
    console.log('VAT Toggle:', {
        vatEnabled: vatEnabled,
        vatRate: vatRate,
        cartLength: cart.length
    });
    
    updateCartTotals();
}

// Load customer vehicles
function loadCustomerVehicles(customerId) {
    // Show section immediately with loading state
    $('#vehicleSection').show();
    $('#vehicleSelect').html('<option value="">Loading vehicles...</option>');
    
    const url = "<?php echo e(route('customers.vehicles.get', ':id')); ?>".replace(':id', customerId);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                customerVehicles = data.vehicles;
                populateVehicleDropdown();
                
                if (customerVehicles.length > 0) {
                    // Auto-select primary vehicle if exists
                    const primaryVehicle = customerVehicles.find(v => v.is_primary);
                    if (primaryVehicle) {
                        $('#vehicleSelect').val(primaryVehicle.id);
                        selectVehicle();
                    }
                } else {
                    // No vehicles - show helpful message
                    $('#vehicleSelect').html('<option value="">No vehicles - Click + to add</option>');
                }
            }
        })
        .catch(error => {
            console.error('Error loading vehicles:', error);
            $('#vehicleSelect').html('<option value="">Error loading vehicles</option>');
        });
}

// Populate vehicle dropdown
function populateVehicleDropdown() {
    const select = $('#vehicleSelect');
    select.html('<option value="">Select Vehicle</option>');
    
    customerVehicles.forEach(vehicle => {
        select.append(`<option value="${vehicle.id}" 
            data-make="${vehicle.make_name}" 
            data-model="${vehicle.model_name}"
            data-reg="${vehicle.registration_number}"
            data-mileage="${vehicle.mileage}">
            ${vehicle.display_name}
        </option>`);
    });
}

// Select vehicle
function selectVehicle() {
    const vehicleId = $('#vehicleSelect').val();
    
    if (vehicleId) {
        currentVehicle = customerVehicles.find(v => v.id == vehicleId);
        
        if (currentVehicle) {
            const html = `
                <div class="alert alert-light py-2 small mb-0">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted">Make:</span><br>
                            <strong class="${!currentVehicle.make_id ? 'text-warning bg-warning bg-opacity-25 px-1 rounded' : ''}">${currentVehicle.make_name}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Model:</span><br>
                            <strong class="${!currentVehicle.model_id ? 'text-warning bg-warning bg-opacity-25 px-1 rounded' : ''}">${currentVehicle.model_name}</strong>
                        </div>
                        ${currentVehicle.engine ? `
                        <div class="col-6">
                            <span class="text-muted">Engine:</span><br>
                            <strong>${currentVehicle.engine}</strong>
                        </div>
                        ` : ''}
                        <div class="col-6">
                            <span class="text-muted">Reg:</span><br>
                            <strong>${currentVehicle.registration_number || 'N/A'}</strong>
                        </div>
                        ${currentVehicle.mileage ? `
                        <div class="col-6">
                            <span class="text-muted">Mileage:</span><br>
                            <strong>${currentVehicle.mileage} km</strong>
                        </div>
                        ` : ''}
                        ${currentVehicle.vin_number ? `
                        <div class="col-12">
                            <span class="text-muted">VIN:</span>
                            <strong>${currentVehicle.vin_number}</strong>
                        </div>
                        ` : ''}
                        ${!currentVehicle.make_id || !currentVehicle.model_id ? `
                        <div class="col-12 mt-2">
                            <div class="alert alert-warning py-2 mb-0" style="border-left: 4px solid #ffc107;">
                                <div class="d-flex align-items-center">
                                    <i class="ri-alert-line fs-5 me-2"></i>
                                    <div>
                                        <strong>Incomplete Vehicle Data</strong><br>
                                        <small>Make/Model missing. Please edit customer to update vehicle details.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            $('#vehicleInfo').html(html).show();
        }
    } else {
        currentVehicle = null;
        $('#vehicleInfo').hide();
    }
}

// Show Add Vehicle Modal
function showAddVehicleModal() {
    if (!currentCustomer) {
        toastr.error('Please select a customer first');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('addVehicleModal'));
    modal.show();
    
    // Reset form
    $('#addVehicleForm')[0].reset();
    $('#newVehicleModel').prop('disabled', true);
    
    // Initialize Select2 for makes
    initializeAddVehicleSelect2();
}

// Initialize Select2 for Add Vehicle modal
function initializeAddVehicleSelect2() {
    // Make Select2
    $('.select2-add-vehicle-make').select2({
        dropdownParent: $('#addVehicleModal'),
        placeholder: 'Select Make',
        allowClear: true,
        ajax: {
            url: '<?php echo e(route("api.car-makes")); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.results
                };
            }
        }
    }).on('change', function() {
        const makeId = $(this).val();
        const modelSelect = $('.select2-add-vehicle-model');
        
        if (makeId) {
            modelSelect.prop('disabled', false);
            
            // Re-initialize model Select2 with make filter
            modelSelect.select2('destroy');
            modelSelect.select2({
                dropdownParent: $('#addVehicleModal'),
                placeholder: 'Select Model',
                allowClear: true,
                ajax: {
                    url: '<?php echo e(route("api.car-models")); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            make_id: makeId
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    }
                }
            });
        } else {
            modelSelect.prop('disabled', true).val('').trigger('change');
        }
    });
    
    // Model Select2 (initially disabled)
    $('.select2-add-vehicle-model').select2({
        dropdownParent: $('#addVehicleModal'),
        placeholder: 'Select Model',
        disabled: true
    });
}

// Add new vehicle
function addNewVehicle() {
    const makeId = $('#newVehicleMake').val();
    const modelId = $('#newVehicleModel').val();
    const engine = $('#newVehicleEngine').val();
    const year = $('#newVehicleYear').val();
    const registration = $('#newVehicleReg').val();
    const mileage = $('#newVehicleMileage').val();
    const isPrimary = $('#newVehiclePrimary').is(':checked');
    
    if (!makeId || !modelId || !registration) {
        toastr.error('Please fill in Make, Model, and Registration');
        return;
    }
    
    if (!currentCustomer) {
        toastr.error('No customer selected');
        return;
    }
    
    toastr.info('Adding vehicle...');
    
    const url = "<?php echo e(route('customers.vehicles.store', ':id')); ?>".replace(':id', currentCustomer.id);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            make_id: makeId,
            model_id: modelId,
            engine: engine,
            year: year,
            registration_number: registration,
            mileage: mileage,
            is_primary: isPrimary
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Vehicle added successfully!');
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('addVehicleModal')).hide();
            
            // Reload customer vehicles
            loadCustomerVehicles(currentCustomer.id);
        } else {
            toastr.error(data.message || 'Failed to add vehicle');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error adding vehicle');
    });
}

// Update payment methods
function updatePaymentMethods() {
    const select = $('#paymentMethod');
    const currentValue = select.val();
    
    select.find('option:not(:first)').remove();
    
    // Check customer terms
    const customerTerms = currentCustomer ? currentCustomer.terms : 'cash';
    
    // If customer allows credit/on account
    if (currentCustomer && customerTerms === 'credit') {
        select.append('<option value="credit">On Account</option>');
        $('#onAccountOption').show();
        } else {
        $('#onAccountOption').hide();
    }
    
    // Always allow these payment methods
    select.append('<option value="cash">Cash</option>');
    select.append('<option value="card">Card</option>');
    select.append('<option value="eft">EFT</option>');
    
    // If mixed terms, allow combination
    if (currentCustomer && customerTerms === 'mixed') {
        select.append('<option value="mixed">Mixed Payment</option>');
    }
    
    if (currentValue) select.val(currentValue);
}

// Helper function to calculate discount amount based on type
function getDiscountAmount(subtotal) {
    const discountValue = parseFloat($('#discountInput').val()) || 0;
    
    if (discountTypeSettings === 'percentage') {
        return (subtotal * discountValue) / 100;
    } else {
        return discountValue;
    }
}

// Calculate change
function calculateChange() {
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get discount amount based on type
    const discount = getDiscountAmount(subtotal);
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
            $('#vehicleMake').val('');
            $('#vehicleModel').val('');
            $('#vehicleReg').val('');
            $('#vehicleMileage').val('');
            $('#customerSearch').val('');
            currentCustomer = null;
            $('#customerInfo').addClass('d-none');
            $('#customerInfoForm').hide();
            $('#walkInCustomerForm').hide();
            $('#clearCustomerBtn').hide();
            // Keep VAT setting - user preference maintained
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
    
    // Role-based discount validation
    if (discountTypeSettings === 'percentage') {
        // Check if percentage exceeds role limit
        if (discountValue > maxDiscountLimit) {
            toastr.warning(`Your role (${userRole}) allows maximum ${maxDiscountLimit}% discount`);
            $(this).val(maxDiscountLimit.toFixed(2));
            discountValue = maxDiscountLimit;
        }
        
        // Prevent percentage > 100%
        if (discountValue > 100) {
            $(this).val('100.00');
            discountValue = 100;
        }
    } else {
        // Flat discount - prevent discount greater than subtotal
    if (discountValue > subtotal && subtotal > 0) {
        $(this).val(subtotal.toFixed(2));
        discountValue = subtotal;
        toastr.error('Discount cannot exceed subtotal');
        }
    }
    
    // Additional check for flat discount if it exceeds role percentage limit
    if (discountTypeSettings === 'flat' && subtotal > 0) {
        const discountPercentage = (discountValue / subtotal) * 100;
        if (discountPercentage > maxDiscountLimit) {
            const maxAllowedDiscount = (subtotal * maxDiscountLimit) / 100;
            toastr.warning(`Your role (${userRole}) allows maximum ${maxDiscountLimit}% discount (R ${maxAllowedDiscount.toFixed(2)})`);
            $(this).val(maxAllowedDiscount.toFixed(2));
        }
    }
    
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

// Item discount input handler with debouncing
let discountUpdateTimeout;
$(document).on('input', '.item-discount', function() {
    const itemId = parseInt($(this).data('item-id'));
    const inputElement = this;
    let discountValue = parseFloat($(this).val()) || 0;
    
    const item = cart.find(item => item.id === itemId);
    if (item) {
        // Update item discount immediately for smooth UX
        item.discount = discountValue;
        
        // Clear previous timeout
        clearTimeout(discountUpdateTimeout);
        
        // Debounce validation and display update
        discountUpdateTimeout = setTimeout(() => {
        // Prevent negative values
        if (discountValue < 0) {
                $(inputElement).val('0.00');
            discountValue = 0;
        }
        
            // Role-based discount validation for line items
        const lineTotal = item.price * item.quantity;
            const maxDiscountAmount = (lineTotal * maxDiscountLimit) / 100;
        
            // Check if discount exceeds role-based limit
        if (discountValue > maxDiscountAmount) {
                toastr.warning(`Your role (${userRole}) allows maximum ${maxDiscountLimit}% discount (R ${maxDiscountAmount.toFixed(2)}) on this item`);
                $(inputElement).val(maxDiscountAmount.toFixed(2));
            discountValue = maxDiscountAmount;
                item.discount = discountValue;
        }
        
        // Prevent discount greater than line total
        if (discountValue > lineTotal) {
                $(inputElement).val(lineTotal.toFixed(2));
            discountValue = lineTotal;
            toastr.error('Discount cannot exceed line total');
                item.discount = discountValue;
        }
        
            // Update totals without refreshing display
            updateCartTotals();
        }, 300);
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
    fetch('<?php echo e(route('customers.store')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            name: name,
            terms: type === 'credit' ? 'credit' : 'cash',
            credit_limit: creditLimit,
            email: email,
            phone: phone,
            address: address,
            quick_add: '1'
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

// Show Quick Add Product section
function showQuickAddProduct() {
    $('#quickAddSection').slideDown();
    $('#quickAddName').focus();
}

// Quick Add Product functionality
function addQuickProduct() {
    const name = $('#quickAddName').val().trim();
    const price = parseFloat($('#quickAddPrice').val());
    const qty = parseFloat($('#quickAddQty').val()) || 1;
    
    if (!name || !price) {
        toastr.error('Please fill in product name and price');
        return;
    }
    
    if (price <= 0) {
        toastr.error('Price must be greater than 0');
        return;
    }
    
    if (qty < 0) {
        toastr.error('Quantity cannot be negative');
        return;
    }
    
    // Disable button during processing
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating...';
    
    // Save product to database first
    fetch('<?php echo e(route('products.quickAdd')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
        name: name,
        price_normal: price,
            qty: 1,
            unit_cost: price * 0.7 // Estimate 70% cost
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create product object for cart
            const newProduct = {
                id: data.product.id,
                name: data.product.name,
                sku: data.product.sku,
                barcode_primary: data.product.barcode_primary,
                price_normal: parseFloat(data.product.price_normal),
                price_online: parseFloat(data.product.price_online),
                price_workshop: parseFloat(data.product.price_workshop),
                on_hand: data.product.on_hand || 0,
        reserved: 0,
                available: data.product.on_hand || 0,
        image: null,
                category_id: data.product.category_id,
                category_name: data.product.category_name || 'General',
                brand_name: data.product.brand_name || 'Generic'
            };
            
            // Add to cart silently
            addToCartSilent(newProduct, qty);
            
            // Reload products list
            loadProducts();
    
    // Clear quick add fields
    $('#quickAddName').val('');
    $('#quickAddPrice').val('');
            $('#quickAddQty').val('1'); // Reset to default 1
    
    // Hide quick add section
            $('#quickAddSection').slideUp();
            
            // Single success message
            toastr.success(`✓ ${name} created and added to cart!`, '', {
                timeOut: 2000
            });
        } else {
            toastr.error(data.message || 'Failed to create product');
        }
        
        // Re-enable button
        btn.disabled = false;
        btn.innerHTML = originalText;
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error creating product');
        
        // Re-enable button
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

// Update price tier for all cart items
function updatePriceTier() {
    const priceTier = $('#priceTier').val();
    
    cart.forEach(item => {
        if (!item.isQuickAdd) {
            switch(priceTier) {
                case 'normal':
                    item.price = item.price_normal;
                    break;
                case 'online':
                    item.price = item.price_online;
                    break;
                case 'workshop':
                    item.price = item.price_workshop;
                    break;
            }
        }
    });
    
    updateCartDisplay();
    updateCartTotals();
}

// Apply price tier to all cart items based on customer
function applyPriceTierToCart() {
    if (!currentCustomer) return;
    
    const priceTier = currentCustomer.price_tier || 'normal';
    
    cart.forEach(item => {
        if (!item.isQuickAdd) {
            switch(priceTier) {
                case 'normal':
                    item.price = item.price_normal;
                    break;
                case 'online':
                    item.price = item.price_online;
                    break;
                case 'workshop':
                    item.price = item.price_workshop;
                    break;
            }
        }
    });
    
    updateCartDisplay();
    updateCartTotals();
}

// Note: POS is always enabled - customers can be registered or walk-in

// Update payment fields based on customer type and payment method
function updatePaymentFields() {
    const paymentMethod = $('#paymentMethod').val();
    const isCreditCustomer = currentCustomer && currentCustomer.terms === 'credit';
    
    if (paymentMethod === 'credit') {
        // Credit payment - hide amount paid and change
        $('#amountPaidRow').hide();
        $('#changeRow').hide();
        $('#amountPaid').val(0);
        $('#amountPaid').prop('readonly', false);
        
        // Show reference field for credit payments
        $('#paymentReferenceRow').show();
            } else {
        // Cash/Card/EFT payment - show amount paid
        $('#amountPaidRow').show();
        $('#paymentReferenceRow').show();
        
        // Calculate totals for setting amount
        const subtotal = cart.reduce((sum, item) => {
            const itemTotal = (item.price * item.quantity) - (item.discount || 0);
            return sum + itemTotal;
        }, 0);
        const discount = getDiscountAmount(subtotal);
        const shipping = parseFloat($('#shippingInput').val()) || 0;
        const totalAfterDiscount = subtotal - discount + shipping;
        const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
        const grandTotal = totalAfterDiscount + vatAmount;
        
        // Set amount paid to grand total
        $('#amountPaid').val(grandTotal.toFixed(2));
        
        // For cash terms customers (walk-in), amount is readonly and no change shown
        if (!currentCustomer || currentCustomer.terms === 'cash') {
            $('#amountPaid').prop('readonly', true);
            $('#changeRow').hide(); // Cash customers pay exact amount, no change
        } else if (isCreditCustomer) {
            // Credit customer choosing cash/card payment - allow amount editing and show change
            $('#amountPaid').prop('readonly', false);
            $('#changeRow').show();
            calculateChange();
        } else {
            $('#amountPaid').prop('readonly', false);
        $('#changeRow').show();
        calculateChange();
        }
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
    
    // Get discount amount based on type (percentage or flat)
    const discount = getDiscountAmount(subtotal);
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Show payment modal for all customers (credit and cash)
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    
    // Set default amount paid to grand total
    $('#amountPaid').val(grandTotal.toFixed(2));
    
    // Customer type alert and payment method setup
    let alertHtml = '';
    
    if (currentCustomer && currentCustomer.terms === 'credit') {
        // Credit customer - can choose credit or cash/card payment
        const availableCredit = (currentCustomer.credit_limit || 0) - Math.abs(currentCustomer.balance || 0);
        
        alertHtml = `
            <div class="alert alert-info">
                <i class="ri-information-line me-2"></i><strong>Credit Customer</strong><br>
                <small>You can pay using credit (On Account) or Cash/Card/EFT.</small><br>
                <small>Available Credit: <strong>R ${availableCredit.toFixed(2)}</strong></small>
            </div>
        `;
        
        // Show credit option in payment method dropdown
        $('#onAccountOption').show();
        
        // Set default payment method to credit for credit customers
        $('#paymentMethod').val('credit');
    } else {
        // Cash customer - must pay full amount
        alertHtml = `
        <div class="alert alert-warning">
            <i class="ri-alert-line me-2"></i><strong>Cash Customer</strong><br>
                <small>Cash customers must pay full amount.</small>
        </div>
    `;
        
        // Hide credit option for non-credit customers
        $('#onAccountOption').hide();
        
        // Set default payment method to cash
        $('#paymentMethod').val('cash');
        
        // Make amount paid readonly for cash customers (they pay full amount)
        $('#amountPaid').prop('readonly', true);
        
        // Hide change row for cash customers (they pay exact amount)
        $('#changeRow').hide();
    }
    
    $('#customerTypeAlert').html(alertHtml);
    
    // Update payment fields based on selection
    updatePaymentFields();
    
    paymentModal.show();
}

// Save as Quotation
function saveAsQuotation() {
    if (cart.length === 0) {
        toastr.error('Cart is empty');
        return;
    }
    
    if (!currentCustomer) {
        toastr.error('Please select a customer');
        return;
    }
    
    // Calculate totals
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    const discount = getDiscountAmount(subtotal);
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Prepare quote data
    const quoteData = {
        customer_id: currentCustomer.id,
        quote_date: new Date().toISOString().split('T')[0],
        valid_until: new Date(Date.now() + 30*24*60*60*1000).toISOString().split('T')[0], // 30 days
        subtotal: subtotal,
        discount: discount,
        shipping: shipping,
        vat_amount: vatAmount,
        grand_total: grandTotal,
        items: cart.map(item => ({
            product_id: item.id,
            product_name: item.name,
            quantity: item.quantity,
            unit_price: item.price,
            discount: item.discount || 0,
            total: (item.price * item.quantity) - (item.discount || 0)
        })),
        notes: 'Created from POS'
    };
    
    // Show loading
    toastr.info('Creating quotation...');
    
    // Save quotation
    fetch('<?php echo e(route('quotes.store')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(quoteData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Quotation created successfully!');
            
            // Clear cart
            cart = [];
            currentCustomer = null;
            updateCartDisplay();
            $('#customerSelect').val('').trigger('change');
            $('#customerInfo').addClass('d-none');
            
            // Redirect to quotes page
            if (confirm('Quotation saved! Would you like to view it now?')) {
                window.open('<?php echo e(route('quotes.index')); ?>', '_blank');
            }
        } else {
            toastr.error(data.message || 'Failed to create quotation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while creating quotation');
    });
}

// Process sale directly (for credit customers)
function processSaleDirect(paymentMethod, amountPaid, paymentReference) {
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get discount amount based on type (percentage or flat)
    const discount = getDiscountAmount(subtotal);
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Get walk-in customer details if no customer selected
    let walkInCustomerDetails = null;
    if (!currentCustomer) {
        const walkInName = $('#walkInName').val().trim();
        const walkInPhone = $('#walkInPhone').val().trim();
        const walkInEmail = $('#walkInEmail').val().trim();
        const walkInAddress = $('#walkInAddress').val().trim();
        
        if (walkInName) {
            walkInCustomerDetails = {
                name: walkInName,
                phone: walkInPhone,
                email: walkInEmail,
                address: walkInAddress
            };
        }
    }
    
    // Prepare sale data
    const saleData = {
        cart: cart,
        customer_id: currentCustomer ? currentCustomer.id : null,
        walk_in_customer: walkInCustomerDetails,
        payment_method: paymentMethod,
        amount_paid: amountPaid,
        payment_reference: paymentReference,
        vat_enabled: vatEnabled,
        vat_rate: vatRate,
        discount_amount: discount,
        discount_type: 'amount',
        shipping: shipping,
        vehicle_id: currentVehicle ? currentVehicle.id : null,
        vehicle_make: currentVehicle ? currentVehicle.make_name : '',
        vehicle_model: currentVehicle ? currentVehicle.model_name : '',
        vehicle_reg: currentVehicle ? currentVehicle.registration_number : '',
        vehicle_mileage: currentVehicle ? currentVehicle.mileage : '',
        _token: '<?php echo e(csrf_token()); ?>'
    };
    
    // Process sale
    fetch('<?php echo e(route('pos.process-sale')); ?>', {
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
            // Store invoice data globally for post-sale actions
            window.currentInvoiceId = data.invoice_id;
            window.currentInvoiceNumber = data.invoice_number;
            window.currentInvoicePdfUrl = data.pdf_url;
            window.currentInvoiceTotal = grandTotal;
            
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
                $('#customerSelect').val('');
            $('#vehicleSelect').val('');
            currentCustomer = null;
            currentVehicle = null;
                $('#customerInfo').addClass('d-none');
            $('#vehicleSection').hide();
            $('#vehicleInfo').hide();
                $('#vatEnabled').prop('checked', false);
                vatEnabled = false;
            updateCartDisplay();
            
            // Show post-sale actions modal
            $('#postSaleInvoiceNumber').text(data.invoice_number);
            $('#postSaleTotal').text(grandTotal.toFixed(2));
            const postSaleModal = new bootstrap.Modal(document.getElementById('postSaleModal'));
            postSaleModal.show();
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
    // Clear any previous error messages
    toastr.clear();
    
    const paymentMethod = $('#paymentMethod').val();
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const paymentReference = $('#paymentReference').val();
    
    // Calculate subtotal with item discounts
    const subtotal = cart.reduce((sum, item) => {
        const itemTotal = (item.price * item.quantity) - (item.discount || 0);
        return sum + itemTotal;
    }, 0);
    
    // Get discount amount based on type (percentage or flat)
    const discount = getDiscountAmount(subtotal);
    const shipping = parseFloat($('#shippingInput').val()) || 0;
    
    const totalAfterDiscount = subtotal - discount + shipping;
    const vatAmount = vatEnabled ? totalAfterDiscount * (vatRate / 100) : 0;
    const grandTotal = totalAfterDiscount + vatAmount;
    
    // Validation for credit payment only
    if (paymentMethod === 'credit') {
        if (!currentCustomer || currentCustomer.terms !== 'credit') {
            toastr.error('Only credit customers can use credit payment');
            return;
        }
        
        // Check credit limit
        const availableCredit = (currentCustomer.credit_limit || 0) - Math.abs(currentCustomer.balance || 0);
        if (grandTotal > availableCredit) {
            toastr.error(`Insufficient credit limit. Available: R ${availableCredit.toFixed(2)}, Required: R ${grandTotal.toFixed(2)}`);
            return;
        }
    }
    
    // For Cash/Card/EFT - no validation needed, amount paid is auto-set and change is calculated
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
    
    // Process sale
    processSaleDirect(paymentMethod, amountPaid, paymentReference);
}

// Post-Sale Actions
function downloadInvoicePDF() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    const url = '<?php echo e(route("invoices.pdf", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    window.open(url, '_blank');
    toastr.success('Opening PDF...');
}

function printInvoice() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    const url = '<?php echo e(route("invoices.print", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    window.open(url, '_blank');
    toastr.success('Opening print view...');
}

function printInvoiceInline() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    const url = '<?php echo e(route("invoices.print", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    
    // Create hidden iframe for printing
    let printFrame = document.getElementById('printFrame');
    if (!printFrame) {
        printFrame = document.createElement('iframe');
        printFrame.id = 'printFrame';
        printFrame.style.display = 'none';
        printFrame.style.position = 'fixed';
        printFrame.style.width = '0';
        printFrame.style.height = '0';
        printFrame.style.border = 'none';
        document.body.appendChild(printFrame);
    }
    
    // Load invoice in iframe
    printFrame.src = url;
    
    // Wait for iframe to load, then trigger print
    printFrame.onload = function() {
        try {
            // Small delay to ensure content is rendered
            setTimeout(function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 500);
        } catch (e) {
            toastr.error('Print failed. Please try Download PDF instead.');
            console.error('Print error:', e);
        }
    };
    
    toastr.info('Preparing print...');
}

function sendWhatsApp() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    toastr.info('Preparing WhatsApp...');
    
    const url = '<?php echo e(route("invoices.whatsapp", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.whatsapp_url) {
            // Open WhatsApp based on setting
            const shareType = data.share_type || 'web';
            const whatsappTab = window.open(data.whatsapp_url, '_blank');
            
            if (whatsappTab) {
                if (shareType === 'desktop') {
                    // Auto-copy message to clipboard for desktop app
                    const message = data.message || '';
                    if (message) {
                        copyToClipboard(message);
                        toastr.success('WhatsApp Desktop app opened! Message copied to clipboard. Just paste (Ctrl+V) in the app.', {
                            timeOut: 5000
                        });
                    } else {
                        toastr.warning('WhatsApp Desktop app opened.');
                    }
                } else {
                    toastr.success('WhatsApp Web opened! Message is pre-filled and ready to send.');
                }
            } else {
                // If popup blocked, provide manual link
                toastr.warning('Please allow popups, or click the link in the notification.');
            }
        } else {
            toastr.error(data.message || 'Failed to generate WhatsApp link');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error sending to WhatsApp');
    });
}

// Copy to clipboard helper function
function copyToClipboard(text) {
    // Modern clipboard API
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            console.log('Message copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy:', err);
            // Fallback
            copyToClipboardFallback(text);
        });
    } else {
        // Fallback for older browsers or non-secure contexts
        copyToClipboardFallback(text);
    }
}

// Fallback copy method
function copyToClipboardFallback(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        console.log('Message copied to clipboard (fallback)');
    } catch (err) {
        console.error('Fallback copy failed:', err);
    }
    
    document.body.removeChild(textArea);
}

function sendEmail() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    toastr.info('Sending email...');
    
    const url = '<?php echo e(route("invoices.email", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message || 'Email sent successfully!');
        } else {
            toastr.error(data.message || 'Failed to send email');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error sending email');
    });
}

function downloadPickingList() {
    if (!window.currentInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    const url = '<?php echo e(route("invoices.picking-list", ":id")); ?>'.replace(':id', window.currentInvoiceId);
    window.open(url, '_blank');
    toastr.success('Generating picking list...');
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/pos/index.blade.php ENDPATH**/ ?>