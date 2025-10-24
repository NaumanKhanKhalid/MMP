<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-user-line me-2"></i> Customer Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
    <!-- Customer Header -->
    <div class="bg-light p-3 rounded mb-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                        <h4 class="mb-1"><?php echo e($customer->display_name); ?></h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary"><?php echo e($customer->customer_code); ?></span>
                    <span class="badge bg-<?php echo e($customer->customer_type === 'business' ? 'success' : 'secondary'); ?>">
                                <?php echo e(ucfirst($customer->customer_type)); ?>

                            </span>
                    <span class="badge bg-<?php echo e($customer->customer_status === 'active' ? 'success' : 'secondary'); ?>">
                                <?php echo e(ucfirst($customer->customer_status)); ?>

                            </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <small class="text-muted">Customer Since</small><br>
                <strong><?php echo e($customer->created_at->format('d M Y')); ?></strong>
            </div>
        </div>
</div>

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-user-line me-2"></i> Basic Information</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Name:</td>
                    <td><?php echo e($customer->name); ?></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Customer Code:</td>
                    <td><span class="badge bg-primary"><?php echo e($customer->customer_code); ?></span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Type:</td>
                    <td>
                        <span class="badge bg-<?php echo e($customer->customer_type === 'business' ? 'success' : 'secondary'); ?>">
                            <?php echo e(ucfirst($customer->customer_type)); ?>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Status:</td>
                    <td>
                        <span class="badge bg-<?php echo e($customer->customer_status === 'active' ? 'success' : 'secondary'); ?>">
                            <?php echo e(ucfirst($customer->customer_status)); ?>

                        </span>
                    </td>
                </tr>
                <?php if($customer->isBusiness() && $customer->company_name): ?>
                <tr>
                    <td class="fw-bold text-muted">Company:</td>
                    <td><?php echo e($customer->company_name); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($customer->isBusiness() && $customer->contact_person): ?>
                <tr>
                    <td class="fw-bold text-muted">Contact Person:</td>
                    <td><?php echo e($customer->contact_person); ?></td>
                </tr>
                <?php endif; ?>
            </table>

            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-phone-line me-2"></i> Contact Details</h6>
            
            <table class="table table-sm table-borderless">
                <?php if($customer->email): ?>
                <tr>
                    <td width="40%" class="fw-bold text-muted">Email:</td>
                    <td><i class="ri-mail-line me-2"></i><?php echo e($customer->email); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($customer->phone): ?>
                <tr>
                    <td class="fw-bold text-muted">Phone:</td>
                    <td><i class="ri-phone-line me-2"></i><?php echo e($customer->phone); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($customer->address): ?>
                <tr>
                    <td class="fw-bold text-muted">Address:</td>
                    <td><i class="ri-map-pin-line me-2"></i><?php echo e($customer->address); ?></td>
                </tr>
                        <?php endif; ?>
                <?php if($customer->city): ?>
                <tr>
                    <td class="fw-bold text-muted">City:</td>
                    <td><?php echo e($customer->city); ?></td>
                </tr>
                        <?php endif; ?>
            </table>

            <!-- Vehicles Section -->
            <?php if($customer->vehicles()->count() > 0): ?>
            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-car-line me-2"></i> Vehicles</h6>
            
            <?php $__currentLoopData = $customer->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border rounded p-3 mb-2 <?php echo e($vehicle->is_primary ? 'border-success' : ''); ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">
                        <?php echo e($vehicle->make->name ?? 'N/A'); ?> <?php echo e($vehicle->model->name ?? ''); ?>

                        <?php if($vehicle->year): ?>
                            <small class="text-muted">(<?php echo e($vehicle->year); ?>)</small>
                        <?php endif; ?>
                    </h6>
                    <?php if($vehicle->is_primary): ?>
                        <span class="badge bg-success-transparent">Primary</span>
                    <?php endif; ?>
                </div>
                
                <table class="table table-sm table-borderless mb-0">
                    <?php if($vehicle->engine): ?>
                    <tr>
                        <td width="40%" class="fw-bold text-muted small">Engine:</td>
                        <td class="small"><?php echo e($vehicle->engine); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($vehicle->registration_number): ?>
                    <tr>
                        <td class="fw-bold text-muted small">Registration:</td>
                        <td class="small"><?php echo e($vehicle->registration_number); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($vehicle->vin_number): ?>
                    <tr>
                        <td class="fw-bold text-muted small">VIN:</td>
                        <td class="small"><?php echo e($vehicle->vin_number); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($vehicle->color): ?>
                    <tr>
                        <td class="fw-bold text-muted small">Color:</td>
                        <td class="small"><?php echo e($vehicle->color); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($vehicle->mileage): ?>
                    <tr>
                        <td class="fw-bold text-muted small">Mileage:</td>
                        <td class="small"><?php echo e($vehicle->mileage); ?> km</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-bank-card-line me-2"></i> Account Details</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Customer Type:</td>
                    <td>
                        <span class="badge bg-<?php echo e($customer->customer_type === 'business' ? 'success' : 'secondary'); ?>">
                            <?php echo e(ucfirst($customer->customer_type)); ?>

                        </span>
                    </td>
                </tr>
                <?php if($customer->customer_type === 'credit' && $customer->credit_limit > 0): ?>
                <tr>
                    <td class="fw-bold text-muted">Credit Limit:</td>
                    <td>
                            <span class="text-success fw-bold">R<?php echo e(number_format($customer->credit_limit, 2)); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Current Balance:</td>
                    <td>
                        <span class="fw-bold <?php echo e($customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted')); ?>">
                            R<?php echo e(number_format($customer->balance, 2)); ?>

                        </span>
                        <?php if($customer->isOverCreditLimit()): ?>
                            <br><small class="text-danger"><i class="ri-alert-line me-1"></i>Over credit limit!</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Available Credit:</td>
                    <td><span class="text-info fw-bold">R<?php echo e(number_format($customer->available_credit, 2)); ?></span></td>
                </tr>
                        <?php endif; ?>
            </table>

            <?php if($customer->notes): ?>
            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-file-text-line me-2"></i> Notes</h6>
            <div class="bg-light p-3 rounded">
                <p class="mb-0"><?php echo e($customer->notes); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditCustomerModal" data-id="<?php echo e($customer->id); ?>">
        <i class="ri-pencil-line me-1"></i> Edit Customer
    </button>
</div><?php /**PATH C:\xampp\htdocs\MMP\resources\views/customers/partials/view_modal.blade.php ENDPATH**/ ?>