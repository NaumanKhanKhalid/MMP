<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Create Stock Count</h4>
            <p class="fs-13 text-muted mb-0">Start a new physical stock count</p>
        </div>
        <div>
            <a href="<?php echo e(route('stock-counts.index')); ?>" class="btn btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Stock Count Details</div>
                </div>
                <form id="createStockCountForm">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Count Name -->
                            <div class="col-md-12">
                                <label class="form-label">Count Name <span class="text-danger">*</span></label>
                                <input type="text" name="count_name" class="form-control" placeholder="e.g., Monthly Count - October 2025" required>
                                <small class="text-muted">Give this count a descriptive name</small>
                            </div>

                            <!-- Count Date -->
                            <div class="col-md-6">
                                <label class="form-label">Count Date <span class="text-danger">*</span></label>
                                <input type="date" name="count_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>

                            <!-- Filters Header -->
                            <div class="col-md-12">
                                <hr>
                                <h6 class="mb-3"><i class="ri-filter-line me-2"></i> Filters (Optional)</h6>
                                <p class="text-muted small">Apply filters to count specific products only. Leave blank to count all products.</p>
                            </div>

                            <!-- Category Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Brand Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">All Brands</option>
                                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($brand->id); ?>"><?php echo e($brand->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Bin Location Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Bin Location</label>
                                <input type="text" name="bin_location" class="form-control" placeholder="e.g., A-16">
                                <small class="text-muted">Filter by bin/aisle location</small>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes about this count..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="<?php echo e(route('stock-counts.index')); ?>" class="btn btn-light me-2">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="ri-add-line me-1"></i> Create Stock Count
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('createStockCountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';

    const formData = new FormData(this);
    
    const url = '<?php echo e(route("stock-counts.store")); ?>';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            const url = '<?php echo e(route("stock-counts.count", ":id")); ?>'.replace(':id', data.count_id);
            window.location.href = url;
        } else {
            alert(data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-add-line me-1"></i> Create Stock Count';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating stock count');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-add-line me-1"></i> Create Stock Count';
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/stock-counts/create.blade.php ENDPATH**/ ?>