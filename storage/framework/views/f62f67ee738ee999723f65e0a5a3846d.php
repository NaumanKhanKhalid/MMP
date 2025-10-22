<?php $__env->startSection('title', 'Returns & Credit Notes'); ?>

<?php $__env->startSection('content'); ?>
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
                <i class="bi bi-arrow-return-left me-2"></i>Returns & Credit Notes
            </h2>
            <p class="text-muted mb-0">Manage product returns and credit notes</p>
        </div>
        <div class="mt-3 mt-md-0">
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-circle me-1"></i>New Return
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card <?php echo e(request()->hasAny(['status', 'type', 'refund_method', 'search']) ? 'border-primary' : ''); ?>">
                <div class="card-header <?php echo e(request()->hasAny(['status', 'type', 'refund_method', 'search']) ? 'bg-primary text-white' : 'bg-light'); ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-funnel me-2"></i>Filters
                            <?php if(request()->hasAny(['status', 'type', 'refund_method', 'search'])): ?>
                                <span class="badge bg-light text-primary ms-2">Active</span>
                            <?php endif; ?>
                        </h6>
                        <?php if(request()->hasAny(['status', 'type', 'refund_method', 'search'])): ?>
                            <button type="button" class="btn btn-sm btn-light" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i>Clear All
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Return Type</label>
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="full" <?php echo e(request('type') == 'full' ? 'selected' : ''); ?>>Full Return</option>
                                <option value="partial" <?php echo e(request('type') == 'partial' ? 'selected' : ''); ?>>Partial Return</option>
                                <option value="exchange" <?php echo e(request('type') == 'exchange' ? 'selected' : ''); ?>>Exchange</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Refund Method</label>
                            <select class="form-select" id="refundFilter">
                                <option value="">All Methods</option>
                                <option value="cash" <?php echo e(request('refund_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                                <option value="store_credit" <?php echo e(request('refund_method') == 'store_credit' ? 'selected' : ''); ?>>Store Credit</option>
                                <option value="exchange" <?php echo e(request('refund_method') == 'exchange' ? 'selected' : ''); ?>>Exchange</option>
                                <option value="bank_transfer" <?php echo e(request('refund_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search returns..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <?php if(!request()->hasAny(['status', 'type', 'refund_method', 'search'])): ?>
                        <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-outline-secondary w-100" disabled title="No active filters">
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Return #</th>
                            <th class="border-0">Invoice #</th>
                            <th class="border-0 d-none d-md-table-cell">Customer</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 d-none d-lg-table-cell">Amount</th>
                            <th class="border-0 d-none d-xl-table-cell">Refund Method</th>
                            <th class="border-0 d-none d-md-table-cell">Date</th>
                            <th class="border-0 text-end" width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="returnsTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($return->return_number); ?></strong>
                                <br>
                                <small class="text-muted d-md-none"><?php echo e($return->created_at->format('M d, Y')); ?></small>
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="viewInvoice(<?php echo e($return->invoice_id); ?>)" class="text-primary">
                                    <?php echo e($return->invoice->invoice_number); ?>

                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php if($return->customer): ?>
                                    <?php echo e($return->customer->name); ?>

                                <?php else: ?>
                                    <span class="text-muted">Walk-in Customer</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($return->return_type_badge); ?>">
                                    <?php echo e(ucfirst($return->return_type)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($return->status_badge); ?>">
                                    <?php echo e(ucfirst($return->status)); ?>

                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <strong>R <?php echo e(number_format($return->total_amount, 2)); ?></strong>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <span class="badge bg-<?php echo e($return->refund_method_badge); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $return->refund_method))); ?>

                                </span>
                                <br>
                                <small class="text-muted">
                                    <?php if($return->stock_handling_type === 'restock'): ?>
                                        <i class="ri-inbox-line"></i> Restock
                                    <?php elseif($return->stock_handling_type === 'writeoff'): ?>
                                        <i class="ri-delete-bin-line"></i> Write-off
                                    <?php else: ?>
                                        <i class="ri-file-text-line"></i> Credit Only
                                    <?php endif; ?>
                                </small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php echo e($return->created_at->format('M d, Y')); ?>

                                <br>
                                <small class="text-muted"><?php echo e($return->created_at->format('H:i A')); ?></small>
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- View Button -->
                                    <button type="button" class="btn btn-sm btn-info-light btn-icon" 
                                            onclick="viewReturn(<?php echo e($return->id); ?>)" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <?php if($return->status === 'pending'): ?>
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-warning-light btn-icon" 
                                                onclick="editReturn(<?php echo e($return->id); ?>)" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <!-- Approve Button -->
                                        <button type="button" class="btn btn-sm btn-success-light btn-icon" 
                                                onclick="approveReturn(<?php echo e($return->id); ?>)" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        
                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-sm btn-danger-light btn-icon" 
                                                onclick="rejectReturn(<?php echo e($return->id); ?>)" title="Reject">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if($return->status === 'approved'): ?>
                                        <!-- Complete Button -->
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                onclick="completeReturn(<?php echo e($return->id); ?>)" title="Complete Return">
                                            <i class="bi bi-check-double me-1"></i>Complete
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if($return->status === 'completed' && $return->creditNote->count() > 0): ?>
                                        <!-- Download Credit Note -->
                                        <button type="button" class="btn btn-sm btn-secondary-light btn-icon" 
                                                onclick="downloadCreditNote(<?php echo e($return->creditNote->first()->id); ?>)" 
                                                title="Download Credit Note">
                                            <i class="bi bi-file-earmark-pdf"></i>
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
                                    <p>No returns found</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing <?php echo e($returns->firstItem() ?? 0); ?> to <?php echo e($returns->lastItem() ?? 0); ?> of <?php echo e($returns->total()); ?> results
                </div>
                <div>
                    <?php echo e($returns->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be loaded here -->
<div id="modalContainer"></div>

<!-- View Return Modal -->
<div class="modal fade" id="viewReturnModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" id="viewReturnModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Invoice Modal Container -->
<div id="invoiceModalContainer"></div>

<!-- Reject Return Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="mb-3">
                        <label for="rejectNotes" class="form-label">Reason for Rejection *</label>
                        <textarea class="form-control" id="rejectNotes" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmReject()">
                    <i class="bi bi-x-circle me-1"></i>Reject Return
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentReturnId = null;
let selectedInvoice = null;
let invoiceItems = [];

// Open create modal
function openCreateModal() {
    fetch('<?php echo e(route("returns.create")); ?>')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('createReturnModal'));
            modal.show();
            
            // Attach event listeners after modal loads
            setTimeout(() => {
                attachReturnModalListeners();
            }, 100);
        });
}

// Attach event listeners for return modal
function attachReturnModalListeners() {
    const invoiceSelect = document.getElementById('invoiceSelect');
    const returnType = document.getElementById('returnType');
    const refundMethod = document.getElementById('refundMethod');
    const returnReason = document.getElementById('returnReason');
    const submitBtn = document.getElementById('submitReturnBtn');
    
    if (invoiceSelect) {
        invoiceSelect.addEventListener('change', function() {
            handleInvoiceChange(this.value);
        });
    }
    
    if (returnType) {
        returnType.addEventListener('change', updateReturnAmount);
    }
    
    if (refundMethod) {
        refundMethod.addEventListener('change', updateReturnAmount);
    }
    
    if (returnReason) {
        returnReason.addEventListener('input', updateReturnAmount);
    }
    
    if (submitBtn) {
        submitBtn.addEventListener('click', submitReturn);
    }
    
    // Event delegation for dynamically created elements
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('return-qty')) {
            updateReturnAmount();
        }
    });
}

// Handle invoice change
function handleInvoiceChange(invoiceId) {
    console.log('Invoice changed to:', invoiceId);
    
    if (invoiceId) {
        loadInvoiceItems(invoiceId);
    } else {
        hideInvoiceDetails();
    }
}

// Load invoice items
function loadInvoiceItems(invoiceId) {
    const url = '<?php echo e(route("returns.invoice-items", ":id")); ?>'.replace(':id', invoiceId);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedInvoice = data.invoice;
                invoiceItems = data.items;
                showInvoiceDetails(data.invoice);
                populateItemsTable(data.items);
                updateReturnAmount();
            } else {
                alert('Error loading invoice items: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading invoice items');
        });
}

// Show invoice details
function showInvoiceDetails(invoice) {
    const invoiceDetails = document.getElementById('invoiceDetails');
    const invoiceInfo = document.getElementById('invoiceInfo');
    
    if (!invoiceInfo) return;
    
    invoiceInfo.innerHTML = `
        <div class="row">
            <div class="col-6">
                <strong>Invoice #:</strong> ${invoice.invoice_number}<br>
                <strong>Date:</strong> ${new Date(invoice.created_at).toLocaleDateString()}<br>
                <strong>Customer:</strong> ${invoice.customer ? invoice.customer.name : 'Walk-in Customer'}
            </div>
            <div class="col-6">
                <strong>Total:</strong> R ${parseFloat(invoice.grand_total).toFixed(2)}<br>
                <strong>Items:</strong> ${invoice.items.length} items<br>
                <strong>Status:</strong> ${invoice.payment_status}
            </div>
        </div>
    `;
    
    invoiceDetails.style.display = 'block';
    document.getElementById('itemsCard').style.display = 'block';
}

// Hide invoice details
function hideInvoiceDetails() {
    const invoiceDetails = document.getElementById('invoiceDetails');
    const itemsCard = document.getElementById('itemsCard');
    const itemsTableBody = document.getElementById('itemsTableBody');
    const submitBtn = document.getElementById('submitReturnBtn');
    
    if (invoiceDetails) invoiceDetails.style.display = 'none';
    if (itemsCard) itemsCard.style.display = 'none';
    if (itemsTableBody) itemsTableBody.innerHTML = '';
    if (submitBtn) submitBtn.disabled = true;
}

// Populate items table
function populateItemsTable(items) {
    const tbody = document.getElementById('itemsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    items.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>
                <strong>${item.product_name}</strong>
                ${item.product_barcode ? `<br><small class="text-muted">Barcode: ${item.product_barcode}</small>` : ''}
            </td>
            <td>${item.product_sku}</td>
            <td class="text-center">${item.quantity}</td>
            <td>
                <input type="number" class="form-control form-control-sm return-qty" 
                       data-item-id="${item.id}" 
                       data-max-qty="${item.quantity}" 
                       data-unit-price="${item.unit_price}"
                       min="0" max="${item.quantity}" value="0">
            </td>
            <td class="text-end">R ${parseFloat(item.unit_price).toFixed(2)}</td>
            <td>
                <select class="form-select form-select-sm condition-select" 
                        data-item-id="${item.id}">
                    <option value="new">New</option>
                    <option value="used">Used</option>
                    <option value="damaged">Damaged</option>
                    <option value="defective">Defective</option>
                </select>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    updateReturnAmount();
}

// Update return amount
function updateReturnAmount() {
    const returnQtyInputs = document.querySelectorAll('.return-qty');
    let totalAmount = 0;
    let hasItems = false;
    
    returnQtyInputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        const unitPrice = parseFloat(input.dataset.unitPrice);
        const lineTotal = qty * unitPrice;
        totalAmount += lineTotal;
        
        if (qty > 0) {
            hasItems = true;
        }
    });
    
    const totalElement = document.getElementById('totalReturnAmount');
    if (totalElement) {
        totalElement.textContent = `R ${totalAmount.toFixed(2)}`;
    }
    
    const invoiceSelect = document.getElementById('invoiceSelect');
    const returnType = document.getElementById('returnType');
    const refundMethod = document.getElementById('refundMethod');
    const returnReason = document.getElementById('returnReason');
    const submitBtn = document.getElementById('submitReturnBtn');
    
    if (invoiceSelect && returnType && refundMethod && returnReason && submitBtn) {
        const canSubmit = invoiceSelect.value && returnType.value && refundMethod.value && returnReason.value.trim() && hasItems;
        submitBtn.disabled = !canSubmit;
    }
}

// Form submission
function submitReturn() {
    const returnQtyInputs = document.querySelectorAll('.return-qty');
    const items = [];
    
    returnQtyInputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        if (qty > 0) {
            const conditionSelect = document.querySelector(`.condition-select[data-item-id="${input.dataset.itemId}"]`);
            items.push({
                invoice_item_id: input.dataset.itemId,
                quantity_returned: qty,
                return_reason: document.getElementById('returnReason').value,
                condition: conditionSelect ? conditionSelect.value : 'new',
                restock: true
            });
        }
    });

    // Get stock handling type from radio buttons
    const stockHandling = document.querySelector('input[name="stockHandling"]:checked');

    const formData = {
        invoice_id: document.getElementById('invoiceSelect').value,
        return_type: document.getElementById('returnType').value,
        reason: document.getElementById('returnReason').value,
        refund_method: document.getElementById('refundMethod').value,
        stock_handling_type: stockHandling ? stockHandling.value : 'restock',
        restock_items: stockHandling ? stockHandling.value === 'restock' : true,
        items: items
    };
    
    fetch('<?php echo e(route("returns.store")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Return created successfully! Return #: ' + data.return_number);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating return');
    });
}

// View return
function viewReturn(id) {
    const url = '<?php echo e(route("returns.show", ":id")); ?>'.replace(':id', id);
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('viewReturnModalContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('viewReturnModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading return:', error);
            alert('Error loading return details');
        });
}

// Edit return
function editReturn(id) {
    const url = '<?php echo e(route("returns.edit", ":id")); ?>'.replace(':id', id);
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editReturnModal'));
            modal.show();
        });
}

// Delete return
function deleteReturn(id) {
    if (confirm('Are you sure you want to delete this return?')) {
        const url = '<?php echo e(route("returns.destroy", ":id")); ?>'.replace(':id', id);
        
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
        });
    }
}

// Approve return
function approveReturn(id) {
    if (confirm('Are you sure you want to approve this return?')) {
        const url = '<?php echo e(route("returns.approve", ":id")); ?>'.replace(':id', id);
        
        fetch(url, {
            method: 'POST',
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
        });
    }
}

// Reject return
function rejectReturn(id) {
    currentReturnId = id;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Confirm reject
function confirmReject() {
    const notes = document.getElementById('rejectNotes').value;
    if (!notes.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }

    const url = '<?php echo e(route("returns.reject", ":id")); ?>'.replace(':id', currentReturnId);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// Complete return
function completeReturn(id) {
    if (confirm('Are you sure you want to complete this return? This will process the refund and restock items.')) {
        const url = '<?php echo e(route("returns.complete", ":id")); ?>'.replace(':id', id);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Return completed successfully! Credit Note: ' + data.credit_note_number);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

// Download credit note
function downloadCreditNote(creditNoteId) {
    const url = '<?php echo e(route("returns.credit-note-pdf", ":id")); ?>'.replace(':id', creditNoteId);
    window.open(url, '_blank');
}

// View invoice (from returns page)
function viewInvoice(invoiceId) {
    // Check if invoice modal structure exists, if not create it
    let invoiceModalContainer = document.getElementById('invoiceModalContainer');
    if (!invoiceModalContainer) {
        invoiceModalContainer = document.createElement('div');
        invoiceModalContainer.id = 'invoiceModalContainer';
        document.body.appendChild(invoiceModalContainer);
    }
    
    // Create modal structure if it doesn't exist
    if (!document.getElementById('viewInvoiceModal')) {
        invoiceModalContainer.innerHTML = `
            <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content" id="viewInvoiceModalContent">
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    const url = '<?php echo e(route("invoices.view-modal", ":id")); ?>'.replace(':id', invoiceId);
    
    // Show loading in modal content
    const modalContent = document.getElementById('viewInvoiceModalContent');
    modalContent.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading invoice details...</p>
        </div>
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    modal.show();
    
    // Fetch invoice content
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Invoice not found');
            }
            return response.text();
        })
        .then(html => {
            modalContent.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading invoice:', error);
            modalContent.innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error loading invoice details. Please try again.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            `;
        });
}

// Filter functions
document.getElementById('statusFilter').addEventListener('change', filterReturns);
document.getElementById('typeFilter').addEventListener('change', filterReturns);
document.getElementById('refundFilter').addEventListener('change', filterReturns);
document.getElementById('searchInput').addEventListener('input', debounce(filterReturns, 500));

// Debounce function for search input
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

function filterReturns() {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const refund = document.getElementById('refundFilter').value;
    const search = document.getElementById('searchInput').value;
    
    // Build query string
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (type) params.append('type', type);
    if (refund) params.append('refund_method', refund);
    if (search) params.append('search', search);
    
    // Reload page with filters
    const url = '<?php echo e(route("returns.index")); ?>' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearFilters() {
    window.location.href = '<?php echo e(route("returns.index")); ?>';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/returns/index.blade.php ENDPATH**/ ?>