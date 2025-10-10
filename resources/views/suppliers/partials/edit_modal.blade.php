<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-pencil-line me-2"></i> Edit Supplier
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('suppliers.update', $supplier) }}" method="POST" id="supplierEditForm">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-0">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-fill" id="editSupplierTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-basic" type="button" role="tab">
                    <i class="ri-building-line me-1"></i> Basic Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-contact" type="button" role="tab">
                    <i class="ri-phone-line me-1"></i> Contact
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="business-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-business" type="button" role="tab">
                    <i class="ri-bank-card-line me-1"></i> Business
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="financial-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-financial" type="button" role="tab">
                    <i class="ri-money-dollar-circle-line me-1"></i> Financial
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4" id="editSupplierTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="edit-basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Code</label>
                        <input type="text" name="supplier_code" class="form-control" value="{{ $supplier->supplier_code }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Type <span class="text-danger">*</span></label>
                        <select name="supplier_type" class="form-control" required>
                            <option value="company" @if($supplier->supplier_type=='company') selected @endif>Company</option>
                            <option value="individual" @if($supplier->supplier_type=='individual') selected @endif>Individual</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" @if($supplier->status=='active') selected @endif>Active</option>
                            <option value="inactive" @if($supplier->status=='inactive') selected @endif>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ $supplier->contact_person }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tax Number</label>
                        <input type="text" name="tax_number" class="form-control" value="{{ $supplier->tax_number }}">
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div class="tab-pane fade" id="edit-contact" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $supplier->address }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Business Tab -->
            <div class="tab-pane fade" id="edit-business" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="payment_terms" class="form-control" required>
                            <option value="COD" @if($supplier->payment_terms=='COD') selected @endif>COD</option>
                            <option value="7 days" @if($supplier->payment_terms=='7 days') selected @endif>7 days</option>
                            <option value="14 days" @if($supplier->payment_terms=='14 days') selected @endif>14 days</option>
                            <option value="30 days" @if($supplier->payment_terms=='30 days') selected @endif>30 days</option>
                            <option value="60 days" @if($supplier->payment_terms=='60 days') selected @endif>60 days</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Lead Time (Days)</label>
                        <input type="number" name="lead_time" class="form-control" value="{{ $supplier->lead_time }}" min="0" max="365">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Bank Details</label>
                        <textarea name="bank_details" class="form-control" rows="2" placeholder="Bank name, account number, etc.">{{ $supplier->bank_details }}</textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes about this supplier">{{ $supplier->notes }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Financial Tab -->
            <div class="tab-pane fade" id="edit-financial" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" value="{{ $supplier->credit_limit }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Current Balance</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" class="form-control" value="{{ $supplier->balance }}" readonly>
                        </div>
                        <small class="text-muted">This is calculated from transactions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ri-save-line me-1"></i> Update Supplier
        </button>
    </div>
</form>
