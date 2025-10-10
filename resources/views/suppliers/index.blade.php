@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Suppliers</h4>
            <button class="btn btn-primary" id="openCreateSupplierModal">
                <i class="ri-add-line me-1"></i> Add Supplier
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
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
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                                <td>
                                    <span class="badge bg-info-transparent">{{ $supplier->supplier_code }}</span>
                                </td>
                                <td>{{ $supplier->name }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $supplier->supplier_type === 'company' ? 'primary' : 'secondary' }}-transparent">
                                        {{ ucfirst($supplier->supplier_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        @if($supplier->email)
                                            <span class="d-block mb-1"><i
                                                    class="ri-mail-line me-2 align-middle fs-14 text-muted"></i>{{ $supplier->email }}</span>
                                        @endif
                                        @if($supplier->phone)
                                            <span class="d-block"><i
                                                    class="ri-phone-line me-2 align-middle fs-14 text-muted"></i>{{ $supplier->phone }}</span>
                                        @endif
                                        @if($supplier->contact_person)
                                            <small class="text-muted">{{ $supplier->contact_person }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-warning-transparent">{{ $supplier->payment_terms }}</span>
                                </td>
                                <td>
                                    @if($supplier->credit_limit > 0)
                                        <span class="text-success">R{{ number_format($supplier->credit_limit, 2) }}</span>
                                    @else
                                        <span class="text-muted">No limit</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="{{ $supplier->balance < 0 ? 'text-danger' : ($supplier->balance > 0 ? 'text-success' : 'text-muted') }}">
                                        R{{ number_format($supplier->balance, 2) }}
                                    </span>
                                    @if($supplier->isOverCreditLimit())
                                        <br><small class="text-danger">Over limit!</small>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->status == 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- Toggle -->
                                        <form method="POST" action="{{ route('suppliers.toggle.status', $supplier->id) }}"
                                            class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $supplier->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-toggle-{{ $supplier->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>
                                        <!-- View -->
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewSupplierModal"
                                            data-id="{{ $supplier->id }}" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditSupplierModal"
                                            data-id="{{ $supplier->id }}" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteSupplier{{ $supplier->id }}" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteSupplier{{ $supplier->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier->id) }}">
                                        @csrf @method('DELETE')
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="ri-delete-bin-line me-2"></i> Delete Supplier
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">

                                                Are you sure you want to delete the supplier
                                                <strong>{{ $supplier->name }}</strong>?
                                                @php
                                                    $productCount = $supplier->products()->count();
                                                    $grnCount = $supplier->grns()->count();
                                                    $poCount = $supplier->purchaseOrders()->count();
                                                @endphp

                                                @if($productCount > 0 || $grnCount > 0 || $poCount > 0)
                                                    <div class="alert alert-danger mt-3" role="alert">
                                                        <h6 class="alert-heading"><i class="ri-error-warning-line me-2"></i>Cannot
                                                            Delete Supplier</h6>
                                                        <p class="mb-2">This supplier has associated records that prevent deletion:
                                                        </p>
                                                        <ul class="mb-0">
                                                            @if($productCount > 0)
                                                                <li><strong>{{ $productCount }}</strong> product(s) are linked to this
                                                                    supplier</li>
                                                            @endif
                                                            @if($grnCount > 0)
                                                                <li><strong>{{ $grnCount }}</strong> goods receipt(s) are linked to this
                                                                    supplier</li>
                                                            @endif
                                                            @if($poCount > 0)
                                                                <li><strong>{{ $poCount }}</strong> purchase order(s) are linked to this
                                                                    supplier</li>
                                                            @endif
                                                        </ul>
                                                        <hr>
                                                        <p class="mb-0"><strong>Solution:</strong> Remove or reassign these records
                                                            before deleting the supplier.</p>
                                                    </div>
                                                @else

                                                @endif
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    <i class="ri-close-line me-1"></i> Cancel
                                                </button>
                                                @if($productCount > 0 || $grnCount > 0 || $poCount > 0)
                                                    <button type="button" class="btn btn-secondary" disabled>
                                                        <i class="ri-delete-bin-line me-1"></i> Cannot Delete
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="ri-delete-bin-line me-1"></i> Delete Supplier
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No suppliers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top-0">
                {{ $suppliers->links() }}
            </div>
        </div>

        <!-- Supplier Modals -->
        <div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="supplierModalContent"></div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Create Supplier
            $('#openCreateSupplierModal').on('click', function () {
                $.get("{{ route('suppliers.create-modal') }}", function (html) {
                    $('#supplierModalContent').html(html);
                    $('#supplierModal').modal('show');
                });
            });

            // View Supplier
            $(document).on('click', '.openViewSupplierModal', function () {
                var id = $(this).data('id');
                $.get("{{ route('suppliers.view-modal', ':id') }}".replace(':id', id), function (html) {
                    $('#supplierModalContent').html(html);
                    $('#supplierModal').modal('show');
                });
            });

            // Edit Supplier
            $(document).on('click', '.openEditSupplierModal', function () {
                var id = $(this).data('id');
                $.get("{{ route('suppliers.edit-modal', ':id') }}".replace(':id', id), function (html) {
                    $('#supplierModalContent').html(html);
                    $('#supplierModal').modal('show');
                });
            });
        });
    </script>

    <!-- Payment Modal Container -->
    <div id="paymentModalContainer"></div>
@endpush