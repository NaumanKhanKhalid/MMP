@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Categories</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-circle me-1"></i> Add Category
            </button>
        </div>

        <!-- Categories Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="lh-1">
                                            <span class="avatar avatar-sm p-1 bg-light avatar-rounded">
                                                <img src="{{ $category->logo ? asset('storage/' . $category->logo) : asset('assets/images/company-logos/1.png') }}"
                                                    alt="" style="width:32px;height:32px;object-fit:cover;">
                                            </span>
                                        </div>
                                        <div>{{ $category->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $category->parent?->name ?? '-' }}</td>
                                <td>
                                    @if ($category->status == 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View -->
                                        <a data-bs-toggle="offcanvas" href="#viewCategory{{ $category->id }}" role="button"
                                            aria-controls="viewCategory{{ $category->id }}"
                                            class="btn btn-sm btn-primary-light btn-icon">
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editCategory{{ $category->id }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <!-- Status Toggle -->
                                        <form method="POST" action="{{ route('toggle.category.status', $category->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $category->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="{{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-toggle-{{ $category->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteCategory{{ $category->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Offcanvas: View Category -->
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="viewCategory{{ $category->id }}">
                                <div class="offcanvas-header">
                                    <h5>Category Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <p><strong>Name:</strong> {{ $category->name }}</p>
                                    <p><strong>Parent:</strong> {{ $category->parent?->name ?? '-' }}</p>
                                    <p><strong>Status:</strong> {{ $category->status == 'active' ? 'Active' : 'Inactive' }}
                                    </p>
                                    {{-- <p><strong>Slug:</strong> {{ $category->slug ?? '-' }}</
                                        p> --}}
                                    <hr>
                                </div>
                            </div>

                            <!-- Modal: Edit Category -->
                            <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('categories.update', $category->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-xl-12 mb-3">
                                                    <div class="d-flex align-items-start flex-wrap gap-3">
                                                        <div>
                                                            <span class="avatar avatar-xxl">
                                                                <img src="{{ $category->logo ? asset('storage/' . $category->logo) : asset('assets/images/company-logos/1.png') }}"
                                                                    alt="Category Logo"
                                                                    style="width:64px;height:64px;object-fit:cover;">
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="fw-medium d-block mb-2">Category Logo</span>
                                                            <div class="btn-list mb-1">
                                                                <label class="btn btn-sm btn-primary btn-wave mb-0">
                                                                    <i class="ri-upload-2-line me-1"></i>Change Image
                                                                    <input type="file" name="logo" class="d-none"
                                                                        accept="image/*">
                                                                </label>
                                                              
                                                            </div>
                                                            <span class="d-block fs-12 text-muted">
                                                                Use JPEG, PNG, or GIF. Best size: 200x200 pixels. Max 5MB.
                                                            </span>
                                                        </div>
                                                    </div>
                                                   
                                                </div>

                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $category->name) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Parent</label>
                                                    <select name="parent_id" class="form-select">
                                                        <option value="">-- None --</option>
                                                        @foreach ($parents as $p)
                                                            @if ($p->id !== $category->id)
                                                                <option value="{{ $p->id }}"
                                                                    {{ old('parent_id', $category->parent_id) == $p->id ? 'selected' : '' }}>
                                                                    {{ $p->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active"
                                                            {{ $category->status == 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ $category->status == 'inactive' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
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

                            <!-- Modal: Delete Category -->
                            <div class="modal fade" id="deleteCategory{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $category->name }}</strong>?
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
                                <td colspan="5" class="text-center text-muted">No categories found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer border-top-0">
                <div class="d-flex align-items-center">
                    <div>
                        @if ($categories->total())
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                            {{ $categories->total() }} entries
                        @endif
                    </div>
                    <div class="ms-auto">
                        <nav aria-label="Page navigation">
                            {{ $categories->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Create Category -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-xl-12 mb-3">
                            <div class="d-flex align-items-start flex-wrap gap-3">
                                <div>
                                    <span class="avatar avatar-xxl">
                                        <img src="{{ asset('assets/images/company-logos/1.png') }}" alt="Category Logo"
                                            style="width:64px;height:64px;object-fit:cover;">
                                    </span>
                                </div>
                                <div>
                                    <span class="fw-medium d-block mb-2">Category Logo</span>
                                    <div class="btn-list mb-1">
                                        <label class="btn btn-sm btn-primary btn-wave mb-0">
                                            <i class="ri-upload-2-line me-1"></i>Change Image
                                            <input type="file" name="logo" class="d-none" accept="image/*">
                                        </label>
                                    </div>
                                    <span class="d-block fs-12 text-muted">
                                        Use JPEG, PNG, or GIF. Best size: 200x200 pixels. Max 5MB.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Parent</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- None --</option>
                                @foreach ($parents as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active"
                                    {{ old('status', default: 'active') == 'active' ? 'selected' : '' }}>Active
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
