<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-award-line me-2"></i> Brand Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4">
    <div class="row">
        <!-- Logo -->
        @if($brand->logo)
            <div class="col-12 mb-4 text-center">
                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
            </div>
        @endif

        <!-- Brand Name -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-award-line me-1"></i> Brand Name</label>
            <p class="mb-0">{{ $brand->name }}</p>
        </div>

        <!-- Status -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-toggle-line me-1"></i> Status</label>
            <p class="mb-0">
                <span class="badge bg-{{ $brand->status === 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($brand->status) }}
                </span>
            </p>
        </div>

        <!-- Total Products -->
        <div class="col-12 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-shopping-bag-line me-1"></i> Total Products</label>
            <p class="mb-0">
                <span class="badge bg-primary">{{ $brand->products->count() ?? 0 }}</span>
            </p>
        </div>

        <!-- Description -->
        @if($brand->description)
            <div class="col-12 mb-3">
                <label class="form-label fw-bold text-muted"><i class="ri-file-text-line me-1"></i> Description</label>
                <p class="mb-0">{{ $brand->description }}</p>
            </div>
        @endif

        <!-- Created Date -->
        <div class="col-12 mt-3 pt-3 border-top">
            <small class="text-muted">
                <i class="ri-calendar-line me-1"></i> Created: {{ $brand->created_at->format('d M Y, h:i A') }}
            </small>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditBrandModal" data-id="{{ $brand->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Brand
    </button>
</div>
