@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Engines</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEngineModal">
            <i class="bi bi-plus-circle me-1"></i> Add Engine
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Displacement</th>
                        <th>Fuel Type</th>
                        <th>Cylinder</th>
                        <th>Power</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($engines as $engine)
                        <tr>
                            <td>{{ $loop->iteration + ($engines->currentPage() - 1) * $engines->perPage() }}</td>
                            <td>{{ $engine->code }}</td>
                            <td>{{ $engine->displacement ?? '-' }}</td>
                            <td>{{ $engine->fuel_type ?? '-' }}</td>
                            <td>{{ $engine->cylinder ?? '-' }}</td>
                            <td>{{ $engine->power ?? '-' }}</td>
                            <td>
                                @if($engine->status === 'active')
                                    <span class="badge rounded-pill bg-success-transparent">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('toggle.engine.status', $engine->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $engine->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                            title="{{ $engine->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-toggle-{{ $engine->status === 'active' ? 'line' : 'fill' }}"></i>
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editEngine{{ $engine->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteEngine{{ $engine->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editEngine{{ $engine->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('engines.update', $engine->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Engine</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Code</label>
                                                <input type="text" name="code" class="form-control" value="{{ old('code', $engine->code) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Displacement</label>
                                                <input type="text" name="displacement" class="form-control" value="{{ old('displacement', $engine->displacement) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Fuel Type</label>
                                                <input type="text" name="fuel_type" class="form-control" value="{{ old('fuel_type', $engine->fuel_type) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Cylinder</label>
                                                <input type="number" name="cylinder" class="form-control" value="{{ old('cylinder', $engine->cylinder) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Power</label>
                                                <input type="text" name="power" class="form-control" value="{{ old('power', $engine->power) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $engine->status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $engine->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <div class="modal fade" id="deleteEngine{{ $engine->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('engines.destroy', $engine->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $engine->code }}</strong>?
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
                            <td colspan="8" class="text-center text-muted">No engines found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer border-top-0">
            {{ $engines->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createEngineModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('engines.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Engine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Displacement</label>
                            <input type="text" name="displacement" class="form-control" value="{{ old('displacement') }}">
                        </div>
                        <div class="mb-3">
                            <label>Fuel Type</label>
                            <input type="text" name="fuel_type" class="form-control" value="{{ old('fuel_type') }}">
                        </div>
                        <div class="mb-3">
                            <label>Cylinder</label>
                            <input type="number" name="cylinder" class="form-control" value="{{ old('cylinder') }}">
                        </div>
                        <div class="mb-3">
                            <label>Power</label>
                            <input type="text" name="power" class="form-control" value="{{ old('power') }}">
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
