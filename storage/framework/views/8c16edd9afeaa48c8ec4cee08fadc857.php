<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-width="fullwidth" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POS - MMP Auto-Meister</title>
    <meta name="Description" content="Point of Sale System">
    <meta name="Author" content="MMP Auto-Meister">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('public/assets/images/brand-logos/favicon.ico')); ?>" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link id="style" href="<?php echo e(asset('public/assets/libs/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?php echo e(asset('public/assets/css/styles.css')); ?>" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?php echo e(asset('public/assets/css/icons.css')); ?>" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="<?php echo e(asset('public/assets/libs/node-waves/waves.min.css')); ?>" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="<?php echo e(asset('public/assets/libs/simplebar/simplebar.min.css')); ?>" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        .pos-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .pos-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .product-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: #007bff;
        }

        .success-modal .modal-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .warning-modal .modal-header {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .error-modal .modal-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .info-modal .modal-header {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .cart-item {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-weight: 600;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .cart-total {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 15px;
            padding: 20px;
        }

        .barcode-scanner {
            background: #fff;
            border: 2px dashed #007bff;
            border-radius: 15px;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .barcode-scanner:hover {
            background: #f8f9ff;
            border-color: #0056b3;
        }

        .payment-methods {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
        }

        .payment-btn {
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .payment-btn:hover {
            transform: translateY(-2px);
        }

        .pos-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="pos-container">
    <div class="container-fluid p-4">
        <!-- POS Header -->
        <div class="pos-header text-white mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0"><i class="ri-shopping-cart-2-line me-2"></i>Point of Sale</h2>
                    <p class="mb-0 opacity-75">MMP Auto-Meister - Quick Sales Terminal</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-light btn-sm" onclick="showClearCartModal()">
                            <i class="ri-refresh-line me-1"></i>Clear Cart
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="holdSale()">
                            <i class="ri-pause-line me-1"></i>Hold Sale
                        </button>
                        <button class="btn btn-info btn-sm" onclick="loadHeldSale()">
                            <i class="ri-play-line me-1"></i>Load Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column - Products -->
            <div class="col-lg-8">
                <!-- Product Search & Barcode Scanner -->
                <div class="pos-card p-4 mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="product-search p-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-lg" id="productSearch"
                                        placeholder="Search products by name, SKU, or barcode..." 
                                        autocomplete="off"
                                        onkeyup="searchProducts()"
                                        autofocus>
                                </div>
                                <div id="searchResults" class="list-group mt-3" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px;">
                                    <div class="text-center text-muted py-5">
                                        <i class="ri-search-line fs-1 mb-2 d-block"></i>
                                        <p class="mb-0">Start typing to search products...</p>
                                        <small>Search by name, SKU, or scan barcode</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Instructions -->
                <div class="pos-card p-4">
                    <div class="alert alert-info mb-0">
                        <i class="ri-information-line me-2"></i>
                        <strong>How to add products:</strong> Type product name, SKU, or scan barcode in the search box above. Click on the product from dropdown to add to cart.
                    </div>
                </div>
            </div>

            <!-- Right Column - Shopping Cart -->
            <div class="col-lg-4">
                <!-- Customer Selection -->
                <div class="pos-card p-4 mb-4">
                    <h5 class="mb-3"><i class="ri-user-line me-2"></i>Customer</h5>
                    <div class="input-group mb-3">
                        <select class="form-select" id="customerSelect" onchange="selectCustomer()">
                            <option value="">Walk-in Customer</option>
                            <!-- Customers will be loaded dynamically -->
                        </select>
                        <button class="btn btn-outline-primary" onclick="addNewCustomer()">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                    <div id="customerInfo" class="d-none">
                        <!-- Customer info will be displayed here -->
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="pos-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="ri-shopping-cart-line me-2"></i>Shopping Cart</h5>
                        <span class="badge bg-primary" id="cartItemCount">0 items</span>
                    </div>

                    <div id="cartItems" style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <i class="ri-shopping-cart-line fs-1 mb-2"></i>
                            <p class="mb-0">Your cart is empty</p>
                            <small>Add products to get started</small>
                        </div>
                    </div>
                </div>

                <!-- Cart Total & Actions -->
                <div class="pos-card p-4 mb-4">
                    <div class="cart-total mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fs-6 opacity-75">Items</div>
                                <div class="h5 mb-0" id="totalItems">0</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-6 opacity-75">Subtotal</div>
                                <div class="h5 mb-0" id="cartSubtotal">$0.00</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-6 opacity-75">Total</div>
                                <div class="h4 mb-0" id="cartTotal">$0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="mb-3">
                        <label class="form-label">Discount</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="discountAmount" placeholder="0.00"
                                step="0.01">
                            <select class="form-select" id="discountType">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods mb-4">
                        <h6 class="mb-3">Payment Method</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-success payment-btn w-100" onclick="processPayment('cash')">
                                    <i class="ri-money-dollar-circle-line me-1"></i>Cash
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary payment-btn w-100" onclick="processPayment('card')">
                                    <i class="ri-bank-card-line me-1"></i>Card
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-info payment-btn w-100" onclick="processPayment('eft')">
                                    <i class="ri-bank-line me-1"></i>EFT
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-warning payment-btn w-100" onclick="processPayment('account')">
                                    <i class="ri-account-box-line me-1"></i>On Account
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Experience Options -->
                    <div class="customer-experience mb-4" id="customerExperienceSection" style="display: none;">
                        <h6 class="mb-3">Customer Invoice & Delivery</h6>
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <button class="btn btn-primary w-100" onclick="generatePDF()" id="pdfBtn">
                                    <i class="ri-file-pdf-line me-2"></i>Download Invoice PDF
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-success w-100" onclick="generatePickingList()" id="pickingBtn">
                                    <i class="ri-list-check-2 me-1"></i>Picking List
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-info w-100" onclick="sendWhatsApp()" id="whatsappBtn">
                                    <i class="ri-whatsapp-line me-1"></i>WhatsApp
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg" onclick="completeSale()" disabled
                            id="completeSaleBtn">
                            <i class="ri-check-line me-2"></i>Complete Sale
                        </button>
                        <button class="btn btn-outline-secondary" onclick="saveAsQuote()">
                            <i class="ri-file-text-line me-2"></i>Save as Quote
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade success-modal" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="ri-checkbox-circle-line me-2"></i>Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-checkbox-circle-line text-success display-4 mb-3"></i>
                    <h4 id="successTitle">Operation Completed</h4>
                    <p class="text-muted mb-0" id="successMessage">Your operation was completed successfully.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade error-modal" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="ri-error-warning-line me-2"></i>Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-error-warning-line text-danger display-4 mb-3"></i>
                    <h4 id="errorTitle">Operation Failed</h4>
                    <p class="text-muted mb-0" id="errorMessage">An error occurred while processing your request.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Modal -->
    <div class="modal fade warning-modal" id="warningModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="ri-alert-line me-2"></i>Warning</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-alert-line text-warning display-4 mb-3"></i>
                    <h4 id="warningTitle">Please Confirm</h4>
                    <p class="text-muted mb-0" id="warningMessage">Are you sure you want to continue?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning px-4" id="warningConfirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Modal -->
    <div class="modal fade info-modal" id="infoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="ri-information-line me-2"></i>Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-information-line text-info display-4 mb-3"></i>
                    <h4 id="infoTitle">Information</h4>
                    <p class="text-muted mb-0" id="infoMessage">Here's some important information.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-info px-4" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Cart Confirmation Modal -->
    <div class="modal fade" id="clearCartModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="ri-delete-bin-line me-2"></i>Clear Cart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-delete-bin-line text-warning display-4 mb-3"></i>
                    <h4>Clear Shopping Cart?</h4>
                    <p class="text-muted">This will remove all items from your cart. This action cannot be undone.</p>
                    <div class="alert alert-warning mt-3">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Warning:</strong> You have <span id="clearCartItemCount">0</span> items in your cart totaling $<span id="clearCartTotal">0.00</span>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning px-4" onclick="clearCartConfirmed()">Clear Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Warning Modal -->
    <div class="modal fade" id="stockWarningModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="ri-error-warning-line me-2"></i>Stock Warning</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ri-error-warning-line text-warning display-4"></i>
                    </div>
                    <h5 class="text-center mb-3" id="stockWarningTitle">Insufficient Stock</h5>
                    <p class="text-muted text-center" id="stockWarningMessage"></p>
                    <div class="alert alert-warning mt-3">
                        <i class="ri-information-line me-2"></i>
                        Available stock: <strong id="availableStock">0</strong> units
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-warning px-4" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Success Modal -->
    <div class="modal fade success-modal" id="saleSuccessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="ri-checkbox-circle-line me-2"></i>Sale Completed</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ri-checkbox-circle-line text-success display-4 mb-3"></i>
                    <h4 class="text-success mb-3">Sale Completed Successfully!</h4>
                    
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-8">
                            <div class="alert alert-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Invoice Number:</strong>
                                    <span id="successInvoiceNumber" class="badge bg-success fs-6"></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <strong>Total Amount:</strong>
                                    <span id="successInvoiceTotal" class="fw-bold fs-5"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="customer-experience-options">
                        <h6 class="mb-3">Customer Experience Options</h6>
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <button class="btn btn-primary w-100" onclick="generatePDF()" id="pdfBtn">
                                    <i class="ri-file-pdf-line me-2"></i>Download Invoice PDF
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-success w-100" onclick="generatePickingList()" id="pickingBtn">
                                    <i class="ri-list-check-2 me-1"></i>Picking List
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-info w-100" onclick="sendWhatsApp()" id="whatsappBtn">
                                    <i class="ri-whatsapp-line me-1"></i>WhatsApp
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal" onclick="continueShopping()">Continue Shopping</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">
                        <i class="ri-user-add-line me-2"></i>Add New Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="customerForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerName" class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" id="customerName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="customerEmail">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerPhone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" id="customerPhone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerCompany" class="form-label">Company</label>
                                    <input type="text" class="form-control" id="customerCompany">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="customerAddress" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="customerCity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customerPostalCode" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="customerPostalCode">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">
                        <i class="ri-money-dollar-circle-line me-2"></i>Complete Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Total Amount:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <strong id="paymentTotal">$0.00</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Payment Method:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span id="paymentMethod" class="badge bg-primary">Cash</span>
                        </div>
                    </div>
                    <div class="mb-3" id="cashPaymentSection">
                        <label for="amountReceived" class="form-label">Amount Received</label>
                        <input type="number" class="form-control" id="amountReceived" step="0.01"
                            placeholder="0.00">
                        <div class="form-text">Enter the amount received from customer</div>
                    </div>
                    <div class="row mb-3" id="changeSection" style="display: none;">
                        <div class="col-6">
                            <strong>Change:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <strong id="changeAmount" class="text-success">$0.00</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="finalizePayment()">
                        <i class="ri-check-line me-1"></i>Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Barcode Scanner Modal -->
    <div class="modal fade" id="barcodeScannerModal" tabindex="-1" aria-labelledby="barcodeScannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeScannerModalLabel">
                        <i class="ri-barcode-box-line me-2"></i>Barcode Scanner
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div id="scanner-container"
                            style="width: 100%; height: 300px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <i class="ri-barcode-box-line fs-1 text-muted mb-2"></i>
                                <p class="text-muted">Scanner will appear here</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary me-2" onclick="startScanner()">
                                <i class="ri-play-line me-1"></i>Start Scanner
                            </button>
                            <button class="btn btn-secondary" onclick="stopScanner()">
                                <i class="ri-stop-line me-1"></i>Stop Scanner
                            </button>
                        </div>
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                Point your camera at a barcode to scan it automatically
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('public/assets/libs/@popperjs/core/umd/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/libs/node-waves/waves.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <!-- POS JavaScript -->
    <script>
        // Global variables
        let cart = [];
        let products = [];
        let customers = [];
        let categories = [];
        let currentCustomer = null;
        let currentDiscount = 0;
        let currentView = 'grid';
        let currentInvoiceId = null;
        let currentInvoiceNumber = null;

        // Modal Management Functions
        function showSuccessModal(title = 'Success', message = 'Operation completed successfully.') {
            document.getElementById('successTitle').textContent = title;
            document.getElementById('successMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        }

        function showErrorModal(title = 'Error', message = 'An error occurred.') {
            document.getElementById('errorTitle').textContent = title;
            document.getElementById('errorMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('errorModal'));
            modal.show();
        }

        function showWarningModal(title, message, confirmCallback) {
            document.getElementById('warningTitle').textContent = title;
            document.getElementById('warningMessage').textContent = message;
            
            const confirmBtn = document.getElementById('warningConfirmBtn');
            // Remove previous event listeners
            confirmBtn.replaceWith(confirmBtn.cloneNode(true));
            document.getElementById('warningConfirmBtn').onclick = confirmCallback;
            
            const modal = new bootstrap.Modal(document.getElementById('warningModal'));
            modal.show();
        }

        function showInfoModal(title = 'Information', message = 'Here is some information.') {
            document.getElementById('infoTitle').textContent = title;
            document.getElementById('infoMessage').textContent = message;
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        }

        function showStockWarning(productName, availableStock, requestedQuantity) {
            document.getElementById('stockWarningTitle').textContent = 'Insufficient Stock';
            document.getElementById('stockWarningMessage').textContent = 
                `Cannot add ${requestedQuantity} units of "${productName}" - insufficient stock available.`;
            document.getElementById('availableStock').textContent = availableStock;
            
            const modal = new bootstrap.Modal(document.getElementById('stockWarningModal'));
            modal.show();
        }

        function showClearCartModal() {
            if (cart.length === 0) {
                showInfoModal('Cart Empty', 'Your shopping cart is already empty.');
                return;
            }

            const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            document.getElementById('clearCartItemCount').textContent = itemCount;
            document.getElementById('clearCartTotal').textContent = cartTotal.toFixed(2);
            
            const modal = new bootstrap.Modal(document.getElementById('clearCartModal'));
            modal.show();
        }

        function showSaleSuccessModal(invoiceId, invoiceNumber, totalAmount) {
            currentInvoiceId = invoiceId;
            currentInvoiceNumber = invoiceNumber;
            
            document.getElementById('successInvoiceNumber').textContent = invoiceNumber;
            document.getElementById('successInvoiceTotal').textContent = `$${parseFloat(totalAmount).toFixed(2)}`;
            
            const modal = new bootstrap.Modal(document.getElementById('saleSuccessModal'));
            modal.show();
        }

        // Initialize POS
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            loadCustomers();
            loadCategories();
            setupProductSearch();
            updateCartDisplay();

            // Show welcome message
            setTimeout(() => {
                showToast('POS System Ready!', 'success');
            }, 1000);
        });

        // Load products
        function loadProducts() {
            fetch('<?php echo e(route('pos.products')); ?>')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    displayProducts(products);
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    showErrorModal('Load Error', 'Failed to load products. Please refresh the page.');
                });
        }

        // Load customers
        function loadCustomers() {
            fetch('<?php echo e(route('pos.customers')); ?>')
                .then(response => response.json())
                .then(data => {
                    customers = data;
                    populateCustomerSelect();
                })
                .catch(error => {
                    console.error('Error loading customers:', error);
                    showToast('Failed to load customers', 'error');
                });
        }

        // Load categories - DISABLED (search only mode)
        function loadCategories() {
            // Not needed in search-only mode
            return;
        }

        // Display products - DISABLED (search only mode, no grid)
        function displayProducts(productsToShow) {
            // Not used anymore - search dropdown only
            return;
        }

        // Create product card - DISABLED (search only mode)
        function createProductCard(product) {
            // Not used anymore
            return document.createElement('div');
        }

        // Create product row for list view
        function createProductRow(product) {
            const row = document.createElement('div');
            row.className = 'd-flex align-items-center p-3 border-bottom';

            const stockClass = product.on_hand > 0 ? 'text-success' : 'text-danger';
            const stockText = product.on_hand > 0 ? `${product.on_hand} in stock` : 'Out of Stock';

            row.innerHTML = `
                <div class="avatar avatar-md bg-light me-3">
                    <img src="${product.image || '<?php echo e(asset('public/assets/images/products/default.jpg')); ?>'}" alt="${product.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${product.name}</h6>
                    <small class="text-muted">SKU: ${product.sku}</small>
                    <br>
                    <small class="${stockClass}">${stockText}</small>
                </div>
                <div class="text-end me-3">
                    <div class="h6 mb-0 text-primary">$${parseFloat(product.price_normal).toFixed(2)}</div>
                </div>
                <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id})">
                    <i class="ri-add-line"></i>
                </button>
            `;

            return row;
        }

        // Add to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) {
                showErrorModal('Product Not Found', 'The selected product could not be found.');
                return;
            }

            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                const newQuantity = existingItem.quantity + 1;
                if (newQuantity > product.on_hand) {
                    showStockWarning(product.name, product.on_hand, newQuantity);
                    return;
                }
                existingItem.quantity = newQuantity;
            } else {
                if (product.on_hand < 1) {
                    showStockWarning(product.name, product.on_hand, 1);
                    return;
                }
                cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: parseFloat(product.price_normal),
                    quantity: 1,
                    stock: product.on_hand
                });
            }

            updateCartDisplay();
            showToast(`${product.name} added to cart`, 'success');
        }

        // Update cart display
        function updateCartDisplay() {
            const cartContainer = document.getElementById('cartItems');
            const itemCount = document.getElementById('cartItemCount');
            const totalItems = document.getElementById('totalItems');
            const subtotal = document.getElementById('cartSubtotal');
            const total = document.getElementById('cartTotal');
            const completeBtn = document.getElementById('completeSaleBtn');

            if (cart.length === 0) {
                cartContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="ri-shopping-cart-line fs-1 mb-2"></i>
                        <p class="mb-0">Your cart is empty</p>
                        <small>Add products to get started</small>
                    </div>
                `;
                completeBtn.disabled = true;
            } else {
                cartContainer.innerHTML = '';
                cart.forEach((item, index) => {
                    const cartItem = createCartItem(item, index);
                    cartContainer.appendChild(cartItem);
                });
                completeBtn.disabled = false;
            }

            // Update totals
            const totalItemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartSubtotalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const finalTotal = cartSubtotalAmount - currentDiscount;

            itemCount.textContent = `${totalItemsCount} items`;
            totalItems.textContent = totalItemsCount;
            subtotal.textContent = `$${cartSubtotalAmount.toFixed(2)}`;
            total.textContent = `$${finalTotal.toFixed(2)}`;
        }

        // Create cart item
        function createCartItem(item, index) {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item p-3 mb-2';

            cartItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">SKU: ${item.sku}</small>
                        <div class="h6 mb-0 text-primary">$${item.price.toFixed(2)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary quantity-btn" onclick="updateQuantity(${index}, -1)">
                            <i class="ri-subtract-line"></i>
                        </button>
                        <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.stock}" 
                               onchange="setQuantity(${index}, this.value)">
                        <button class="btn btn-sm btn-outline-secondary quantity-btn" onclick="updateQuantity(${index}, 1)">
                            <i class="ri-add-line"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
                <div class="text-end mt-2">
                    <strong>Total: $${(item.price * item.quantity).toFixed(2)}</strong>
                </div>
            `;

            return cartItem;
        }

        // Update quantity
        function updateQuantity(index, change) {
            const item = cart[index];
            const newQuantity = item.quantity + change;

            if (newQuantity <= 0) {
                removeFromCart(index);
            } else if (newQuantity <= item.stock) {
                item.quantity = newQuantity;
                updateCartDisplay();
            } else {
                showStockWarning(item.name, item.stock, newQuantity);
            }
        }

        // Set quantity
        function setQuantity(index, quantity) {
            const item = cart[index];
            const newQuantity = parseInt(quantity);

            if (newQuantity <= 0) {
                removeFromCart(index);
            } else if (newQuantity <= item.stock) {
                item.quantity = newQuantity;
                updateCartDisplay();
            } else {
                showStockWarning(item.name, item.stock, newQuantity);
                item.quantity = item.stock;
                updateCartDisplay();
            }
        }

        // Remove from cart
        function removeFromCart(index) {
            const item = cart[index];
            cart.splice(index, 1);
            updateCartDisplay();
            showToast(`${item.name} removed from cart`, 'info');
        }

        // Clear cart confirmed
        function clearCartConfirmed() {
            cart = [];
            currentCustomer = null;
            currentDiscount = 0;
            document.getElementById('customerSelect').value = '';
            document.getElementById('discountAmount').value = '';
            updateCartDisplay();
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('clearCartModal'));
            modal.hide();
            
            showSuccessModal('Cart Cleared', 'Your shopping cart has been cleared successfully.');
        }

        // Hold sale
        function holdSale() {
            if (cart.length === 0) {
                showInfoModal('Cart Empty', 'Cannot hold an empty sale. Add some products to your cart first.');
                return;
            }

            localStorage.setItem('heldSale', JSON.stringify({
                cart: cart,
                customer: currentCustomer,
                discount: currentDiscount,
                timestamp: new Date().toISOString()
            }));

            showSuccessModal('Sale Held', 'Current sale has been held successfully. You can load it later.');
        }

        // Load held sale
        function loadHeldSale() {
            const heldSale = localStorage.getItem('heldSale');
            if (heldSale) {
                showWarningModal(
                    'Load Held Sale', 
                    'This will replace your current cart with the held sale. Continue?',
                    function() {
                        const saleData = JSON.parse(heldSale);
                        cart = saleData.cart;
                        currentCustomer = saleData.customer;
                        currentDiscount = saleData.discount;

                        updateCartDisplay();
                        updateCustomerDisplay();
                        
                        const modal = bootstrap.Modal.getInstance(document.getElementById('warningModal'));
                        modal.hide();
                        
                        showSuccessModal('Sale Loaded', 'Held sale has been loaded successfully.');
                    }
                );
            } else {
                showInfoModal('No Held Sale', 'No held sale found in storage.');
            }
        }

        // Complete sale
        function completeSale() {
            if (cart.length === 0) {
                showInfoModal('Cart Empty', 'Please add some products to your cart before completing the sale.');
                return;
            }

            showWarningModal(
                'Complete Sale',
                'Are you sure you want to complete this sale? This action cannot be undone.',
                function() {
                    calculateDiscount();
                    showPaymentModal();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('warningModal'));
                    modal.hide();
                }
            );
        }

        // Show payment modal
        function showPaymentModal() {
            const total = calculateCartTotal();
            document.getElementById('paymentTotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('paymentMethod').textContent = 'Cash';
            document.getElementById('amountReceived').value = total.toFixed(2);

            // Show/hide cash payment section
            document.getElementById('cashPaymentSection').style.display = 'block';

            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        // Process payment
        function processPayment(method) {
            if (cart.length === 0) {
                showInfoModal('Cart Empty', 'Please add some products to your cart first.');
                return;
            }

            // Set payment method and show modal
            document.getElementById('paymentMethod').textContent = method.charAt(0).toUpperCase() + method.slice(1);

            if (method === 'cash') {
                document.getElementById('cashPaymentSection').style.display = 'block';
            } else {
                document.getElementById('cashPaymentSection').style.display = 'none';
            }

            const total = calculateCartTotal();
            document.getElementById('paymentTotal').textContent = `$${total.toFixed(2)}`;
            
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        // Finalize payment
        function finalizePayment() {
            const method = document.getElementById('paymentMethod').textContent.toLowerCase();
            const total = calculateCartTotal();

            if (method === 'cash') {
                const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
                if (amountReceived < total) {
                    showErrorModal('Insufficient Amount', 'Amount received is less than total amount.');
                    return;
                }
            }

            const paymentData = {
                cart: cart,
                customer_id: currentCustomer ? currentCustomer.id : null,
                payment_method: method,
                discount_amount: currentDiscount,
                discount_type: document.getElementById('discountType').value,
                amount_received: method === 'cash' ? parseFloat(document.getElementById('amountReceived').value) : total
            };

            fetch('<?php echo e(route('pos.process-sale')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify(paymentData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                        modal.hide();

                        showSaleSuccessModal(data.invoice_id, data.invoice_number, data.grand_total);
                        
                        // Clear cart
                        cart = [];
                        currentCustomer = null;
                        currentDiscount = 0;
                        updateCartDisplay();
                    } else {
                        showErrorModal('Sale Failed', data.message || 'Error processing sale.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('Network Error', 'Unable to process sale. Please check your connection.');
                });
        }

        function continueShopping() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('saleSuccessModal'));
            modal.hide();
        }

        // Setup product search
        function setupProductSearch() {
            const searchInput = document.getElementById('productSearch');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                if (query.length > 2) {
                    searchProducts(query);
                } else {
                    displayProducts(products);
                }
            });
        }

        // Search products
        function searchProducts(query = null) {
            const searchTerm = query || document.getElementById('productSearch').value.toLowerCase();
            const resultsContainer = document.getElementById('searchResults');

            if (searchTerm.length < 2) {
                resultsContainer.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="ri-search-line fs-1 mb-2 d-block"></i>
                        <p class="mb-0">Start typing to search products...</p>
                        <small>Search by name, SKU, or scan barcode</small>
                    </div>
                `;
                return;
            }

            const filteredProducts = products.filter(product =>
                product.name.toLowerCase().includes(searchTerm) ||
                product.sku.toLowerCase().includes(searchTerm) ||
                (product.barcode_primary && product.barcode_primary.toLowerCase().includes(searchTerm))
            );

            // Display as dropdown list
            if (filteredProducts.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="ri-close-circle-line fs-2 mb-2 d-block"></i>
                        <p class="mb-0">No products found for "${searchTerm}"</p>
                        <small>Try different search term</small>
                    </div>
                `;
                return;
            }

            resultsContainer.innerHTML = '';
            filteredProducts.forEach(product => {
                const stockClass = product.on_hand > 0 ? 'text-success' : 'text-danger';
                const stockText = product.on_hand > 0 ? `${product.on_hand} in stock` : 'Out of stock';
                
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.onclick = (e) => {
                    e.preventDefault();
                    addToCart(product.id);
                    document.getElementById('productSearch').value = '';
                    searchProducts();
                };
                
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${product.name}</h6>
                            <small class="text-muted">SKU: ${product.sku} | ${product.barcode_primary || 'No barcode'}</small>
                        </div>
                        <div class="text-end">
                            <div class="h6 mb-0 text-primary">R ${parseFloat(product.price_normal).toFixed(2)}</div>
                            <small class="${stockClass}">${stockText}</small>
                        </div>
                    </div>
                `;
                
                resultsContainer.appendChild(item);
            });
        }

        // Filter by category - DISABLED (search only mode)
        function filterByCategory(categoryId) {
            // Not used in search-only mode
            return;
        }

        // Change view
        function changeView(view) {
            currentView = view;
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });

            if (view === 'grid') {
                document.getElementById('productsGrid').classList.remove('d-none');
                document.getElementById('productsList').classList.add('d-none');
                event.target.classList.add('active');
            } else {
                document.getElementById('productsGrid').classList.add('d-none');
                document.getElementById('productsList').classList.remove('d-none');
                event.target.classList.add('active');
                displayProducts(products);
            }
        }

        // Sort products
        function sortProducts() {
            const sortBy = document.getElementById('sortProducts').value;
            let sortedProducts = [...products];

            switch (sortBy) {
                case 'name':
                    sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'price':
                    sortedProducts.sort((a, b) => parseFloat(a.price_normal) - parseFloat(b.price_normal));
                    break;
                case 'stock':
                    sortedProducts.sort((a, b) => b.on_hand - a.on_hand);
                    break;
            }

            displayProducts(sortedProducts);
        }

        // Populate customer select
        function populateCustomerSelect() {
            const select = document.getElementById('customerSelect');
            select.innerHTML = '<option value="">Walk-in Customer</option>';

            customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.id;
                option.textContent = customer.name;
                select.appendChild(option);
            });
        }

        // Select customer
        function selectCustomer() {
            const customerId = document.getElementById('customerSelect').value;
            currentCustomer = customerId ? customers.find(c => c.id == customerId) : null;
            updateCustomerDisplay();
        }

        // Update customer display
        function updateCustomerDisplay() {
            const customerInfo = document.getElementById('customerInfo');

            if (currentCustomer) {
                customerInfo.innerHTML = `
                    <div class="alert alert-info">
                        <h6 class="mb-1">${currentCustomer.name}</h6>
                        <small class="text-muted">${currentCustomer.email}</small>
                        <br>
                        <small class="text-muted">${currentCustomer.phone}</small>
                    </div>
                `;
                customerInfo.classList.remove('d-none');
            } else {
                customerInfo.classList.add('d-none');
            }
        }

        // Populate category filters
        function populateCategoryFilters() {
            const container = document.getElementById('categoryFilters');

            categories.forEach(category => {
                const col = document.createElement('div');
                col.className = 'col-auto';
                col.innerHTML = `
                    <button class="btn btn-outline-primary" data-category="${category.id}" 
                            onclick="filterByCategory(${category.id})">
                        ${category.name}
                    </button>
                `;
                container.appendChild(col);
            });
        }

        // Barcode scanner functions
        function openBarcodeScanner() {
            const modal = new bootstrap.Modal(document.getElementById('barcodeScannerModal'));
            modal.show();
        }

        function startScanner() {
            const scannerContainer = document.getElementById('scanner-container');

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: scannerContainer,
                    constraints: {
                        width: 480,
                        height: 320,
                        facingMode: "environment"
                    },
                },
                decoder: {
                    readers: [
                        "code_128_reader",
                        "ean_reader",
                        "ean_8_reader",
                        "code_39_reader",
                        "code_39_vin_reader",
                        "codabar_reader",
                        "upc_reader",
                        "upc_e_reader",
                        "i2of5_reader"
                    ]
                },
                locate: true,
                locator: {
                    patchSize: "medium",
                    halfSample: true
                },
            }, function(err) {
                if (err) {
                    console.error(err);
                    showErrorModal('Scanner Error', 'Error initializing barcode scanner: ' + err.message);
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(function(data) {
                console.log('Barcode detected:', data.codeResult.code);

                // Stop scanner
                Quagga.stop();

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('barcodeScannerModal'));
                modal.hide();

                // Search for product by barcode
                searchProductByBarcode(data.codeResult.code);
            });
        }

        function stopScanner() {
            Quagga.stop();
        }

        function searchProductByBarcode(barcode) {
            fetch(`<?php echo e(route('pos.product-by-barcode')); ?>?barcode=${encodeURIComponent(barcode)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;
                        // Add product to cart
                        addProductToCart(product);
                        showSuccessModal('Product Found', `${product.name} has been added to cart.`);
                    } else {
                        showErrorModal('Product Not Found', `No product found for barcode: ${barcode}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('Search Error', 'Error searching for product.');
                });
        }

        function addProductToCart(product) {
            const existingItem = cart.find(item => item.id === product.id);

            if (existingItem) {
                if (existingItem.quantity < product.on_hand) {
                    existingItem.quantity += 1;
                } else {
                    showStockWarning(product.name, product.on_hand, existingItem.quantity + 1);
                    return;
                }
            } else {
                if (product.on_hand > 0) {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        sku: product.sku,
                        price: parseFloat(product.price_normal),
                        quantity: 1,
                        stock: product.on_hand
                    });
                } else {
                    showStockWarning(product.name, product.on_hand, 1);
                    return;
                }
            }

            updateCartDisplay();
        }

        // Add new customer
        function addNewCustomer() {
            // Clear form
            document.getElementById('customerForm').reset();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('customerModal'));
            modal.show();
        }

        // Save customer
        document.getElementById('customerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const customerData = {
                name: document.getElementById('customerName').value,
                email: document.getElementById('customerEmail').value,
                phone: document.getElementById('customerPhone').value,
                company: document.getElementById('customerCompany').value,
                address: document.getElementById('customerAddress').value,
                city: document.getElementById('customerCity').value,
                postal_code: document.getElementById('customerPostalCode').value,
                status: 'active'
            };

            fetch('<?php echo e(route('customers.store')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify(customerData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
                        modal.hide();

                        // Add to customers list
                        customers.push(data.customer);

                        // Update select
                        populateCustomerSelect();

                        // Select the new customer
                        document.getElementById('customerSelect').value = data.customer.id;
                        selectCustomer();

                        showSuccessModal('Customer Added', 'Customer has been added successfully!');
                    } else {
                        showErrorModal('Add Customer Failed', data.message || 'Error adding customer.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('Network Error', 'Unable to add customer. Please check your connection.');
                });
        });

        // Calculate discount
        function calculateDiscount() {
            const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
            const discountType = document.getElementById('discountType').value;

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            if (discountAmount > 0) {
                if (discountType === 'percentage') {
                    currentDiscount = subtotal * (discountAmount / 100);
                } else {
                    currentDiscount = discountAmount;
                }
            } else {
                currentDiscount = 0;
            }

            updateCartDisplay();
        }

        // Calculate cart total
        function calculateCartTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            return subtotal - currentDiscount;
        }

        // Update cart display with discount
        function updateCartDisplayWithDiscount() {
            calculateDiscount();
            updateCartDisplay();
        }

        // Add discount change listeners
        document.getElementById('discountAmount').addEventListener('input', updateCartDisplayWithDiscount);
        document.getElementById('discountType').addEventListener('change', updateCartDisplayWithDiscount);

        // Cash payment change calculator
        document.getElementById('amountReceived').addEventListener('input', function() {
            const amountReceived = parseFloat(this.value) || 0;
            const total = calculateCartTotal();
            const change = amountReceived - total;

            const changeSection = document.getElementById('changeSection');
            const changeAmount = document.getElementById('changeAmount');

            if (change > 0) {
                changeSection.style.display = 'block';
                changeAmount.textContent = `$${change.toFixed(2)}`;
                changeAmount.className = 'text-success';
            } else if (change < 0) {
                changeSection.style.display = 'block';
                changeAmount.textContent = `$${Math.abs(change).toFixed(2)}`;
                changeAmount.className = 'text-danger';
            } else {
                changeSection.style.display = 'none';
            }
        });

        // Customer Experience Functions
        function showCustomerExperienceOptions(invoiceId, invoiceNumber) {
            currentInvoiceId = invoiceId;
            currentInvoiceNumber = invoiceNumber;
            document.getElementById('customerExperienceSection').style.display = 'block';
        }

        // Generate PDF Invoice
        function generatePDF() {
            if (currentInvoiceId) {
                window.open(`<?php echo e(url('pos/invoice-pdf')); ?>/${currentInvoiceId}`, '_blank');
            } else {
                showErrorModal('PDF Error', 'No invoice available for download.');
            }
        }

        // Print Invoice
        function printInvoice() {
            if (currentInvoiceId) {
                const printWindow = window.open(`<?php echo e(url('pos/invoice-pdf')); ?>/${currentInvoiceId}`, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            } else {
                showErrorModal('Print Error', 'No invoice available for printing.');
            }
        }

        // Generate Picking List
        function generatePickingList() {
            if (currentInvoiceId) {
                window.open(`<?php echo e(url('pos/picking-list')); ?>/${currentInvoiceId}`, '_blank');
            } else {
                showErrorModal('Picking List Error', 'No invoice available for picking list.');
            }
        }

        // Send WhatsApp
        function sendWhatsApp() {
            if (!currentCustomer || !currentCustomer.phone) {
                showInfoModal('Contact Required', 'Please select a customer with a phone number or enter a phone number to send via WhatsApp.');
                return;
            }

            fetch(`<?php echo e(url('pos/send-invoice')); ?>/${currentInvoiceId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    method: 'whatsapp',
                    contact: currentCustomer.phone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.open(data.whatsapp_url, '_blank');
                } else {
                    showErrorModal('WhatsApp Error', data.message || 'Error sending WhatsApp message.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('Network Error', 'Unable to send WhatsApp message.');
            });
        }

        // Send Email
        function sendEmail() {
            if (!currentCustomer || !currentCustomer.email) {
                showInfoModal('Contact Required', 'Please select a customer with an email address to send the invoice.');
                return;
            }

            fetch(`<?php echo e(url('pos/send-invoice')); ?>/${currentInvoiceId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    method: 'email',
                    contact: currentCustomer.email
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal('Email Sent', 'Invoice has been sent successfully via email!');
                } else {
                    showErrorModal('Email Error', data.message || 'Error sending email.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorModal('Network Error', 'Unable to send email.');
            });
        }

        // Save as quote
        function saveAsQuote() {
            if (cart.length === 0) {
                showInfoModal('Cart Empty', 'Please add some products to your cart before saving as quote.');
                return;
            }

            showWarningModal(
                'Save as Quote',
                'Are you sure you want to save this cart as a quote?',
                function() {
                    window.location.href = '<?php echo e(route('quotes.create')); ?>?cart=' + encodeURIComponent(JSON.stringify(cart));
                    const modal = bootstrap.Modal.getInstance(document.getElementById('warningModal'));
                    modal.hide();
                }
            );
        }

        // Toastr notifications for non-critical messages
        function showToast(message, type = 'success') {
            const toastrOptions = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            switch (type) {
                case 'success':
                    toastr.success(message, 'Success', toastrOptions);
                    break;
                case 'error':
                    toastr.error(message, 'Error', toastrOptions);
                    break;
                case 'warning':
                    toastr.warning(message, 'Warning', toastrOptions);
                    break;
                case 'info':
                    toastr.info(message, 'Information', toastrOptions);
                    break;
            }
        }
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\MMP\resources\views/pos/index.blade.php ENDPATH**/ ?>