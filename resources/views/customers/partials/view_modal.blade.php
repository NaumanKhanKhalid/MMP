
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-user-line me-2"></i> Customer Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-0">
    <!-- Customer Header Card -->
    <div class="bg-light p-4 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="ri-user-line fs-24"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $customer->display_name }}</h4>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info">{{ $customer->customer_code }}</span>
                            <span class="badge bg-{{ $customer->customer_type === 'business' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($customer->customer_type) }}
                            </span>
                            <span class="badge bg-{{ $customer->customer_status === 'active' ? 'success' : ($customer->customer_status === 'inactive' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($customer->customer_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">
                    <small>Customer Since</small><br>
                    <strong>{{ $customer->created_at->format('M d, Y') }}</strong>
                </div>
            </div>
        </div>
</div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-fill" id="viewCustomerTabs-{{ $customer->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-basic-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-user-line me-1"></i> Basic Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-contact-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-phone-line me-1"></i> Contact
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicle-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-vehicle-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-car-line me-1"></i> Vehicle
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="account-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-account-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-bank-card-line me-1"></i> Account
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preferences-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-preferences-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-settings-3-line me-1"></i> Preferences
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payments-tab-{{ $customer->id }}" data-bs-toggle="tab" data-bs-target="#view-payments-{{ $customer->id }}" type="button" role="tab">
                <i class="ri-money-dollar-circle-line me-1"></i> Payments & Ledger
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-4" id="viewCustomerTabContent-{{ $customer->id }}">
        <!-- Basic Info Tab -->
        <div class="tab-pane fade show active" id="view-basic-{{ $customer->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Full Name</label>
                    <p class="form-control-static">{{ $customer->name }}</p>
                </div>
                @if($customer->isBusiness() && $customer->company_name)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Company Name</label>
                        <p class="form-control-static">{{ $customer->company_name }}</p>
                    </div>
                @endif
                @if($customer->isIndividual() && $customer->date_of_birth)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Date of Birth</label>
                        <p class="form-control-static">{{ $customer->date_of_birth->format('M d, Y') }} ({{ $customer->age }} years old)</p>
                    </div>
                @endif
                @if($customer->tax_number)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Tax Number</label>
                        <p class="form-control-static">{{ $customer->tax_number }}</p>
                </div>
                @endif
                @if($customer->contact_person)
                <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Contact Person</label>
                        <p class="form-control-static">{{ $customer->contact_person }}</p>
                    </div>
                @endif
            </div>
                </div>

        <!-- Contact Tab -->
        <div class="tab-pane fade" id="view-contact-{{ $customer->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Email</label>
                    <p class="form-control-static">
                        @if($customer->email)
                            <i class="ri-mail-line me-2"></i>{{ $customer->email }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Phone</label>
                    <p class="form-control-static">
                        @if($customer->phone)
                            <i class="ri-phone-line me-2"></i>{{ $customer->phone }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold text-muted">Full Address</label>
                    <p class="form-control-static">
                        @if($customer->full_address)
                            <i class="ri-map-pin-line me-2"></i>{{ $customer->full_address }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Vehicle Tab -->
        <div class="tab-pane fade" id="view-vehicle-{{ $customer->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Make</label>
                    <p class="form-control-static">{{ $customer->vehicle_make ?: 'Not provided' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Model</label>
                    <p class="form-control-static">{{ $customer->vehicle_model ?: 'Not provided' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">VIN</label>
                    <p class="form-control-static">{{ $customer->vehicle_vin ?: 'Not provided' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Registration</label>
                    <p class="form-control-static">{{ $customer->vehicle_reg ?: 'Not provided' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Mileage</label>
                    <p class="form-control-static">{{ $customer->vehicle_mileage ?: 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Account Tab -->
        <div class="tab-pane fade" id="view-account-{{ $customer->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Payment Terms</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $customer->terms === 'on_account' ? 'warning' : 'success' }}">
                            {{ ucfirst($customer->terms) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Credit Limit</label>
                    <p class="form-control-static">
                        @if($customer->credit_limit > 0)
                            <span class="text-success fw-bold">R{{ number_format($customer->credit_limit, 2) }}</span>
                        @else
                            <span class="text-muted">No limit set</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Current Balance</label>
                    <p class="form-control-static">
                        <span class="fw-bold {{ $customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted') }}">
                            R{{ number_format($customer->balance, 2) }}
                        </span>
                        @if($customer->isOverCreditLimit())
                            <br><small class="text-danger"><i class="ri-alert-line me-1"></i>Over credit limit!</small>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Available Credit</label>
                    <p class="form-control-static">
                        <span class="text-info fw-bold">R{{ number_format($customer->available_credit, 2) }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Price Tier</label>
                    <p class="form-control-static">
                        <span class="badge bg-info">{{ ucfirst($customer->price_tier) }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Statement Delivery</label>
                    <p class="form-control-static">
                        <span class="badge bg-secondary">{{ ucfirst($customer->statement_delivery) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Preferences Tab -->
        <div class="tab-pane fade" id="view-preferences-{{ $customer->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Marketing Consent</label>
                    <p class="form-control-static">
                        @if($customer->marketing_consent)
                            <span class="badge bg-success"><i class="ri-check-line me-1"></i>Consented</span>
                        @else
                            <span class="badge bg-secondary"><i class="ri-close-line me-1"></i>Not Consented</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">SMS Consent</label>
                    <p class="form-control-static">
                        @if($customer->sms_consent)
                            <span class="badge bg-success"><i class="ri-check-line me-1"></i>Consented</span>
                        @else
                            <span class="badge bg-secondary"><i class="ri-close-line me-1"></i>Not Consented</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Customer Type</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $customer->customer_type === 'business' ? 'primary' : 'secondary' }}">
                            {{ ucfirst($customer->customer_type) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $customer->customer_status === 'active' ? 'success' : ($customer->customer_status === 'inactive' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($customer->customer_status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Payments & Ledger Tab -->
        <div class="tab-pane fade" id="view-payments-{{ $customer->id }}" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Payment History & Ledger</h6>
                <button type="button" class="btn btn-sm btn-primary" onclick="recordCustomerPayment({{ $customer->id }})" data-bs-dismiss="modal">
                    <i class="ri-money-dollar-circle-line me-1"></i> Record Payment
                </button>
            </div>

            <!-- Balance Summary -->
            <div class="alert alert-{{ $customer->balance > 0 ? 'warning' : 'success' }} mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Current Balance</small>
                        <strong class="fs-18">R {{ number_format($customer->balance, 2) }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Credit Limit</small>
                        <strong class="fs-18">R {{ number_format($customer->credit_limit, 2) }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Available Credit</small>
                        <strong class="fs-18">R {{ number_format($customer->available_credit, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Ledger Entries -->
            @if($customer->ledgerEntries->count() > 0)
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
                            @foreach($customer->ledgerEntries as $entry)
                            <tr>
                                <td>{{ $entry->transaction_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $entry->transaction_type === 'invoice' ? 'primary' : ($entry->transaction_type === 'payment' ? 'success' : 'warning') }}-transparent">
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
    <button type="button" class="btn btn-info" onclick="generateCustomerStatement({{ $customer->id }})" data-bs-dismiss="modal">
        <i class="ri-file-text-line me-1"></i> Generate Statement
    </button>
    <button type="button" class="btn btn-primary" onclick="recordCustomerPayment({{ $customer->id }})" data-bs-dismiss="modal">
        <i class="ri-money-dollar-circle-line me-1"></i> Record Payment
    </button>
    <button type="button" class="btn btn-warning openEditCustomerModal" data-id="{{ $customer->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Customer
    </button>
</div>

<script>
function recordCustomerPayment(customerId) {
    // Open payment modal with this customer pre-selected
    fetch(`{{ route('payments.create') }}?type=customer&customer_id=${customerId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('paymentModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('createPaymentModal'));
            
            // Pre-select the customer
            setTimeout(() => {
                const customerSelect = document.getElementById('customerId');
                if (customerSelect) {
                    customerSelect.value = customerId;
                    customerSelect.dispatchEvent(new Event('change'));
                }
            }, 100);
            
            modal.show();
        });
}

function generateCustomerStatement(customerId) {
    // Open statement form modal
    const url = "{{ url('statements/customer') }}/" + customerId + "/form";
    
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
