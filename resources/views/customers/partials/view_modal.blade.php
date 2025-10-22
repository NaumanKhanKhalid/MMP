<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-user-line me-2"></i> Customer Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
    <!-- Customer Header -->
    <div class="bg-light p-3 rounded mb-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                        <h4 class="mb-1">{{ $customer->display_name }}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">{{ $customer->customer_code }}</span>
                    <span class="badge bg-{{ $customer->customer_type === 'business' ? 'success' : 'secondary' }}">
                                {{ ucfirst($customer->customer_type) }}
                            </span>
                    <span class="badge bg-{{ $customer->customer_status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($customer->customer_status) }}
                            </span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <small class="text-muted">Customer Since</small><br>
                <strong>{{ $customer->created_at->format('d M Y') }}</strong>
            </div>
        </div>
</div>

    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-user-line me-2"></i> Basic Information</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Name:</td>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Customer Code:</td>
                    <td><span class="badge bg-primary">{{ $customer->customer_code }}</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Type:</td>
                    <td>
                        <span class="badge bg-{{ $customer->customer_type === 'business' ? 'success' : 'secondary' }}">
                            {{ ucfirst($customer->customer_type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Status:</td>
                    <td>
                        <span class="badge bg-{{ $customer->customer_status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($customer->customer_status) }}
                        </span>
                    </td>
                </tr>
                @if($customer->isBusiness() && $customer->company_name)
                <tr>
                    <td class="fw-bold text-muted">Company:</td>
                    <td>{{ $customer->company_name }}</td>
                </tr>
                @endif
                @if($customer->isBusiness() && $customer->contact_person)
                <tr>
                    <td class="fw-bold text-muted">Contact Person:</td>
                    <td>{{ $customer->contact_person }}</td>
                </tr>
                @endif
            </table>

            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-phone-line me-2"></i> Contact Details</h6>
            
            <table class="table table-sm table-borderless">
                @if($customer->email)
                <tr>
                    <td width="40%" class="fw-bold text-muted">Email:</td>
                    <td><i class="ri-mail-line me-2"></i>{{ $customer->email }}</td>
                </tr>
                @endif
                @if($customer->phone)
                <tr>
                    <td class="fw-bold text-muted">Phone:</td>
                    <td><i class="ri-phone-line me-2"></i>{{ $customer->phone }}</td>
                </tr>
                @endif
                @if($customer->address)
                <tr>
                    <td class="fw-bold text-muted">Address:</td>
                    <td><i class="ri-map-pin-line me-2"></i>{{ $customer->address }}</td>
                </tr>
                        @endif
                @if($customer->city)
                <tr>
                    <td class="fw-bold text-muted">City:</td>
                    <td>{{ $customer->city }}</td>
                </tr>
                        @endif
            </table>

            <!-- Vehicles Section -->
            @if($customer->vehicles()->count() > 0)
            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-car-line me-2"></i> Vehicles</h6>
            
            @foreach($customer->vehicles as $vehicle)
            <div class="border rounded p-3 mb-2 {{ $vehicle->is_primary ? 'border-success' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">
                        {{ $vehicle->make->name ?? 'N/A' }} {{ $vehicle->model->name ?? '' }}
                        @if($vehicle->year)
                            <small class="text-muted">({{ $vehicle->year }})</small>
                        @endif
                    </h6>
                    @if($vehicle->is_primary)
                        <span class="badge bg-success-transparent">Primary</span>
                    @endif
                </div>
                
                <table class="table table-sm table-borderless mb-0">
                    @if($vehicle->engine)
                    <tr>
                        <td width="40%" class="fw-bold text-muted small">Engine:</td>
                        <td class="small">{{ $vehicle->engine }}</td>
                    </tr>
                    @endif
                    @if($vehicle->registration_number)
                    <tr>
                        <td class="fw-bold text-muted small">Registration:</td>
                        <td class="small">{{ $vehicle->registration_number }}</td>
                    </tr>
                    @endif
                    @if($vehicle->vin_number)
                    <tr>
                        <td class="fw-bold text-muted small">VIN:</td>
                        <td class="small">{{ $vehicle->vin_number }}</td>
                    </tr>
                    @endif
                    @if($vehicle->color)
                    <tr>
                        <td class="fw-bold text-muted small">Color:</td>
                        <td class="small">{{ $vehicle->color }}</td>
                    </tr>
                    @endif
                    @if($vehicle->mileage)
                    <tr>
                        <td class="fw-bold text-muted small">Mileage:</td>
                        <td class="small">{{ $vehicle->mileage }} km</td>
                    </tr>
                    @endif
                </table>
            </div>
            @endforeach
            @endif
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">
            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-bank-card-line me-2"></i> Account Details</h6>
            
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="40%" class="fw-bold text-muted">Customer Type:</td>
                    <td>
                        <span class="badge bg-{{ $customer->customer_type === 'business' ? 'success' : 'secondary' }}">
                            {{ ucfirst($customer->customer_type) }}
                        </span>
                    </td>
                </tr>
                @if($customer->customer_type === 'credit' && $customer->credit_limit > 0)
                <tr>
                    <td class="fw-bold text-muted">Credit Limit:</td>
                    <td>
                            <span class="text-success fw-bold">R{{ number_format($customer->credit_limit, 2) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Current Balance:</td>
                    <td>
                        <span class="fw-bold {{ $customer->balance < 0 ? 'text-danger' : ($customer->balance > 0 ? 'text-success' : 'text-muted') }}">
                            R{{ number_format($customer->balance, 2) }}
                        </span>
                        @if($customer->isOverCreditLimit())
                            <br><small class="text-danger"><i class="ri-alert-line me-1"></i>Over credit limit!</small>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted">Available Credit:</td>
                    <td><span class="text-info fw-bold">R{{ number_format($customer->available_credit, 2) }}</span></td>
                </tr>
                        @endif
            </table>

            @if($customer->notes)
            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-file-text-line me-2"></i> Notes</h6>
            <div class="bg-light p-3 rounded">
                <p class="mb-0">{{ $customer->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditCustomerModal" data-id="{{ $customer->id }}">
        <i class="ri-pencil-line me-1"></i> Edit Customer
    </button>
</div>