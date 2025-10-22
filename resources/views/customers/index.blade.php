@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="ri-user-line me-2"></i> Customers (Debtors)</h4>
            <button class="btn btn-primary" id="openCreateCustomerModal">
                <i class="ri-user-add-line me-1"></i> Add Customer
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('customers.index') }}" id="customerSearchForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Name, Code, Email, Phone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Type</label>
                            <select name="type" class="form-control">
                                <option value="">All</option>
                                <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-control">
                                <option value="">All</option>
                                <option value="individual" {{ request('category') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="business" {{ request('category') == 'business' ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <a href="{{ route('customers.index') }}" class="btn btn-light">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                <table class="table table-bordered table-striped">
                    <thead class="sticky-top bg-white" style="z-index: 10;">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Terms</th>
                            <th>Credit Limit</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customersTableBody">
                        @include('customers.partials.table')
                    </tbody>
                </table>
                </div>
            </div>
            <div class="card-footer">
                <div id="paginationContainer">
                    @include('customers.partials.pagination')
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="ri-delete-bin-line me-2"></i>Delete Customer
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the customer <strong id="deleteCustomerName"></strong>?</p>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteCustomerForm" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="ri-delete-bin-line me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Modals -->
                <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="customerModalContent"></div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    // Global variables and functions for customer filtering
                    let currentPage = {{ $customers->currentPage() }};
                    let isLoading = false;

                    function loadCustomers() {
                        if (isLoading) return;
                        
                        isLoading = true;
                        showLoading();
                        
                        const params = {
                            search: $('input[name="search"]').val(),
                            status: $('select[name="status"]').val(),
                            type: $('select[name="type"]').val(),
                            category: $('select[name="category"]').val(),
                            page: currentPage,
                            ajax: 1
                        };
                        
                        $.get('{{ route("customers.index") }}', params, function(data) {
                            $('#customersTableBody').html(data.table);
                            $('#paginationContainer').html(data.pagination);
                            hideLoading();
                            isLoading = false;
                        }).fail(function(xhr, status, error) {
                            console.error('AJAX error:', xhr.status, xhr.statusText);
                            toastr.error('Error loading customers: ' + xhr.status + ' ' + xhr.statusText);
                            hideLoading();
                            isLoading = false;
                        });
                    }

                    function loadCustomersPage(page) {
                        currentPage = page;
                        loadCustomers();
                    }

                    function showLoading() {
                        $('#customersTableBody').html('<tr><td colspan="9" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                    }

                    function hideLoading() {
                        // Loading removed by new content
                    }

                    $(function() {
                        // Create Customer
                        $('#openCreateCustomerModal').on('click', function() {
                            $.get("{{ route('customers.create') }}", function(html) {
                                $('#customerModalContent').html(html);
                                $('#customerModal').modal('show');
                            });
                        });

                        // View Customer
                        $(document).on('click', '.openViewCustomerModal', function() {
                            var id = $(this).data('id');
                            $.get("{{ route('customers.view-modal', ':id') }}".replace(':id', id), function(html) {
                                $('#customerModalContent').html(html);
                                $('#customerModal').modal('show');
                            });
                        });

                        // Edit Customer
                        $(document).on('click', '.openEditCustomerModal', function() {
                            var id = $(this).data('id');
                            $.get("{{ route('customers.edit-modal', ':id') }}".replace(':id', id), function(html) {
                                $('#customerModalContent').html(html);
                                $('#customerModal').modal('show');
                            });
                        });

                        // Delete Customer
                        $(document).on('click', '.openDeleteCustomerModal', function(e) {
                            e.stopPropagation();
                            var id = $(this).data('id');
                            var name = $(this).data('name');
                            $('#deleteCustomerName').text(name);
                            $('#deleteCustomerForm').attr('action', "{{ route('customers.destroy', ':id') }}".replace(':id', id));
                            $('#deleteCustomerModal').modal('show');
                        });

                        // Handle Delete Form Submission
                        $(document).on('submit', '#deleteCustomerForm', function(e) {
                            e.preventDefault();
                            var $form = $(this);
                            var $button = $form.find('button[type="submit"]');
                            
                            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Deleting...');
                            
                            $.ajax({
                                url: $form.attr('action'),
                                method: 'POST',
                                data: $form.serialize(),
                                success: function(response) {
                                    $('#deleteCustomerModal').modal('hide');
                                    toastr.success('Customer deleted successfully!');
                                    loadCustomers();
                                },
                                error: function(xhr) {
                                    var error = xhr.responseJSON?.message || 'Failed to delete customer';
                                    toastr.error(error);
                                    $button.prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i>Delete');
                                }
                            });
                        });

                        // Row Click to View
                        $(document).on('click', '.clickable-row', function(e) {
                            // Don't trigger if clicking on buttons or forms
                            if ($(e.target).closest('button, form, a').length === 0) {
                                var id = $(this).data('id');
                                $.get("{{ route('customers.view-modal', ':id') }}".replace(':id', id), function(html) {
                                    $('#customerModalContent').html(html);
                                    $('#customerModal').modal('show');
                                });
                            }
                        });

                        // Toggle Status with AJAX
                        $(document).on('submit', '.toggle-status-form', function(e) {
                            e.preventDefault();
                            
                            var $form = $(this);
                            var $button = $form.find('button[type="submit"]');
                            var $icon = $button.find('i');
                            var originalIcon = $icon.attr('class');
                            
                            // Show loading state
                            $icon.attr('class', 'ri-loader-4-line spinner-border spinner-border-sm');
                            $button.prop('disabled', true);
                            
                            $.ajax({
                                url: $form.attr('action'),
                                method: 'POST',
                                data: $form.serialize(),
                                success: function(response) {
                                    // Reload the table to show updated status
                                    loadCustomers();
                                    toastr.success('Customer status updated successfully!');
                                },
                                error: function(xhr) {
                                    // Restore original state
                                    $icon.attr('class', originalIcon);
                                    $button.prop('disabled', false);
                                    toastr.error('Failed to update status');
                                }
                            });
                        });

                        // Initialize filters with debounce
                        function initializeFilters() {
                            let searchTimeout;
                            
                            $('input[name="search"]').on('keyup', function() {
                                clearTimeout(searchTimeout);
                                searchTimeout = setTimeout(function() {
                                    currentPage = 1;
                                    loadCustomers();
                                }, 500);
                            });
                            
                            $('select[name="status"], select[name="type"], select[name="category"]').on('change', function() {
                                currentPage = 1;
                                loadCustomers();
                            });
                            
                            // Prevent form submission
                            $('#customerSearchForm').on('submit', function(e) {
                                e.preventDefault();
                                currentPage = 1;
                                loadCustomers();
                            });
                        }

                        initializeFilters();
                    });
                </script>
            @endpush

        <!-- Payment Modal Container -->
        <div id="paymentModalContainer"></div>
        @endsection
    </div>
</div>
