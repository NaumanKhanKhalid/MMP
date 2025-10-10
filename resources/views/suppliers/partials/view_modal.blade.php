<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="ri-truck-line me-2"></i> Supplier Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-0">
    <!-- Supplier Header Card -->
    <div class="bg-light p-4 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="ri-truck-line fs-24"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $supplier->name }}</h4>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary">{{ $supplier->supplier_code }}</span>
                            <span class="badge bg-{{ $supplier->supplier_type === 'company' ? 'success' : 'secondary' }}">
                                {{ ucfirst($supplier->supplier_type) }}
                            </span>
                            <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">
                    <small>Supplier Since</small><br>
                    <strong>{{ $supplier->created_at}}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-fill" id="viewSupplierTabs-{{ $supplier->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $supplier->id }}" data-bs-toggle="tab" data-bs-target="#view-basic-{{ $supplier->id }}" type="button" role="tab">
                <i class="ri-building-line me-1"></i> Basic Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab-{{ $supplier->id }}" data-bs-toggle="tab" data-bs-target="#view-contact-{{ $supplier->id }}" type="button" role="tab">
                <i class="ri-phone-line me-1"></i> Contact
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="business-tab-{{ $supplier->id }}" data-bs-toggle="tab" data-bs-target="#view-business-{{ $supplier->id }}" type="button" role="tab">
                <i class="ri-bank-card-line me-1"></i> Business
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="financial-tab-{{ $supplier->id }}" data-bs-toggle="tab" data-bs-target="#view-financial-{{ $supplier->id }}" type="button" role="tab">
                <i class="ri-money-dollar-circle-line me-1"></i> Financial
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payments-tab-{{ $supplier->id }}" data-bs-toggle="tab" data-bs-target="#view-payments-{{ $supplier->id }}" type="button" role="tab">
                <i class="ri-wallet-line me-1"></i> Payments & Ledger
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-4" id="viewSupplierTabContent-{{ $supplier->id }}">
        <!-- Basic Info Tab -->
        <div class="tab-pane fade show active" id="view-basic-{{ $supplier->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Supplier Name</label>
                    <p class="form-control-static">{{ $supplier->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Supplier Code</label>
                    <p class="form-control-static">
                        <span class="badge bg-primary">{{ $supplier->supplier_code }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Supplier Type</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $supplier->supplier_type === 'company' ? 'success' : 'secondary' }}">
                            {{ ucfirst($supplier->supplier_type) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </p>
                </div>
                @if($supplier->contact_person)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Contact Person</label>
                        <p class="form-control-static">{{ $supplier->contact_person }}</p>
                    </div>
                @endif
                @if($supplier->tax_number)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Tax Number</label>
                        <p class="form-control-static">{{ $supplier->tax_number }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Tab -->
        <div class="tab-pane fade" id="view-contact-{{ $supplier->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Email</label>
                    <p class="form-control-static">
                        @if($supplier->email)
                            <i class="ri-mail-line me-2"></i>{{ $supplier->email }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Phone</label>
                    <p class="form-control-static">
                        @if($supplier->phone)
                            <i class="ri-phone-line me-2"></i>{{ $supplier->phone }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold text-muted">Address</label>
                    <p class="form-control-static">
                        @if($supplier->address)
                            <i class="ri-map-pin-line me-2"></i>{{ $supplier->address }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Business Tab -->
        <div class="tab-pane fade" id="view-business-{{ $supplier->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Payment Terms</label>
                    <p class="form-control-static">
                        <span class="badge bg-info">{{ $supplier->payment_terms }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Lead Time</label>
                    <p class="form-control-static">
                        @if($supplier->lead_time)
                            <span class="text-primary fw-bold">{{ $supplier->lead_time }} days</span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                    </p>
                </div>
                @if($supplier->bank_details)
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-muted">Bank Details</label>
                        <p class="form-control-static">{{ $supplier->bank_details }}</p>
                    </div>
                @endif
                @if($supplier->notes)
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-muted">Notes</label>
                        <p class="form-control-static">{{ $supplier->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Financial Tab -->
        <div class="tab-pane fade" id="view-financial-{{ $supplier->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Credit Limit</label>
                    <p class="form-control-static">
                        @if($supplier->credit_limit > 0)
                            <span class="text-success fw-bold">R{{ number_format($supplier->credit_limit, 2) }}</span>
                        @else
                            <span class="text-muted">No limit set</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Current Balance</label>
                    <p class="form-control-static">
                        <span class="fw-bold {{ $supplier->balance < 0 ? 'text-danger' : ($supplier->balance > 0 ? 'text-success' : 'text-muted') }}">
                            R{{ number_format($supplier->balance, 2) }}
                        </span>
                        @if($supplier->isOverCreditLimit())
                            <br><small class="text-danger"><i class="ri-alert-line me-1"></i>Over credit limit!</small>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Available Credit</label>
                    <p class="form-control-static">
                        <span class="text-info fw-bold">R{{ number_format($supplier->available_credit, 2) }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Credit Status</label>
                    <p class="form-control-static">
                        @if($supplier->hasCreditLimit())
                            @if($supplier->isOverCreditLimit())
                                <span class="badge bg-danger">Over Limit</span>
                            @else
                                <span class="badge bg-success">Within Limit</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">No Credit Limit</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Payments & Ledger Tab -->
        <div class="tab-pane fade" id="view-payments-{{ $supplier->id }}" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Payment History & Ledger</h6>
                <button type="button" class="btn btn-sm btn-success" onclick="recordSupplierPayment({{ $supplier->id }})" data-bs-dismiss="modal">
                    <i class="ri-wallet-line me-1"></i> Record Payment
                </button>
            </div>

            <!-- Balance Summary -->
            <div class="alert alert-{{ $supplier->balance > 0 ? 'success' : 'info' }} mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Amount We Owe</small>
                        <strong class="fs-18">R {{ number_format($supplier->balance, 2) }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Credit Limit</small>
                        <strong class="fs-18">R {{ number_format($supplier->credit_limit, 2) }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Available Credit</small>
                        <strong class="fs-18">R {{ number_format($supplier->available_credit, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Ledger Entries -->
            @if($supplier->ledgerEntries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Document #</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->ledgerEntries as $entry)
                            <tr>
                                <td>{{ $entry->transaction_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $entry->transaction_type === 'supplier_invoice' ? 'primary' : ($entry->transaction_type === 'payment' ? 'success' : 'warning') }}-transparent">
                                        {{ ucfirst(str_replace('_', ' ', $entry->transaction_type)) }}
                                    </span>
                                </td>
                                <td>{{ $entry->document_number }}</td>
                                <td class="text-end {{ $entry->debit > 0 ? 'text-danger' : '' }}">
                                    {{ $entry->debit > 0 ? 'R ' . number_format($entry->debit, 2) : '-' }}
                                </td>
                                <td class="text-end {{ $entry->credit > 0 ? 'text-success' : '' }}">
                                    {{ $entry->credit > 0 ? 'R ' . number_format($entry->credit, 2) : '-' }}
                                </td>
                                <td class="text-end fw-bold">R {{ number_format($entry->balance, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i> No transactions yet
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    @if(auth()->user()->isOwner())
    <button type="button" class="btn btn-info" onclick="generateSupplierStatement({{ $supplier->id }})" data-bs-dismiss="modal">
        <i class="ri-file-text-line me-1"></i> Generate Statement
    </button>
    @endif
    <button type="button" class="btn btn-success" onclick="recordSupplierPayment({{ $supplier->id }})" data-bs-dismiss="modal">
        <i class="ri-wallet-line me-1"></i> Record Payment
    </button>
    <button type="button" class="btn btn-warning openEditSupplierModal" data-id="{{ $supplier->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Supplier
    </button>
</div>

<script>
function recordSupplierPayment(supplierId) {
    // Open payment modal with this supplier pre-selected
    fetch(`{{ route('payments.create') }}?type=supplier&supplier_id=${supplierId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('paymentModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('createPaymentModal'));
            
            // Pre-select the supplier
            setTimeout(() => {
                const supplierSelect = document.getElementById('supplierId');
                if (supplierSelect) {
                    supplierSelect.value = supplierId;
                    supplierSelect.dispatchEvent(new Event('change'));
                }
            }, 100);
            
            modal.show();
        });
}

function generateSupplierStatement(supplierId) {
    // Open statement form modal
    const url = "{{ url('statements/supplier') }}/" + supplierId + "/form";
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Create or get statement modal container
            let modalContainer = document.getElementById('statementModalContainer');
            if (!modalContainer) {
                modalContainer = document.createElement('div');
                modalContainer.id = 'statementModalContainer';
                document.body.appendChild(modalContainer);
            }
            
            modalContainer.innerHTML = `
                <div class="modal fade" id="statementFormModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            ${html}
                        </div>
                    </div>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('statementFormModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading statement form. Please try again.');
        });
}
</script>
