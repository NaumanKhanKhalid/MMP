@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Vehicle Makes</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMakeModal">
            <i class="bi bi-plus-circle me-1"></i> Add Vehicle Make
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($makes as $make)
                        <tr>
                            <td>{{ $loop->iteration + ($makes->currentPage() - 1) * $makes->perPage() }}</td>
                            <td>{{ $make->name }}</td>
                            <td>
                                @if ($make->status == 'active')
                                    <span class="badge rounded-pill bg-success-transparent">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('toggle.make.status', $make->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $make->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                            title="{{ $make->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-toggle-{{ $make->status === 'active' ? 'line' : 'fill' }}"></i>
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editMake{{ $make->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteMake{{ $make->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal: Edit Make -->
                        <div class="modal fade" id="editMake{{ $make->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('makes.update', $make->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Vehicle Make</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $make->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $make->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $make->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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

                        <!-- Modal: Delete Make -->
                        <div class="modal fade" id="deleteMake{{ $make->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('makes.destroy', $make->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $make->name }}</strong>?
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
                            <td colspan="4" class="text-center text-muted">No vehicle makes found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-top-0">
            {{ $makes->links() }}
        </div>
    </div>

    <!-- Modal: Create Make -->
    <div class="modal fade" id="createMakeModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('makes.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vehicle Make</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
