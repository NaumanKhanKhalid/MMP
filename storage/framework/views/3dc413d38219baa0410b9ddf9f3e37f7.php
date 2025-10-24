<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="ri-user-add-line me-2"></i> Add New Customer
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="<?php echo e(route('customers.store')); ?>" method="POST" id="customerCreateForm">
    <?php echo csrf_field(); ?>
    
    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-md-6">
                <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-user-line me-2"></i> Basic Information</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Code</label>
                        <input type="text" name="customer_code" class="form-control" placeholder="Auto-generated">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Type <span class="text-danger">*</span></label>
                        <select name="customer_type" class="form-control" required id="customerTypeSelect">
                            <option value="credit">Credit Customer</option>
                            <option value="cash">Cash Customer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                        <select name="customer_category" class="form-control" required id="customerCategorySelect">
                            <option value="individual">Individual</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="companyNameField" style="display:none;">
                        <label class="form-label fw-bold">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3" id="contactPersonField" style="display:none;">
                        <label class="form-label fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
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
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="South Africa">
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
                    <!-- Vehicle fields will be added here dynamically -->
                </div>
                
                <div class="alert alert-info d-none" id="noVehiclesAlert">
                    <i class="ri-information-line me-2"></i>No vehicles added yet. Click "Add Vehicle" to add one.
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="ri-bank-card-line me-2"></i> Account Settings</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Terms <span class="text-danger">*</span></label>
                        <select name="terms" class="form-control" required id="paymentTermsSelect">
                            <option value="cash">Cash</option>
                            <option value="on_account">On Account</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price Tier <span class="text-danger">*</span></label>
                        <select name="price_tier" class="form-control" required>
                            <option value="normal">Normal</option>
                            <option value="online">Online</option>
                            <option value="workshop">Workshop</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="creditLimitField">
                        <label class="form-label fw-bold">Credit Limit</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" name="credit_limit" class="form-control" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="customer_status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
        <button type="submit" class="btn btn-success">
            <i class="ri-add-line me-1"></i> Add Customer
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    let vehicleIndex = 0;
    let tempVehicles = [];

    // Show/hide company name and contact person based on category
    $('#customerCategorySelect').on('change', function() {
        if ($(this).val() === 'business') {
            $('#companyNameField').show();
            $('#contactPersonField').show();
        } else {
            $('#companyNameField').hide();
            $('#contactPersonField').hide();
        }
    });

    // Show/hide credit limit based on customer type
    $('#customerTypeSelect').on('change', function() {
        if ($(this).val() === 'credit') {
            $('#creditLimitField').show();
            $('#paymentTermsSelect').val('on_account');
        } else {
            $('#creditLimitField').hide();
            $('#paymentTermsSelect').val('cash');
        }
    });

    // Add Vehicle Button
    $('#addVehicleBtn').on('click', function() {
        addVehicleField();
    });

    function addVehicleField() {
        const vehicleHtml = `
            <div class="vehicle-item border rounded p-3 mb-3" data-index="${vehicleIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-secondary"><i class="ri-car-line me-2"></i>Vehicle #${vehicleIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-vehicle-btn" data-index="${vehicleIndex}">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Make</label>
                        <select name="vehicles[${vehicleIndex}][make_id]" class="form-control vehicle-make-select" data-index="${vehicleIndex}">
                            <option value="">Select Make</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Model</label>
                        <select name="vehicles[${vehicleIndex}][model_id]" class="form-control vehicle-model-select" data-index="${vehicleIndex}" disabled>
                            <option value="">Select Model</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Engine</label>
                        <input type="text" name="vehicles[${vehicleIndex}][engine]" class="form-control" placeholder="e.g. 2.0 Turbo">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">Registration</label>
                        <input type="text" name="vehicles[${vehicleIndex}][registration_number]" class="form-control" placeholder="e.g. ABC123GP">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">VIN</label>
                        <input type="text" name="vehicles[${vehicleIndex}][vin_number]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">Year</label>
                        <input type="text" name="vehicles[${vehicleIndex}][year]" class="form-control" placeholder="e.g. 2020">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="vehicles[${vehicleIndex}][mileage]" class="form-control" placeholder="e.g. 50000">
                    </div>
                   
                    <div class="col-md-6 mb-2">
                        <label class="form-check-label mt-4">
                            <input type="checkbox" name="vehicles[${vehicleIndex}][is_primary]" class="form-check-input" value="1">
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

    function initializeVehicleSelect2(index) {
        // Make Select2
        $(`.vehicle-make-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Search Make...',
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: 'new:' + term,
                    text: '+ Add "' + term + '"',
                    newTag: true
                };
            },
            ajax: {
                url: '<?php echo e(route("api.car-makes")); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                // Quick add new make
                var makeName = data.id.replace('new:', '');
                $.ajax({
                    url: '<?php echo e(route("car-makes.quick-add")); ?>',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        name: makeName
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Make added successfully!');
                            // Replace the temporary option with the real one
                            var $select = $(`.vehicle-make-select[data-index="${index}"]`);
                            
                            // Remove temporary option
                            $select.find('option[value^="new:"]').remove();
                            
                            // Get data from response (could be response.make or response.data)
                            var makeData = response.make || response.data;
                            
                            // Add new option and select it
                            var newOption = new Option(makeData.name, makeData.id, true, true);
                            $select.append(newOption);
                            
                            // Trigger change to update Select2 display
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: makeData.id,
                                        text: makeData.name
                                    }
                                }
                            });
                            
                            // Now enable and setup model select
                            const modelSelect = $(`.vehicle-model-select[data-index="${index}"]`);
                            const engineSelect = $(`.vehicle-engine-select[data-index="${index}"]`);
                            modelSelect.prop('disabled', false).select2('destroy');
                            engineSelect.prop('disabled', true);
                            initializeModelSelect2(index, makeData.id);
                        }
                    },
                    error: function(xhr) {
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
            
            // Reset and enable model select
            modelSelect.val(null).trigger('change');
            
            if (makeId) {
                modelSelect.prop('disabled', false);
                // Reinitialize model select with make filter
                modelSelect.select2('destroy');
                initializeModelSelect2(index, makeId);
            } else {
                modelSelect.prop('disabled', true);
            }
        });

        // Model Select2 (initially disabled)
        $(`.vehicle-model-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Select make first...',
            disabled: true
        });

        // Engine Select2 (initially disabled)
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
            placeholder: 'Search Model...',
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: 'new:' + term,
                    text: '+ Add "' + term + '"',
                    newTag: true
                };
            },
            ajax: {
                url: '<?php echo e(route("api.car-models")); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        make_id: makeId
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                // Quick add new model
                var modelName = data.id.replace('new:', '');
                $.ajax({
                    url: '<?php echo e(route("car-models.quick-add")); ?>',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        name: modelName,
                        make_id: makeId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Model added successfully!');
                            // Replace the temporary option with the real one
                            var $select = $(`.vehicle-model-select[data-index="${index}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            // Get data from response (could be response.model or response.data)
                            var modelData = response.model || response.data;
                            
                            // Add new option and select it
                            var newOption = new Option(modelData.name, modelData.id, true, true);
                            $select.append(newOption);
                            
                            // Trigger change to update Select2 display
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: modelData.id,
                                        text: modelData.name
                                    }
                                }
                            });
                            
                            // Now enable and setup engine select with the model ID
                            const engineSelect = $(`.vehicle-engine-select[data-index="${index}"]`);
                            engineSelect.prop('disabled', false).select2('destroy');
                            initializeEngineSelect2(index, modelData.id);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to add model');
                        $(`.vehicle-model-select[data-index="${index}"]`).val(null).trigger('change');
                    }
                });
            }
        }).on('change', function() {
            const modelId = $(this).val();
            
            // Skip if it's a temporary "new:" value
            if (modelId && modelId.toString().startsWith('new:')) {
                return;
            }
            
            const engineSelect = $(`.vehicle-engine-select[data-index="${index}"]`);
            
            engineSelect.val(null).trigger('change');
            
            if (modelId) {
                engineSelect.prop('disabled', false);
                engineSelect.select2('destroy');
                initializeEngineSelect2(index, modelId);
            } else {
                engineSelect.prop('disabled', true);
            }
        });
    }

    function initializeEngineSelect2(index, modelId) {
        $(`.vehicle-engine-select[data-index="${index}"]`).select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#customerModal'),
            placeholder: 'Search Engine...',
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: 'new:' + term,
                    text: '+ Add "' + term + '"',
                    newTag: true
                };
            },
            ajax: {
                url: '<?php echo e(route("api.engines")); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        model_id: modelId
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newTag) {
                // Quick add new engine
                var engineName = data.id.replace('new:', '');
                $.ajax({
                    url: '<?php echo e(route("car-engines.quick-add")); ?>',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        name: engineName,
                        code: engineName,
                        model_id: modelId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Engine added successfully!');
                            // Replace the temporary option with the real one
                            var $select = $(`.vehicle-engine-select[data-index="${index}"]`);
                            $select.find('option[value^="new:"]').remove();
                            
                            // Get data from response (could be response.engine or response.data)
                            var engineData = response.engine || response.data;
                            
                            // Add new option and select it
                            var newOption = new Option(engineData.name, engineData.id, true, true);
                            $select.append(newOption);
                            
                            // Trigger change to update Select2 display
                            $select.trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: engineData.id,
                                        text: engineData.name
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to add engine');
                        $(`.vehicle-engine-select[data-index="${index}"]`).val(null).trigger('change');
                    }
                });
            }
        });
    }

    // Remove vehicle
    $(document).on('click', '.remove-vehicle-btn', function() {
        const index = $(this).data('index');
        $(`.vehicle-item[data-index="${index}"]`).remove();
        
        // Show alert if no vehicles
        if ($('#vehiclesContainer .vehicle-item').length === 0) {
            $('#noVehiclesAlert').removeClass('d-none');
        }
    });

    // AJAX Form Submission
    $('#customerCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Customer created successfully!');
                $('#customerModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Failed to create customer';
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

    // Show no vehicles alert initially
    $('#noVehiclesAlert').removeClass('d-none');
});
</script>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/customers/partials/create_modal.blade.php ENDPATH**/ ?>