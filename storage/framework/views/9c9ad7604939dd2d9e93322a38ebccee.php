<?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="supplier-row" data-id="<?php echo e($supplier->id); ?>" style="cursor: pointer;">
        <td><?php echo e($loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage()); ?></td>
        <td>
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                    <i class="ri-truck-line text-primary"></i>
                </div>
                <div>
                    <div class="fw-semibold"><?php echo e($supplier->name); ?></div>
                    <?php if($supplier->contact_person): ?>
                        <small class="text-muted"><?php echo e($supplier->contact_person); ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </td>
        <td>
            <span class="badge bg-<?php echo e($supplier->supplier_type === 'company' ? 'primary' : 'secondary'); ?>-transparent">
                <?php echo e(ucfirst($supplier->supplier_type)); ?>

            </span>
        </td>
        <td>
            <div>
                <?php if($supplier->email): ?>
                    <div class="d-flex align-items-center mb-1">
                        <i class="ri-mail-line me-2 text-muted"></i>
                        <small><?php echo e($supplier->email); ?></small>
                    </div>
                <?php endif; ?>
                <?php if($supplier->phone): ?>
                    <div class="d-flex align-items-center">
                        <i class="ri-phone-line me-2 text-muted"></i>
                        <small><?php echo e($supplier->phone); ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <span class="badge bg-warning-transparent"><?php echo e($supplier->payment_terms ?? 'N/A'); ?></span>
        </td>
        <td>
            <?php if($supplier->credit_limit > 0): ?>
                <span class="text-success fw-semibold">R<?php echo e(number_format($supplier->credit_limit, 2)); ?></span>
            <?php else: ?>
                <span class="text-muted">No limit</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="<?php echo e($supplier->balance < 0 ? 'text-danger' : ($supplier->balance > 0 ? 'text-success' : 'text-muted')); ?> fw-semibold">
                R<?php echo e(number_format($supplier->balance, 2)); ?>

            </span>
            <?php if($supplier->isOverCreditLimit()): ?>
                <br><small class="text-danger">Over limit!</small>
            <?php endif; ?>
        </td>
                                        <td>
                                            <?php if($supplier->status == 'active'): ?>
                                                <span class="badge rounded-pill bg-success-transparent">Active</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                            <?php endif; ?>
                                        </td>
        <td class="text-end">
            <div class="btn-list">
                <!-- Toggle Status -->
                <form method="POST" action="<?php echo e(route('suppliers.toggle.status', $supplier->id)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-sm <?php echo e($supplier->status === 'active' ? 'btn-warning-light' : 'btn-success-light'); ?> btn-icon" title="<?php echo e($supplier->status === 'active' ? 'Deactivate' : 'Activate'); ?>">
                        <i class="ri-toggle-<?php echo e($supplier->status === 'active' ? 'line' : 'fill'); ?>"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon openViewSupplierModal" data-id="<?php echo e($supplier->id); ?>" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon openEditSupplierModal" data-id="<?php echo e($supplier->id); ?>" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon openDeleteSupplierModal" data-id="<?php echo e($supplier->id); ?>" data-name="<?php echo e($supplier->name); ?>" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="10" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-truck-line fs-48 text-muted mb-2"></i>
                <h6>No suppliers found</h6>
                <p class="text-muted mb-0">Start by adding your first supplier</p>
            </div>
        </td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/suppliers/partials/table.blade.php ENDPATH**/ ?>