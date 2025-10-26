<div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
        <i class="ri-add-line me-2"></i> Create New Purchase Order
        </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="poCreateForm">
    <?php echo csrf_field(); ?>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <!-- Basic Information Section -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="ri-information-line me-1"></i> Basic Information
                </h6>
    </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" id="createSupplier" class="form-select" required>
                            <option value="">-- Select Supplier --</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" 
                                        data-email="<?php echo e($supplier->email); ?>" 
                                        data-phone="<?php echo e($supplier->phone); ?>"
                                        data-payment-terms="<?php echo e($supplier->payment_terms ?? ''); ?>"
                                        data-lead-time="<?php echo e($supplier->lead_time ?? 0); ?>">
                                    <?php echo e($supplier->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" id="createOrderDate" 
                               class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" name="expected_delivery_date" id="createExpectedDate" 
                               class="form-control" min="<?php echo e(date('Y-m-d')); ?>">
                    </div>
                </div>
                <div class="row">
                    
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="createStatus" class="form-select" required>
                            <option value="draft" selected>Draft</option>
                            <option value="approved">Approved</option>
                        </select>
                        <small class="text-muted">
                            <i class="ri-information-line"></i> 
                            Draft = Internal editing | Approved = Ready to send
                        </small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="createNotes" class="form-control" rows="2" 
                                  placeholder="Internal notes..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Search Section -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="ri-search-line me-1"></i> Product Search
                </h6>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <input type="text" id="createProductSearch" class="form-control" 
                           placeholder="Search products by name, SKU, or description... (Press F2 to focus)">
                    <div id="createSearchResults" class="search-results-dropdown" style="display: none;">
                        <!-- Search results will appear here -->
                    </div>
                    </div>
                </div>
            </div>

        <!-- Items Table Section -->
        <div class="card mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="ri-shopping-cart-line me-1"></i> Purchase Order Items
                </h6>
                <span class="badge bg-primary" id="createItemCount">0 items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="createItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Product</th>
                                <th width="12%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Total</th>
                                <th width="8%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="createItemsBody">
                            <tr id="createNoItemsRow">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="ri-shopping-cart-2-line fs-32 mb-2 d-block"></i>
                                    No items added yet. Search and add products above.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Calculations Section -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end" id="createSubtotalDisplay">R 0.00</td>
                            </tr>
                                <div class="form-check mb-2">
                                    <label class="form-check-label" for="createVatEnabled">
                                        Include VAT (<?php echo e($vatSettings['vat_rate'] ?? 15); ?>%)
                                    </label>
                                </div>
                            <tr id="createVatRow" style="display: none;">
                                <td class="text-end fw-semibold">VAT (<?php echo e($vatSettings['vat_rate'] ?? 15); ?>%):</td>
                                <td class="text-end" id="createVatDisplay">R 0.00</td>
                            </tr>
                            <tr class="table-primary">
                                <td class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold fs-18" id="createGrandTotalDisplay">R 0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Hidden inputs for calculations -->
        <input type="hidden" name="subtotal" id="createSubtotal" value="0">
        <input type="hidden" name="vat" id="createVat" value="0">
        <input type="hidden" name="grand_total" id="createGrandTotal" value="0">
        <input type="hidden" name="items" id="createItemsData" value="[]">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary" id="createSubmitBtn">
            <i class="ri-save-line me-1"></i> Create Purchase Order
        </button>
    </div>
</form>

<style>
.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1050;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.search-result-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}
</style>

<script>
$(function() {
    let createItemCounter = 0;
    let createSearchTimeout = null;
    const vatRate = <?php echo e($vatSettings['vat_rate'] ?? 15); ?> / 100;

    // Initialize Select2 for supplier
    setTimeout(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#createSupplier').select2({
                dropdownParent: $('#poModal'),
                width: '100%',
                placeholder: '-- Select Supplier --'
            });
        }
    }, 200);

    // Auto-fill payment terms and expected delivery when supplier changes
    $('#createSupplier').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const paymentTerms = selectedOption.data('payment-terms') || '';
        const leadTime = parseInt(selectedOption.data('lead-time')) || 0;
        const orderDate = $('#createOrderDate').val();
        
        // Auto-fill payment terms
        $('#createPaymentTerms').val(paymentTerms);
        
        // Auto-calculate expected delivery date
        if (orderDate && leadTime > 0) {
            const orderDateObj = new Date(orderDate);
            orderDateObj.setDate(orderDateObj.getDate() + leadTime);
            const expectedDate = orderDateObj.toISOString().split('T')[0];
            $('#createExpectedDate').val(expectedDate);
        }
    });

    // Recalculate expected delivery when order date changes
    $('#createOrderDate').on('change', function() {
        const selectedOption = $('#createSupplier').find('option:selected');
        const leadTime = parseInt(selectedOption.data('lead-time')) || 0;
        const orderDate = $(this).val();
        
        if (orderDate && leadTime > 0) {
            const orderDateObj = new Date(orderDate);
            orderDateObj.setDate(orderDateObj.getDate() + leadTime);
            const expectedDate = orderDateObj.toISOString().split('T')[0];
            $('#createExpectedDate').val(expectedDate);
        }
    });

    // Product Search
    let createSearchXhr = null;
    
    $('#createProductSearch').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length >= 2) {
                createSearchProducts(query);
            }
        } else if (e.key === 'Escape') {
            $('#createSearchResults').hide();
                        $(this).val('');
        }
    });

    $('#createProductSearch').on('input', function() {
        const query = $(this).val().trim();
        
        if (createSearchTimeout) {
            clearTimeout(createSearchTimeout);
        }

        if (query.length < 2) {
            $('#createSearchResults').hide();
            return;
        }

        $('#createSearchResults').html('<div class="p-3 text-center"><div class="spinner-border spinner-border-sm me-2"></div>Searching...</div>').show();

        createSearchTimeout = setTimeout(function() {
            createSearchProducts(query);
        }, 500);
    });

    // F2 key focus on search
    $(document).on('keydown', function(e) {
        if (e.key === 'F2' && $('#poModal').hasClass('show')) {
            e.preventDefault();
            $('#createProductSearch').focus();
        }
    });

    function createSearchProducts(query) {
        if (createSearchXhr) {
            createSearchXhr.abort();
        }

        createSearchXhr = $.ajax({
            url: '<?php echo e(route("purchase-orders.search-products")); ?>',
            method: 'GET',
            data: { q: query },
            success: function(products) {
                displayCreateSearchResults(products);
            },
            error: function(xhr) {
                if (xhr.statusText !== 'abort') {
                    $('#createSearchResults').html('<div class="p-3 text-danger">Error loading products</div>');
                    }
               }
            });
    }

    function displayCreateSearchResults(products) {
        if (products.length === 0) {
            $('#createSearchResults').html('<div class="p-3 text-muted">No products found</div>').show();
            return;
        }

        let html = '';
        products.forEach(function(product) {
            html += `
                <div class="search-result-item" data-product='${JSON.stringify(product)}'>
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">${product.name}</div>
                            <small class="text-muted">SKU: ${product.sku || 'N/A'}</small>
                            ${product.category ? `<span class="badge bg-light text-dark ms-2">${product.category}</span>` : ''}
                            ${product.brand ? `<span class="badge bg-light text-dark ms-1">${product.brand}</span>` : ''}
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold text-success">R ${parseFloat(product.cost_price || 0).toFixed(2)}</div>
                            <small class="text-muted">Cost Price</small>
                        </div>
                    </div>
                    ${product.description ? `<div class="small text-muted mt-1">${product.description.substring(0, 60)}...</div>` : ''}
                </div>
            `;
        });

        $('#createSearchResults').html(html).show();
    }

    // Add product to PO
    $(document).on('click', '.search-result-item', function() {
        const product = $(this).data('product');
        addCreateProductToPO(product);
        $('#createProductSearch').val('');
        $('#createSearchResults').hide();
    });

    function addCreateProductToPO(product) {
        // Check if product already exists
        let existingRow = null;
        $('#createItemsBody tr[data-product-id="' + product.id + '"]').each(function() {
            existingRow = $(this);
        });

        if (existingRow && existingRow.length > 0) {
            // Increment quantity
            const qtyInput = existingRow.find('.create-item-qty');
            const currentQty = parseInt(qtyInput.val()) || 0;
            qtyInput.val(currentQty + 1);
            calculateCreateItemTotal(existingRow);
            toastr.info('Product quantity increased');
            return;
        }

        // Hide no items row
        $('#createNoItemsRow').hide();

        // Add new row
        createItemCounter++;
        const costPrice = parseFloat(product.cost_price || 0).toFixed(2);
        
        const row = `
            <tr data-product-id="${product.id}">
                <td class="align-middle text-center">${createItemCounter}</td>
                <td class="align-middle">
                    <div class="fw-semibold">${product.name}</div>
                    <small class="text-muted">SKU: ${product.sku || 'N/A'}</small>
                    <input type="hidden" class="create-item-product-id" value="${product.id}">
                </td>
                <td class="align-middle">
                    <input type="number" class="form-control form-control-sm create-item-qty" 
                           value="1" min="1" step="1">
                </td>
                <td class="align-middle">
                    <input type="number" class="form-control form-control-sm create-item-price" 
                           value="${costPrice}" min="0" step="0.01">
                </td>
                <td class="align-middle text-end create-item-total-display">
                    R ${costPrice}
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-create-item" title="Remove">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#createItemsBody').append(row);
        updateCreateItemNumbers();
        calculateCreateTotals();
    }

    // Remove item
    $(document).on('click', '.remove-create-item', function() {
                $(this).closest('tr').remove();
        updateCreateItemNumbers();
        calculateCreateTotals();
        
        if ($('#createItemsBody tr[data-product-id]').length === 0) {
            $('#createNoItemsRow').show();
        }
    });

    // Calculate item total when qty or price changes
    $(document).on('input', '.create-item-qty, .create-item-price', function() {
        const row = $(this).closest('tr');
        calculateCreateItemTotal(row);
    });

    function calculateCreateItemTotal(row) {
        const qty = parseFloat(row.find('.create-item-qty').val()) || 0;
        const price = parseFloat(row.find('.create-item-price').val()) || 0;
        const total = qty * price;
        
        row.find('.create-item-total-display').text('R ' + total.toFixed(2));
        calculateCreateTotals();
    }

    // Show VAT row if enabled by default
    if ($('#createVatEnabled').is(':checked')) {
        $('#createVatRow').show();
    }

    // Calculate totals
    $(document).on('change', '#createVatEnabled', function() {
        if ($(this).is(':checked')) {
            $('#createVatRow').show();
        } else {
            $('#createVatRow').hide();
        }
        calculateCreateTotals();
    });

    function calculateCreateTotals() {
        let subtotal = 0;

        // Calculate subtotal from all items
        $('#createItemsBody tr[data-product-id]').each(function() {
            const qty = parseFloat($(this).find('.create-item-qty').val()) || 0;
            const price = parseFloat($(this).find('.create-item-price').val()) || 0;
            subtotal += qty * price;
        });

        const vatEnabled = $('#createVatEnabled').is(':checked');
        const vatInclusive = <?php echo e(($vatSettings['vat_inclusive'] ?? false) ? 'true' : 'false'); ?>;
        
        let vat = 0;
        let grandTotal = subtotal;
        
        if (vatEnabled) {
            if (vatInclusive) {
                // VAT is included in the price
                vat = subtotal - (subtotal / (1 + vatRate));
                grandTotal = subtotal;
            } else {
                // VAT is added to the price
                vat = subtotal * vatRate;
                grandTotal = subtotal + vat;
            }
        }

        // Update displays
        $('#createSubtotalDisplay').text('R ' + subtotal.toFixed(2));
        $('#createVatDisplay').text('R ' + vat.toFixed(2));
        $('#createGrandTotalDisplay').text('R ' + grandTotal.toFixed(2));

        // Update hidden inputs
        $('#createSubtotal').val(subtotal.toFixed(2));
        $('#createVat').val(vat.toFixed(2));
        $('#createGrandTotal').val(grandTotal.toFixed(2));

        // Update item count
        const itemCount = $('#createItemsBody tr[data-product-id]').length;
        $('#createItemCount').text(itemCount + ' item' + (itemCount !== 1 ? 's' : ''));
    }

    function updateCreateItemNumbers() {
        $('#createItemsBody tr[data-product-id]').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
        createItemCounter = $('#createItemsBody tr[data-product-id]').length;
    }

    // Form submission
    $('#poCreateForm').on('submit', function(e) {
        e.preventDefault();

        // Validate items
        const items = [];
        $('#createItemsBody tr[data-product-id]').each(function() {
            const productId = $(this).find('.create-item-product-id').val();
            const qty = parseFloat($(this).find('.create-item-qty').val()) || 0;
            const price = parseFloat($(this).find('.create-item-price').val()) || 0;
            const total = qty * price;

            items.push({
                product_id: productId,
                quantity: qty,
                unit_price: price,
                total: total
            });
        });

        if (items.length === 0) {
            toastr.error('Please add at least one item to the purchase order');
            return;
        }

        // Prepare form data
        const formData = {
            supplier_id: $('#createSupplier').val(),
            order_date: $('#createOrderDate').val(),
            expected_delivery_date: $('#createExpectedDate').val(),
            status: $('#createStatus').val(),
            notes: $('#createNotes').val(),
            payment_terms: $('#createPaymentTerms').val(),
            subtotal: $('#createSubtotal').val(),
            total_discount: 0,
            shipping: 0,
            vat: $('#createVat').val(),
            vat_enabled: $('#createVatEnabled').is(':checked') ? 1 : 0,
            grand_total: $('#createGrandTotal').val(),
            items: items
        };

        // Disable submit button
        const submitBtn = $('#createSubmitBtn');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');

        // Submit via AJAX
        $.ajax({
            url: '<?php echo e(route("purchase-orders.store")); ?>',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#poModal').modal('hide');
                    window.location.reload();
                } else {
                    toastr.error(response.message || 'Failed to create purchase order');
                    submitBtn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Create Purchase Order');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                let errorMessage = 'Failed to create purchase order';
                
                if (response && response.message) {
                    errorMessage = response.message;
                } else if (response && response.errors) {
                    errorMessage = Object.values(response.errors).flat().join('<br>');
                }
                
                toastr.error(errorMessage);
                submitBtn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Create Purchase Order');
            }
        });
    });

    // Close search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#createProductSearch, #createSearchResults').length) {
            $('#createSearchResults').hide();
        }
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/purchase_orders/partials/create_modal.blade.php ENDPATH**/ ?>