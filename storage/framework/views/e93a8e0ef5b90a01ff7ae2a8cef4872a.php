<?php $__env->startSection('title', 'Workshop Job Cards'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .modal-dialog-scrollable .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .clickable-row {
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Print Styles */
    @media print {
        body {
            margin: 0;
            padding: 10px;
        }
        
    .container-fluid {
            padding: 0 !important;
        }
        
        .d-flex.justify-content-between,
        .card.shadow-sm,
        .btn,
        .dropdown,
        .pagination,
        .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-body {
            padding: 0 !important;
        }
        
        .table {
            font-size: 10px !important;
        }
        
        .table thead th {
            background-color: #007bff !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .badge {
            border: 1px solid #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .text-primary {
            color: #007bff !important;
        }
        
        .text-success {
            color: #28a745 !important;
        }
        
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h4 class="mb-0 me-3">
                <i class="ri-tools-line me-2"></i>Workshop Job Cards
            </h4>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <!-- New Job Card Button -->
            <button class="btn btn-primary-light btn-wave me-2 waves-effect waves-light" onclick="openCreateModal()">
                <i class="ri-add-line me-1"></i>New Job Card
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-3">
                <div class="card-body">
            <form id="filterForm" method="GET" action="<?php echo e(route('job-cards.index')); ?>">
                <div class="row g-2">
                        <div class="col-md-3">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Search by job number, customer..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="booked" <?php echo e(request('status') == 'booked' ? 'selected' : ''); ?>>Booked In</option>
                            <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                            <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                            <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                        </div>
                    <div class="col-md-2">
                        <select name="vehicle_make" id="vehicleMakeFilter" class="form-select">
                                <option value="">All Makes</option>
                            <?php $__currentLoopData = \App\Models\CarMake::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $make): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($make->name); ?>" <?php echo e(request('vehicle_make') == $make->name ? 'selected' : ''); ?>>
                                    <?php echo e($make->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <div class="col-md-2">
                        <input type="text" name="customer_name" class="form-control" 
                            placeholder="Customer Name..." value="<?php echo e(request('customer_name')); ?>">
                        </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" 
                            placeholder="From Date" value="<?php echo e(request('date_from')); ?>">
                        </div>
                    <div class="col-md-1">
                        <div class="d-grid gap-1">
                            <button type="button" class="btn btn-outline-info" id="clearFilters">
                                Reset
                            </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Job Cards Table -->
    <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="card-title">
                    Job Cards<span class="badge bg-light text-default rounded ms-1 fs-12 align-middle"><?php echo e($jobCards->total()); ?></span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <!-- Print & Export Dropdown -->
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="printJobCards()">
                                    <i class="ri-printer-line me-2 text-secondary"></i>Print
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo e(route('job-cards.export', ['format' => 'pdf'])); ?>">
                                    <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                                </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('job-cards.export', ['format' => 'csv'])); ?>">
                                    <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                                </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('job-cards.export', ['format' => 'excel'])); ?>">
                                    <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <div class="table-responsive position-relative" id="jobCardsTable">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Job Card #</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Job Description</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jobCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jobCard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="clickable-row" onclick="viewJobCard(<?php echo e($jobCard->id); ?>)"  style="cursor: pointer;">
                            <td><?php echo e($loop->iteration + ($jobCards->currentPage() - 1) * $jobCards->perPage()); ?></td>
                            
                            
                            <td>
                                <strong class="text-primary"><?php echo e($jobCard->job_card_number); ?></strong>
                            </td>
                            
                            
                            <td>
                                <div>
                                    <strong><?php echo e($jobCard->customer_name); ?></strong>
                                    <?php if($jobCard->customer_phone): ?>
                                        <br><small class="text-muted"><?php echo e($jobCard->customer_phone); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            
                            <td>
                                <div>
                                    <?php if($jobCard->vehicle_make || $jobCard->vehicle_model): ?>
                                        <strong><?php echo e($jobCard->vehicle_make); ?> <?php echo e($jobCard->vehicle_model); ?></strong>
                                    <?php endif; ?>
                                    <?php if($jobCard->vehicle_registration): ?>
                                        <br><small class="text-muted"><?php echo e($jobCard->vehicle_registration); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="<?php echo e($jobCard->job_description); ?>">
                                    <?php echo e($jobCard->job_description); ?>

                                </div>
                            </td>
                            
                            
                            <td>
                                <span class="badge rounded-pill bg-<?php echo e($jobCard->status_badge); ?>-transparent">
                                    <?php echo e($jobCard->status_text); ?>

                                </span>
                            </td>
                            
                            
                            <td>
                                <strong>R <?php echo e(number_format($jobCard->grand_total, 2)); ?></strong>
                            </td>
                            
                            
                            <td>
                                <?php echo e($jobCard->created_at->format('M d, Y')); ?>

                                <br>
                                <small class="text-muted"><?php echo e($jobCard->created_at->format('H:i A')); ?></small>
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-sm btn-info-light btn-icon" onclick="viewJobCard(<?php echo e($jobCard->id); ?>)" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <?php if($jobCard->status !== 'completed' && $jobCard->status !== 'cancelled'): ?>
                                    <button type="button" class="btn btn-sm btn-success-light btn-icon" onclick="editJobCard(<?php echo e($jobCard->id); ?>)" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <!-- Status Action Buttons -->
                                    <?php if($jobCard->status === 'pending'): ?>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="changeStatus(<?php echo e($jobCard->id); ?>, 'booked')" title="Book In">
                                        <i class="ri-calendar-check-line me-1"></i>Book In
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if($jobCard->status === 'booked'): ?>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="changeStatus(<?php echo e($jobCard->id); ?>, 'in_progress')" title="Start Work">
                                        <i class="ri-play-circle-line me-1"></i>Start Work
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if($jobCard->status === 'in_progress'): ?>
                                    <button type="button" class="btn btn-sm btn-success" onclick="changeStatus(<?php echo e($jobCard->id); ?>, 'completed')" title="Mark Complete">
                                        <i class="ri-checkbox-circle-line me-1"></i>Complete
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if($jobCard->status === 'completed' && !$jobCard->final_invoice_id): ?>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="convertToInvoice(<?php echo e($jobCard->id); ?>)" title="Convert to Invoice">
                                        <i class="ri-receipt-line me-1"></i>Invoice
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if($jobCard->final_invoice_id): ?>
                                    <button type="button" class="btn btn-sm btn-success-light btn-icon" onclick="viewJobInvoice(<?php echo e($jobCard->final_invoice_id); ?>)" title="View Invoice">
                                        <i class="ri-receipt-line"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <!-- PDF Download -->
                                    <button type="button" class="btn btn-sm btn-primary-light btn-icon" onclick="downloadPDF(<?php echo e($jobCard->id); ?>)" title="Download PDF">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <?php if($jobCard->status !== 'completed' && $jobCard->status !== 'cancelled'): ?>
                                    <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="deleteJobCard(<?php echo e($jobCard->id); ?>)" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line fs-1 mb-3"></i>
                                    <p>No job cards found</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
                </div>
        <div class="card-footer">
            <?php echo e($jobCards->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>

<!-- Modals will be loaded here -->
<div id="modalContainer"></div>

<script>
// Job Card Modal Variables
let partRowCount = 0;
let labourRowCount = 0;
let jobCardProducts = [];
let jobCardTechnicians = [];

// Open create modal
function openCreateModal() {
    fetch('<?php echo e(route("job-cards.create")); ?>')
        .then(response => response.text())
        .then(html => {
            const container = document.getElementById('modalContainer');
            container.innerHTML = html;
            
            // Get data from modal's data attributes
            const modalElement = document.getElementById('createJobCardModal');
            if (modalElement) {
                try {
                    const productsData = modalElement.getAttribute('data-products');
                    const techniciansData = modalElement.getAttribute('data-technicians');
                    
                    jobCardProducts = productsData ? JSON.parse(productsData) : [];
                    jobCardTechnicians = techniciansData ? JSON.parse(techniciansData) : [];
                    
                    console.log('✅ Data loaded from attributes');
                    console.log('Products:', jobCardProducts.length);
                    console.log('Technicians:', jobCardTechnicians.length);
                } catch (e) {
                    console.error('Error parsing data:', e);
                    jobCardProducts = [];
                    jobCardTechnicians = [];
                }
            }
            
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Initialize after data is set
            setTimeout(() => {
                initializeJobCardModal();
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading form');
        });
}

// Initialize job card modal
function initializeJobCardModal() {
    // Reset counters
    partRowCount = 0;
    labourRowCount = 0;
    
    console.log('🔧 Initializing Job Card Modal');
    console.log('Products available:', jobCardProducts.length);
    console.log('Technicians available:', jobCardTechnicians.length);
    
    // If no products, show error
    if (jobCardProducts.length === 0) {
        console.error('❌ ERROR: No products loaded!');
        alert('⚠️ No products found. Please add products first from Products page.');
        return;
    }
    
    // Initialize POS-style customer search
    initializeJobCardCustomerSearch();
    
    // Initialize Quick Add Customer form handler
    initializeQuickAddCustomerForm();

    // Product search
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function(e) {
            handleProductSearch(e);
        });
        
        // Add Enter key support
        productSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const resultsDiv = document.getElementById('productSearchResults');
                const firstResult = resultsDiv.querySelector('.product-search-result');
                if (firstResult) {
                    const productId = firstResult.getAttribute('data-product-id');
                    addProductToJobCard(productId);
                }
            }
        });
    }

    // Button event listeners
    const addPartBtn = document.getElementById('addPartBtn');
    if (addPartBtn) {
        addPartBtn.addEventListener('click', addPartRow);
    }

    const addLabourBtn = document.getElementById('addLabourBtn');
    if (addLabourBtn) {
        addLabourBtn.addEventListener('click', addLabourRow);
    }

    const scanBarcodeBtn = document.getElementById('scanBarcodeBtn');
    if (scanBarcodeBtn) {
        scanBarcodeBtn.addEventListener('click', scanBarcode);
    }

    // Form submission
    const createJobCardForm = document.getElementById('createJobCardForm');
    if (createJobCardForm) {
        createJobCardForm.addEventListener('submit', handleJobCardFormSubmit);
    }
}

// ========================================
// POS-Style Customer & Vehicle Functions
// ========================================

let jobCardCustomers = [];
let jobCardCurrentCustomer = null;
let jobCardCurrentVehicle = null;

// Initialize customer search
function initializeJobCardCustomerSearch() {
    console.log('🔄 Initializing customer search...');
    console.log('📍 Fetching from:', '<?php echo e(route('pos.customers')); ?>');
    
    // Load all customers once on modal open
    fetch('<?php echo e(route('pos.customers')); ?>')
        .then(response => {
            console.log('📡 Response received:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`Failed to load customers: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📦 Raw data received:', data);
            // Direct assignment like Quotation (data is already the array)
            jobCardCustomers = data || [];
            console.log('✅ Loaded customers for job card:', jobCardCustomers.length);
            console.log('👥 Sample customer:', jobCardCustomers[0]);
            
            if (jobCardCustomers.length === 0) {
                console.warn('⚠️ No customers loaded - check database');
                toastr.warning('No customers found in database');
    } else {
                console.log('✅ Customer search ready!');
            }
        })
        .catch(error => {
            console.error('❌ Error loading customers:', error);
            toastr.error('Error loading customers: ' + error.message);
        });

    // Customer search input
    const customerSearch = document.getElementById('customerSearch');
    if (customerSearch) {
        customerSearch.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const resultsDiv = document.getElementById('customerSearchResults');
            
            console.log('🔍 Search query:', query, '| Available customers:', jobCardCustomers.length);
            
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                // Show walk-in form if search is empty
                const walkInForm = document.getElementById('walkInCustomerForm');
                if (walkInForm) walkInForm.style.display = 'block';
                return;
            }
            
            // Check if customers are loaded
            if (jobCardCustomers.length === 0) {
                resultsDiv.innerHTML = '<div class="list-group-item text-warning"><i class="ri-alert-line me-2"></i>Loading customers...</div>';
                resultsDiv.style.display = 'block';
                return;
            }
            
            // Filter customers
            const filtered = jobCardCustomers.filter(customer => 
                customer.name.toLowerCase().includes(query) ||
                (customer.phone && customer.phone.toLowerCase().includes(query)) ||
                (customer.email && customer.email.toLowerCase().includes(query))
            );
            
            console.log('✅ Filtered results:', filtered.length);
            
            if (filtered.length > 0) {
                resultsDiv.innerHTML = filtered.map(customer => `
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action" 
                       onclick="selectJobCardCustomer(${customer.id})">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">${customer.name}</h6>
                                <small class="text-muted">${customer.phone || 'No phone'} • ${customer.email || 'No email'}</small>
                            </div>
                            <span class="badge bg-${customer.terms === 'credit' ? 'success' : 'info'}">${customer.terms === 'credit' ? 'Credit' : 'Cash'}</span>
                        </div>
                    </a>
                `).join('');
                resultsDiv.style.display = 'block';
                // Hide walk-in form when results found
                const walkInForm = document.getElementById('walkInCustomerForm');
                if (walkInForm) walkInForm.style.display = 'none';
            } else {
                resultsDiv.innerHTML = '<div class="list-group-item text-muted"><i class="ri-search-line me-2"></i>No customers found</div>';
                resultsDiv.style.display = 'block';
                // Show walk-in form when no results
                const walkInForm = document.getElementById('walkInCustomerForm');
                if (walkInForm) walkInForm.style.display = 'block';
            }
        });
    }
}

// Select customer
function selectJobCardCustomer(customerId) {
    console.log('👤 Selecting customer ID:', customerId);
    const customer = jobCardCustomers.find(c => c.id == customerId);
    if (!customer) {
        console.log('❌ Customer not found');
        return;
    }
    
    console.log('✅ Customer found:', customer);
    jobCardCurrentCustomer = customer;
    
    // Update UI
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearchResults').style.display = 'none';
    document.getElementById('walkInCustomerForm').style.display = 'none';
    
    document.getElementById('selectedCustomerCard').style.display = 'block';
    document.getElementById('selectedCustomerName').textContent = customer.name;
    document.getElementById('selectedCustomerDetails').textContent = `${customer.phone || 'No phone'} • ${customer.email || 'No email'}`;
    
    // Show vehicle dropdown, hide manual entry
    const vehicleSelectSection = document.getElementById('vehicleSelectSection');
    const vehicleManualEntry = document.getElementById('vehicleManualEntry');
    if (vehicleSelectSection) vehicleSelectSection.style.display = 'block';
    if (vehicleManualEntry) vehicleManualEntry.style.display = 'none';
    
    // Add hidden input for customer_id
    let customerIdInput = document.getElementById('selectedCustomerId');
    if (!customerIdInput) {
        customerIdInput = document.createElement('input');
        customerIdInput.type = 'hidden';
        customerIdInput.id = 'selectedCustomerId';
        customerIdInput.name = 'customer_id';
        document.getElementById('createJobCardForm').appendChild(customerIdInput);
    }
    customerIdInput.value = customer.id;
    
    // Load customer vehicles
    loadJobCardCustomerVehicles();
}

// Clear selected customer
function clearSelectedCustomer() {
    jobCardCurrentCustomer = null;
    jobCardCurrentVehicle = null;
    
    document.getElementById('selectedCustomerCard').style.display = 'none';
    document.getElementById('walkInCustomerForm').style.display = 'block';
    
    // Show manual entry, hide dropdown
    const vehicleSelectSection = document.getElementById('vehicleSelectSection');
    const vehicleManualEntry = document.getElementById('vehicleManualEntry');
    if (vehicleSelectSection) vehicleSelectSection.style.display = 'none';
    if (vehicleManualEntry) vehicleManualEntry.style.display = 'block';
    
    document.getElementById('vehicleSelect').innerHTML = '<option value="">Select Vehicle...</option>';
    
    // Remove hidden input
    const customerIdInput = document.getElementById('selectedCustomerId');
    if (customerIdInput) {
        customerIdInput.remove();
    }
    
    // Clear vehicle info
    document.getElementById('vehicleMake').value = '';
    document.getElementById('vehicleModel').value = '';
    document.getElementById('vehicleYear').value = '';
    document.getElementById('vehicleEngine').value = '';
    document.getElementById('vehicleRegistration').value = '';
    document.getElementById('vehicleVIN').value = '';
    document.getElementById('vehicleMileage').value = '';
}

// Open Add Customer Modal
window.openJobCardAddCustomerModal = function() {
    const addCustomerModal = new bootstrap.Modal(document.getElementById('addJobCardCustomerModal'), {
        backdrop: false,
        keyboard: true
    });
    addCustomerModal.show();
};

// Initialize Quick Add Customer Form Handler (Same as Quotation)
function initializeQuickAddCustomerForm() {
    console.log('✅ Initializing Quick Add Customer form handler');
    
    $('#addJobCardCustomerForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        console.log('📝 Customer form submitted');
        
        const formData = $(this).serialize() + '&quick_add=1';
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span>Creating...');
        
        $.ajax({
            url: '<?php echo e(route('customers.store')); ?>',
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log('✅ Customer created:', response);
                if (response.success) {
                    toastr.success('Customer created successfully!');
                    
                    // Auto-select the new customer
                    const customer = {
                        id: response.customer.id,
                        name: response.customer.name,
                        email: response.customer.email || '',
                        phone: response.customer.phone || '',
                        terms: response.customer.terms || 'cash',
                        price_tier: response.customer.price_tier || 'normal'
                    };
                    
                    console.log('👤 New customer:', customer);
                    
                    // Add to customers array
                    jobCardCustomers.push(customer);
                    console.log('📋 Total customers:', jobCardCustomers.length);
                    
                    // Select the customer
                    console.log('🎯 Selecting customer ID:', customer.id);
                    selectJobCardCustomer(customer.id);
                    
                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addJobCardCustomerModal'));
                    modal.hide();
                    $('#addJobCardCustomerForm')[0].reset();
                    
                    console.log('✅ Quick Add Customer completed!');
                }
            },
            error: function(xhr) {
                console.error('❌ Error creating customer:', xhr);
                let errorMsg = 'Failed to create customer.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
}

// Load customer vehicles
function loadJobCardCustomerVehicles() {
    console.log('🚗 Loading vehicles for customer:', jobCardCurrentCustomer);
    
    // Clear existing data first
    jobCardCurrentVehicle = null;
    document.getElementById('vehicleSelect').innerHTML = '<option value="">Select Vehicle...</option>';
    document.getElementById('vehicleInfo').style.display = 'none';
    
    if (!jobCardCurrentCustomer) {
        console.log('❌ No customer selected');
        return;
    }
    
    const customerId = jobCardCurrentCustomer.id;
    const url = '<?php echo e(route('api.customers.vehicles', ':id')); ?>'.replace(':id', customerId);
    
    console.log('📍 Fetching vehicles from:', url);
    
    fetch(url)
        .then(response => {
            console.log('📡 Vehicle response:', response.status, response.statusText);
            return response.json();
        })
        .then(data => {
            console.log('🚗 Vehicle data received:', data);
            const select = document.getElementById('vehicleSelect');
            
            if (data.length > 0) {
                console.log('✅ Found vehicles:', data.length);
                data.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.id;
                    option.textContent = `${vehicle.make_name} ${vehicle.model_name} (${vehicle.year || 'N/A'}) - ${vehicle.registration || 'No Reg'}`;
                    option.dataset.vehicle = JSON.stringify(vehicle);
                    select.appendChild(option);
                });
                console.log('🚗 Vehicle dropdown populated');
            } else {
                console.log('⚠️ No vehicles found for customer');
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No vehicles found';
                option.disabled = true;
                select.appendChild(option);
            }
        })
        .catch(error => {
            console.error('❌ Error loading vehicles:', error);
            toastr.error('Error loading vehicles');
        });
}

// Vehicle select change handler (Same as Quotation)
window.selectJobCardVehicle = function() {
    const select = document.getElementById('vehicleSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    console.log('🚗 Vehicle selected:', selectedOption?.value);
    
    if (!selectedOption || !selectedOption.value) {
        document.getElementById('vehicleInfo').style.display = 'none';
        document.getElementById('vehicleInfo').innerHTML = '';
        jobCardCurrentVehicle = null;
        return;
    }
    
    const vehicle = JSON.parse(selectedOption.dataset.vehicle);
    jobCardCurrentVehicle = vehicle;
    
    console.log('✅ Vehicle data:', vehicle);
    
    // Display vehicle info in disabled inputs (Same as Quotation)
    const html = `
        <div class="card shadow-sm mt-2">
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-road-map-line text-danger me-1"></i>Registration
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.registration || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-car-line text-primary me-1"></i>Make
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.make_name || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-car-line text-success me-1"></i>Model
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.model_name || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-calendar-line text-info me-1"></i>Year
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.year || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-settings-3-line text-warning me-1"></i>Engine
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.engine_name || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-barcode-line text-secondary me-1"></i>VIN
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.vin || 'N/A'}" disabled>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1 fw-semibold">
                            <i class="ri-speed-line text-warning me-1"></i>Mileage
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" 
                               value="${vehicle.mileage ? vehicle.mileage + ' km' : 'N/A'}" disabled>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('vehicleInfo').innerHTML = html;
    document.getElementById('vehicleInfo').style.display = 'block';
    
    console.log('🚗 Vehicle info displayed');
};

// Open Add Vehicle Modal
window.openAddVehicleModal = function() {
    if (!jobCardCurrentCustomer) {
        toastr.error('Please select a customer first');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('addVehicleModal'));
    modal.show();
};

// Load vehicle models based on make
window.loadVehicleModels = function() {
    const makeId = document.getElementById('addVehicleMake').value;
    const modelSelect = document.getElementById('addVehicleModel');
    const engineSelect = document.getElementById('addVehicleEngine');
    
    modelSelect.innerHTML = '<option value="">Select Model...</option>';
    engineSelect.innerHTML = '<option value="">Select Engine...</option>';
    
    if (!makeId) return;
    
    const url = '<?php echo e(route('api.vehicle-makes.models', ':id')); ?>'.replace(':id', makeId);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            data.forEach(model => {
                const option = document.createElement('option');
                option.value = model.id;
                option.textContent = model.name;
                modelSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading models:', error);
            toastr.error('Error loading models');
        });
};

// Engine is now a simple text input (removed loadVehicleEngines function)

// Add vehicle to job card
window.addVehicleToJobCard = function() {
    if (!jobCardCurrentCustomer) {
        toastr.error('Please select a customer first');
        return;
    }
    
    const makeId = document.getElementById('addVehicleMake').value;
    const modelId = document.getElementById('addVehicleModel').value;
    const year = document.getElementById('addVehicleYear').value;
    const engine = document.getElementById('addVehicleEngine').value; // Text input now
    const registration = document.getElementById('addVehicleRegistration').value;
    const vin = document.getElementById('addVehicleVIN').value;
    const mileage = document.getElementById('addVehicleMileage').value;
    
    if (!makeId || !modelId || !year) {
        toastr.error('Please fill in Make, Model, and Year');
        return;
    }
    
    console.log('🚗 Adding vehicle with engine:', engine);
    
    // Save vehicle to customer (Use route name)
    const url = '<?php echo e(route('customers.vehicles.store', ':customerId')); ?>'.replace(':customerId', jobCardCurrentCustomer.id);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            make_id: makeId,              // ✅ Correct parameter name
            model_id: modelId,            // ✅ Correct parameter name
            year: year,
            engine: engine,               // ✅ Engine as text
            registration_number: registration,  // ✅ Correct parameter name
            vin_number: vin,              // ✅ Correct parameter name
            mileage: mileage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Vehicle added successfully:', data.vehicle);
            toastr.success('Vehicle added successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
            modal.hide();
            
            // Clear form
            document.getElementById('addVehicleMake').value = '';
            document.getElementById('addVehicleModel').value = '';
            document.getElementById('addVehicleYear').value = '';
            document.getElementById('addVehicleEngine').value = '';
            document.getElementById('addVehicleRegistration').value = '';
            document.getElementById('addVehicleVIN').value = '';
            document.getElementById('addVehicleMileage').value = '';
            
            // Reload vehicles and auto-select the new one
            const newVehicleId = data.vehicle.id;
            console.log('🔄 Reloading vehicles and auto-selecting:', newVehicleId);
            
            // Reload vehicles
            const url = '<?php echo e(route('api.customers.vehicles', ':id')); ?>'.replace(':id', jobCardCurrentCustomer.id);
            
            fetch(url)
                .then(response => response.json())
                .then(vehicles => {
                    console.log('🚗 Vehicles reloaded:', vehicles.length);
                    
                    const select = document.getElementById('vehicleSelect');
                    select.innerHTML = '<option value="">Select Vehicle...</option>';
                    
                    if (vehicles.length > 0) {
                        vehicles.forEach(vehicle => {
                            const option = document.createElement('option');
                            option.value = vehicle.id;
                            option.textContent = `${vehicle.make_name} ${vehicle.model_name} (${vehicle.year || 'N/A'}) - ${vehicle.registration || 'No Reg'}`;
                            option.dataset.vehicle = JSON.stringify(vehicle);
                            select.appendChild(option);
                        });
                        
                        // Auto-select the newly added vehicle
                        select.value = newVehicleId;
                        
                        // Trigger the select event to display vehicle info
                        selectJobCardVehicle();
                        
                        console.log('✅ New vehicle auto-selected!');
                    }
                })
                .catch(error => {
                    console.error('❌ Error reloading vehicles:', error);
                    toastr.error('Error loading vehicles');
                });
        } else {
            toastr.error(data.message || 'Error adding vehicle');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error adding vehicle');
    });
};

// Product search handler
function handleProductSearch(event) {
    const query = event.target.value.toLowerCase();
    const resultsDiv = document.getElementById('productSearchResults');
    
    console.log('Search query:', query, 'Products count:', jobCardProducts.length);
    
    if (query.length < 2) {
        resultsDiv.style.display = 'none';
        return;
    }
    
    if (jobCardProducts.length === 0) {
        resultsDiv.innerHTML = '<div class="list-group-item text-danger">No products loaded. Please refresh the page.</div>';
        resultsDiv.style.display = 'block';
        return;
    }
    
    const filteredProducts = jobCardProducts.filter(product => 
        product.name.toLowerCase().includes(query) ||
        product.sku.toLowerCase().includes(query) ||
        (product.barcode_primary && product.barcode_primary.toLowerCase().includes(query)) ||
        (product.supplier_code && product.supplier_code.toLowerCase().includes(query)) ||
        (product.oe_numbers && product.oe_numbers.toLowerCase().includes(query))
    );
    
    console.log('Filtered products:', filteredProducts.length);
    
    if (filteredProducts.length > 0) {
        resultsDiv.innerHTML = filteredProducts.map(product => {
            // Calculate available stock
            const onHand = product.on_hand || 0;
            const reserved = product.reserved || 0;
            const available = onHand - reserved;
            
            // Determine stock badge
            let stockBadge = '';
            if (available > 0) {
                stockBadge = `<span class="badge bg-success">Available: ${available}</span>`;
            } else if (onHand > 0 && available <= 0) {
                stockBadge = `<span class="badge bg-warning">Reserved</span>`;
            } else if (onHand === 0) {
                stockBadge = `<span class="badge bg-danger">Out of Stock</span>`;
            } else {
                stockBadge = `<span class="badge bg-secondary">Unknown</span>`;
            }
            
            return `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action product-search-result" data-product-id="${product.id}">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="flex-grow-1">
                    <h6 class="mb-1">${product.name}</h6>
                            <p class="mb-1 small text-muted">
                                SKU: ${product.sku} | Barcode: ${product.barcode_primary || 'N/A'}
                                ${product.supplier_code ? ` | Supplier: ${product.supplier_code}` : ''}
                                ${product.oe_numbers ? ` | OE: ${product.oe_numbers}` : ''}
                            </p>
                            <div class="d-flex gap-2 align-items-center">
                <small class="text-success"><strong>R${product.price_workshop || product.price_normal || 0}</strong></small>
                                ${stockBadge}
                            </div>
                        </div>
                    </div>
            </a>
            `;
        }).join('');
        resultsDiv.style.display = 'block';
        
        // Add click event listeners to search results
        resultsDiv.querySelectorAll('.product-search-result').forEach(item => {
            item.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addProductToJobCard(productId);
            });
        });
    } else {
        // No results - show Quick Add (Same as Quotation/POS Style)
        resultsDiv.innerHTML = `
            <div class="p-3">
                <div class="card border-success shadow-sm">
                    <div class="card-body p-3">
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-product-hunt-line text-primary me-1"></i>Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="quickProductName" value="${query}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-price-tag-3-line text-success me-1"></i>Price <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control form-control-sm" id="quickProductPrice" value="0.00" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold mb-1">
                                    <i class="ri-stack-line text-warning me-1"></i>Qty <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control form-control-sm" id="quickProductQty" value="1" min="1">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success flex-grow-1" id="quickAddJobCardProductBtn">
                                <i class="ri-check-line me-1"></i>Create & Add to Job Card
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="document.getElementById('productSearchResults').style.display='none'; document.getElementById('productSearch').value=''; document.getElementById('productSearch').focus();">
                                <i class="ri-close-line me-1"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        resultsDiv.style.display = 'block';
    }
}

// Quick Add Product Button Handler (Same as Quotation)
$(document).on('click', '#quickAddJobCardProductBtn', function() {
    const productName = $('#quickProductName').val().trim();
    const productPrice = parseFloat($('#quickProductPrice').val()) || 0;
    const productQty = parseInt($('#quickProductQty').val()) || 1;
    
    if (!productName || productPrice <= 0) {
        toastr.error('Please enter product name and valid price');
        return;
    }
    
    // Create product via AJAX
    const formData = {
        name: productName,
        price_normal: productPrice,
        qty: productQty,
        _token: '<?php echo e(csrf_token()); ?>'
    };
    
    $(this).prop('disabled', true).html(
        '<i class="ri-loader-4-line ri-spin me-1"></i>Creating...');
    
    $.post('<?php echo e(route('products.quickAdd')); ?>', formData, function(response) {
        if (response.success) {
            toastr.success('Product created & added to job card!');
            
            const product = response.product;
            
            // Add product to job card products array
            jobCardProducts.push({
                id: product.id,
                name: product.name,
                sku: product.sku,
                barcode_primary: product.barcode_primary,
                price_workshop: product.price_normal,
                on_hand: productQty,
                reserved: 0
            });
            
            // Add to parts table
            addProductToJobCard(product.id);
            
            // Clear search and hide results
            $('#productSearchResults').hide();
            $('#productSearch').val('').focus();
        }
    }).fail(function(xhr) {
        toastr.error(xhr.responseJSON?.message || 'Failed to create product');
        $('#quickAddJobCardProductBtn').prop('disabled', false).html(
            '<i class="ri-check-line me-1"></i>Create & Add to Job Card');
    });
});

// Debounce helper
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add product to job card table
function addProductToJobCard(productId) {
    const product = jobCardProducts.find(p => p.id == productId);
    if (!product) return;
    
    // Check if product already exists
    const existingRow = document.querySelector(`#partsTableBody tr[data-product-id="${productId}"]`);
    if (existingRow) {
        // Increase quantity
        const qtyInput = existingRow.querySelector('input[name*="quantity_used"]');
        qtyInput.value = parseFloat(qtyInput.value) + 1;
        updatePartTotal(existingRow);
        return;
    }
    
    // Calculate available stock (on_hand - reserved)
    const onHand = product.on_hand || 0;
    const reserved = product.reserved || 0;
    const available = onHand - reserved;
    
    // Determine badge color and text
    let badgeClass = 'bg-secondary';
    let badgeText = 'Unknown';
    let badgeTitle = 'Stock info unavailable';
    
    if (available > 0) {
        badgeClass = 'bg-success';
        badgeText = `Available: ${available}`;
        badgeTitle = `Available: ${available} | Reserved: ${reserved} | Total: ${onHand}`;
    } else if (onHand > 0 && available <= 0) {
        badgeClass = 'bg-warning';
        badgeText = 'Reserved';
        badgeTitle = `All stock reserved (${reserved} reserved out of ${onHand})`;
    } else if (onHand === 0) {
        badgeClass = 'bg-danger';
        badgeText = 'Out of Stock';
        badgeTitle = 'Out of stock';
    }
    
    const row = document.createElement('tr');
    row.setAttribute('data-product-id', productId);
    row.innerHTML = `
        <td>${partRowCount + 1}</td>
        <td>
            <strong>${product.name}</strong><br>
            <small class="text-muted">${product.sku}</small>
            <input type="hidden" name="items[${partRowCount}][product_id]" value="${product.id}">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="items[${partRowCount}][quantity_used]" 
                   min="0.001" step="0.001" value="1" 
                   onchange="updatePartTotal(this.closest('tr'))">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="items[${partRowCount}][unit_price]" 
                   min="0" step="0.01" value="${product.price_workshop || 0}" 
                   onchange="updatePartTotal(this.closest('tr'))">
        </td>
        <td class="part-total">R${product.price_workshop || 0}.00</td>
        <td class="stock-badge">
            <span class="badge ${badgeClass}" title="${badgeTitle}">${badgeText}</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="removePartRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    document.getElementById('partsTableBody').appendChild(row);
    partRowCount++;
    updatePartsCount();
    updatePartTotal(row);
    
    // Clear search
    document.getElementById('productSearch').value = '';
    document.getElementById('productSearchResults').style.display = 'none';
}

// Add part row manually
function addPartRow() {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${partRowCount + 1}</td>
        <td>
            <select class="form-select form-select-sm" name="items[${partRowCount}][product_id]" onchange="updateProductInfo(this)" required>
                <option value="">Select Product...</option>
                ${jobCardProducts.map(p => `<option value="${p.id}" data-price="${p.price_workshop || 0}">${p.name} - ${p.sku}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="items[${partRowCount}][quantity_used]" 
                   min="0.001" step="0.001" value="1" 
                   onchange="updatePartTotal(this.closest('tr'))">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="items[${partRowCount}][unit_price]" 
                   min="0" step="0.01" value="0" 
                   onchange="updatePartTotal(this.closest('tr'))">
        </td>
        <td class="part-total">R0.00</td>
        <td class="stock-badge">
            <span class="badge bg-secondary">Select Product</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="removePartRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    document.getElementById('partsTableBody').appendChild(row);
    partRowCount++;
    updatePartsCount();
}

// Add labour row (Simplified: Description + Price only, Zero Cost)
function addLabourRow() {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${labourRowCount + 1}</td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="labour[${labourRowCount}][labour_description]" 
                   placeholder="e.g., Oil change, Brake service, Engine diagnostic..." required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="labour[${labourRowCount}][total_amount]" 
                   min="0" step="0.01" value="0" 
                   onchange="updateLabourTotal(this.closest('tr'))" 
                   placeholder="Enter price">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="removeLabourRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    // Hidden fields for backend compatibility (zero cost, 1 hour @ 0 rate)
    row.innerHTML += `
        <input type="hidden" name="labour[${labourRowCount}][labour_type]" value="other">
        <input type="hidden" name="labour[${labourRowCount}][hours_worked]" value="1">
        <input type="hidden" name="labour[${labourRowCount}][hourly_rate]" value="0">
        <input type="hidden" name="labour[${labourRowCount}][detailed_description]" value="">
        <input type="hidden" name="labour[${labourRowCount}][notes]" value="">
        <input type="hidden" name="labour[${labourRowCount}][technician_id]" value="">
    `;
    
    document.getElementById('labourTableBody').appendChild(row);
    labourRowCount++;
    updateLabourCount();
    updateLabourTotal(row);
}

// Update product info when selected
function updateProductInfo(select) {
    const row = select.closest('tr');
    
    if (!select.value) {
        // No product selected - reset to unknown
        row.querySelector('.part-total').textContent = 'R0.00';
        row.querySelector('.stock-badge').innerHTML = '<span class="badge bg-secondary">Unknown</span>';
        row.querySelector('input[name*="unit_price"]').value = 0;
        return;
    }
    
    const option = select.options[select.selectedIndex];
    const productId = select.value;
    const price = option.dataset.price || 0;
    
    // Find product in array to get stock info
    const product = jobCardProducts.find(p => p.id == productId);
    
    // Update price
    const priceInput = row.querySelector('input[name*="unit_price"]');
    priceInput.value = price;
    
    // Calculate available stock (on_hand - reserved)
    const onHand = product?.on_hand || 0;
    const reserved = product?.reserved || 0;
    const available = onHand - reserved;
    
    // Update stock badge with detailed info
    const stockBadge = row.querySelector('.stock-badge');
    let badgeHTML = '';
    
    if (available > 0) {
        badgeHTML = `<span class="badge bg-success" title="Available: ${available} | Reserved: ${reserved} | Total: ${onHand}">Available: ${available}</span>`;
    } else if (onHand > 0 && available <= 0) {
        badgeHTML = `<span class="badge bg-warning" title="All stock reserved (${reserved} reserved out of ${onHand})">Reserved</span>`;
    } else if (onHand === 0) {
        badgeHTML = `<span class="badge bg-danger" title="Out of stock">Out of Stock</span>`;
    } else {
        badgeHTML = `<span class="badge bg-secondary" title="Stock info unavailable">Unknown</span>`;
    }
    
    stockBadge.innerHTML = badgeHTML;
    
    updatePartTotal(row);
}

// Update part total
function updatePartTotal(row) {
    const qty = parseFloat(row.querySelector('input[name*="quantity_used"]').value) || 0;
    const price = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
    const total = qty * price;
    row.querySelector('.part-total').textContent = 'R' + total.toFixed(2);
    updatePartsTotal();
}

// Update labour total (Simplified: Just read the price directly)
function updateLabourTotal(row) {
    const totalInput = row.querySelector('input[name*="total_amount"]');
    const total = parseFloat(totalInput ? totalInput.value : 0) || 0;
    
    // Update the hidden hourly_rate to match total_amount for backend
    const hourlyRateInput = row.querySelector('input[name*="hourly_rate"]');
    if (hourlyRateInput) {
        hourlyRateInput.value = total; // Store price as hourly_rate
    }
    
    updateLabourTotalSum();
}

// Update parts count
function updatePartsCount() {
    const count = document.getElementById('partsTableBody')?.getElementsByTagName('tr').length || 0;
    const countElem = document.getElementById('partsCount');
    if (countElem) countElem.textContent = count;
}

// Update labour count
function updateLabourCount() {
    const count = document.getElementById('labourTableBody')?.getElementsByTagName('tr').length || 0;
    const countElem = document.getElementById('labourCount');
    if (countElem) countElem.textContent = count;
}

// Renumber rows
function renumberPartRows() {
    const rows = document.getElementById('partsTableBody')?.getElementsByTagName('tr');
    if (rows) {
        Array.from(rows).forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }
}

function renumberLabourRows() {
    const rows = document.getElementById('labourTableBody')?.getElementsByTagName('tr');
    if (rows) {
        Array.from(rows).forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }
}

// Update totals
function updatePartsTotal() {
    let total = 0;
    document.querySelectorAll('.part-total').forEach(cell => {
        const value = parseFloat(cell.textContent.replace('R', '')) || 0;
        total += value;
    });
    const totalElem = document.getElementById('partsTotal');
    if (totalElem) totalElem.textContent = total.toFixed(2);
    calculateJobTotals(); // Recalculate grand total with VAT
}

function updateLabourTotalSum() {
    let total = 0;
    // Sum up all labour prices directly from inputs
    document.querySelectorAll('input[name*="[total_amount]"]').forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    const totalElem = document.getElementById('labourTotal');
    if (totalElem) totalElem.textContent = total.toFixed(2);
    calculateJobTotals(); // Recalculate grand total with VAT
}

// Calculate Job Totals with VAT
function calculateJobTotals() {
    const partsTotal = parseFloat(document.getElementById('partsTotal')?.textContent || 0);
    const labourTotal = parseFloat(document.getElementById('labourTotal')?.textContent || 0);
    const subtotal = partsTotal + labourTotal;
    
    // Update Subtotal
    const subtotalElem = document.getElementById('subtotal');
    if (subtotalElem) {
        subtotalElem.textContent = subtotal.toFixed(2);
    }
    
    // Check if VAT is enabled
    const vatEnabled = <?php echo e($vatEnabled ?? false ? 'true' : 'false'); ?>;
    const vatInclusive = <?php echo e($vatInclusive ?? false ? 'true' : 'false'); ?>;
    
    if (vatEnabled) {
        const vatRate = parseFloat(document.getElementById('vatRate')?.value || <?php echo e($vatRate ?? 15); ?>);
        let vatAmount, grandTotal;
        
        if (vatInclusive) {
            // VAT is included in the prices
            grandTotal = subtotal;
            vatAmount = subtotal - (subtotal / (1 + vatRate / 100));
        } else {
            // VAT is added on top
            vatAmount = (subtotal * vatRate) / 100;
            grandTotal = subtotal + vatAmount;
        }
        
        // Update VAT Amount (text content, not input value)
        const vatAmountElem = document.getElementById('vatAmount');
        if (vatAmountElem) {
            vatAmountElem.textContent = vatAmount.toFixed(2);
        }
        
        // Update Grand Total
        const grandTotalElem = document.getElementById('grandTotal');
        if (grandTotalElem) {
            grandTotalElem.textContent = grandTotal.toFixed(2);
        }
    } else {
        // No VAT
        const vatAmountElem = document.getElementById('vatAmount');
        if (vatAmountElem) {
            vatAmountElem.textContent = '0.00';
        }
        
        const grandTotalElem = document.getElementById('grandTotal');
        if (grandTotalElem) {
            grandTotalElem.textContent = subtotal.toFixed(2);
        }
    }
}

// Remove rows
function removePartRow(button) {
    button.closest('tr').remove();
    renumberPartRows();
    updatePartsCount();
    updatePartsTotal();
}

function removeLabourRow(button) {
    button.closest('tr').remove();
    renumberLabourRows();
    updateLabourCount();
    updateLabourTotalSum();
}

// Barcode scanning
function scanBarcode() {
    alert('Focus on product search and scan barcode');
    document.getElementById('productSearch')?.focus();
}

// Quick Add Product function removed - Only search and select existing products

// Form submission
function handleJobCardFormSubmit(e) {
    e.preventDefault();
    
    console.log('📝 Job card form submitted');
    
    // VALIDATION: For walk-in customers, email OR phone is required
    if (!jobCardCurrentCustomer) {
        const walkInEmail = $('#walkInEmail').val().trim();
        const walkInPhone = $('#walkInPhone').val().trim();
        
        if (!walkInEmail && !walkInPhone) {
            toastr.error('Please provide either email or phone number for walk-in customer.');
            return;
        }
    }
    
    const formData = new FormData(e.target);
    
    // Show loading state
    const submitBtn = $('#createJobCardForm button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Creating...');
    
    $.ajax({
        url: '<?php echo e(route("job-cards.store")); ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            console.log('✅ Job card created:', data);
        if (data.success) {
                toastr.success('Job card created successfully! Job Card #: ' + data.job_card_number);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createJobCardModal'));
                if (modal) modal.hide();
                
                // Reload page
                setTimeout(() => {
            location.reload();
                }, 1500);
        } else {
                toastr.error('Error creating job card: ' + data.message);
                submitBtn.prop('disabled', false).html(originalText);
            }
        },
        error: function(xhr) {
            console.error('❌ Error creating job card:', xhr);
            let errorMsg = 'Failed to create job card.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            toastr.error(errorMsg);
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

// View job card
function viewJobCard(id) {
    const url = '<?php echo e(route("job-cards.show", ":id")); ?>'.replace(':id', id);
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('viewJobCardModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading job card details');
        });
}

// Edit job card
function editJobCard(id) {
    const url = '<?php echo e(route("job-cards.edit", ":id")); ?>'.replace(':id', id);
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editJobCardModal'));
            modal.show();
            
            // Attach submit handler after modal loads
            setTimeout(() => {
                const editForm = document.getElementById('editJobCardForm');
                if (editForm) {
                    editForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitEditJobCard(id);
                    });
                }
            }, 100);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading edit form');
        });
}

// Submit edit job card
function submitEditJobCard(jobCardId) {
    const form = document.getElementById('editJobCardForm');
    const formData = new FormData(form);
    const url = '<?php echo e(route("job-cards.update", ":id")); ?>'.replace(':id', jobCardId);
    
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Job card updated successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editJobCardModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating job card');
    });
}

// Change status
function changeStatus(id, status) {
    const statusText = {
        'booked': 'Book In',
        'in_progress': 'Start Work',
        'completed': 'Complete',
        'cancelled': 'Cancel'
    };
    
    // Show themed confirmation modal
    showStatusChangeModal(id, status, statusText[status]);
}

function showStatusChangeModal(jobCardId, status, statusText) {
    $('#statusChangeJobCardId').val(jobCardId);
    $('#statusChangeStatus').val(status);
    $('#statusChangeText').text(statusText);
    $('#statusChangeModal').modal('show');
}

function confirmStatusChange() {
    const jobCardId = $('#statusChangeJobCardId').val();
    const status = $('#statusChangeStatus').val();
    
    const url = '<?php echo e(route("job-cards.change-status", ":id")); ?>'.replace(':id', jobCardId);
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            $('#statusChangeModal').modal('hide');
            toastr.success('Job card status updated successfully!');
            setTimeout(() => location.reload(), 1000);
            } else {
            toastr.error('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        toastr.error('Error changing status');
        });
}

// Convert to invoice
function convertToInvoice(id) {
    // Show themed confirmation modal
    showConvertToInvoiceModal(id);
}

function showConvertToInvoiceModal(jobCardId) {
    // First check if job card can be converted
    const url = '<?php echo e(route("job-cards.show", ":id")); ?>'.replace(':id', jobCardId);
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.job_card) {
            const jobCard = data.job_card;
            
            // Check if can convert
            if (jobCard.status !== 'completed') {
                toastr.error('Job card must be completed before converting to invoice');
                return;
            }
            
            if (jobCard.final_invoice_id) {
                toastr.error('Job card has already been converted to invoice');
                return;
            }
            
            // Show confirmation modal
            $('#convertJobCardId').val(jobCardId);
            $('#convertToInvoiceModal').modal('show');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error loading job card details');
    });
}

function confirmSimpleConvertToInvoice() {
    const jobCardId = $('#convertJobCardId').val();
    const url = '<?php echo e(route("job-cards.convert-to-invoice", ":id")); ?>'.replace(':id', jobCardId);
    
    // Show loading on button
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Converting...';
    btn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            payment_method: 'on_account',
            amount_paid: 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#convertToInvoiceModal').modal('hide');
            // Show post-conversion modal with share options
            showJobCardPostSaleModal(data.invoice_id, data.invoice_number, data.grand_total);
        } else {
            toastr.error(data.message || 'Error converting to invoice');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error converting to invoice');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Post-sale modal functions (after job card conversion)
let currentJobCardInvoiceId = null;

function showJobCardPostSaleModal(invoiceId, invoiceNumber, grandTotal) {
    currentJobCardInvoiceId = invoiceId;
    $('#jobCardPostSaleInvoiceNumber').text(invoiceNumber);
    $('#jobCardPostSaleTotal').text(parseFloat(grandTotal).toFixed(2));
    $('#jobCardPostSaleModal').modal('show');
    toastr.success('Invoice created successfully!');
}

function closeJobCardPostSaleModal() {
    $('#jobCardPostSaleModal').modal('hide');
    setTimeout(() => location.reload(), 300);
}

function viewJobCardInvoice() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    const invoiceNumber = $('#jobCardPostSaleInvoiceNumber').text();
    const url = '<?php echo e(route('invoices.index')); ?>?search=' + encodeURIComponent(invoiceNumber);
    window.location.href = url;
}

// Share functions for job card converted invoice
function downloadJobCardInvoicePDF() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    const url = '<?php echo e(route("invoices.pdf", ":id")); ?>'.replace(':id', currentJobCardInvoiceId);
    window.open(url, '_blank');
}

function printJobCardInvoiceInline() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    const url = '<?php echo e(route("invoices.print", ":id")); ?>'.replace(':id', currentJobCardInvoiceId);
    window.open(url, '_blank');
}

function sendJobCardWhatsApp() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    // Use POST request for WhatsApp
    $.post('<?php echo e(route("invoices.whatsapp", ":id")); ?>'.replace(':id', currentJobCardInvoiceId))
        .done(function(response) {
            if (response.success) {
                const whatsappType = '<?php echo e(\App\Models\Setting::where('key', 'whatsapp_share_type')->value('value') ?? 'web'); ?>';
                
                if (whatsappType === 'desktop') {
                    // Copy message to clipboard for desktop app
                    if (navigator.clipboard && response.message) {
                        navigator.clipboard.writeText(response.message).then(() => {
                            toastr.success('Message copied! Opening WhatsApp...');
                        });
                    }
                }
                
                // Open WhatsApp
                window.open(response.url, '_blank');
            } else {
                toastr.error(response.message || 'Failed to send WhatsApp');
            }
        })
        .fail(function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'Error sending WhatsApp';
            toastr.error(errorMsg);
        });
}

function sendJobCardEmail() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    
    // Use POST request for Email
    $.post('<?php echo e(route("invoices.email", ":id")); ?>'.replace(':id', currentJobCardInvoiceId))
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message || 'Email sent successfully!');
            } else {
                toastr.error(response.message || 'Failed to send email');
            }
        })
        .fail(function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'Error sending email';
            toastr.error(errorMsg);
        });
}

function downloadJobCardPickingList() {
    if (!currentJobCardInvoiceId) {
        toastr.error('No invoice found');
        return;
    }
    const url = '<?php echo e(route("invoices.picking-list", ":id")); ?>'.replace(':id', currentJobCardInvoiceId);
    window.open(url, '_blank');
}

// Download PDF
function downloadPDF(id) {
    const url = '<?php echo e(route("job-cards.pdf", ":id")); ?>'.replace(':id', id);
    window.open(url, '_blank');
}

// Delete job card
function deleteJobCard(id) {
    if (confirm('Are you sure you want to delete this job card?')) {
        const url = '<?php echo e(route("job-cards.destroy", ":id")); ?>'.replace(':id', id);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting job card');
        });
    }
}

// View invoice from job card
function viewJobInvoice(invoiceId) {
    // Create modal container if doesn't exist
    let invoiceModalContainer = document.getElementById('invoiceModalContainer');
    if (!invoiceModalContainer) {
        invoiceModalContainer = document.createElement('div');
        invoiceModalContainer.id = 'invoiceModalContainer';
        document.body.appendChild(invoiceModalContainer);
    }
    
    // Create modal structure if doesn't exist
    if (!document.getElementById('viewInvoiceModal')) {
        invoiceModalContainer.innerHTML = `
            <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content" id="viewInvoiceModalContent">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    const url = '<?php echo e(route("invoices.view-modal", ":id")); ?>'.replace(':id', invoiceId);
    const modalContent = document.getElementById('viewInvoiceModalContent');
    
    modalContent.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading invoice...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    modal.show();
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            modalContent.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">Error loading invoice details</div>
                </div>
            `;
        });
}

// Print Job Cards Function
function printJobCards() {
    window.print();
}

// AJAX Filter Functionality
$(document).ready(function() {
    let searchTimeout;

    // Search input with debounce (only for main filter, not modals)
    $('#filterForm #searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            console.log('Search triggered');
            filterJobCards();
        }, 500);
    });

    // Select filters with immediate response
    $('#statusFilter, #vehicleMakeFilter').on('change', function() {
        console.log('Filter changed');
        filterJobCards();
    });
    
    // Other filter inputs (scoped to filterForm only)
    $('#filterForm input[name="customer_name"], #filterForm input[name="date_from"]').on('change', function() {
        console.log('Filter changed');
        filterJobCards();
    });

    // Clear filters button
    $('#clearFilters').on('click', function() {
        $('#filterForm')[0].reset();
        window.location.href = '<?php echo e(route('job-cards.index')); ?>';
    });

    // Filter function
function filterJobCards() {
        const formData = $('#filterForm').serialize();
        console.log('Filtering with data:', formData);

        $.ajax({
            url: '<?php echo e(route('job-cards.index')); ?>',
            type: 'GET',
            data: formData,
            beforeSend: function() {
                // Show loading overlay
                $('#jobCardsTable').append(
                    '<div class="position-absolute top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );
            },
            success: function(response) {
                console.log('Filter response received');
                // Parse the response and update the table
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                const newTable = doc.querySelector('.table');
                const newPagination = doc.querySelector('.pagination');

                if (newTable) {
                    $('.table').replaceWith(newTable);
                    console.log('Table updated');
                }

                if (newPagination) {
                    $('.pagination').replaceWith(newPagination);
                    console.log('Pagination updated');
                }

                // Update URL without page reload
                const url = new URL(window.location);
                url.search = new URLSearchParams(formData).toString();
                window.history.pushState({}, '', url);

                // Re-initialize click handlers for new content
                initializeRowClickHandlers();
            },
            error: function(xhr, status, error) {
                console.error('Filter error:', xhr, status, error);
                toastr.error('Failed to filter job cards. Please try again.');
            },
            complete: function() {
                $('.spinner-border').remove();
            }
        });
    }

    // Initialize row click handlers
    function initializeRowClickHandlers() {
        $('.clickable-row').off('click').on('click', function(e) {
            if (!$(e.target).closest('button, .btn-list').length) {
                const jobCardId = $(this).data('job-card-id') || $(this).attr('onclick')?.match(/\d+/)?.[0];
                if (jobCardId) {
                    viewJobCard(jobCardId);
                }
            }
        });
    }

    // Initialize on page load
    initializeRowClickHandlers();
    
    // Store current job card ID for conversion
    let currentJobCardId = null;
    let currentJobCardTotal = 0;
    let currentCustomerType = null;
});

// Show convert to invoice modal
function showConvertToInvoiceModal(jobCardId) {
    currentJobCardId = jobCardId;
    
    // Fetch job card details
    const url = '<?php echo e(route("job-cards.show", ":id")); ?>'.replace(':id', jobCardId) + '?format=json';
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const jobCard = data.job_card;
                currentJobCardTotal = parseFloat(jobCard.grand_total);
                currentCustomerType = jobCard.customer_type;
                
                // Reset form
                $('#invoicePaymentMethod').val('cash');
                $('#invoiceAmountPaid').val(currentJobCardTotal.toFixed(2));
                $('#invoicePaymentReference').val('');
                
                // Update display totals
                $('#invoiceTotalDisplay').text('R ' + currentJobCardTotal.toFixed(2));
                $('#balanceDueDisplay').text('R ' + currentJobCardTotal.toFixed(2));
                
                // Show/hide payment options based on customer type
                if (currentCustomerType === 'credit') {
                    $('#invoiceOnAccountOption').show();
                    $('#invoicePaymentMethod').val('credit');
                    $('#invoiceAmountPaid').val('0.00');
                    $('#cashCustomerWarning').hide();
                    
                    // Show customer type alert
                    $('#customerTypeAlert').html(`
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i><strong>Credit Customer</strong><br>
                            <small>Customer can pay on account or make partial/full payment now.</small>
                        </div>
                    `);
                } else {
                    $('#invoiceOnAccountOption').hide();
                    $('#cashCustomerWarning').show();
                    
                    // Show customer type alert
                    $('#customerTypeAlert').html(`
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i><strong>Cash Customer</strong><br>
                            <small>Invoice Total: R ${currentJobCardTotal.toFixed(2)}</small><br>
                            <small>Cash customers must pay immediately. Credit sales not allowed.</small>
                        </div>
                    `);
                }
                
                // Update payment fields
                updateInvoicePaymentFields();
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('convertToInvoiceModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error loading job card details');
        });
}

// Update invoice payment fields based on payment method
function updateInvoicePaymentFields() {
    const paymentMethod = $('#invoicePaymentMethod').val();
    
    if (paymentMethod === 'credit') {
        $('#invoiceAmountPaidRow').hide();
        $('#invoiceChangeRow').hide();
        $('#invoiceAmountPaid').val('0.00');
        $('#balanceDueDisplay').text('R ' + currentJobCardTotal.toFixed(2));
    } else {
        $('#invoiceAmountPaidRow').show();
        $('#invoiceChangeRow').show();
        $('#invoiceAmountPaid').val(currentJobCardTotal.toFixed(2));
        
        // Show/hide cash customer warning
        if (currentCustomerType === 'cash') {
            $('#cashCustomerWarning').show();
        } else {
            $('#cashCustomerWarning').hide();
        }
        
        calculateInvoiceChange();
    }
}

// Calculate change for invoice
function calculateInvoiceChange() {
    const amountPaid = parseFloat($('#invoiceAmountPaid').val()) || 0;
    const change = amountPaid - currentJobCardTotal;
    const balanceDue = currentJobCardTotal - amountPaid;
    
    $('#invoiceChangeAmount').text('R ' + change.toFixed(2));
    $('#balanceDueDisplay').text('R ' + Math.max(0, balanceDue).toFixed(2));
}

// Amount paid input handler
$(document).on('input', '#invoiceAmountPaid', function() {
    calculateInvoiceChange();
});

// Confirm convert to invoice
function confirmConvertToInvoice() {
    const paymentMethod = $('#invoicePaymentMethod').val();
    const amountPaid = parseFloat($('#invoiceAmountPaid').val()) || 0;
    const paymentReference = $('#invoicePaymentReference').val();
    
    // Validation
    if (paymentMethod === 'credit') {
        if (currentCustomerType !== 'credit') {
            toastr.error('Only credit customers can use credit payment');
            return;
        }
    } else {
        // Cash customers must pay in full
        if (currentCustomerType === 'cash' && amountPaid < currentJobCardTotal) {
            toastr.error('Cash customers must pay in full. Amount paid must be equal to invoice total.');
            return;
        }
        // Credit customers can pay partial or full
        if (amountPaid < 0) {
            toastr.error('Amount paid cannot be negative');
            return;
        }
    }
    
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Converting...';
    btn.disabled = true;
    
    // Convert to invoice
    const url = '<?php echo e(route("job-cards.convert-to-invoice", ":id")); ?>'.replace(':id', currentJobCardId);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            payment_method: paymentMethod,
            amount_paid: amountPaid,
            payment_reference: paymentReference
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Job card converted to invoice successfully!');
            
            // Close modals
            bootstrap.Modal.getInstance(document.getElementById('convertToInvoiceModal')).hide();
            bootstrap.Modal.getInstance(document.getElementById('viewJobCardModal')).hide();
            
            // Redirect to invoices or reload page
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                location.reload();
            }
        } else {
            toastr.error(data.message || 'Error converting to invoice');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error converting to invoice');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>

<!-- Status Change Confirmation Modal -->
<div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-warning text-white">
                <div class="w-100 text-center">
                    <h5 class="modal-title mb-0">
                        <i class="ri-question-line me-2"></i>Confirm Status Change
                    </h5>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ri-alert-line text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Are you sure you want to <span id="statusChangeText" class="fw-bold text-primary"></span> this job card?</h6>
                <p class="text-muted small mb-0">This action will update the job card status and may affect stock reservations.</p>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center border-top">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning px-4" onclick="confirmStatusChange()">
                    <i class="ri-check-line me-1"></i>Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Convert to Invoice Confirmation Modal -->
<div class="modal fade" id="convertToInvoiceModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-info text-white">
                <div class="w-100 text-center">
                    <h5 class="modal-title mb-0">
                        <i class="ri-file-list-3-line me-2"></i>Convert to Invoice
                    </h5>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="ri-file-text-line text-info" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Are you sure you want to convert this job card to an invoice?</h6>
                <p class="text-muted small mb-0">This will create a new invoice and consume reserved stock.</p>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center border-top">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-info px-4" onclick="confirmSimpleConvertToInvoice()">
                    <i class="ri-check-line me-1"></i>Convert
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Job Card Post-Sale Modal (After Conversion) -->
<div class="modal fade" id="jobCardPostSaleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <div class="w-100 text-center">
                    <h5 class="modal-title mb-0">
                        <i class="ri-checkbox-circle-line me-2"></i>Invoice Created Successfully
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
                                <h5 id="jobCardPostSaleInvoiceNumber" class="text-primary mb-0 fw-bold"></h5>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">Total Amount</small>
                                <h5 class="text-success mb-0 fw-bold">R <span id="jobCardPostSaleTotal">0.00</span></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" onclick="downloadJobCardInvoicePDF()">
                        <i class="ri-file-pdf-line me-2"></i>Download PDF
                    </button>
                    <button type="button" class="btn btn-danger" onclick="printJobCardInvoiceInline()">
                        <i class="ri-printer-line me-2"></i>Print Invoice
                    </button>
                    <button type="button" class="btn btn-success" onclick="sendJobCardWhatsApp()">
                        <i class="ri-whatsapp-line me-2"></i>Send via WhatsApp
                    </button>
                    <button type="button" class="btn btn-info" onclick="sendJobCardEmail()">
                        <i class="ri-mail-line me-2"></i>Send via Email
                    </button>
                    <button type="button" class="btn btn-warning" onclick="downloadJobCardPickingList()">
                        <i class="ri-file-list-3-line me-2"></i>Download Picking List
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center border-top">
                <button type="button" class="btn btn-secondary px-4" onclick="closeJobCardPostSaleModal()">
                    <i class="ri-close-line me-1"></i>Close & Continue
                </button>
                <button type="button" class="btn btn-primary px-4" onclick="viewJobCardInvoice()">
                    <i class="ri-eye-line me-1"></i>View Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden inputs for modals -->
<input type="hidden" id="statusChangeJobCardId" value="">
<input type="hidden" id="statusChangeStatus" value="">
<input type="hidden" id="convertJobCardId" value="">

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/job-cards/index.blade.php ENDPATH**/ ?>