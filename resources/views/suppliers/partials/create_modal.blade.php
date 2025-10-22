<div class="modal-header">
    <h5 class="modal-title">
        <i class="ri-truck-add-line me-2"></i> Add New Supplier
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('suppliers.store') }}" method="POST" id="supplierCreateForm">
    @csrf
    
    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-md-6">
                <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-building-line me-2"></i> Basic Information</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Code</label>
                        <input type="text" name="supplier_code" class="form-control" placeholder="Auto-generated">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Type <span class="text-danger">*</span></label>
                        <select name="supplier_type" class="form-control" required>
                            <option value="company">Company</option>
                            <option value="individual">Individual</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tax Number</label>
                        <input type="text" name="tax_number" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-phone-line me-2"></i> Contact Details</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-6">
                <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-bank-card-line me-2"></i> Business Details</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="payment_terms" class="form-control" required>
                            <option value="COD">COD</option>
                            <option value="7 days">7 days</option>
                            <option value="14 days">14 days</option>
                            <option value="30 days" selected>30 days</option>
                            <option value="60 days">60 days</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Lead Time (Days)</label>
                        <input type="number" name="lead_time" class="form-control" min="0" max="365" value="0">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Bank Details</label>
                        <textarea name="bank_details" class="form-control" rows="2" placeholder="Bank name, account number, etc."></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes about this supplier"></textarea>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-money-dollar-circle-line me-2"></i> Financial Information</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Initial Balance</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="balance" class="form-control" step="0.01" value="0">
                        </div>
                        <small class="text-muted">Starting balance for this supplier</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-success">
            <i class="ri-add-line me-1"></i> Add Supplier
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#supplierCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Adding...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Supplier created successfully!');
                $('#supplierModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Failed to create supplier';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
