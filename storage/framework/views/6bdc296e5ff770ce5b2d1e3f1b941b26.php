<?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="clickable-row" onclick="openViewModal('<?php echo e($p->id); ?>')" style="cursor: pointer;">
        <td><?php echo e($loop->iteration + ($products->currentPage() - 1) * $products->perPage()); ?></td>

        
        <td>
            <div class="d-flex">
                <span class="avatar avatar-md avatar-square bg-primary-transparent p-1">
                    <img src="<?php echo e($p->primary_image_url); ?>" class="w-100 h-100" alt="<?php echo e($p->name); ?>">
                </span>
                <div class="ms-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center">
                        <a href="<?php echo e(route('products.show', $p->id)); ?>"><?php echo e($p->name); ?></a>
                    </p>
                    <p class="fs-12 text-muted mb-0">SKU: <?php echo e($p->sku); ?></p>
                </div>
            </div>
        </td>

        
        <td>
            <?php if($p->supplier_code): ?>
                <span class="badge bg-info-transparent"><?php echo e($p->supplier_code); ?></span>
            <?php else: ?>
                <span class="text-muted">-</span>
            <?php endif; ?>
        </td>

        
        <td>
            <?php
                $lastBatch = $p->stockBatches->sortByDesc('received_date')->first();
                $lastCost = $lastBatch ? $lastBatch->landed_unit_cost : 0;
            ?>
            <span class="text-dark">R <?php echo e(number_format($lastCost, 2)); ?></span>
        </td>

        
        <td>
            <?php 
                $onHand = $p->on_hand_sum ?? 0;
                $actualStock = $p->actual_stock_sum ?? 0;
                $reserved = $p->reserved ?? 0;
                $available = $onHand - $reserved;
            ?>
            
            <?php if($actualStock < 0): ?>
                <span class="badge bg-danger text-white rounded-pill" title="Negative Stock (from ledger)">
                    <?php echo e($actualStock); ?>

                </span>
            <?php elseif($onHand < 0): ?>
                <span class="badge bg-danger-light rounded-pill" title="Negative Stock (from batches)">
                    <?php echo e($onHand); ?>

                </span>
            <?php elseif($onHand == 0): ?>
                <span class="badge bg-warning-light text-dark rounded-pill">0</span>
            <?php elseif($onHand <= $p->reorder_level): ?>
                <span class="badge bg-info-light text-dark rounded-pill"><?php echo e($onHand); ?></span>
            <?php else: ?>
                <span class="badge bg-success-transparent rounded-pill"><?php echo e($onHand); ?></span>
            <?php endif; ?>
            
            <?php if($reserved > 0): ?>
                <br><small class="text-warning" style="font-size: 9px;">Reserved: <?php echo e($reserved); ?></small>
            <?php endif; ?>
            
            <?php if($available > 0 && $available < $onHand): ?>
                <br><small class="text-success" style="font-size: 9px;">Available: <?php echo e($available); ?></small>
            <?php endif; ?>
            
            <?php if($actualStock != $onHand && $actualStock < 0): ?>
                <br><small class="text-danger" style="font-size: 9px;">(Ledger: <?php echo e($actualStock); ?>)</small>
            <?php endif; ?>
        </td>

        
        <td>R <?php echo e(number_format($p->price_normal, 2)); ?></td>

        
        <td>R <?php echo e(number_format($p->price_online, 2)); ?></td>

        
        <td>R <?php echo e(number_format($p->price_workshop, 2)); ?></td>

        
        <td>
            <?php if($p->oeNumbers && $p->oeNumbers->count() > 0): ?>
                <div class="d-flex flex-wrap gap-1">
                    <?php $__currentLoopData = $p->oeNumbers->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-primary-transparent rounded-pill" title="<?php echo e($oe->oe_number); ?>">
                            <?php echo e($oe->oe_number); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($p->oeNumbers->count() > 2): ?>
                        <span class="badge bg-secondary-transparent rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="<?php echo e($p->oeNumbers->skip(2)->pluck('oe_number')->implode(', ')); ?>">
                            +<?php echo e($p->oeNumbers->count() - 2); ?>

                        </span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <span class="text-muted">-</span>
            <?php endif; ?>
        </td>

        
        <td>
            <?php if($p->crossRefs && $p->crossRefs->count() > 0): ?>
                <div class="d-flex flex-wrap gap-1">
                    <?php $__currentLoopData = $p->crossRefs->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cross): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-info-transparent rounded-pill" title="<?php echo e($cross->cross_ref); ?>">
                            <?php echo e($cross->cross_ref); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($p->crossRefs->count() > 2): ?>
                        <span class="badge bg-secondary-transparent rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="<?php echo e($p->crossRefs->skip(2)->pluck('cross_ref')->implode(', ')); ?>">
                            +<?php echo e($p->crossRefs->count() - 2); ?>

                        </span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <span class="text-muted">-</span>
            <?php endif; ?>
        </td>

        
        <td>
            <?php if($p->status === 'active'): ?>
                <span class="badge rounded-pill bg-success-transparent">Active</span>
            <?php else: ?>
                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
            <?php endif; ?>
        </td>
        
        <td class="text-end">
            <div class="btn-list">
                <!-- Toggle Status -->
                <form method="POST" action="<?php echo e(route('products.toggleStatus', $p->id)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-sm <?php echo e($p->status === 'active' ? 'btn-warning-light' : 'btn-success-light'); ?> btn-icon"
                        title="<?php echo e($p->status === 'active' ? 'Deactivate' : 'Activate'); ?>">
                        <i class="ri-toggle-<?php echo e($p->status === 'active' ? 'line' : 'fill'); ?>"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#viewProductModal-<?php echo e($p->id); ?>" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#editProductModal-<?php echo e($p->id); ?>" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Print Barcode -->
                <button class="btn btn-sm btn-info-light btn-icon" 
                    onclick="printSingleBarcode(<?php echo e($p->id); ?>, '<?php echo e($p->name); ?>', '<?php echo e($p->sku); ?>', '<?php echo e($p->barcode_primary); ?>')" 
                    title="Print Barcode">
                    <i class="ri-printer-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#deleteProduct<?php echo e($p->id); ?>" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
    
    
    <?php echo $__env->make('products._view_modal', ['product' => $p], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php echo $__env->make('products._edit_modal', [
        'product' => $p,
        'brands' => $brands,
        'categories' => $categories,
        'subCategories' => $subCategories,
        'makes' => $makes,
        'models' => $models,
        'engines' => $engines,
        'suppliers' => $suppliers
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <div class="modal fade" id="deleteProduct<?php echo e($p->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('products.destroy', $p->id)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete product <strong><?php echo e($p->name); ?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="12" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-inbox-line fs-48 text-muted mb-2"></i>
                <h6>No products found</h6>
                <p class="text-muted mb-0">Try adjusting your filters or add a new product</p>
            </div>
        </td>
    </tr>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/products/partials/table.blade.php ENDPATH**/ ?>