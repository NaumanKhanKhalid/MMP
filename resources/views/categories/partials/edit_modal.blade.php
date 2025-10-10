<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-pencil-line me-2"></i> Edit Category
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('categories.update', $category) }}" method="POST" id="categoryEditForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="active" @if($category->status=='active') selected @endif>Active</option>
                    <option value="inactive" @if($category->status=='inactive') selected @endif>Inactive</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Logo (Optional)</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Max 2MB</small>
                @if($category->logo)
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                        <label class="form-check-label" for="remove_logo">Remove current logo</label>
                    </div>
                @endif
            </div>
            @if($category->logo)
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Current Logo</label>
                    <div>
                        <img src="{{ asset($category->logo) }}" alt="{{ $category->name }}" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    </div>
                </div>
            @endif
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Category description (optional)">{{ $category->description }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ri-save-line me-1"></i> Update Category
        </button>
    </div>
</form>
