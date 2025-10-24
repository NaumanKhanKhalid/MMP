<?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="clickable-row" style="cursor: pointer;" data-id="<?php echo e($customer->id); ?>">
        <td><?php echo e($loop->iteration + ($customers->currentPage() - 1) * $customers->perPage()); ?></td>
        <td>
            <span class="badge bg-info-transparent"><?php echo e($customer->customer_code); ?></span>
        </td>
        <td>
            <div>
                <strong><?php echo e($customer->display_name); ?></strong>
                <?php if($customer->isBusiness() && $customer->company_name): ?>
                    <br><small class="text-muted"><?php echo e($customer->name); ?></small>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <span class="badge bg-<?php echo e($customer->customer_category === 'business' ? 'primary' : 'secondary'); ?>-transparent">
                <?php echo e(ucfirst($customer->customer_category)); ?>

            </span>
            <br>
            <small class="text-muted">
                <span class="badge bg-<?php echo e($customer->customer_type === 'credit' ? 'warning' : 'success'); ?>-transparent">
                    <?php echo e(ucfirst($customer->customer_type)); ?>

                </span>
            </small>
        </td>
        <td>
            <div>
                <?php if($customer->email): ?>
                    <span class="d-block mb-1"><i class="ri-mail-line me-2 align-middle fs-14 text-muted"></i><?php echo e($customer->email); ?></span>
                <?php endif; ?>
                <?php if($customer->phone): ?>
                    <span class="d-block"><i class="ri-phone-line me-2 align-middle fs-14 text-muted"></i><?php echo e($customer->phone); ?></span>
                <?php endif; ?>
                <?php if($customer->contact_person): ?>
                    <small class="text-muted"><?php echo e($customer->contact_person); ?></small>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <span class="badge bg-<?php echo e($customer->terms === 'on_account' ? 'warning' : 'success'); ?>-transparent">
                <?php echo e(ucfirst($customer->terms)); ?>

            </span>
        </td>
        <td>
            <?php if($customer->credit_limit > 0): ?>
                <span class="text-success">R<?php echo e(number_format($customer->credit_limit, 2)); ?></span>
            <?php else: ?>
                <span class="text-muted">No limit</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="<?php echo e($customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted')); ?>">
                R<?php echo e(number_format($customer->balance, 2)); ?>

            </span>
            <?php if($customer->isOverCreditLimit()): ?>
                <br><small class="text-danger">Over limit!</small>
            <?php endif; ?>
        </td>
        <td>
            <?php if($customer->customer_status === 'active'): ?>
                <span class="badge rounded-pill bg-success-transparent">Active</span>
            <?php elseif($customer->customer_status === 'inactive'): ?>
                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
            <?php elseif($customer->customer_status === 'suspended'): ?>
                <span class="badge rounded-pill bg-danger-transparent">Suspended</span>
            <?php endif; ?>
        </td>
        <td class="text-end">
            <div class="btn-list">
                <!-- Toggle Status -->
                <form action="<?php echo e(route('customers.toggle-status', $customer)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-sm <?php echo e($customer->customer_status === 'active' ? 'btn-warning-light' : ($customer->customer_status === 'inactive' ? 'btn-success-light' : 'btn-danger-light')); ?> btn-icon" title="<?php echo e($customer->customer_status === 'active' ? 'Deactivate' : ($customer->customer_status === 'inactive' ? 'Activate' : 'Unsuspend')); ?>">
                        <i class="ri-toggle-<?php echo e($customer->customer_status === 'active' ? 'line' : 'fill'); ?>"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon openViewCustomerModal" data-id="<?php echo e($customer->id); ?>" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon openEditCustomerModal" data-id="<?php echo e($customer->id); ?>" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon openDeleteCustomerModal" data-id="<?php echo e($customer->id); ?>" data-name="<?php echo e($customer->display_name); ?>" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="9" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-user-unfollow-line fs-48 mb-2"></i>
                <p class="mb-0">No customers found</p>
            </div>
        </td>
    </tr>
<?php endif; ?>


<?php /**PATH C:\xampp\htdocs\MMP\resources\views/customers/partials/table.blade.php ENDPATH**/ ?>