<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($suppliers->firstItem() ?? 0); ?></b> to <b><?php echo e($suppliers->lastItem() ?? 0); ?></b> of <b><?php echo e($suppliers->total()); ?></b> entries <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            <?php if($suppliers->hasPages()): ?>
                <!-- Previous Button -->
                <li class="page-item <?php echo e($suppliers->onFirstPage() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="javascript:void(0)" 
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadSuppliersPage(<?php echo e($suppliers->currentPage() - 1); ?>); }">
                        Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                    $start = max(1, $suppliers->currentPage() - 2);
                    $end = min($suppliers->lastPage(), $suppliers->currentPage() + 2);
                ?>
                
                <?php for($page = $start; $page <= $end; $page++): ?>
                    <?php if($page == $suppliers->currentPage()): ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" 
                               onclick="loadSuppliersPage(<?php echo e($page); ?>)"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?php echo e(!$suppliers->hasMorePages() ? 'disabled' : ''); ?>">
                    <a class="page-link" href="javascript:void(0)" 
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadSuppliersPage(<?php echo e($suppliers->currentPage() + 1); ?>); }">
                        Next
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/suppliers/partials/pagination.blade.php ENDPATH**/ ?>