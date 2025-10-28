<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-file-text-line me-2"></i> Purchase Order #<?php echo e($purchaseOrder->po_number); ?>

        <?php
            $statusClasses = [
                'draft' => 'bg-secondary',
                'approved' => 'bg-info',
                'sent' => 'bg-info',
                'partially_received' => 'bg-warning',
                'closed' => 'bg-success',
                'completed' => 'bg-success',
                'cancelled' => 'bg-danger'
            ];
            $statusClass = $statusClasses[$purchaseOrder->status] ?? 'bg-secondary';
        ?>
        <span class="badge <?php echo e($statusClass); ?> ms-2"><?php echo e(ucfirst(str_replace('_', ' ', $purchaseOrder->status))); ?></span>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
    
    <!-- Supplier & PO Info Side by Side -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-primary-transparent py-2">
                    <h6 class="mb-0"><i class="ri-building-line me-1"></i>Supplier Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="120">Name:</td>
                            <td><?php echo e($purchaseOrder->supplier->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Email:</td>
                            <td><?php echo e($purchaseOrder->supplier->email ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Phone:</td>
                            <td><?php echo e($purchaseOrder->supplier->phone ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Address:</td>
                            <td><?php echo e($purchaseOrder->supplier->address ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-file-info-line me-1"></i>Purchase Order Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="140">PO Number:</td>
                            <td><span class="badge bg-primary"><?php echo e($purchaseOrder->po_number); ?></span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Order Date:</td>
                            <td><?php echo e($purchaseOrder->order_date->format('d M Y')); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Expected Delivery:</td>
                            <td>
                                <?php echo e($purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('d M Y') : 'Not set'); ?>

                                <?php if($purchaseOrder->expected_delivery_date && $purchaseOrder->expected_delivery_date < now() && !in_array($purchaseOrder->status, ['completed', 'cancelled'])): ?>
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if($purchaseOrder->received_date): ?>
                        <tr>
                            <td class="fw-semibold">Received Date:</td>
                            <td><?php echo e($purchaseOrder->received_date->format('d M Y')); ?></td>
                        </tr>
                    <?php endif; ?>
                        <tr>
                            <td class="fw-semibold">Created By:</td>
                            <td><?php echo e($purchaseOrder->user->name ?? 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <?php if($purchaseOrder->delivery_address || $purchaseOrder->payment_terms): ?>
    <div class="row mb-3">
        <?php if($purchaseOrder->delivery_address): ?>
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-info-transparent py-2">
                    <h6 class="mb-0"><i class="ri-map-pin-line me-1"></i>Delivery Address</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($purchaseOrder->delivery_address); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($purchaseOrder->payment_terms): ?>
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-warning-transparent py-2">
                    <h6 class="mb-0"><i class="ri-file-list-2-line me-1"></i>Payment Terms</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($purchaseOrder->payment_terms); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Purchase Order Items Table -->
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="ri-shopping-cart-line me-1"></i>Order Items</h6>
            <span class="badge bg-primary"><?php echo e($purchaseOrder->items->count()); ?> items</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">Product</th>
                            <th width="15%" class="text-center">Quantity</th>
                            <th width="15%" class="text-end">Unit Price</th>
                            <th width="15%" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $purchaseOrder->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($item->product->name ?? 'Product not found'); ?></div>
                                <small class="text-muted">SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark"><?php echo e($item->quantity); ?></span>
                            </td>
                            <td class="text-end">R <?php echo e(number_format($item->unit_price, 2)); ?></td>
                            <td class="text-end fw-semibold">R <?php echo e(number_format($item->total, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="row">
        <div class="col-md-7">
            <?php if($purchaseOrder->notes): ?>
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-secondary-transparent py-2">
                    <h6 class="mb-0"><i class="ri-sticky-note-line me-1"></i>Notes</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($purchaseOrder->notes); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-5">
            <div class="card border shadow-sm">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-calculator-line me-1"></i>Order Summary</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-end fw-semibold">Subtotal:</td>
                            <td class="text-end" width="120">R <?php echo e(number_format($purchaseOrder->subtotal, 2)); ?></td>
                        </tr>
                        <?php if($purchaseOrder->total_discount > 0): ?>
                        <tr>
                            <td class="text-end text-danger fw-semibold">Discount:</td>
                            <td class="text-end text-danger">- R <?php echo e(number_format($purchaseOrder->total_discount, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($purchaseOrder->shipping > 0): ?>
                        <tr>
                            <td class="text-end fw-semibold">Shipping:</td>
                            <td class="text-end">R <?php echo e(number_format($purchaseOrder->shipping, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($purchaseOrder->vat_enabled && $purchaseOrder->vat > 0): ?>
                        <tr>
                            <td class="text-end fw-semibold">VAT:</td>
                            <td class="text-end">R <?php echo e(number_format($purchaseOrder->vat, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr class="table-success">
                            <td class="text-end fw-bold fs-16">Grand Total:</td>
                            <td class="text-end fw-bold fs-16">R <?php echo e(number_format($purchaseOrder->grand_total, 2)); ?></td>
                            </tr>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-primary" onclick="printPurchaseOrder(<?php echo e($purchaseOrder->id); ?>)">
        <i class="ri-printer-line me-1"></i> Print
    </button>
</div>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/purchase_orders/partials/view_modal.blade.php ENDPATH**/ ?>