<?php
    $isDraft = $purchaseOrder->status === 'draft';
    $isApproved = $purchaseOrder->status === 'approved';
    $isEditable = $isDraft || $isApproved;
?>

<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-edit-line me-2"></i> Edit Purchase Order #<?php echo e($purchaseOrder->po_number); ?>

        <?php if($isApproved): ?>
            <span class="badge bg-info ms-2">Limited Edit Mode</span>
        <?php endif; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="poEditForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
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
                        <select name="supplier_id" id="editSupplier" class="form-select" required <?php echo e($isApproved ? 'disabled' : ''); ?>>
                            <option value="">Select Supplier</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" 
                                        <?php echo e($purchaseOrder->supplier_id == $supplier->id ? 'selected' : ''); ?>>
                                    <?php echo e($supplier->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($isApproved): ?>
                            <input type="hidden" name="supplier_id" value="<?php echo e($purchaseOrder->supplier_id); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" id="editOrderDate" 
                               class="form-control" value="<?php echo e($purchaseOrder->order_date->format('Y-m-d')); ?>" required <?php echo e($isApproved ? 'readonly' : ''); ?>>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" name="expected_delivery_date" id="editExpectedDate" 
                               class="form-control" value="<?php echo e($purchaseOrder->expected_delivery_date?->format('Y-m-d')); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Terms</label>
                        <textarea name="payment_terms" id="editPaymentTerms" 
                                  class="form-control" rows="2" <?php echo e($isApproved ? 'readonly' : ''); ?>><?php echo e($purchaseOrder->payment_terms); ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="editNotes" class="form-control" rows="2"><?php echo e($purchaseOrder->notes); ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editStatus" class="form-select" required <?php echo e($isApproved ? 'disabled' : ''); ?>>
                            <option value="draft" <?php echo e($purchaseOrder->status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="approved" <?php echo e($purchaseOrder->status == 'approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="cancelled" <?php echo e($purchaseOrder->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        </select>
                        <?php if($isApproved): ?>
                            <input type="hidden" name="status" value="<?php echo e($purchaseOrder->status); ?>">
                        <?php endif; ?>
                        <small class="text-muted">
                            <i class="ri-information-line"></i> 
                            Other statuses (Sent, Partially Received, Closed) are set by system workflow
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Search Section -->
        <?php if(!$isApproved): ?>
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="ri-search-line me-1"></i> Product Search
                </h6>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <input type="text" id="editProductSearch" class="form-control" 
                           placeholder="Search products by name, SKU, or description... (Press F2 to focus)">
                    <div id="editSearchResults" class="search-results-dropdown" style="display: none;"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Items Table Section -->
        <div class="card mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="ri-shopping-cart-line me-1"></i> Purchase Order Items
                </h6>
                <span class="badge bg-primary" id="editItemCount"><?php echo e($purchaseOrder->items->count()); ?> items</span>
                </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="editItemsTable">
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
                        <tbody id="editItemsBody">
                            <?php $__currentLoopData = $purchaseOrder->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-product-id="<?php echo e($item->product_id); ?>">
                                <td class="align-middle text-center"><?php echo e($index + 1); ?></td>
                                <td class="align-middle">
                                    <div class="fw-semibold"><?php echo e($item->product->name ?? 'N/A'); ?></div>
                                    <small class="text-muted">SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></small>
                                    <input type="hidden" class="edit-item-product-id" value="<?php echo e($item->product_id); ?>">
                                </td>
                                <td class="align-middle">
                                    <input type="number" class="form-control form-control-sm edit-item-qty" 
                                           value="<?php echo e($item->quantity); ?>" min="1" step="1" <?php echo e($isApproved ? 'readonly' : ''); ?>>
                                </td>
                                <td class="align-middle">
                                    <input type="number" class="form-control form-control-sm edit-item-price" 
                                           value="<?php echo e($item->unit_price); ?>" min="0" step="0.01" <?php echo e($isApproved ? 'readonly' : ''); ?>>
                                </td>
                                <td class="align-middle text-end edit-item-total-display">
                                    R <?php echo e(number_format($item->total, 2)); ?>

                                </td>
                                <td class="align-middle text-center">
                                    <?php if(!$isApproved): ?>
                                    <button type="button" class="btn btn-sm btn-danger remove-edit-item" title="Remove">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="editVatEnabled" name="vat_enabled"
                                   <?php echo e($purchaseOrder->vat_enabled ? 'checked' : ''); ?> <?php echo e($isApproved ? 'disabled' : ''); ?>>
                            <label class="form-check-label" for="editVatEnabled">
                                Include VAT (<?php echo e($vatSettings['vat_rate'] ?? 15); ?>%)
                            </label>
                </div>
            </div>
                    <div class="col-md-4">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="text-end" id="editSubtotalDisplay">R <?php echo e(number_format($purchaseOrder->subtotal, 2)); ?></td>
                            </tr>
                            <tr>
                                <td class="text-end">
                                    <label for="editDiscount" class="mb-0">Discount:</label>
                                </td>
                                <td class="text-end">
                                    <input type="number" name="total_discount" id="editDiscount" 
                                           class="form-control form-control-sm text-end" 
                                           value="<?php echo e($purchaseOrder->total_discount); ?>" min="0" step="0.01" <?php echo e($isApproved ? 'readonly' : ''); ?>>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end">
                                    <label for="editShipping" class="mb-0">Shipping:</label>
                                </td>
                                <td class="text-end">
                                    <input type="number" name="shipping" id="editShipping" 
                                           class="form-control form-control-sm text-end" 
                                           value="<?php echo e($purchaseOrder->shipping); ?>" min="0" step="0.01" <?php echo e($isApproved ? 'readonly' : ''); ?>>
                                </td>
                            </tr>
                            <tr id="editVatRow" style="display: <?php echo e($purchaseOrder->vat_enabled ? 'table-row' : 'none'); ?>;">
                                <td class="text-end fw-semibold">VAT:</td>
                                <td class="text-end" id="editVatDisplay">R <?php echo e(number_format($purchaseOrder->vat, 2)); ?></td>
                            </tr>
                            <tr class="table-primary">
                                <td class="text-end fw-bold">Grand Total:</td>
                                <td class="text-end fw-bold fs-18" id="editGrandTotalDisplay">R <?php echo e(number_format($purchaseOrder->grand_total, 2)); ?></td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="subtotal" id="editSubtotal" value="<?php echo e($purchaseOrder->subtotal); ?>">
        <input type="hidden" name="vat" id="editVat" value="<?php echo e($purchaseOrder->vat); ?>">
        <input type="hidden" name="grand_total" id="editGrandTotal" value="<?php echo e($purchaseOrder->grand_total); ?>">
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
            <i class="ri-save-line me-1"></i> Update Purchase Order
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
</style>

<script>
$(function() {
    let editItemCounter = <?php echo e($purchaseOrder->items->count()); ?>;
    const vatRate = <?php echo e($vatSettings['vat_rate'] ?? 15); ?> / 100;
    let editSearchTimeout = null;
    let editSearchXhr = null;

    // Initialize Select2
    setTimeout(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#editSupplier').select2({
                dropdownParent: $('#poModal'),
            width: '100%'
        });
        }
    }, 200);

    // Product Search (similar to create modal)
    $('#editProductSearch').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length >= 2) {
                editSearchProducts(query);
            }
        } else if (e.key === 'Escape') {
            $('#editSearchResults').hide();
            $(this).val('');
        }
    });

    $('#editProductSearch').on('input', function() {
        const query = $(this).val().trim();
        
        if (editSearchTimeout) {
            clearTimeout(editSearchTimeout);
        }

        if (query.length < 2) {
            $('#editSearchResults').hide();
            return;
        }

        $('#editSearchResults').html('<div class="p-3 text-center"><div class="spinner-border spinner-border-sm me-2"></div>Searching...</div>').show();

        editSearchTimeout = setTimeout(function() {
            editSearchProducts(query);
        }, 500);
    });

    function editSearchProducts(query) {
        if (editSearchXhr) {
            editSearchXhr.abort();
        }

        editSearchXhr = $.ajax({
            url: '<?php echo e(route("purchase-orders.search-products")); ?>',
            method: 'GET',
            data: { q: query },
            success: function(products) {
                displayEditSearchResults(products);
            },
            error: function(xhr) {
                if (xhr.statusText !== 'abort') {
                    $('#editSearchResults').html('<div class="p-3 text-danger">Error loading products</div>');
                }
            }
        });
    }

    function displayEditSearchResults(products) {
        if (products.length === 0) {
            $('#editSearchResults').html('<div class="p-3 text-muted">No products found</div>').show();
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
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold text-success">R ${parseFloat(product.cost_price || 0).toFixed(2)}</div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#editSearchResults').html(html).show();
    }

    // Add product from search
    $(document).on('click', '#editSearchResults .search-result-item', function() {
        const product = $(this).data('product');
        addEditProductToPO(product);
        $('#editProductSearch').val('');
        $('#editSearchResults').hide();
    });

    function addEditProductToPO(product) {
        // Check if exists
        let existingRow = $('#editItemsBody tr[data-product-id="' + product.id + '"]');
        
        if (existingRow.length > 0) {
            const qtyInput = existingRow.find('.edit-item-qty');
            qtyInput.val(parseInt(qtyInput.val()) + 1);
            calculateEditItemTotal(existingRow);
            toastr.info('Product quantity increased');
            return;
        }

        // Add new row
        editItemCounter++;
        const costPrice = parseFloat(product.cost_price || 0).toFixed(2);
        
        const row = `
            <tr data-product-id="${product.id}">
                <td class="align-middle text-center">${editItemCounter}</td>
                <td class="align-middle">
                    <div class="fw-semibold">${product.name}</div>
                    <small class="text-muted">SKU: ${product.sku || 'N/A'}</small>
                    <input type="hidden" class="edit-item-product-id" value="${product.id}">
                </td>
                <td class="align-middle">
                    <input type="number" class="form-control form-control-sm edit-item-qty" 
                           value="1" min="1" step="1">
                </td>
                <td class="align-middle">
                    <input type="number" class="form-control form-control-sm edit-item-price" 
                           value="${costPrice}" min="0" step="0.01">
                </td>
                <td class="align-middle text-end edit-item-total-display">
                    R ${costPrice}
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-edit-item" title="Remove">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#editItemsBody').append(row);
        updateEditItemNumbers();
        calculateEditTotals();
    }

    // Remove item
    $(document).on('click', '.remove-edit-item', function() {
        if ($('#editItemsBody tr[data-product-id]').length <= 1) {
            toastr.error('Purchase order must have at least one item');
            return;
        }
                $(this).closest('tr').remove();
        updateEditItemNumbers();
        calculateEditTotals();
    });

    // Calculate on input
    $(document).on('input', '.edit-item-qty, .edit-item-price', function() {
        calculateEditItemTotal($(this).closest('tr'));
    });

    $(document).on('input', '#editDiscount, #editShipping', calculateEditTotals);
    
    $(document).on('change', '#editVatEnabled', function() {
        if ($(this).is(':checked')) {
            $('#editVatRow').show();
        } else {
            $('#editVatRow').hide();
        }
        calculateEditTotals();
    });

    function calculateEditItemTotal(row) {
        const qty = parseFloat(row.find('.edit-item-qty').val()) || 0;
        const price = parseFloat(row.find('.edit-item-price').val()) || 0;
        const total = qty * price;
        
        row.find('.edit-item-total-display').text('R ' + total.toFixed(2));
        calculateEditTotals();
    }

    function calculateEditTotals() {
        let subtotal = 0;

        $('#editItemsBody tr[data-product-id]').each(function() {
            const qty = parseFloat($(this).find('.edit-item-qty').val()) || 0;
            const price = parseFloat($(this).find('.edit-item-price').val()) || 0;
            subtotal += qty * price;
        });

        const discount = parseFloat($('#editDiscount').val()) || 0;
        const shipping = parseFloat($('#editShipping').val()) || 0;
        const vatEnabled = $('#editVatEnabled').is(':checked');

        let afterDiscount = subtotal - discount;
        let vat = 0;
        
        if (vatEnabled) {
            vat = (afterDiscount + shipping) * vatRate;
        }

        const grandTotal = afterDiscount + shipping + vat;

        $('#editSubtotalDisplay').text('R ' + subtotal.toFixed(2));
        $('#editVatDisplay').text('R ' + vat.toFixed(2));
        $('#editGrandTotalDisplay').text('R ' + grandTotal.toFixed(2));

        $('#editSubtotal').val(subtotal.toFixed(2));
        $('#editVat').val(vat.toFixed(2));
        $('#editGrandTotal').val(grandTotal.toFixed(2));

        const itemCount = $('#editItemsBody tr[data-product-id]').length;
        $('#editItemCount').text(itemCount + ' item' + (itemCount !== 1 ? 's' : ''));
    }

    function updateEditItemNumbers() {
        $('#editItemsBody tr[data-product-id]').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
        editItemCounter = $('#editItemsBody tr[data-product-id]').length;
    }

    // Form submission
    $('#poEditForm').on('submit', function(e) {
        e.preventDefault();

        const items = [];
        $('#editItemsBody tr[data-product-id]').each(function() {
            const productId = $(this).find('.edit-item-product-id').val();
            const qty = parseFloat($(this).find('.edit-item-qty').val()) || 0;
            const price = parseFloat($(this).find('.edit-item-price').val()) || 0;
            const total = qty * price;

            items.push({
                product_id: productId,
                quantity: qty,
                unit_price: price,
                total: total
            });
        });

        if (items.length === 0) {
            toastr.error('Purchase order must have at least one item');
            return;
        }

        const formData = {
            supplier_id: $('#editSupplier').val(),
            order_date: $('#editOrderDate').val(),
            expected_delivery_date: $('#editExpectedDate').val(),
            status: $('#editStatus').val(),
            notes: $('#editNotes').val(),
            delivery_address: $('#editDeliveryAddress').val(),
            payment_terms: $('#editPaymentTerms').val(),
            subtotal: $('#editSubtotal').val(),
            total_discount: $('#editDiscount').val(),
            shipping: $('#editShipping').val(),
            vat: $('#editVat').val(),
            vat_enabled: $('#editVatEnabled').is(':checked') ? 1 : 0,
            grand_total: $('#editGrandTotal').val(),
            items: items,
            _method: 'PUT'
        };

        const submitBtn = $('#editSubmitBtn');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

        $.ajax({
            url: '<?php echo e(route("purchase-orders.update", $purchaseOrder->id)); ?>',
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
                    toastr.error(response.message || 'Failed to update purchase order');
                    submitBtn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Update Purchase Order');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                let errorMessage = 'Failed to update purchase order';
                
                if (response && response.message) {
                    errorMessage = response.message;
                } else if (response && response.errors) {
                    errorMessage = Object.values(response.errors).flat().join('<br>');
                }
                
                toastr.error(errorMessage);
                submitBtn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Update Purchase Order');
            }
        });
    });

    // Close search on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#editProductSearch, #editSearchResults').length) {
            $('#editSearchResults').hide();
        }
    });

    // Initial calculation
    calculateEditTotals();
});
</script>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/purchase_orders/partials/edit_modal.blade.php ENDPATH**/ ?>