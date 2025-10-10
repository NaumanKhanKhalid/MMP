<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="ri-folder-line me-2"></i> Category Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4">
    <div class="row">
        <!-- Logo -->
        @if($category->logo)
            <div class="col-12 mb-4 text-center">
                <img src="{{ asset($category->logo) }}" alt="{{ $category->name }}" class="rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
            </div>
        @endif

        <!-- Category Name -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-folder-line me-1"></i> Category Name</label>
            <p class="mb-0">{{ $category->name }}</p>
        </div>

        <!-- Type -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-node-tree me-1"></i> Type</label>
            <p class="mb-0">
                @if($category->parent)
                    <span class="badge bg-secondary">Sub-category of {{ $category->parent->name }}</span>
                @else
                    <span class="badge bg-success">Main Category</span>
                @endif
            </p>
        </div>

        <!-- Status -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-toggle-line me-1"></i> Status</label>
            <p class="mb-0">
                <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($category->status) }}
                </span>
            </p>
        </div>

        <!-- Total Products -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-muted"><i class="ri-shopping-bag-line me-1"></i> Total Products</label>
            <p class="mb-0">
                <span class="badge bg-primary">{{ $category->products->count() ?? 0 }}</span>
            </p>
        </div>

        <!-- Description -->
        @if($category->description)
            <div class="col-12 mb-3">
                <label class="form-label fw-bold text-muted"><i class="ri-file-text-line me-1"></i> Description</label>
                <p class="mb-0">{{ $category->description }}</p>
            </div>
        @endif

        <!-- Sub-categories -->
        @if($category->children && $category->children->count() > 0)
            <div class="col-12 mb-3">
                <label class="form-label fw-bold text-muted"><i class="ri-folder-2-line me-1"></i> Sub-categories ({{ $category->children->count() }})</label>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($category->children as $child)
                        <span class="badge bg-light text-dark border">{{ $child->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Created Date -->
        <div class="col-12 mt-3 pt-3 border-top">
            <small class="text-muted">
                <i class="ri-calendar-line me-1"></i> Created: {{ $category->created_at->format('d M Y, h:i A') }}
            </small>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditCategoryModal" data-id="{{ $category->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Category
    </button>
</div>
