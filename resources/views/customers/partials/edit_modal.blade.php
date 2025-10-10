
<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-pencil-line me-2"></i> Edit Customer
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('customers.update', $customer) }}" method="POST" id="customerEditForm">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-0">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-fill" id="editCustomerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-basic" type="button" role="tab">
                    <i class="ri-user-line me-1"></i> Basic Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-contact" type="button" role="tab">
                    <i class="ri-phone-line me-1"></i> Contact
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vehicle-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-vehicle" type="button" role="tab">
                    <i class="ri-car-line me-1"></i> Vehicle
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="account-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-account" type="button" role="tab">
                    <i class="ri-bank-card-line me-1"></i> Account
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="preferences-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-preferences" type="button" role="tab">
                    <i class="ri-settings-3-line me-1"></i> Preferences
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4" id="editCustomerTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="edit-basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Type <span class="text-danger">*</span></label>
                        <select name="customer_type" class="form-control" required>
                            <option value="individual" @if($customer->customer_type=='individual') selected @endif>Individual</option>
                            <option value="business" @if($customer->customer_type=='business') selected @endif>Business</option>
                        </select>
                    </div>
                    @if($customer->isBusiness())
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $customer->company_name }}">
                        </div>
                    @endif
                    @if($customer->isIndividual())
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tax Number</label>
                        <input type="text" name="tax_number" class="form-control" value="{{ $customer->tax_number }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ $customer->contact_person }}">
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div class="tab-pane fade" id="edit-contact" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ $customer->city }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ $customer->postal_code }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ $customer->country }}">
                    </div>
                </div>
            </div>

            <!-- Vehicle Tab -->
            <div class="tab-pane fade" id="edit-vehicle" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Make</label>
                        <input type="text" name="vehicle_make" class="form-control" value="{{ $customer->vehicle_make }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Model</label>
                        <input type="text" name="vehicle_model" class="form-control" value="{{ $customer->vehicle_model }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">VIN</label>
                        <input type="text" name="vehicle_vin" class="form-control" value="{{ $customer->vehicle_vin }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control" value="{{ $customer->vehicle_reg }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control" value="{{ $customer->vehicle_mileage }}">
                    </div>
                </div>
            </div>

            <!-- Account Tab -->
            <div class="tab-pane fade" id="edit-account" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="terms" class="form-control" required>
                            <option value="cash" @if($customer->terms=='cash') selected @endif>Cash</option>
                            <option value="on_account" @if($customer->terms=='on_account') selected @endif>On Account</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" value="{{ $customer->credit_limit }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price Tier <span class="text-danger">*</span></label>
                        <select name="price_tier" class="form-control" required>
                            <option value="normal" @if($customer->price_tier=='normal') selected @endif>Normal</option>
                            <option value="online" @if($customer->price_tier=='online') selected @endif>Online</option>
                            <option value="workshop" @if($customer->price_tier=='workshop') selected @endif>Workshop</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Statement Delivery <span class="text-danger">*</span></label>
                        <select name="statement_delivery" class="form-control" required>
                            <option value="email" @if($customer->statement_delivery=='email') selected @endif>Email</option>
                            <option value="whatsapp" @if($customer->statement_delivery=='whatsapp') selected @endif>WhatsApp</option>
                            <option value="pdf" @if($customer->statement_delivery=='pdf') selected @endif>PDF</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preferences Tab -->
            <div class="tab-pane fade" id="edit-preferences" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Status <span class="text-danger">*</span></label>
                        <select name="customer_status" class="form-control" required>
                            <option value="active" @if($customer->customer_status=='active') selected @endif>Active</option>
                            <option value="inactive" @if($customer->customer_status=='inactive') selected @endif>Inactive</option>
                            <option value="suspended" @if($customer->customer_status=='suspended') selected @endif>Suspended</option>
                        </select>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="marketing_consent" id="marketing_consent" value="1" @if($customer->marketing_consent) checked @endif>
                            <label class="form-check-label fw-bold" for="marketing_consent">
                                Marketing Consent
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="sms_consent" id="sms_consent" value="1" @if($customer->sms_consent) checked @endif>
                            <label class="form-check-label fw-bold" for="sms_consent">
                                SMS Consent
                            </label>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ri-save-line me-1"></i> Update Customer
        </button>
    </div>
</form>
