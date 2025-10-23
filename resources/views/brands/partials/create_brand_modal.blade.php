<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-award-line me-2"></i> Add New Brand
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form id="brandCreateForm" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body p-4">
        <div class="alert alert-info alert-sm mb-3">
            <small><i class="ri-information-line me-1"></i> You can create multiple brands. Each will be automatically selected in the product form.</small>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Brand Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="e.g., Toyota, BMW, Bosch">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Brand Code</label>
                <input type="text" name="code" class="form-control" placeholder="e.g., TOY, BMW, BOS">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Logo (Optional)</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Max 2MB</small>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brand description (optional)"></textarea>
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
        <button type="submit" class="btn btn-primary" id="createBrandBtn">
            <i class="ri-add-line me-1"></i> Add Brand
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#brandCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $('#createBrandBtn');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="ri-loader-4-line me-1"></i> Creating...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("brands.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Add new brand to the select dropdown
                    const brandSelect = $('select[name="brand_id"]');
                    const newOption = new Option(response.brand.name, response.brand.id, true, true);
                    brandSelect.append(newOption).trigger('change');
                    
                    // Show success message
                    toastr.success('Brand created successfully and selected!');
                    
                    // Reset form but keep modal open for more entries
                    $('#brandCreateForm')[0].reset();
                    
                    // Focus on the name field for next entry
                    $('input[name="name"]').focus();
                } else {
                    toastr.error(response.message || 'Failed to create brand');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create brand';
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
