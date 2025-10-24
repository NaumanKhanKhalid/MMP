<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b><?php echo e($customers->firstItem() ?? 0); ?></b> to <b><?php echo e($customers->lastItem() ?? 0); ?></b> of <b><?php echo e($customers->total()); ?></b> entries
        <i class="bi bi-arrow-right ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            
            <?php if($customers->onFirstPage()): ?>
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage(<?php echo e($customers->currentPage() - 1); ?>)">Previous</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $customers->getUrlRange(1, $customers->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="page-item <?php echo e($page == $customers->currentPage() ? 'active' : ''); ?>">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage(<?php echo e($page); ?>)"><?php echo e($page); ?></a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($customers->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage(<?php echo e($customers->currentPage() + 1); ?>)">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>


<?php /**PATH C:\xampp\htdocs\MMP\resources\views/customers/partials/pagination.blade.php ENDPATH**/ ?>