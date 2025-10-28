<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Stock Counts</h4>
            <p class="fs-13 text-muted mb-0">Physical stock count management</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="openCreateStockCountModal()">
                <i class="bi bi-plus-circle me-1"></i>New Stock Count
            </button>
        </div>
    </div>

    <!-- Stock Counts Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">All Stock Counts</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Count #</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Filters</th>
                                    <th>Progress</th>
                                    <th>Variances</th>
                                    <th>Variance Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $counts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold"><?php echo e($count->count_number); ?></span>
                                    </td>
                                    <td><?php echo e($count->count_name); ?></td>
                                    <td><?php echo e($count->count_date->format('d M Y')); ?></td>
                                    <td>
                                        <?php if($count->category): ?>
                                            <span class="badge bg-info-transparent"><?php echo e($count->category->name); ?></span>
                                        <?php endif; ?>
                                        <?php if($count->brand): ?>
                                            <span class="badge bg-primary-transparent"><?php echo e($count->brand->name); ?></span>
                                        <?php endif; ?>
                                        <?php if($count->bin_location): ?>
                                            <span class="badge bg-secondary-transparent"><?php echo e($count->bin_location); ?></span>
                                        <?php endif; ?>
                                        <?php if(!$count->category && !$count->brand && !$count->bin_location): ?>
                                            <span class="text-muted">All Products</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?php echo e($count->progress_percentage); ?>%"
                                                 aria-valuenow="<?php echo e($count->progress_percentage); ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?php echo e($count->counted_products); ?>/<?php echo e($count->total_products); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($count->products_with_variance > 0): ?>
                                            <span class="badge bg-warning"><?php echo e($count->products_with_variance); ?> items</span>
                                        <?php else: ?>
                                            <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($count->total_variance_value != 0): ?>
                                            <span class="fw-semibold <?php echo e($count->total_variance_value > 0 ? 'text-success' : 'text-danger'); ?>">
                                                R <?php echo e(number_format(abs($count->total_variance_value), 2)); ?>

                                                <i class="ri-arrow-<?php echo e($count->total_variance_value > 0 ? 'up' : 'down'); ?>-line"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">R 0.00</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($count->status === 'draft'): ?>
                                            <span class="badge bg-secondary">Draft</span>
                                        <?php elseif($count->status === 'in_progress'): ?>
                                            <span class="badge bg-primary">In Progress</span>
                                        <?php elseif($count->status === 'completed'): ?>
                                            <span class="badge bg-warning">Completed</span>
                                        <?php elseif($count->status === 'posted'): ?>
                                            <span class="badge bg-success">Posted</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <?php if($count->canEdit()): ?>
                                                <button type="button" class="btn btn-sm btn-primary-light btn-icon" onclick="openCountPage(<?php echo e($count->id); ?>)" title="Start Counting">
                                                    <i class="bi bi-calculator"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if($count->isCompleted() || $count->isPosted()): ?>
                                                <button type="button" class="btn btn-sm btn-info-light btn-icon" onclick="viewVarianceReport(<?php echo e($count->id); ?>)" title="Variance Report">
                                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if($count->canPost()): ?>
                                                <button type="button" class="btn btn-sm btn-success-light btn-icon" onclick="postCount(<?php echo e($count->id); ?>)" title="Post Adjustments">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if(!$count->isPosted() && !$count->isCancelled()): ?>
                                                <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="cancelCount(<?php echo e($count->id); ?>)" title="Cancel Count">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ri-inbox-line fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No stock counts found</p>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateStockCountModal()">
                                            <i class="bi bi-plus-circle me-1"></i>Create First Stock Count
                                        </button>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($counts->hasPages()): ?>
                <div class="card-footer">
                    <?php echo e($counts->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Stock Count Modal -->
<div class="modal fade" id="createStockCountModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="createStockCountModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Global function for submitting stock count form
window.submitStockCountForm = function() {
    const form = document.getElementById('createStockCountForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form || !submitBtn) {
        console.error('Form or button not found');
        return;
    }
    
    // Validate
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';
    
    // Get form data
    const formData = new FormData(form);
    
    // Submit
    fetch('<?php echo e(route("stock-counts.store")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modalEl = document.getElementById('createStockCountModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            
            // Success message
            toastr.success(data.message);
            
            // Redirect
            setTimeout(() => {
                window.location.href = '<?php echo e(route("stock-counts.count", ":id")); ?>'.replace(':id', data.count_id);
            }, 500);
        } else {
            toastr.error(data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-add-line me-1"></i> Create Stock Count';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error creating stock count');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-add-line me-1"></i> Create Stock Count';
    });
};

// Open create stock count modal
function openCreateStockCountModal() {
    const url = '<?php echo e(route("stock-counts.create")); ?>';
    
    // Show loading
    document.getElementById('createStockCountModalContent').innerHTML = `
        <div class="modal-header">
            <h5 class="modal-title"><i class="ri-file-add-line me-2"></i> New Stock Count</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading form...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('createStockCountModal'));
    modal.show();
    
    // Fetch content with AJAX headers
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
        .then(response => response.text())
        .then(html => {
            document.getElementById('createStockCountModalContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('createStockCountModalContent').innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="ri-error-warning-line me-2"></i> Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0">
                        <i class="ri-close-circle-line me-2"></i> Error loading form. Please try again.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            `;
        });
}

// Open counting page (redirects for now - can be modal later)
function openCountPage(id) {
    const url = '<?php echo e(route("stock-counts.count", ":id")); ?>'.replace(':id', id);
    window.location.href = url;
}

// View variance report (redirects for now - can be modal later)
function viewVarianceReport(id) {
    const url = '<?php echo e(route("stock-counts.variance-report", ":id")); ?>'.replace(':id', id);
    window.location.href = url;
}

// Post stock count
function postCount(id) {
    if (!confirm('Are you sure you want to post this stock count? This will create adjustments and update stock levels.')) {
        return;
    }

    const url = '<?php echo e(route("stock-counts.post", ":id")); ?>'.replace(':id', id);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error posting stock count');
    });
}

// Cancel stock count
function cancelCount(id) {
    if (!confirm('Are you sure you want to cancel this stock count?')) {
        return;
    }

    const url = '<?php echo e(route("stock-counts.cancel", ":id")); ?>'.replace(':id', id);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error cancelling stock count');
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/stock-counts/index.blade.php ENDPATH**/ ?>