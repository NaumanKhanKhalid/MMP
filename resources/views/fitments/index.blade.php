@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Product Fitments</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductFitmentModal">
            <i class="bi bi-plus-circle me-1"></i> Add Product Fitment
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Engine</th>
                        <th>Years</th>
                        <th>Market</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fitments as $fitment)
                        <tr>
                            <td>{{ $loop->iteration + ($fitments->currentPage() - 1) * $fitments->perPage() }}</td>
                            <td>{{ $fitment->product->name ?? '-' }}</td>
                            <td>{{ $fitment->make->name ?? '-' }}</td>
                            <td>{{ $fitment->model->name ?? '-' }}</td>
                            <td>{{ $fitment->engine->code ?? '-' }}</td>
                            <td>{{ $fitment->year_start }} - {{ $fitment->year_end }}</td>
                            <td>{{ $fitment->market ?? '-' }}</td>
                            <td class="text-end">
                                <div class="btn-list">
                                    <!-- Edit -->
                                    <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editProductFitment{{ $fitment->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteProductFitment{{ $fitment->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editProductFitment{{ $fitment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('product.fitments.update', $fitment->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Product Fitment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Product</label>
                                                <select name="product_id" class="form-select" required>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $fitment->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Make</label>
                                                <select name="make_id" class="form-select" required>
                                                    @foreach($makes as $make)
                                                        <option value="{{ $make->id }}" {{ $fitment->make_id == $make->id ? 'selected' : '' }}>
                                                            {{ $make->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Model</label>
                                                <select name="model_id" class="form-select" required>
                                                    @foreach($models as $model)
                                                        <option value="{{ $model->id }}" {{ $fitment->model_id == $model->id ? 'selected' : '' }}>
                                                            {{ $model->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Engine</label>
                                                <select name="engine_id" class="form-select" required>
                                                    @foreach($engines as $engine)
                                                        <option value="{{ $engine->id }}" {{ $fitment->engine_id == $engine->id ? 'selected' : '' }}>
                                                            {{ $engine->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Year Start</label>
                                                <input type="number" name="year_start" class="form-control" value="{{ old('year_start', $fitment->year_start) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Year End</label>
                                                <input type="number" name="year_end" class="form-control" value="{{ old('year_end', $fitment->year_end) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Market</label>
                                                <input type="text" name="market" class="form-control" value="{{ old('market', $fitment->market) }}">
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
                        <div class="modal fade" id="deleteProductFitment{{ $fitment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('product.fitments.destroy', $fitment->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this fitment?
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
                            <td colspan="8" class="text-center text-muted">No product fitments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer border-top-0">
            {{ $fitments->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createProductFitmentModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('product.fitments.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product Fitment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Product</label>
                            <select name="product_id" class="form-select" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Make</label>
                            <select name="make_id" class="form-select" required>
                                @foreach($makes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Model</label>
                            <select name="model_id" class="form-select" required>
                                @foreach($models as $model)
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
                            <label>Year Start</label>
                            <input type="number" name="year_start" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Year End</label>
                            <input type="number" name="year_end" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Market</label>
                            <input type="text" name="market" class="form-control">
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
