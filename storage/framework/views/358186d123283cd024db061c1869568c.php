<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="ri-folder-2-line me-2"></i> Add Sub-Category
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form id="subCategoryCreateForm" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    
    <div class="modal-body p-4">
        <div class="alert alert-info alert-sm mb-3">
            <small><i class="ri-information-line me-1"></i> You can create multiple sub-categories. Each will be automatically selected in the product form.</small>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Select Main Category <span class="text-danger">*</span></label>
                <select name="parent_id" class="form-control" required>
                    <option value="">Choose main category...</option>
                    <?php $__currentLoopData = \App\Models\Category::whereNull('parent_id')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($parent->id); ?>"><?php echo e($parent->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
            <i class="ri-check-line me-1"></i> Done
        </button>
        <button type="submit" class="btn btn-info" id="createSubCategoryBtn">
            <i class="ri-add-line me-1"></i> Add Sub-Category
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#subCategoryCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $('#createSubCategoryBtn');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="ri-loader-4-line me-1"></i> Creating...').prop('disabled', true);
        
        $.ajax({
            url: '<?php echo e(route("categories.store")); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Add new subcategory to the select dropdown
                    const subcategorySelect = $('select[name="subcategory_id"]');
                    const newOption = new Option(response.category.name, response.category.id, true, true);
                    subcategorySelect.append(newOption).trigger('change');
                    
                    // Show success message
                    toastr.success('Sub-category created successfully and selected!');
                    
                    // Reset form but keep modal open for more entries
                    $('#subCategoryCreateForm')[0].reset();
                    
                    // Focus on the name field for next entry
                    $('input[name="name"]').focus();
                } else {
                    toastr.error(response.message || 'Failed to create sub-category');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create sub-category';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/categories/partials/create_subcategory_modal.blade.php ENDPATH**/ ?>