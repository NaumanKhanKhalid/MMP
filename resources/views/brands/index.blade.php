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
            <button class="btn btn-primary" id="openCreateBrandModal">
                <i class="ri-add-line me-1"></i> Add Brand
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
                            <th>Products</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @if ($brand->logo)
                                                <img src="{{ asset($brand->logo) }}" alt="Logo" width="40" height="40" class="rounded-circle">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ri-award-line text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $brand->name }}</h6>
                                            @if($brand->slug)
                                                <small class="text-muted">{{ $brand->slug }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset($brand->logo) }}" alt="Logo" width="50" height="50" class="rounded">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($brand->status == 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $brand->products_count ?? 0 }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View -->
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewBrandModal" data-id="{{ $brand->id }}" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditBrandModal" data-id="{{ $brand->id }}" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <!-- Status Toggle -->
                                        <form method="POST" action="{{ route('brands.toggle-status', $brand->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $brand->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="{{ $brand->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-toggle-{{ $brand->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteBrand{{ $brand->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteBrand{{ $brand->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('brands.destroy', $brand->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="ri-delete-bin-line me-2"></i> Delete Brand
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <!-- Brand Info -->
                                                <div class="mb-4">
                                                    @if($brand->logo)
                                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                                            <i class="ri-award-line text-muted fs-24"></i>
                                                        </div>
                                                    @endif
                                                    <h5 class="mb-1">{{ $brand->name }}</h5>
                                                    <span class="badge bg-{{ $brand->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($brand->status) }}
                                                    </span>
                                                </div>

                                                <!-- Simple Warning -->
                                                <div class="alert alert-warning" role="alert">
                                                    <i class="ri-alert-line me-2"></i>
                                                    <strong>Are you sure?</strong> This brand will be moved to trash and can be restored later.
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    <i class="ri-close-line me-1"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ri-delete-bin-line me-1"></i> Delete Brand
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No brands found</td>
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
                            {{ $brands->total() }} results
                        @endif
                    </div>
                    <div class="ms-auto">
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Modals -->
    <div class="modal fade" id="brandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="brandModalContent"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Create Brand
            $('#openCreateBrandModal').on('click', function() {
                $.get("{{ route('brands.create-modal') }}", function(html) {
                    $('#brandModalContent').html(html);
                    $('#brandModal').modal('show');
                });
            });

            // View Brand
            $(document).on('click', '.openViewBrandModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('brands.view-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#brandModalContent').html(html);
                    $('#brandModal').modal('show');
                });
            });

            // Edit Brand
            $(document).on('click', '.openEditBrandModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('brands.edit-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#brandModalContent').html(html);
                    $('#brandModal').modal('show');
                });
            });
        });
    </script>
@endpush