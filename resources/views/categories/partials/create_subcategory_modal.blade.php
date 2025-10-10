<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="ri-folder-2-line me-2"></i> Add Sub-Category
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('categories.store') }}" method="POST" id="subCategoryCreateForm" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Select Main Category <span class="text-danger">*</span></label>
                <select name="parent_id" class="form-control" required>
                    <option value="">Choose main category...</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Sub-Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="e.g., Brake Pads, Brake Discs">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logo (Optional)</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Max 2MB</small>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Sub-category description (optional)"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-info">
            <i class="ri-add-line me-1"></i> Add Sub-Category
        </button>
    </div>
</form>

