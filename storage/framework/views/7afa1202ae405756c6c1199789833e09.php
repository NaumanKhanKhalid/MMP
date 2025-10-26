

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Sub-Categories</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Sub-Categories</h4>
            <button class="btn btn-info" id="openCreateSubCategoryModal">
                <i class="ri-folder-2-line me-1"></i> Add Sub-Category
            </button>
        </div>

        <!-- Sub-Categories Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration + ($categories->currentPage() - 1) * $categories->perPage()); ?></td>
                                <td>
                                    <div class="avatar avatar-sm p-1 bg-light avatar-rounded">
                                        <?php if($category->logo): ?>
                                            <img src="<?php echo e(asset($category->logo)); ?>" alt="<?php echo e($category->name); ?>" style="width:40px;height:40px;object-fit:cover;">
                                        <?php else: ?>
                                            <i class="ri-folder-2-line fs-20"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo e($category->name); ?></td>
                                <td>
                                    <span class="badge bg-success"><?php echo e($category->parent->name); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($category->products->count()); ?></span>
                                </td>
                                <td>
                                    <?php if($category->status == 'active'): ?>
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View -->
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewCategoryModal" data-id="<?php echo e($category->id); ?>" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditCategoryModal" data-id="<?php echo e($category->id); ?>" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <!-- Status Toggle -->
                                        <form method="POST" action="<?php echo e(route('toggle.category.status', $category->id)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit"
                                                class="btn btn-sm <?php echo e($category->status === 'active' ? 'btn-warning-light' : 'btn-success-light'); ?> btn-icon"
                                                title="<?php echo e($category->status === 'active' ? 'Deactivate' : 'Activate'); ?>">
                                                <i class="ri-toggle-<?php echo e($category->status === 'active' ? 'line' : 'fill'); ?>"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteCategory<?php echo e($category->id); ?>">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteCategory<?php echo e($category->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="<?php echo e(route('categories.destroy', $category->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Delete Sub-Category</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong><?php echo e($category->name); ?></strong>?</p>
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-folder-2-line fs-48 mb-2"></i>
                                    <p>No sub-categories found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer border-top-0">
                <div class="d-flex align-items-center">
                    <div>
                        <?php if($categories->total()): ?>
                            Showing <?php echo e($categories->firstItem()); ?> to <?php echo e($categories->lastItem()); ?> of <?php echo e($categories->total()); ?> entries
                        <?php endif; ?>
                    </div>
                    <div class="ms-auto">
                        <?php echo e($categories->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Category Modals -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="categoryModalContent"></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Create Sub-Category
            $('#openCreateSubCategoryModal').on('click', function() {
                $.get("<?php echo e(route('categories.create-subcategory-modal')); ?>", function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });

            // View Category
            $(document).on('click', '.openViewCategoryModal', function() {
                var id = $(this).data('id');
                $.get("<?php echo e(route('categories.view-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });

            // Edit Category
            $(document).on('click', '.openEditCategoryModal', function() {
                var id = $(this).data('id');
                $.get("<?php echo e(route('categories.edit-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });
        });

       
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/categories/sub_categories.blade.php ENDPATH**/ ?>