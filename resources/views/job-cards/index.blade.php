@extends('layouts.app')

@section('title', 'Workshop Job Cards')

@section('content')
<style>
    .modal-dialog-scrollable .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .table-responsive {
        border: none;
    }
    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Prevent unnecessary page scrolling */
    .main-content {
        max-height: calc(100vh - 80px);
        overflow-y: auto;
    }
    
    /* Ensure content fits properly */
    .container-fluid {
        padding: 1rem;
    }
</style>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-wrench me-2"></i>Workshop Job Cards
            </h2>
            <p class="text-muted mb-0">Manage workshop jobs and track progress</p>
        </div>
        <div class="mt-3 mt-md-0">
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-circle me-1"></i>New Job Card
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="booked">Booked In</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Vehicle Make</label>
                            <select class="form-select" id="makeFilter">
                                <option value="">All Makes</option>
                                <option value="Toyota">Toyota</option>
                                <option value="Honda">Honda</option>
                                <option value="Ford">Ford</option>
                                <option value="BMW">BMW</option>
                                <option value="Mercedes">Mercedes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Customer</label>
                            <input type="text" class="form-control" id="customerFilter" placeholder="Search customer...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search job cards...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Cards Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Job Card #</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0 d-none d-md-table-cell">Vehicle</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 d-none d-lg-table-cell">Job Description</th>
                            <th class="border-0 d-none d-xl-table-cell">Total</th>
                            <th class="border-0 d-none d-md-table-cell">Date</th>
                            <th class="border-0 text-end" width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jobCardsTableBody">
                        @forelse($jobCards as $jobCard)
                        <tr>
                            <td>
                                <strong>{{ $jobCard->job_card_number }}</strong>
                                <br>
                                <small class="text-muted d-md-none">{{ $jobCard->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $jobCard->customer_name }}</strong>
                                    @if($jobCard->customer_phone)
                                        <br><small class="text-muted">{{ $jobCard->customer_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div>
                                    @if($jobCard->vehicle_make || $jobCard->vehicle_model)
                                        <strong>{{ $jobCard->vehicle_make }} {{ $jobCard->vehicle_model }}</strong>
                                    @endif
                                    @if($jobCard->vehicle_registration)
                                        <br><small class="text-muted">{{ $jobCard->vehicle_registration }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $jobCard->status_badge }}">
                                    {{ $jobCard->status_text }}
                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $jobCard->job_description }}">
                                    {{ $jobCard->job_description }}
                                </div>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <strong>${{ number_format($jobCard->grand_total, 2) }}</strong>
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $jobCard->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $jobCard->created_at->format('H:i A') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-sm btn-info-light btn-icon" onclick="viewJobCard({{ $jobCard->id }})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    @if($jobCard->status !== 'completed' && $jobCard->status !== 'cancelled')
                                    <button type="button" class="btn btn-sm btn-warning-light btn-icon" onclick="editJobCard({{ $jobCard->id }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @endif
                                    
                                    <!-- Status Action Buttons -->
                                    @if($jobCard->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-primary" onclick="changeStatus({{ $jobCard->id }}, 'booked')" title="Book In">
                                        <i class="bi bi-calendar-check me-1"></i>Book In
                                    </button>
                                    @endif
                                    
                                    @if($jobCard->status === 'booked')
                                    <button type="button" class="btn btn-sm btn-primary" onclick="changeStatus({{ $jobCard->id }}, 'in_progress')" title="Start Work">
                                        <i class="bi bi-play-circle me-1"></i>Start Work
                                    </button>
                                    @endif
                                    
                                    @if($jobCard->status === 'in_progress')
                                    <button type="button" class="btn btn-sm btn-success" onclick="changeStatus({{ $jobCard->id }}, 'completed')" title="Mark Complete">
                                        <i class="bi bi-check-circle me-1"></i>Complete
                                    </button>
                                    @endif
                                    
                                    @if($jobCard->status === 'completed' && !$jobCard->final_invoice_id)
                                    <button type="button" class="btn btn-sm btn-warning" onclick="convertToInvoice({{ $jobCard->id }})" title="Convert to Invoice">
                                        <i class="bi bi-receipt me-1"></i>Invoice
                                    </button>
                                    @endif
                                    
                                    @if($jobCard->final_invoice_id)
                                    <button type="button" class="btn btn-sm btn-success-light btn-icon" onclick="viewJobInvoice({{ $jobCard->final_invoice_id }})" title="View Invoice">
                                        <i class="bi bi-receipt"></i>
                                    </button>
                                    @endif
                                    
                                    <!-- PDF Download -->
                                    <button type="button" class="btn btn-sm btn-secondary-light btn-icon" onclick="downloadPDF({{ $jobCard->id }})" title="Download PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    @if($jobCard->status !== 'completed' && $jobCard->status !== 'cancelled')
                                    <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="deleteJobCard({{ $jobCard->id }})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line fs-1 mb-3"></i>
                                    <p>No job cards found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $jobCards->firstItem() ?? 0 }} to {{ $jobCards->lastItem() ?? 0 }} of {{ $jobCards->total() }} results
                </div>
                <div>
                    {{ $jobCards->links() }}
                </div>
            </div>
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
    fetch('{{ route("job-cards.create") }}')
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
    
    // Customer selection
    const customerSelect = document.getElementById('customerSelect');
    if (customerSelect) {
        customerSelect.addEventListener('change', handleCustomerChange);
    }

    // Product search
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', function(e) {
            handleProductSearch(e);
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

// Customer selection handler
function handleCustomerChange() {
    const newCustomerFields = document.getElementById('newCustomerFields');
    
    if (this.value === 'new') {
        newCustomerFields.style.display = 'block';
        document.getElementById('customerName').required = true;
    } else if (this.value) {
        newCustomerFields.style.display = 'none';
        document.getElementById('customerName').required = false;
        
        // Auto-fill customer data
        const option = this.options[this.selectedIndex];
        document.getElementById('customerName').value = option.dataset.name || '';
        document.getElementById('customerPhone').value = option.dataset.phone || '';
        document.getElementById('customerEmail').value = option.dataset.email || '';
        document.getElementById('vehicleMake').value = option.dataset.vehicleMake || '';
        document.getElementById('vehicleModel').value = option.dataset.vehicleModel || '';
        document.getElementById('vehicleVIN').value = option.dataset.vehicleVin || '';
        document.getElementById('vehicleRegistration').value = option.dataset.vehicleReg || '';
        document.getElementById('vehicleMileage').value = option.dataset.vehicleMileage || '';
    } else {
        newCustomerFields.style.display = 'none';
        document.getElementById('customerName').required = false;
    }
}

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
        (product.barcode_primary && product.barcode_primary.toLowerCase().includes(query))
    );
    
    console.log('Filtered products:', filteredProducts.length);
    
    if (filteredProducts.length > 0) {
        resultsDiv.innerHTML = filteredProducts.map(product => `
            <a href="javascript:void(0)" class="list-group-item list-group-item-action" onclick="addProductToJobCard(${product.id})">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${product.name}</h6>
                    <small class="text-primary">${product.sku}</small>
                </div>
                <p class="mb-1 small text-muted">${product.barcode_primary || 'No barcode'}</p>
                <small class="text-success"><strong>R${product.price_workshop || product.price_normal || 0}</strong></small>
            </a>
        `).join('');
        resultsDiv.style.display = 'block';
    } else {
        resultsDiv.innerHTML = '<div class="list-group-item text-muted"><i class="bi bi-search me-2"></i>No products found matching "' + query + '"</div>';
        resultsDiv.style.display = 'block';
    }
}

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
        <td>
            <span class="badge bg-${(product.on_hand && product.on_hand > 0) ? 'success' : 'warning'}">${(product.on_hand && product.on_hand > 0) ? 'In Stock' : 'Will Order'}</span>
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

// Add labour row (Technician field removed as per user request - not in requirements)
function addLabourRow() {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${labourRowCount + 1}</td>
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="labour[${labourRowCount}][labour_description]" 
                   placeholder="e.g., Oil change, Brake service" required>
        </td>
        <td>
            <select class="form-select form-select-sm" name="labour[${labourRowCount}][labour_type]">
                <option value="diagnostic">Diagnostic</option>
                <option value="repair">Repair</option>
                <option value="maintenance">Maintenance</option>
                <option value="installation">Installation</option>
                <option value="other">Other</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="labour[${labourRowCount}][hours_worked]" 
                   min="0" step="0.25" value="1" 
                   onchange="updateLabourTotal(this.closest('tr'))">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" 
                   name="labour[${labourRowCount}][hourly_rate]" 
                   min="0" step="0.01" value="300" 
                   onchange="updateLabourTotal(this.closest('tr'))">
        </td>
        <td class="labour-total">R300.00</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="removeLabourRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    row.innerHTML += `
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
    
    // Update stock badge
    const stockBadge = row.querySelector('.stock-badge');
    if (product && product.on_hand > 0) {
        stockBadge.innerHTML = '<span class="badge bg-success">In Stock</span>';
    } else {
        stockBadge.innerHTML = '<span class="badge bg-warning">Will Order</span>';
    }
    
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

// Update labour total
function updateLabourTotal(row) {
    const hours = parseFloat(row.querySelector('input[name*="hours_worked"]').value) || 0;
    const rate = parseFloat(row.querySelector('input[name*="hourly_rate"]').value) || 0;
    const total = hours * rate;
    row.querySelector('.labour-total').textContent = 'R' + total.toFixed(2);
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
}

function updateLabourTotalSum() {
    let total = 0;
    document.querySelectorAll('.labour-total').forEach(cell => {
        const value = parseFloat(cell.textContent.replace('R', '')) || 0;
        total += value;
    });
    const totalElem = document.getElementById('labourTotal');
    if (totalElem) totalElem.textContent = total.toFixed(2);
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

// Submit Quick Add Product (via modal)
function submitQuickAddProduct() {
    const name = document.getElementById('quickProductName').value;
    const price = parseFloat(document.getElementById('quickProductPrice').value) || 0;
    const qty = parseFloat(document.getElementById('quickProductQty').value) || 1;
    
    if (!name || price <= 0) {
        alert('Please enter product name and price');
        return;
    }
    
    // Create product via API first
    fetch('{{ route("products.quickAdd") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: name,
            price_normal: price,
            qty: 0 // No initial stock for quick add from job card
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to products array
            const newProduct = {
                id: data.product.id,
                name: data.product.name,
                sku: data.product.sku,
                barcode_primary: data.product.barcode_primary,
                price_workshop: price,
                on_hand: 0
            };
            jobCardProducts.push(newProduct);
            
            // Add to job card parts table
            const row = document.createElement('tr');
            row.setAttribute('data-product-id', newProduct.id);
            row.innerHTML = `
                <td>${partRowCount + 1}</td>
                <td>
                    <strong>${newProduct.name}</strong><br>
                    <small class="text-muted">${newProduct.sku} - Just Created</small>
                    <input type="hidden" name="items[${partRowCount}][product_id]" value="${newProduct.id}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="items[${partRowCount}][quantity_used]" 
                           min="0.001" step="0.001" value="${qty}" 
                           onchange="updatePartTotal(this.closest('tr'))">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="items[${partRowCount}][unit_price]" 
                           min="0" step="0.01" value="${price}" 
                           onchange="updatePartTotal(this.closest('tr'))">
                </td>
                <td class="part-total">R${(qty * price).toFixed(2)}</td>
                <td>
                    <span class="badge bg-success">Added</span>
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
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('quickAddProductJobCardModal'));
            modal.hide();
            
            // Clear form
            document.getElementById('quickProductName').value = '';
            document.getElementById('quickProductPrice').value = '';
            document.getElementById('quickProductQty').value = '1';
            
            alert('Product created successfully! SKU: ' + newProduct.sku);
        } else {
            alert('Error creating product: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating product');
    });
}

// Form submission
function handleJobCardFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const url = '{{ route("job-cards.store") }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Job card created successfully! Job Card #: ' + data.job_card_number);
            location.reload();
        } else {
            alert('Error creating job card: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating job card');
    });
}

// View job card
function viewJobCard(id) {
    const url = '{{ route("job-cards.show", ":id") }}'.replace(':id', id);
    
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
    const url = '{{ route("job-cards.edit", ":id") }}'.replace(':id', id);
    
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
    const url = '{{ route("job-cards.update", ":id") }}'.replace(':id', jobCardId);
    
    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    
    if (confirm(`Are you sure you want to ${statusText[status]} this job card?`)) {
        const url = '{{ route("job-cards.change-status", ":id") }}'.replace(':id', id);
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
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
            alert('Error changing status');
        });
    }
}

// Convert to invoice
function convertToInvoice(id) {
    if (confirm('Are you sure you want to convert this job card to an invoice?')) {
        const url = '{{ route("job-cards.convert-to-invoice", ":id") }}'.replace(':id', id);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Job card converted to invoice successfully! Invoice #: ' + data.invoice_number);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error converting to invoice');
        });
    }
}

// Download PDF
function downloadPDF(id) {
    const url = '{{ route("job-cards.pdf", ":id") }}'.replace(':id', id);
    window.open(url, '_blank');
}

// Delete job card
function deleteJobCard(id) {
    if (confirm('Are you sure you want to delete this job card?')) {
        const url = '{{ route("job-cards.destroy", ":id") }}'.replace(':id', id);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
    
    const url = '{{ route("invoices.view-modal", ":id") }}'.replace(':id', invoiceId);
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

// Filter functions
document.getElementById('statusFilter').addEventListener('change', filterJobCards);
document.getElementById('makeFilter').addEventListener('change', filterJobCards);
document.getElementById('customerFilter').addEventListener('input', debounce(filterJobCards, 500));
document.getElementById('searchInput').addEventListener('input', debounce(filterJobCards, 500));

// Debounce function
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

function filterJobCards() {
    const status = document.getElementById('statusFilter').value;
    const make = document.getElementById('makeFilter').value;
    const customer = document.getElementById('customerFilter').value;
    const search = document.getElementById('searchInput').value;
    
    // Build query string
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (make) params.append('vehicle_make', make);
    if (customer) params.append('customer', customer);
    if (search) params.append('search', search);
    
    // Reload with filters
    const url = '{{ route("job-cards.index") }}' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}
</script>
@endsection
