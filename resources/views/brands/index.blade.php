@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Brands</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Brands</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                <i class="bi bi-plus-circle me-1"></i> Add Brand
            </button>
        </div>

        <!-- Brands Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="50"
                                            height="50" class="rounded">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($brand->status == 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View -->
                                        <a data-bs-toggle="offcanvas" href="#viewBrand{{ $brand->id }}" role="button"
                                            aria-controls="viewBrand{{ $brand->id }}"
                                            class="btn btn-sm btn-primary-light btn-icon">
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editBrand{{ $brand->id }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <!-- Status Toggle -->
                                        <form method="POST" action="{{ route('toggle.brand.status', $brand->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $brand->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="{{ $brand->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-toggle-{{ $brand->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteBrand{{ $brand->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Offcanvas: View Brand -->
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="viewBrand{{ $brand->id }}">
                                <div class="offcanvas-header">
                                    <h5>Brand Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <p><strong>Name:</strong> {{ $brand->name }}</p>
                                    <p><strong>Status:</strong> {{ $brand->status == 'active' ? 'Active' : 'Inactive' }}
                                    </p>
                                    <p><strong>Description:</strong> {{ $brand->description ?? '-' }}</p>
                                    <p>
                                        <strong>Logo:</strong><br>
                                        @if ($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" width="120"
                                                class="rounded">
                                        @else
                                            No Logo
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Modal: Edit Brand -->
                            <div class="modal fade" id="editBrand{{ $brand->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('brands.update', $brand->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Brand</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $brand->name) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Logo</label>
                                                    <input type="file" name="logo" class="form-control">
                                                    @if ($brand->logo)
                                                        <img src="{{ asset('storage/' . $brand->logo) }}" width="70"
                                                            class="mt-2 rounded">
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active"
                                                            {{ $brand->status == 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control">{{ old('description', $brand->description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal: Delete Brand -->
                            <div class="modal fade" id="deleteBrand{{ $brand->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('brands.destroy', $brand->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $brand->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No brands found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer border-top-0">
                <div class="d-flex align-items-center">
                    <div>
                        @if ($brands->total())
                            Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of
                            {{ $brands->total() }} entries
                        @endif
                    </div>
                    <div class="ms-auto">
                        <nav aria-label="Page navigation">
                            {{ $brands->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Create Brand -->
    <div class="modal fade" id="createBrandModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
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
@endsection
