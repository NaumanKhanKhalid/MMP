<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($products->firstItem() ?? 0); ?></b> to <b><?php echo e($products->lastItem() ?? 0); ?></b> of <b><?php echo e($products->total()); ?></b> entries 
        <small class="text-muted">(Page <?php echo e($products->currentPage()); ?> of <?php echo e($products->lastPage()); ?>)</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($products->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($products->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="javascript:void(0)" 
                       data-page="<?php echo e($products->currentPage() - 1); ?>"
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadProductsPage(<?php echo e($products->currentPage() - 1); ?>); }">
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $products->currentPage() - 2);
                    $end = min($products->lastPage(), $products->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $products->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" 
                               data-page="<?php echo e($page); ?>"
                               onclick="loadProductsPage(<?php echo e($page); ?>)"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$products->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="javascript:void(0)" 
                       data-page="<?php echo e($products->currentPage() + 1); ?>"
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadProductsPage(<?php echo e($products->currentPage() + 1); ?>); }">
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/products/partials/pagination.blade.php ENDPATH**/ ?>