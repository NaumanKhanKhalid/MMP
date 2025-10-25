<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-pencil-line me-2"></i> Edit Customer
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('customers.update', $customer) }}" method="POST" id="customerEditForm">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-md-6">
                <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-user-line me-2"></i> Basic Information</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Code</label>
                        <input type="text" name="customer_code" class="form-control" value="{{ $customer->customer_code }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                        <select name="customer_category" class="form-control" required id="editCustomerCategorySelect">
                            <option value="individual" {{ $customer->customer_category === 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="business" {{ $customer->customer_category === 'business' ? 'selected' : '' }}>Business</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="editCompanyNameField" style="display:{{ $customer->customer_category === 'business' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $customer->company_name }}">
                    </div>
                    <div class="col-md-6 mb-3" id="editContactPersonField" style="display:{{ $customer->customer_category === 'business' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ $customer->contact_person }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ $customer->city }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ $customer->postal_code }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ $customer->country ?? 'South Africa' }}">
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-primary border-bottom pb-2 mb-0 flex-grow-1"><i class="ri-car-line me-2"></i> Vehicle Information</h6>
                    <button type="button" class="btn btn-sm btn-success" id="addVehicleBtn">
                        <i class="ri-add-line me-1"></i> Add Vehicle
                    </button>
                </div>
                
                <div id="vehiclesContainer">
                    <!-- Existing vehicles will be loaded here -->
                    @foreach($customer->vehicles as $index => $vehicle)
                    <div class="vehicle-item border rounded p-3 mb-3" data-vehicle-id="{{ $vehicle->id }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 text-secondary"><i class="ri-car-line me-2"></i>Vehicle #{{ $index + 1 }}</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-existing-vehicle-btn" data-vehicle-id="{{ $vehicle->id }}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Make</label>
                                <select name="existing_vehicles[{{ $vehicle->id }}][make_id]" class="form-control existing-vehicle-make-select" data-vehicle-id="{{ $vehicle->id }}" data-selected="{{ $vehicle->make_id }}">
                                    <option value="">Select Make</option>
                                    @if($vehicle->make)
                                        <option value="{{ $vehicle->make_id }}" selected>{{ $vehicle->make->name }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Model</label>
                                <select name="existing_vehicles[{{ $vehicle->id }}][model_id]" class="form-control existing-vehicle-model-select" data-vehicle-id="{{ $vehicle->id }}" data-selected="{{ $vehicle->model_id }}">
                                    <option value="">Select Model</option>
                                    @if($vehicle->model)
                                        <option value="{{ $vehicle->model_id }}" selected>{{ $vehicle->model->name }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Engine</label>
                                <input type="text" name="existing_vehicles[{{ $vehicle->id }}][engine]" class="form-control" value="{{ $vehicle->engine ?? '' }}" placeholder="e.g. 2.0 Turbo">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Registration</label>
                                <input type="text" name="existing_vehicles[{{ $vehicle->id }}][registration_number]" class="form-control" value="{{ $vehicle->registration_number }}" placeholder="e.g. ABC123GP">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">VIN</label>
                                <input type="text" name="existing_vehicles[{{ $vehicle->id }}][vin_number]" class="form-control" value="{{ $vehicle->vin_number }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">Year</label>
                                <input type="text" name="existing_vehicles[{{ $vehicle->id }}][year]" class="form-control" value="{{ $vehicle->year }}" placeholder="e.g. 2020">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-bold">Mileage</label>
                                <input type="text" name="existing_vehicles[{{ $vehicle->id }}][mileage]" class="form-control" value="{{ $vehicle->mileage }}" placeholder="e.g. 50000">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-check-label mt-4">
                                    <input type="checkbox" name="existing_vehicles[{{ $vehicle->id }}][is_primary]" class="form-check-input" value="1" {{ $vehicle->is_primary ? 'checked' : '' }}>
                                    <span class="fw-bold">Primary Vehicle</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="alert alert-info d-none" id="noVehiclesAlert">
                    <i class="ri-information-line me-2"></i>No vehicles added yet. Click "Add Vehicle" to add one.
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-bank-card-line me-2"></i> Account Settings</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="terms" class="form-control" required id="editPaymentTermsSelect">
                            <option value="cash" {{ $customer->terms === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit" {{ $customer->terms === 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price Tier <span class="text-danger">*</span></label>
                        <select name="price_tier" class="form-control" required>
                            <option value="normal" {{ $customer->price_tier === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="online" {{ $customer->price_tier === 'online' ? 'selected' : '' }}>Online</option>
                            <option value="workshop" {{ $customer->price_tier === 'workshop' ? 'selected' : '' }}>Workshop</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="editCreditLimitField" style="display:{{ $customer->customer_type === 'credit' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" step="0.01" min="0" value="{{ $customer->credit_limit }}">
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Statement Delivery <span class="text-danger">*</span></label>
                        <select name="statement_delivery" class="form-control" required>
                            <option value="email" {{ $customer->statement_delivery === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="whatsapp" {{ $customer->statement_delivery === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="pdf" {{ $customer->statement_delivery === 'pdf' ? 'selected' : '' }}>PDF</option>
                        </select>
                    </div> --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="customer_status" class="form-control" required>
                            <option value="active" {{ $customer->customer_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $customer->customer_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
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
            <i class="ri-save-line me-1"></i> Update Customer
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    let vehicleIndex = {{ $customer->vehicles->count() }};
    let deletedVehicles = [];
    const customerId = {{ $customer->id }};

    // Show/hide company name and contact person based on category
    $('#editCustomerCategorySelect').on('change', function() {
        if ($(this).val() === 'business') {
            $('#editCompanyNameField').show();
            $('#editContactPersonField').show();
        } else {
            $('#editCompanyNameField').hide();
            $('#editContactPersonField').hide();
        }
    });

    // Show/hide credit limit based on payment terms
    $('#editPaymentTermsSelect').on('change', function() {
        if ($(this).val() === 'credit') {
            $('#editCreditLimitField').show();
        } else {
            $('#editCreditLimitField').hide();
        }
    });
    
    // Initialize credit limit visibility on load
    if ($('#editPaymentTermsSelect').val() === 'credit') {
        $('#editCreditLimitField').show();
    } else {
        $('#editCreditLimitField').hide();
    }

    // Initialize existing vehicle Select2
    $('.existing-vehicle-make-select').each(function() {
        const vehicleId = $(this).data('vehicle-id');
        initializeExistingVehicleSelect2(vehicleId);
    });

    // Add Vehicle Button
    $('#addVehicleBtn').on('click', function() {
        addVehicleField();
    });

    function addVehicleField() {
        const vehicleHtml = `
            <div class="vehicle-item border rounded p-3 mb-3" data-index="${vehicleIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-secondary"><i class="ri-car-line me-2"></i>New Vehicle #${vehicleIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-vehicle-btn" data-index="${vehicleIndex}">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Make</label>
                        <select name="new_vehicles[${vehicleIndex}][make_id]" class="form-control vehicle-make-select" data-index="${vehicleIndex}">
                            <option value="">Select Make</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Model</label>
                        <select name="new_vehicles[${vehicleIndex}][model_id]" class="form-control vehicle-model-select" data-index="${vehicleIndex}" disabled>
                            <option value="">Select Model</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Engine</label>
                        <input type="text" name="new_vehicles[${vehicleIndex}][engine]" class="form-control" placeholder="e.g. 2.0 Turbo">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Registration</label>
                        <input type="text" name="new_vehicles[${vehicleIndex}][registration_number]" class="form-control" placeholder="e.g. ABC123GP">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">VIN</label>
                        <input type="text" name="new_vehicles[${vehicleIndex}][vin_number]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">Year</label>
                        <input type="text" name="new_vehicles[${vehicleIndex}][year]" class="form-control" placeholder="e.g. 2020">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="new_vehicles[${vehicleIndex}][mileage]" class="form-control" placeholder="e.g. 50000">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-check-label mt-4">
                            <input type="checkbox" name="new_vehicles[${vehicleIndex}][is_primary]" class="form-check-input" value="1">
                            <span class="fw-bold">Primary Vehicle</span>
                        </label>
                    </div>
                </div>
            </div>
        `;
        
        $('#vehiclesContainer').append(vehicleHtml);
        $('#noVehiclesAlert').addClass('d-none');
        
        // Initialize Select2 for the new vehicle
        initializeVehicleSelect2(vehicleIndex);
        
        vehicleIndex++;
    }

    function initializeExistingVehicleSelect2(vehicleId) {
        // Make Select2 for existing vehicles
        $(`.existing-vehicle-make-select[data-vehicle-id="${vehicleId}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Search Make...',
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return { id: 'new:' + term, text: '+ Add "' + term + '"', newTag: true };
            },
            ajax: {
                url: '{{ route("api.car-makes") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return { results: data.results };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                var makeName = data.id.replace('new:', '');
                $.ajax({
                    url: '{{ route("car-makes.quick-add") }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', name: makeName },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Make added successfully!');
                            var $select = $(`.existing-vehicle-make-select[data-vehicle-id="${vehicleId}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            var makeData = response.make || response.data;
                            
                            var newOption = new Option(makeData.name, makeData.id, true, true);
                            $select.append(newOption);
                            
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: makeData.id,
                                        text: makeData.name
                                    }
                                }
                            });
                            
                            const modelSelect = $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`);
                            const engineSelect = $(`.existing-vehicle-engine-select[data-vehicle-id="${vehicleId}"]`);
                            modelSelect.prop('disabled', false).select2('destroy');
                            engineSelect.prop('disabled', true);
                            initializeExistingModelSelect2(vehicleId, makeData.id);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add make');
                        $(`.existing-vehicle-make-select[data-vehicle-id="${vehicleId}"]`).val(null).trigger('change');
                    }
                });
            }
        }).on('change', function() {
            const makeId = $(this).val();
            
            // Skip if it's a temporary "new:" value
            if (makeId && makeId.toString().startsWith('new:')) {
                return;
            }
            
            const modelSelect = $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`);
            
            if (makeId) {
                modelSelect.prop('disabled', false).select2('destroy');
                initializeExistingModelSelect2(vehicleId, makeId);
            } else {
                modelSelect.prop('disabled', true).val(null).trigger('change');
            }
        });

        // Initialize Model
        $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
        });
    }

    function initializeExistingModelSelect2(vehicleId, makeId) {
        $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return { id: 'new:' + term, text: '+ Add "' + term + '"', newTag: true };
            },
            ajax: {
                url: '{{ route("api.car-models") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term, make_id: makeId };
                },
                processResults: function(data) {
                    return { results: data.results };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                var modelName = data.id.replace('new:', '');
                $.ajax({
                    url: '{{ route("car-models.quick-add") }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', name: modelName, make_id: makeId },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Model added successfully!');
                            var $select = $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            var modelData = response.model || response.data;
                            
                            var newOption = new Option(modelData.name, modelData.id, true, true);
                            $select.append(newOption);
                            
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: modelData.id,
                                        text: modelData.name
                                    }
                                }
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add model');
                        $(`.existing-vehicle-model-select[data-vehicle-id="${vehicleId}"]`).val(null).trigger('change');
                    }
                });
            }
        });
    }

    function initializeVehicleSelect2(index) {
        // Make Select2 for new vehicles (same as create modal)
        $(`.vehicle-make-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Search Make...',
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return { id: 'new:' + term, text: '+ Add "' + term + '"', newTag: true };
            },
            ajax: {
                url: '{{ route("api.car-makes") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) { return { results: data.results }; },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                var makeName = data.id.replace('new:', '');
                $.ajax({
                    url: '{{ route("car-makes.quick-add") }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', name: makeName },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Make added successfully!');
                            var $select = $(`.vehicle-make-select[data-index="${index}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            var makeData = response.make || response.data;
                            
                            var newOption = new Option(makeData.name, makeData.id, true, true);
                            $select.append(newOption);
                            
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: makeData.id,
                                        text: makeData.name
                                    }
                                }
                            });
                            
                            const modelSelect = $(`.vehicle-model-select[data-index="${index}"]`);
                            const engineSelect = $(`.vehicle-engine-select[data-index="${index}"]`);
                            modelSelect.prop('disabled', false).select2('destroy');
                            engineSelect.prop('disabled', true);
                            initializeModelSelect2(index, makeData.id);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add make');
                        $(`.vehicle-make-select[data-index="${index}"]`).val(null).trigger('change');
                    }
                });
            }
        }).on('change', function() {
            const makeId = $(this).val();
            
            // Skip if it's a temporary "new:" value
            if (makeId && makeId.toString().startsWith('new:')) {
                return;
            }
            
            const modelSelect = $(`.vehicle-model-select[data-index="${index}"]`);
            
            modelSelect.val(null).trigger('change');
            
            if (makeId) {
                modelSelect.prop('disabled', false).select2('destroy');
                initializeModelSelect2(index, makeId);
            } else {
                modelSelect.prop('disabled', true);
            }
        });

        $(`.vehicle-model-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Select make first...',
            disabled: true
        });

        $(`.vehicle-engine-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Select model first...',
            disabled: true
        });
    }

    function initializeModelSelect2(index, makeId) {
        $(`.vehicle-model-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return { id: 'new:' + term, text: '+ Add "' + term + '"', newTag: true };
            },
            ajax: {
                url: '{{ route("api.car-models") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) { return { q: params.term, make_id: makeId }; },
                processResults: function(data) { return { results: data.results }; },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                var modelName = data.id.replace('new:', '');
                $.ajax({
                    url: '{{ route("car-models.quick-add") }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', name: modelName, make_id: makeId },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Model added successfully!');
                            var $select = $(`.vehicle-model-select[data-index="${index}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            var modelData = response.model || response.data;
                            
                            var newOption = new Option(modelData.name, modelData.id, true, true);
                            $select.append(newOption);
                            
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: modelData.id,
                                        text: modelData.name
                                    }
                                }
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add model');
                        $(`.vehicle-model-select[data-index="${index}"]`).val(null).trigger('change');
                    }
                });
            }
        });
    }

    // Remove new vehicle
    $(document).on('click', '.remove-vehicle-btn', function() {
        const index = $(this).data('index');
        $(`.vehicle-item[data-index="${index}"]`).remove();
        
        if ($('#vehiclesContainer .vehicle-item').length === 0) {
            $('#noVehiclesAlert').removeClass('d-none');
        }
    });

    // Remove existing vehicle
    $(document).on('click', '.remove-existing-vehicle-btn', function() {
        const vehicleId = $(this).data('vehicle-id');
        deletedVehicles.push(vehicleId);
        $(`.vehicle-item[data-vehicle-id="${vehicleId}"]`).remove();
        
        if ($('#vehiclesContainer .vehicle-item').length === 0) {
            $('#noVehiclesAlert').removeClass('d-none');
        }
    });

    // AJAX Form Submission
    $('#customerEditForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        // Add deleted vehicles to form data
        deletedVehicles.forEach(function(vehicleId) {
            formData.append('deleted_vehicles[]', vehicleId);
        });
        
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Customer updated successfully!');
                $('#customerModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Failed to update customer';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(errorMsg);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Check if vehicles exist initially
    if ($('#vehiclesContainer .vehicle-item').length === 0) {
        $('#noVehiclesAlert').removeClass('d-none');
    }
});
</script>
