@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Suppliers</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="bi bi-plus-circle me-1"></i> Add Supplier
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ number_format($supplier->balance,2) }}</td>
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
                                    <form method="POST" action="{{ route('suppliers.toggle.status', $supplier->id) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $supplier->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                            title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-toggle-{{ $supplier->status === 'active' ? 'line' : 'fill' }}"></i>
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSupplier{{ $supplier->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteSupplier{{ $supplier->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editSupplier{{ $supplier->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('suppliers.update',$supplier->id) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Supplier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name',$supplier->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email',$supplier->email) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ old('phone',$supplier->phone) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control">{{ old('address',$supplier->address) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Balance</label>
                                                <input type="number" step="0.01" name="balance" class="form-control"
                                                    value="{{ old('balance',$supplier->balance) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $supplier->status=='active'?'selected':'' }}>Active</option>
                                                    <option value="inactive" {{ $supplier->status=='inactive'?'selected':'' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteSupplier{{ $supplier->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('suppliers.destroy',$supplier->id) }}">
                                    @csrf @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $supplier->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No suppliers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top-0">
            {{ $suppliers->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createSupplierModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('suppliers.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3"><label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3"><label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3"><label>Address</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                        <div class="mb-3"><label>Balance</label>
                            <input type="number" step="0.01" name="balance" class="form-control">
                        </div>
                        <div class="mb-3"><label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
