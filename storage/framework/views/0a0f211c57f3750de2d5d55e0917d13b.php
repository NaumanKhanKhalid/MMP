<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($purchaseOrders->firstItem() ?? 0); ?></b> to <b><?php echo e($purchaseOrders->lastItem() ?? 0); ?></b> of <b><?php echo e($purchaseOrders->total()); ?></b> entries 
        <small class="text-muted">(Page <?php echo e($purchaseOrders->currentPage()); ?> of <?php echo e($purchaseOrders->lastPage()); ?>)</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($purchaseOrders->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($purchaseOrders->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($purchaseOrders->previousPageUrl()); ?>" 
                       <?php echo e($purchaseOrders->onFirstPage() ? 'onclick="return false;"' : ''); ?>>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $purchaseOrders->currentPage() - 2);
                    $end = min($purchaseOrders->lastPage(), $purchaseOrders->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $purchaseOrders->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($purchaseOrders->url($page)); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$purchaseOrders->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($purchaseOrders->nextPageUrl()); ?>" 
                       <?php echo e(!$purchaseOrders->hasMorePages() ? 'onclick="return false;"' : ''); ?>>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/purchase_orders/partials/pagination.blade.php ENDPATH**/ ?>