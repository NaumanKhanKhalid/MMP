<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Stock Adjustments</h4>
            <p class="fs-13 text-muted mb-0">Manual stock adjustments history</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdjustmentModal">
                <i class="ri-add-line me-1"></i> New Adjustment
            </button>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Adjustment #</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Product</th>
                            <th>Qty Before</th>
                            <th>Adjustment</th>
                            <th>Qty After</th>
                            <th>Reason</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $adjustments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($adj->adjustment_number); ?></td>
                            <td><?php echo e($adj->adjustment_date->format('d M Y')); ?></td>
                            <td><span class="badge bg-info-transparent"><?php echo e($adj->getAdjustmentTypeLabel()); ?></span></td>
                            <td><?php echo e($adj->product->name); ?></td>
                            <td><?php echo e(number_format($adj->quantity_before, 0)); ?></td>
                            <td class="<?php echo e($adj->isIncrease() ? 'text-success' : 'text-danger'); ?> fw-semibold">
                                <?php echo e($adj->adjustment_qty > 0 ? '+' : ''); ?><?php echo e(number_format($adj->adjustment_qty, 2)); ?>

                            </td>
                            <td><?php echo e(number_format($adj->quantity_after, 0)); ?></td>
                            <td><?php echo e($adj->reason); ?></td>
                            <td><?php echo e($adj->user->name); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No adjustments found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($adjustments->hasPages()): ?>
        <div class="card-footer"><?php echo e($adjustments->links()); ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Create Adjustment Modal -->
<div class="modal fade" id="createAdjustmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createAdjustmentForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-search-line me-1"></i> Product *
                        </label>
                        <select name="product_id" id="productSelect" class="form-select" required>
                            <option value="">Search by SKU, Name, Barcode, OE#, Supplier Code...</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"
                                        data-sku="<?php echo e($product->sku); ?>"
                                        data-barcode="<?php echo e($product->barcode_primary); ?>"
                                        data-supplier-code="<?php echo e($product->supplier_code); ?>"
                                        data-stock="<?php echo e($product->on_hand ?? 0); ?>">
                                    <?php echo e($product->sku); ?> - <?php echo e($product->name); ?>

                                    <?php if($product->supplier_code): ?> (Supplier: <?php echo e($product->supplier_code); ?>) <?php endif; ?>
                                    <?php if($product->brand): ?> - <?php echo e($product->brand->name); ?> <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div id="currentStock" class="mt-2">
                            <!-- Current stock info will appear here -->
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-file-list-line me-1"></i> Type *
                        </label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="">-- Select Adjustment Type --</option>
                            <option value="manual">📝 Manual Adjustment</option>
                            <option value="damage">💔 Damaged Stock</option>
                            <option value="loss">🔍 Lost/Stolen</option>
                            <option value="found">✨ Found/Recovered</option>
                            <option value="correction">✏️ Correction</option>
                        </select>
                        <small class="text-muted">Select why you're adjusting stock</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-calculator-line me-1"></i> Adjustment Quantity *
                        </label>
                        <div class="position-relative">
                            <input type="text" 
                                   name="adjustment_qty" 
                                   id="adjustmentQty"
                                   class="form-control form-control-lg ps-5" 
                                   placeholder="Enter quantity: +10 to add, -5 to remove"
                                   autocomplete="off"
                                   required>
                            <span class="position-absolute top-50 start-0 translate-middle-y ms-3" id="qtyIconWrapper">
                                <i class="ri-add-circle-line text-muted fs-5" id="qtyIcon"></i>
                            </span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="ri-information-line"></i>
                                Type <strong class="text-success">+10</strong> to add stock or <strong class="text-danger">-5</strong> to remove stock. Decimals allowed: +2.5 or -0.75
                            </small>
                        </div>
                        <div id="resultPreview" class="mt-2" style="display: none;">
                            <!-- Will show: Current: 50 → New: 45 -->
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-calendar-line me-1"></i> Adjustment Date *
                        </label>
                        <input type="date" 
                               name="adjustment_date" 
                               class="form-control" 
                               value="<?php echo e(date('Y-m-d')); ?>" 
                               max="<?php echo e(date('Y-m-d')); ?>"
                               required>
                        <small class="text-muted">When did this happen?</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-message-line me-1"></i> Reason *
                        </label>
                        <input type="text" 
                               name="reason" 
                               class="form-control" 
                               placeholder="e.g., Damaged during transport, Found in old warehouse..."
                               required>
                        <small class="text-muted">Explain clearly what happened (for audit trail)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-sticky-note-line me-1"></i> Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  class="form-control" 
                                  rows="2"
                                  placeholder="Additional details, reference numbers, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize Select2 for product search
$(document).ready(function() {
    $('#productSelect').select2({
        dropdownParent: $('#createAdjustmentModal'),
        placeholder: 'Search by SKU, Name, Barcode, OE#, Supplier Code...',
        allowClear: true,
        width: '100%',
        matcher: function(params, data) {
            // If there are no search terms, return all data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Search term
            const term = params.term.toLowerCase();
            const text = data.text.toLowerCase();
            const $option = $(data.element);
            
            // Get data attributes
            const sku = ($option.data('sku') || '').toString().toLowerCase();
            const barcode = ($option.data('barcode') || '').toString().toLowerCase();
            const supplierCode = ($option.data('supplier-code') || '').toString().toLowerCase();

            // Match against multiple fields
            if (text.includes(term) || 
                sku.includes(term) || 
                barcode.includes(term) || 
                supplierCode.includes(term)) {
                return data;
            }

            // Return null if the term should not be displayed
            return null;
        }
    });
});

// Store current stock for calculation
let currentStock = 0;

// Product selection handler
$('#productSelect').on('change', function() {
    const productId = $(this).val();
    if (productId) {
        const selectedOption = $(this).find('option:selected');
        const stock = selectedOption.data('stock') || 0;
        const sku = selectedOption.data('sku') || '';
        const barcode = selectedOption.data('barcode') || '';
        const supplierCode = selectedOption.data('supplier-code') || '';
        
        currentStock = parseFloat(stock);
        
        let html = `
            <div class="alert alert-info mb-0">
                <strong><i class="ri-information-line me-1"></i> Current Stock Information:</strong>
                <div class="mt-2">
                    <span class="badge bg-primary me-2">On Hand: ${stock} units</span>
                    ${sku ? `<span class="badge bg-light text-dark me-2">SKU: ${sku}</span>` : ''}
                    ${barcode ? `<span class="badge bg-light text-dark me-2"><i class="ri-barcode-line"></i> ${barcode}</span>` : ''}
                    ${supplierCode ? `<span class="badge bg-warning-transparent me-2"><i class="ri-building-line"></i> ${supplierCode}</span>` : ''}
                </div>
            </div>
        `;
        
        document.getElementById('currentStock').innerHTML = html;
        
        // Trigger quantity preview update if qty already entered
        $('#adjustmentQty').trigger('input');
    } else {
        document.getElementById('currentStock').innerHTML = '';
        document.getElementById('resultPreview').style.display = 'none';
        currentStock = 0;
    }
});

// Quantity input handler - Show preview and update icon
$('#adjustmentQty').on('input', function() {
    let value = $(this).val().trim();
    const previewDiv = document.getElementById('resultPreview');
    const qtyIcon = document.getElementById('qtyIcon');
    
    // Auto-add + sign if user types just a number
    if (value && !value.startsWith('+') && !value.startsWith('-') && value.match(/^\d/)) {
        value = '+' + value;
        $(this).val(value);
    }
    
    // Update icon based on sign - subtle colors
    if (value.startsWith('+')) {
        qtyIcon.className = 'ri-add-circle-fill text-success fs-5';
    } else if (value.startsWith('-')) {
        qtyIcon.className = 'ri-indeterminate-circle-fill text-danger fs-5';
    } else {
        qtyIcon.className = 'ri-add-circle-line text-muted fs-5';
    }
    
    // Parse quantity for preview
    const qty = parseFloat(value);
    
    if (!isNaN(qty) && qty !== 0 && currentStock !== null) {
        const newStock = currentStock + qty;
        const isIncrease = qty > 0;
        const color = isIncrease ? 'success' : (newStock < 0 ? 'danger' : 'warning');
        const icon = isIncrease ? 'arrow-up' : 'arrow-down';
        
        let html = `
            <div class="alert alert-${color} mb-0 py-2">
                <strong>
                    <i class="ri-${icon}-line me-1"></i> Preview:
                </strong>
                <span class="ms-2">
                    Current: <strong>${currentStock.toFixed(2)}</strong> units
                    ${isIncrease ? '➕' : '➖'}
                    <strong>${Math.abs(qty).toFixed(2)}</strong>
                    → New: <strong>${newStock.toFixed(2)}</strong> units
                </span>
                ${newStock < 0 ? '<br><small><i class="ri-alert-line"></i> Warning: Stock will be NEGATIVE!</small>' : ''}
            </div>
        `;
        
        previewDiv.innerHTML = html;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
});

// Handle keyboard shortcuts
$('#adjustmentQty').on('keydown', function(e) {
    // Allow: backspace, delete, tab, escape, enter, home, end, left, right
    if ([8, 9, 27, 13, 35, 36, 37, 39, 46].indexOf(e.keyCode) !== -1 ||
        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.ctrlKey === true && [65, 67, 86, 88].indexOf(e.keyCode) !== -1) ||
        // Allow: +, -, numbers, decimal point
        (e.keyCode >= 48 && e.keyCode <= 57) || // 0-9
        (e.keyCode >= 96 && e.keyCode <= 105) || // numpad 0-9
        e.keyCode === 187 || e.keyCode === 189 || // + and - keys
        e.keyCode === 109 || e.keyCode === 107 || // numpad + and -
        e.keyCode === 110 || e.keyCode === 190) { // decimal point
        return;
    }
    // Block all other keys
    e.preventDefault();
});

document.getElementById('createAdjustmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Parse and validate quantity
    let qtyValue = document.getElementById('adjustmentQty').value.trim();
    let qty = parseFloat(qtyValue);
    
    // Check if quantity is valid
    if (isNaN(qty) || qty === 0) {
        toastr.error('Please enter a valid quantity (cannot be zero)');
        return;
    }
    
    // Ensure quantity has proper sign
    if (!qtyValue.startsWith('+') && !qtyValue.startsWith('-')) {
        qtyValue = (qty > 0 ? '+' : '') + qtyValue;
        document.getElementById('adjustmentQty').value = qtyValue;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';
    
    fetch('<?php echo e(route('stock-adjustments.store')); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            toastr.success(d.message);
            $('#createAdjustmentModal').modal('hide');
            location.reload();
        } else {
            toastr.error(d.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create Adjustment';
        }
    })
    .catch(error => {
        toastr.error('Error creating adjustment');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Create Adjustment';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/stock-adjustments/index.blade.php ENDPATH**/ ?>