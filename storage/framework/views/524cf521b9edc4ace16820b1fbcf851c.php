<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($quotes->firstItem() ?? 0); ?></b> to <b><?php echo e($quotes->lastItem() ?? 0); ?></b> of <b><?php echo e($quotes->total()); ?></b> entries 
        <small class="text-muted">(Page <?php echo e($quotes->currentPage()); ?> of <?php echo e($quotes->lastPage()); ?>)</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($quotes->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($quotes->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($quotes->previousPageUrl()); ?>" 
                       <?php echo e($quotes->onFirstPage() ? 'onclick="return false;"' : ''); ?>>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $quotes->currentPage() - 2);
                    $end = min($quotes->lastPage(), $quotes->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $quotes->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($quotes->url($page)); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$quotes->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="<?php echo e($quotes->nextPageUrl()); ?>" 
                       <?php echo e(!$quotes->hasMorePages() ? 'onclick="return false;"' : ''); ?>>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/quotes/partials/pagination.blade.php ENDPATH**/ ?>