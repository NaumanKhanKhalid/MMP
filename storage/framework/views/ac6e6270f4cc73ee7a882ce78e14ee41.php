<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Start::page-header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">Suppliers (Creditors)</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                </ol>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary btn-wave waves-effect waves-light" id="openCreateSupplierModal">
                    <i class="ri-add-line me-1"></i>Add Supplier
                </button>
            </div>
        </div>
        <!-- End::page-header -->

        <!-- Start:: Search and Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Search</label>
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Search by name, code, email...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Type</label>
                                <select class="form-select" id="typeFilter">
                                    <option value="">All</option>
                                    <option value="company">Company</option>
                                    <option value="individual">Individual</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Balance</label>
                                <select class="form-select" id="balanceFilter">
                                    <option value="">All</option>
                                    <option value="overdue">Overdue</option>
                                    <option value="positive">Positive</option>
                                    <option value="zero">Zero</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn btn-light w-100" onclick="clearFilters()">
                                    <i class="ri-refresh-line"></i>
            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: Search and Filters -->

        <!-- Start:: Suppliers Table -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead class="sticky-top bg-white" style="z-index: 10;">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Payment Terms</th>
                            <th>Credit Limit</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                                <tbody id="suppliersTableBody">
                                    <?php echo $__env->make('suppliers.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </tbody>
                            </table>
                                            </div>
                                        </div>
                    <div class="card-footer">
                        <div id="paginationContainer">
                            <?php echo $__env->make('suppliers.partials.pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            </div>
            </div>
            </div>
        </div>
        <!-- End:: Suppliers Table -->

        <!-- Start:: Supplier Modals -->
        <div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="supplierModalContent"></div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="ri-delete-bin-line me-2"></i>Delete Supplier
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the supplier <strong id="deleteSupplierName"></strong>?</p>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteSupplierForm" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: Supplier Modals -->
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        let currentPage = <?php echo e($suppliers->currentPage()); ?>;
        let isLoading = false;

        $(document).ready(function() {
            // Initialize filters
            initializeFilters();

            // Create Supplier
            $('#openCreateSupplierModal').on('click', function() {
                loadSupplierModal('<?php echo e(route('suppliers.create-modal')); ?>');
            });

            // View Supplier
            $(document).on('click', '.openViewSupplierModal', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var url = '<?php echo e(url('suppliers')); ?>/' + id + '/view-modal';
                loadSupplierModal(url);
            });

            // Edit Supplier
            $(document).on('click', '.openEditSupplierModal', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var url = '<?php echo e(url('suppliers')); ?>/' + id + '/edit-modal';
                loadSupplierModal(url);
            });

            // Delete Supplier
            $(document).on('click', '.openDeleteSupplierModal', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#deleteSupplierName').text(name);
                $('#deleteSupplierForm').attr('action', '<?php echo e(url('suppliers')); ?>/' + id);
                $('#deleteSupplierModal').modal('show');
            });

            // Row click to view
            $(document).on('click', '.supplier-row', function(e) {
                // Only open modal if not clicking on a button or link
                if (!$(e.target).closest('button, a, form').length) {
                    var id = $(this).data('id');
                    var url = '<?php echo e(url('suppliers')); ?>/' + id + '/view-modal';
                    loadSupplierModal(url);
                }
            });
        });

        function loadSupplierModal(url) {
            console.log('Loading supplier modal from URL:', url);
            $.get(url, function(html) {
                    $('#supplierModalContent').html(html);
                    $('#supplierModal').modal('show');
            }).fail(function(xhr, status, error) {
                console.error('Error loading supplier modal:', xhr.status, xhr.statusText);
                console.error('Response:', xhr.responseText);
                toastr.error('Error loading supplier details: ' + xhr.status + ' ' + xhr.statusText);
            });
        }

        function initializeFilters() {
            // Search input with debounce
            let searchTimeout;
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    loadSuppliers();
                }, 500);
            });

            // Filter dropdowns
            $('#statusFilter, #typeFilter, #balanceFilter').on('change', function() {
                currentPage = 1;
                loadSuppliers();
            });
        }

        function loadSuppliers() {
            if (isLoading) return;

            console.log('Loading suppliers, currentPage:', currentPage);
            isLoading = true;
            showLoading();

            const params = {
                search: $('#searchInput').val(),
                status: $('#statusFilter').val(),
                type: $('#typeFilter').val(),
                balance: $('#balanceFilter').val(),
                page: currentPage
            };

            console.log('AJAX params:', params);

            $.get('<?php echo e(route('suppliers.index')); ?>', params, function(data) {
                console.log('AJAX response:', data);
                $('#suppliersTableBody').html(data.table);
                $('#paginationContainer').html(data.pagination);
                hideLoading();
                isLoading = false;
            }).fail(function(xhr, status, error) {
                console.error('AJAX error:', xhr.status, xhr.statusText);
                console.error('Response:', xhr.responseText);
                toastr.error('Error loading suppliers: ' + xhr.status + ' ' + xhr.statusText);
                hideLoading();
                isLoading = false;
            });
        }

        function loadSuppliersPage(page) {
            console.log('Loading page:', page);
            currentPage = page;
            loadSuppliers();
        }

        function showLoading() {
            $('#suppliersTableBody').html(`
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </td>
        </tr>
    `);
        }

        function hideLoading() {
            // Loading will be replaced by actual data
        }

        function clearFilters() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#typeFilter').val('');
            $('#balanceFilter').val('');
            currentPage = 1;
            loadSuppliers();
        }

        function refreshSuppliers() {
            currentPage = 1;
            loadSuppliers();
            toastr.success('Suppliers refreshed');
        }

        // Pagination click handler
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            currentPage = url.searchParams.get('page') || 1;
            loadSuppliers();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/suppliers/index.blade.php ENDPATH**/ ?>