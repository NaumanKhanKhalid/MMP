@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Car Models</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCarModelModal">
            <i class="bi bi-plus-circle me-1"></i> Add Car Model
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Make</th>
                        <th>Generation</th>
                        <th>Body Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                        <tr>
                            <td>{{ $loop->iteration + ($models->currentPage() - 1) * $models->perPage() }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->make->name ?? '-' }}</td>
                            <td>{{ $model->generation ?? '-' }}</td>
                            <td>{{ $model->body_type ?? '-' }}</td>
                            <td>
                                @if($model->status === 'active')
                                    <span class="badge rounded-pill bg-success-transparent">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('toggle.car-model.status', $model->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $model->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                            title="{{ $model->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-toggle-{{ $model->status === 'active' ? 'line' : 'fill' }}"></i>
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editCarModel{{ $model->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteCarModel{{ $model->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCarModel{{ $model->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('car-models.update', $model->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Car Model</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Make</label>
                                                <select name="make_id" class="form-select" required>
                                                    @foreach($makes as $make)
                                                        <option value="{{ $make->id }}" {{ $model->make_id == $make->id ? 'selected' : '' }}>{{ $make->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $model->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Generation</label>
                                                <input type="text" name="generation" class="form-control" value="{{ old('generation', $model->generation) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Body Type</label>
                                                <input type="text" name="body_type" class="form-control" value="{{ old('body_type', $model->body_type) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $model->status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $model->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <div class="modal fade" id="deleteCarModel{{ $model->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('car-models.destroy', $model->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $model->name }}</strong>?
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
                            <td colspan="7" class="text-center text-muted">No car models found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer border-top-0">
            {{ $models->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createCarModelModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('car-models.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Car Model</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Make</label>
                            <select name="make_id" class="form-select" required>
                                @foreach($makes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Generation</label>
                            <input type="text" name="generation" class="form-control" value="{{ old('generation') }}">
                        </div>
                        <div class="mb-3">
                            <label>Body Type</label>
                            <input type="text" name="body_type" class="form-control" value="{{ old('body_type') }}">
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
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
