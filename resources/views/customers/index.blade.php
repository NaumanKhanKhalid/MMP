@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Customers</h4   >
            <button class="btn btn-primary" id="openCreateCustomerModal">
                <i class="bi bi-person-plus me-1"></i> New Customer
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
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
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-info-transparent">{{ $customer->customer_code }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $customer->display_name }}</strong>
                                        @if($customer->isBusiness() && $customer->company_name)
                                            <br><small class="text-muted">{{ $customer->name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->customer_type === 'business' ? 'primary' : 'secondary' }}-transparent">
                                        {{ ucfirst($customer->customer_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        @if($customer->email)
                                            <span class="d-block mb-1"><i class="ri-mail-line me-2 align-middle fs-14 text-muted"></i>{{ $customer->email }}</span>
                                        @endif
                                        @if($customer->phone)
                                            <span class="d-block"><i class="ri-phone-line me-2 align-middle fs-14 text-muted"></i>{{ $customer->phone }}</span>
                                        @endif
                                        @if($customer->contact_person)
                                            <small class="text-muted">{{ $customer->contact_person }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->terms === 'on_account' ? 'warning' : 'success' }}-transparent">
                                        {{ ucfirst($customer->terms) }}
                                    </span>
                                </td>
                                <td>
                                    @if($customer->credit_limit > 0)
                                        <span class="text-success">R{{ number_format($customer->credit_limit, 2) }}</span>
                                    @else
                                        <span class="text-muted">No limit</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted') }}">
                                        R{{ number_format($customer->balance, 2) }}
                                    </span>
                                    @if($customer->isOverCreditLimit())
                                        <br><small class="text-danger">Over limit!</small>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->customer_status === 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @elseif($customer->customer_status === 'inactive')
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-transparent">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list d-inline-flex gap-1">
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewCustomerModal" data-id="{{ $customer->id }}" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success-light btn-icon openEditCustomerModal" data-id="{{ $customer->id }}" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <form action="{{ route('customers.toggle-status', $customer) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-warning-light btn-icon" title="Toggle Status">
                                                <i class="ri-toggle-line"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteCustomer{{ $customer->id }}" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $customers->links() }}

                <!-- Delete Modals for each customer -->
                @foreach ($customers as $customer)
                    <div class="modal fade" id="deleteCustomer{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <i class="ri-delete-bin-line text-danger" style="font-size: 3rem;"></i>
                                        <h4 class="mt-3">Are you sure?</h4>
                                        <p class="text-muted">You are about to delete customer:</p>
                                        
                                        <p class="text-danger mt-3">
                                            <strong>This action cannot be undone!</strong>
                                        </p>
                                        @if($customer->quotes()->count() > 0 || $customer->invoices()->count() > 0 )
                                            <div class="alert alert-warning mt-3">
                                                <i class="ri-alert-line me-2"></i>
                                                This customer has associated records and cannot be deleted.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    @if($customer->quotes()->count() == 0 && $customer->invoices()->count() == 0 )
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="ri-delete-bin-line me-1"></i> Delete Customer
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-danger" disabled>
                                            <i class="ri-delete-bin-line me-1"></i> Cannot Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Customer Modals -->
                <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="customerModalContent"></div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
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
                    });
                </script>
            @endpush

        <!-- Payment Modal Container -->
        <div id="paymentModalContainer"></div>
        @endsection
    </div>
</div>
