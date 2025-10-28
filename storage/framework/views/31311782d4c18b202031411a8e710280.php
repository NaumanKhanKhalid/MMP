<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($grns->firstItem() ?? 0); ?></b> to <b><?php echo e($grns->lastItem() ?? 0); ?></b> of <b><?php echo e($grns->total()); ?></b> entries 
        <small class="text-muted">(Page <?php echo e($grns->currentPage()); ?> of <?php echo e($grns->lastPage()); ?>)</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($grns->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($grns->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($grns->previousPageUrl()); ?>" 
                       <?php echo e($grns->onFirstPage() ? 'onclick="return false;"' : ''); ?>>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $grns->currentPage() - 2);
                    $end = min($grns->lastPage(), $grns->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $grns->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($grns->url($page)); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$grns->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($grns->nextPageUrl()); ?>" 
                       <?php echo e(!$grns->hasMorePages() ? 'onclick="return false;"' : ''); ?>>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/goods_receipts/partials/pagination.blade.php ENDPATH**/ ?>