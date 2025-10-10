@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Sub-Categories</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Sub-Categories</h4>
            <button class="btn btn-info" id="openCreateSubCategoryModal">
                <i class="ri-folder-2-line me-1"></i> Add Sub-Category
            </button>
        </div>

        <!-- Sub-Categories Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                <td>
                                    <div class="avatar avatar-sm p-1 bg-light avatar-rounded">
                                        @if ($category->logo)
                                            <img src="{{ asset($category->logo) }}" alt="{{ $category->name }}" style="width:40px;height:40px;object-fit:cover;">
                                        @else
                                            <i class="ri-folder-2-line fs-20"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $category->parent->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->products->count() }}</span>
                                </td>
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
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewCategoryModal" data-id="{{ $category->id }}" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditCategoryModal" data-id="{{ $category->id }}" title="Edit">
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

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteCategory{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Delete Sub-Category</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>
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
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-folder-2-line fs-48 mb-2"></i>
                                    <p>No sub-categories found</p>
                                </td>
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
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                        @endif
                    </div>
                    <div class="ms-auto">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Category Modals -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="categoryModalContent"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Create Sub-Category
            $('#openCreateSubCategoryModal').on('click', function() {
                $.get("{{ route('categories.create-subcategory-modal') }}", function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });

            // View Category
            $(document).on('click', '.openViewCategoryModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('categories.view-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });

            // Edit Category
            $(document).on('click', '.openEditCategoryModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('categories.edit-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#categoryModalContent').html(html);
                    $('#categoryModal').modal('show');
                });
            });
        });

       
    </script>
@endpush

