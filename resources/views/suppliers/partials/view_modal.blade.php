<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="ri-truck-line me-2"></i> Supplier Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
    <!-- Supplier Header -->
    <div class="bg-light p-3 rounded mb-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                        <h4 class="mb-1">{{ $supplier->name }}</h4>
                <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">{{ $supplier->supplier_code }}</span>
                            <span class="badge bg-{{ $supplier->supplier_type === 'company' ? 'success' : 'secondary' }}">
                                {{ ucfirst($supplier->supplier_type) }}
                            </span>
                            <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <small class="text-muted">Supplier Since</small><br>
                <strong>{{ $supplier->created_at->format('d M Y') }}</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-building-line me-2"></i> Basic Information</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Name:</td>
                    <td>{{ $supplier->name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Supplier Code:</td>
                    <td><span class="badge bg-primary">{{ $supplier->supplier_code }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Type:</td>
                    <td>
                        <span class="badge bg-{{ $supplier->supplier_type === 'company' ? 'success' : 'secondary' }}">
                            {{ ucfirst($supplier->supplier_type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Status:</td>
                    <td>
                        <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </td>
                </tr>
                @if($supplier->contact_person)
                <tr>
                    <td class="fw-bold text-muted">Contact Person:</td>
                    <td>{{ $supplier->contact_person }}</td>
                </tr>
                @endif
                @if($supplier->tax_number)
                <tr>
                    <td class="fw-bold text-muted">Tax Number:</td>
                    <td>{{ $supplier->tax_number }}</td>
                </tr>
                @endif
            </table>

            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-phone-line me-2"></i> Contact Details</h6>
            
            <table class="table table-sm table-borderless">
                        @if($supplier->email)
                <tr>
                    <td width="40%" class="fw-bold text-muted">Email:</td>
                    <td><i class="ri-mail-line me-2"></i>{{ $supplier->email }}</td>
                </tr>
                        @endif
                        @if($supplier->phone)
                <tr>
                    <td class="fw-bold text-muted">Phone:</td>
                    <td><i class="ri-phone-line me-2"></i>{{ $supplier->phone }}</td>
                </tr>
                        @endif
                        @if($supplier->address)
                <tr>
                    <td class="fw-bold text-muted">Address:</td>
                    <td><i class="ri-map-pin-line me-2"></i>{{ $supplier->address }}</td>
                </tr>
                        @endif
            </table>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-bank-card-line me-2"></i> Business Details</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Payment Terms:</td>
                    <td><span class="badge bg-warning">{{ $supplier->payment_terms }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Lead Time:</td>
                    <td>
                        @if($supplier->lead_time)
                            <span class="text-primary fw-bold">{{ $supplier->lead_time }} days</span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                    </td>
                </tr>
                @if($supplier->bank_details)
                <tr>
                    <td class="fw-bold text-muted">Bank Details:</td>
                    <td>{{ $supplier->bank_details }}</td>
                </tr>
                @endif
                @if($supplier->notes)
                <tr>
                    <td class="fw-bold text-muted">Notes:</td>
                    <td>{{ $supplier->notes }}</td>
                </tr>
                @endif
            </table>

            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-money-dollar-circle-line me-2"></i> Financial Summary</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Credit Limit:</td>
                    <td>
                        @if($supplier->credit_limit > 0)
                            <span class="text-success fw-bold">R{{ number_format($supplier->credit_limit, 2) }}</span>
                        @else
                            <span class="text-muted">No limit set</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Current Balance:</td>
                    <td>
                        <span class="fw-bold {{ $supplier->balance < 0 ? 'text-danger' : ($supplier->balance > 0 ? 'text-success' : 'text-muted') }}">
                            R{{ number_format($supplier->balance, 2) }}
                        </span>
                        @if($supplier->isOverCreditLimit())
                            <br><small class="text-danger"><i class="ri-alert-line me-1"></i>Over credit limit!</small>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Available Credit:</td>
                    <td><span class="text-info fw-bold">R{{ number_format($supplier->available_credit, 2) }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Credit Status:</td>
                    <td>
                        @if($supplier->hasCreditLimit())
                            @if($supplier->isOverCreditLimit())
                                <span class="badge bg-danger">Over Limit</span>
                            @else
                                <span class="badge bg-success">Within Limit</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">No Credit Limit</span>
                        @endif
                                </td>
                            </tr>
                    </table>
        </div>
    </div>

</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditSupplierModal" data-id="{{ $supplier->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Supplier
    </button>
</div>
