@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Model Engines</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModelEngineModal">
            <i class="bi bi-plus-circle me-1"></i> Add Model Engine
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Car Model</th>
                        <th>Engine</th>
                        <th>Year Range</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modelEngines as $me)
                        <tr>
                            <td>{{ $loop->iteration + ($modelEngines->currentPage() - 1) * $modelEngines->perPage() }}</td>
                            <td>{{ $me->carModel->name ?? '-' }}</td>
                            <td>{{ $me->engine->code ?? '-' }}</td>
                            <td>{{ $me->yearRange->start_year ?? '-' }} - {{ $me->yearRange->end_year ?? '-' }}</td>
                            <td>
                                @if($me->status === 'active')
                                    <span class="badge rounded-pill bg-success-transparent">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('toggle.model-engine.status', $me->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $me->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                            title="{{ $me->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-toggle-{{ $me->status === 'active' ? 'line' : 'fill' }}"></i>
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editModelEngine{{ $me->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteModelEngine{{ $me->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModelEngine{{ $me->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('model.engines.update', $me->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Model Engine</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Car Model</label>
                                                <select name="car_model_id" class="form-select" required>
                                                    @foreach($carModels as $model)
                                                        <option value="{{ $model->id }}" {{ $me->car_model_id == $model->id ? 'selected' : '' }}>
                                                            {{ $model->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Engine</label>
                                                <select name="engine_id" class="form-select" required>
                                                    @foreach($engines as $engine)
                                                        <option value="{{ $engine->id }}" {{ $me->engine_id == $engine->id ? 'selected' : '' }}>
                                                            {{ $engine->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $me->status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $me->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <div class="modal fade" id="deleteModelEngine{{ $me->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('model.engines.destroy', $me->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this Model Engine?
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
                            <td colspan="6" class="text-center text-muted">No model engines found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer border-top-0">
            {{ $modelEngines->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModelEngineModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('model.engines.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Model Engine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Car Model</label>
                            <select name="car_model_id" class="form-select" required>
                                @foreach($carModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Engine</label>
                            <select name="engine_id" class="form-select" required>
                                @foreach($engines as $engine)
                                    <option value="{{ $engine->id }}">{{ $engine->code }}</option>
                                @endforeach
                            </select>
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
