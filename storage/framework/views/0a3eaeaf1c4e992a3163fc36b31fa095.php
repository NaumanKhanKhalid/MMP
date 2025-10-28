<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($invoices->firstItem() ?? 0); ?></b> to <b><?php echo e($invoices->lastItem() ?? 0); ?></b> of <b><?php echo e($invoices->total()); ?></b> entries 
        <small class="text-muted">(Page <?php echo e($invoices->currentPage()); ?> of <?php echo e($invoices->lastPage()); ?>)</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($invoices->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($invoices->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($invoices->previousPageUrl()); ?>" 
                       <?php echo e($invoices->onFirstPage() ? 'onclick="return false;"' : ''); ?>>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $invoices->currentPage() - 2);
                    $end = min($invoices->lastPage(), $invoices->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $invoices->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($invoices->url($page)); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$invoices->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($invoices->nextPageUrl()); ?>" 
                       <?php echo e(!$invoices->hasMorePages() ? 'onclick="return false;"' : ''); ?>>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/invoices/partials/pagination.blade.php ENDPATH**/ ?>