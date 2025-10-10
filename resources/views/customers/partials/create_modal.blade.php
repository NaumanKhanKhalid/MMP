
<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="ri-user-add-line me-2"></i> Add New Customer
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('customers.store') }}" method="POST" id="customerCreateForm">
    @csrf
    
    <div class="modal-body p-0">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-fill" id="createCustomerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-create" data-bs-toggle="tab" data-bs-target="#create-basic" type="button" role="tab">
                    <i class="ri-user-line me-1"></i> Basic Info
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab-create" data-bs-toggle="tab" data-bs-target="#create-contact" type="button" role="tab">
                    <i class="ri-phone-line me-1"></i> Contact
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vehicle-tab-create" data-bs-toggle="tab" data-bs-target="#create-vehicle" type="button" role="tab">
                    <i class="ri-car-line me-1"></i> Vehicle
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="account-tab-create" data-bs-toggle="tab" data-bs-target="#create-account" type="button" role="tab">
                    <i class="ri-bank-card-line me-1"></i> Account
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="preferences-tab-create" data-bs-toggle="tab" data-bs-target="#create-preferences" type="button" role="tab">
                    <i class="ri-settings-3-line me-1"></i> Preferences
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4" id="createCustomerTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="create-basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Type <span class="text-danger">*</span></label>
                        <select name="customer_type" class="form-control" required id="customer_type_select">
                            <option value="individual">Individual</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="company_name_field" style="display: none;">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3" id="date_of_birth_field">
                        <label class="form-label fw-bold">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tax Number</label>
                        <input type="text" name="tax_number" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="South Africa">
                    </div>
                </div>
            </div>

            <!-- Vehicle Tab -->
            <div class="tab-pane fade" id="create-vehicle" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Make</label>
                        <input type="text" name="vehicle_make" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Model</label>
                        <input type="text" name="vehicle_model" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">VIN</label>
                        <input type="text" name="vehicle_vin" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Account Tab -->
            <div class="tab-pane fade" id="create-account" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="terms" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="on_account">On Account</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price Tier <span class="text-danger">*</span></label>
                        <select name="price_tier" class="form-control" required>
                            <option value="normal">Normal</option>
                            <option value="online">Online</option>
                            <option value="workshop">Workshop</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Statement Delivery <span class="text-danger">*</span></label>
                        <select name="statement_delivery" class="form-control" required>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preferences Tab -->
            <div class="tab-pane fade" id="create-preferences" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Status <span class="text-danger">*</span></label>
                        <select name="customer_status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="marketing_consent" id="create_marketing_consent" value="1">
                            <label class="form-check-label fw-bold" for="create_marketing_consent">
                                Marketing Consent
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="sms_consent" id="create_sms_consent" value="1">
                            <label class="form-check-label fw-bold" for="create_sms_consent">
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
        <button type="submit" class="btn btn-success">
            <i class="ri-add-line me-1"></i> Add Customer
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Show/hide company name field based on customer type
    $('#customer_type_select').on('change', function() {
        if ($(this).val() === 'business') {
            $('#company_name_field').show();
            $('#date_of_birth_field').hide();
        } else {
            $('#company_name_field').hide();
            $('#date_of_birth_field').show();
        }
    });
});
</script>
