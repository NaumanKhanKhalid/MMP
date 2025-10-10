<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="ri-truck-add-line me-2"></i> Add New Supplier
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('suppliers.store') }}" method="POST" id="supplierCreateForm">
    @csrf
    
    <div class="modal-body p-0">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-fill" id="createSupplierTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-create" data-bs-toggle="tab" data-bs-target="#create-basic" type="button" role="tab">
                    <i class="ri-building-line me-1"></i> Basic Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab-create" data-bs-toggle="tab" data-bs-target="#create-contact" type="button" role="tab">
                    <i class="ri-phone-line me-1"></i> Contact
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="business-tab-create" data-bs-toggle="tab" data-bs-target="#create-business" type="button" role="tab">
                    <i class="ri-bank-card-line me-1"></i> Business
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="financial-tab-create" data-bs-toggle="tab" data-bs-target="#create-financial" type="button" role="tab">
                    <i class="ri-money-dollar-circle-line me-1"></i> Financial
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4" id="createSupplierTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="create-basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Code</label>
                        <input type="text" name="supplier_code" class="form-control" placeholder="Auto-generated if left empty">
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
                </div>
            </div>

            <!-- Contact Tab -->
            <div class="tab-pane fade" id="create-contact" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Business Tab -->
            <div class="tab-pane fade" id="create-business" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="payment_terms" class="form-control" required>
                            <option value="COD">COD</option>
                            <option value="7 days">7 days</option>
                            <option value="14 days">14 days</option>
                            <option value="30 days">30 days</option>
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
            </div>

            <!-- Financial Tab -->
            <div class="tab-pane fade" id="create-financial" role="tabpanel">
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
