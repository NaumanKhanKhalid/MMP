<div class="modal-header">
    <h5 class="modal-title">
        <i class="ri-file-add-line me-2"></i> New Stock Count
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="createStockCountForm">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            <!-- Count Name -->
            <div class="col-12">
                <label class="form-label fw-semibold">
                    <i class="ri-text me-1"></i> Count Name <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="count_name" 
                       class="form-control" 
                       placeholder="e.g., Monthly Count - October 2025" 
                       required>
                <small class="text-muted">Give this count a descriptive name</small>
            </div>

            <!-- Count Date -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="ri-calendar-line me-1"></i> Count Date <span class="text-danger">*</span>
                </label>
                <input type="date" 
                       name="count_date" 
                       class="form-control" 
                       value="{{ date('Y-m-d') }}" 
                       required>
            </div>

            <div class="col-12">
                <hr class="my-2">
            </div>

            <!-- Filters Section -->
            <div class="col-12">
                <h6 class="mb-2">
                    <i class="ri-filter-3-line me-2 text-primary"></i> Filters (Optional)
                </h6>
                <p class="text-muted small mb-3">
                    <i class="ri-information-line"></i> Apply filters to count specific products only. Leave blank to count all products.
                </p>
            </div>

            <!-- Category Filter -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class="ri-folder-line me-1"></i> Category
                </label>
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Brand Filter -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class="ri-price-tag-3-line me-1"></i> Brand
                </label>
                <select name="brand_id" class="form-select">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bin Location Filter -->
            <div class="col-md-6">
                <label class="form-label">
                    <i class="ri-map-pin-line me-1"></i> Bin Location
                </label>
                <input type="text" 
                       name="bin_location" 
                       class="form-control" 
                       placeholder="e.g., A-16">
                <small class="text-muted">Filter by bin/aisle location</small>
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label">
                    <i class="ri-sticky-note-line me-1"></i> Notes
                </label>
                <textarea name="notes" 
                          class="form-control" 
                          rows="2" 
                          placeholder="Optional notes about this count..."></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="submitBtn" onclick="submitStockCountForm()">
            <i class="ri-add-line me-1"></i> Create Stock Count
        </button>
    </div>
</form>

