<?php $__env->startSection('title', 'Users'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Users</h4>
            <button class="btn btn-primary" id="openCreateUserModal">
                <i class="ri-add-line me-1"></i> Add User
            </button>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td>
                                    <div>
                                        <span class="d-block mb-1"><i
                                                class="ri-mail-line me-2 align-middle fs-14 text-muted"></i><?php echo e($user->email); ?></span>
                                    </div>
                                </td>
                                <td>    
                                    <?php if($user->phone): ?>
                                        <span class="d-block"><i class="ri-phone-line me-2 align-middle fs-14 text-muted"></i><?php echo e($user->phone); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-info-transparent"><?php echo e(ucfirst($user->role->name)); ?></span></td>
                                <td>
                                    <?php if($user->last_login_at): ?>
                                        <span class="text-muted"><?php echo e($user->last_login_at->format('M d, Y H:i')); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->status == 'active'): ?>
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View Button -->
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewUserModal" data-id="<?php echo e($user->id); ?>" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditUserModal" data-id="<?php echo e($user->id); ?>" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <form method="POST" action="<?php echo e(route('toggle.user.status', $user->id)); ?>"
                                            class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit"
                                                class="btn btn-sm <?php echo e($user->status === 'active' ? 'btn-warning-light' : 'btn-success-light'); ?> btn-icon"
                                                title="<?php echo e($user->status === 'active' ? 'Deactivate' : 'Activate'); ?>">
                                                <i
                                                    class="ri-toggle-<?php echo e($user->status === 'active' ? 'line' : 'fill'); ?>"></i>
                                            </button>
                                        </form>
                                        <!-- Delete Button -->
                                        <button class="btn btn-sm btn-danger-light btn-icon openDeleteUserModal"
                                            data-id="<?php echo e($user->id); ?>" data-name="<?php echo e($user->name); ?>" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>



                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="card-footer border-top-0">
                <div class="d-flex align-items-center">
                    <div>
                        Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?> entries
                        <i class="bi bi-arrow-right ms-2 fw-medium"></i>
                    </div>
                    <div class="ms-auto">
                        <nav aria-label="Page navigation" class="pagination-style-5">
                            <ul class="pagination mb-0">

                                
                                <li class="page-item <?php echo e($users->onFirstPage() ? 'disabled' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($users->previousPageUrl() ?? 'javascript:void(0);'); ?>">
                                        Prev
                                    </a>
                                </li>

                                
                                <?php for($i = max(1, $users->currentPage() - 2); $i <= min($users->lastPage(), $users->currentPage() + 2); $i++): ?>
                                    <li class="page-item <?php echo e($i == $users->currentPage() ? 'active' : ''); ?>">
                                        <a class="page-link" href="<?php echo e($users->url($i)); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endfor; ?>

                                
                                <li class="page-item <?php echo e(!$users->hasMorePages() ? 'disabled' : ''); ?>">
                                    <a class="page-link text-primary"
                                        href="<?php echo e($users->nextPageUrl() ?? 'javascript:void(0);'); ?>">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ri-delete-bin-line me-2"></i>Delete User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                    <p class="text-muted">This action can be undone by restoring from trash.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteUserForm" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Modals -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="userModalContent"></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Create User
            $('#openCreateUserModal').on('click', function() {
                $.get("<?php echo e(route('users.create-modal')); ?>", function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });

            // View User
            $(document).on('click', '.openViewUserModal', function() {
                var id = $(this).data('id');
                $.get("<?php echo e(route('users.view-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });

            // Edit User
            $(document).on('click', '.openEditUserModal', function() {
                var id = $(this).data('id');
                $.get("<?php echo e(route('users.edit-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });

            // Delete User
            $(document).on('click', '.openDeleteUserModal', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#deleteUserName').text(name);
                $('#deleteUserForm').attr('action', "<?php echo e(route('users.destroy', ':id')); ?>".replace(':id', id));
                $('#deleteUserModal').modal('show');
            });

            // Handle Delete Form Submission
            $(document).on('submit', '#deleteUserForm', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Deleting...');
                
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        $('#deleteUserModal').modal('hide');
                        toastr.success(response.message || 'User deleted successfully!');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        var error = xhr.responseJSON?.message || 'Failed to delete user';
                        toastr.error(error);
                        $button.prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i>Delete');
                    }
                });
            });
        });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/users/index.blade.php ENDPATH**/ ?>