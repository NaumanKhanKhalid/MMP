@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ri-file-text-line me-2"></i>New Quotation</h5>
                        <a href="{{ route('quotes.index') }}" class="btn btn-sm btn-light">
                            <i class="ri-arrow-left-line me-1"></i>Back to Quotes
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quotes.store') }}" method="POST" id="quoteForm">
        @csrf

                            <!-- Customer Selection -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold"><i class="ri-user-line me-1"></i>Customer</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customerSearch"
                                            placeholder="Search customer by name, phone, email..." autocomplete="off">
                                        <button type="button" class="btn btn-success" onclick="showAddCustomerModal()">
                                            <i class="ri-add-line me-1"></i>New Customer
                                        </button>
                                    </div>
                                    <input type="hidden" name="customer_id" id="customerId" required>
                                    <div id="customerSearchResults" class="search-results-dropdown" style="display: none;">
                                    </div>

                                    <!-- Selected Customer Info -->
                                    <div id="selectedCustomerInfo" class="alert alert-info mt-2" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1" id="customerName"></h6>
                                                <small id="customerDetails"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="clearCustomer()">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Selection (Same as POS) -->
                            <div id="vehicleSection" class="row mb-4" style="display: none;">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold"><i class="ri-car-line me-1"></i>Vehicle</label>
                                    <div class="input-group">
                                        <select class="form-select" id="vehicleSelect" name="vehicle_id"
                                            onchange="selectVehicle()">
                                            <option value="">Select Vehicle</option>
                                        </select>
                                        <button type="button" class="btn btn-success" onclick="showAddVehicleModal()"
                                            title="Add Vehicle">
                                            <i class="ri-add-line me-1"></i>New Vehicle
                                        </button>
                                    </div>
                                    <div id="vehicleInfo" class="mt-2" style="display: none;">
                                        <!-- Vehicle details shown here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Products/Items Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold"><i class="ri-shopping-cart-line me-1"></i>Products &
                                        Services</label>
                                    <div class="alert alert-warning">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>Note:</strong> Use the POS system to create quotes with full product
                                        selection.
                                        This form is for manual quote entry only.
                                    </div>
                                    <div id="itemsContainer">
                                        <!-- Items will be added here -->
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success" onclick="addItem()">
                                        <i class="ri-add-line me-1"></i>Add Item
                                    </button>
                                </div>
                            </div>

                            <!-- Quote Details -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Valid Until</label>
                                    <input type="date" name="valid_until" class="form-control"
                                        value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes about this quote..."></textarea>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>Create Quote
                                    </button>
                                    <a href="{{ route('quotes.index') }}" class="btn btn-light">
                                        <i class="ri-close-line me-1"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal (Similar to POS) -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="ri-user-add-line me-2"></i>Add New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Please use the Customers module to add new customers.</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-success" target="_blank">
                        <i class="ri-add-line me-1"></i>Go to Add Customer
                    </a>
                </div>
            </div>
        </div>
        </div>

    <!-- Add Vehicle Modal (Same as POS) -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ri-car-line me-2"></i>Add New Vehicle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
                <form id="addVehicleForm">
                    <div class="modal-body">
        <div class="mb-3">
                            <label class="form-label">Registration Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="newVehicleReg" required>
        </div>
        <div class="mb-3">
                            <label class="form-label">Make <span class="text-danger">*</span></label>
                            <select class="form-select" id="newVehicleMake" required>
                                <option value="">Select Make</option>
                                @foreach (\App\Models\VehicleMake::orderBy('name')->get() as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
        </div>
        <div class="mb-3">
                            <label class="form-label">Model <span class="text-danger">*</span></label>
                            <select class="form-select" id="newVehicleModel" required>
                                <option value="">Select Model</option>
                            </select>
        </div>
        <div class="mb-3">
                            <label class="form-label">Engine (Optional)</label>
                            <input type="text" class="form-control" id="newVehicleEngine"
                                placeholder="e.g., 2.0L Turbo">
        </div>
        <div class="mb-3">
                            <label class="form-label">VIN Number (Optional)</label>
                            <input type="text" class="form-control" id="newVehicleVin">
        </div>
        <div class="mb-3">
                            <label class="form-label">Mileage (Optional)</label>
                            <input type="number" class="form-control" id="newVehicleMileage" placeholder="km">
                        </div>
        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save Vehicle
                        </button>
        </div>
    </form>
            </div>
        </div>
</div>
@endsection

@push('styles')
    <style>
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 5px;
        }

        .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-result-item:hover {
            background: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentCustomer = null;
        let customerVehicles = [];
        let currentVehicle = null;
        let itemCount = 0;

        // Customer Search
        $('#customerSearch').on('input', function() {
            const query = $(this).val();

            if (query.length < 2) {
                $('#customerSearchResults').hide();
                return;
            }

            $.get("{{ route('api.customers.search') }}", {
                q: query
            }, function(customers) {
                if (customers.length > 0) {
                    let html = '';
                    customers.forEach(customer => {
                        html += `
                    <div class="search-result-item" onclick="selectCustomer(${customer.id})">
                        <div class="fw-bold">${customer.name}</div>
                        <small class="text-muted">
                            ${customer.email || 'No email'} | ${customer.phone || 'No phone'}
                            <span class="badge bg-${customer.terms === 'credit' ? 'success' : 'warning'} ms-2">
                                ${customer.terms === 'credit' ? 'Credit' : 'Cash'}
                            </span>
                        </small>
                    </div>
                `;
                    });
                    $('#customerSearchResults').html(html).show();
                } else {
                    $('#customerSearchResults').html(
                        '<div class="p-3 text-center text-muted">No customers found</div>').show();
                }
            });
        });

        // Select Customer (Same as POS)
        function selectCustomer(customerId) {
            $.get("{{ url('api/customers') }}/" + customerId, function(customer) {
                currentCustomer = customer;
                $('#customerId').val(customer.id);
                $('#customerSearch').val(customer.name);
                $('#customerSearchResults').hide();

                // Show customer info
                $('#customerName').text(customer.name);
                $('#customerDetails').html(`
            ${customer.email || 'No email'} | ${customer.phone || 'No phone'}<br>
            <span class="badge bg-${customer.terms === 'credit' ? 'success' : 'warning'}">
                ${customer.terms === 'credit' ? 'Credit Customer' : 'Cash Customer'}
            </span>
        `);
                $('#selectedCustomerInfo').show();

                // Load vehicles
                loadCustomerVehicles(customerId);
            });
        }

        // Load Customer Vehicles (Same as POS)
        function loadCustomerVehicles(customerId) {
            fetch(`/api/customers/${customerId}/vehicles`)
                .then(response => response.json())
                .then(data => {
                    customerVehicles = data;
                    if (data.length > 0) {
                        $('#vehicleSection').show();
                        populateVehicleDropdown();

                        // Auto-select primary vehicle if exists
                        const primaryVehicle = data.find(v => v.is_primary);
                        if (primaryVehicle) {
                            $('#vehicleSelect').val(primaryVehicle.id);
                            selectVehicle();
                        }
                    } else {
                        $('#vehicleSection').show();
                        $('#vehicleSelect').html('<option value="">No vehicles - Click + to add</option>');
                    }
                })
                .catch(error => {
                    console.error('Error loading vehicles:', error);
                    $('#vehicleSelect').html('<option value="">Error loading vehicles</option>');
                });
        }

        // Populate Vehicle Dropdown (Same as POS)
        function populateVehicleDropdown() {
            const select = $('#vehicleSelect');
            select.html('<option value="">Select Vehicle</option>');

            customerVehicles.forEach(vehicle => {
                select.append(`<option value="${vehicle.id}" 
            data-make="${vehicle.make_name}" 
            data-model="${vehicle.model_name}"
            data-reg="${vehicle.registration_number}"
            data-mileage="${vehicle.mileage}">
            ${vehicle.display_name}
        </option>`);
            });
        }

        // Select Vehicle (Same as POS)
        function selectVehicle() {
            const vehicleId = $('#vehicleSelect').val();

            if (vehicleId) {
                currentVehicle = customerVehicles.find(v => v.id == vehicleId);

                if (currentVehicle) {
                    const html = `
                <div class="alert alert-light py-2 small mb-0">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-muted">Make:</span><br>
                            <strong>${currentVehicle.make_name}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Model:</span><br>
                            <strong>${currentVehicle.model_name}</strong>
                        </div>
                        ${currentVehicle.engine ? `
                                <div class="col-6">
                                    <span class="text-muted">Engine:</span><br>
                                    <strong>${currentVehicle.engine}</strong>
                                </div>
                                ` : ''}
                        <div class="col-6">
                            <span class="text-muted">Reg:</span><br>
                            <strong>${currentVehicle.registration_number || 'N/A'}</strong>
                        </div>
                        ${currentVehicle.mileage ? `
                                <div class="col-6">
                                    <span class="text-muted">Mileage:</span><br>
                                    <strong>${currentVehicle.mileage} km</strong>
                                </div>
                                ` : ''}
                        ${currentVehicle.vin_number ? `
                                <div class="col-12">
                                    <span class="text-muted">VIN:</span>
                                    <strong>${currentVehicle.vin_number}</strong>
                                </div>
                                ` : ''}
                    </div>
                </div>
            `;
                    $('#vehicleInfo').html(html).show();
                }
            } else {
                currentVehicle = null;
                $('#vehicleInfo').hide();
            }
        }

        // Clear Customer
        function clearCustomer() {
            currentCustomer = null;
            customerVehicles = [];
            currentVehicle = null;
            $('#customerId').val('');
            $('#customerSearch').val('');
            $('#selectedCustomerInfo').hide();
            $('#vehicleSection').hide();
            $('#vehicleInfo').hide();
        }

        // Show Add Customer Modal
        function showAddCustomerModal() {
            $('#addCustomerModal').modal('show');
        }

        // Show Add Vehicle Modal (Same as POS)
        function showAddVehicleModal() {
            if (!currentCustomer) {
                toastr.error('Please select a customer first');
                return;
            }
            $('#addVehicleModal').modal('show');
        }

        // Load models when make is selected
        $('#newVehicleMake').on('change', function() {
            const makeId = $(this).val();
            if (makeId) {
                $.get(`/api/vehicle-makes/${makeId}/models`, function(models) {
                    let html = '<option value="">Select Model</option>';
                    models.forEach(model => {
                        html += `<option value="${model.id}">${model.name}</option>`;
                    });
                    $('#newVehicleModel').html(html);
                });
            }
        });

        // Add Vehicle Form Submit (Same as POS)
        $('#addVehicleForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                customer_id: currentCustomer.id,
                registration_number: $('#newVehicleReg').val(),
                make_id: $('#newVehicleMake').val(),
                model_id: $('#newVehicleModel').val(),
                engine: $('#newVehicleEngine').val(),
                vin_number: $('#newVehicleVin').val(),
                mileage: $('#newVehicleMileage').val(),
                _token: '{{ csrf_token() }}'
            };

            $.post('/vehicles', formData, function(response) {
                if (response.success) {
                    toastr.success('Vehicle added successfully');
                    $('#addVehicleModal').modal('hide');
                    $('#addVehicleForm')[0].reset();
                    loadCustomerVehicles(currentCustomer.id);
                }
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error adding vehicle');
            });
        });

        // Add Item (Basic for now)
        function addItem() {
            itemCount++;
            const html = `
        <div class="card mb-2" id="item-${itemCount}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label small">Description</label>
                        <input type="text" class="form-control form-control-sm" name="items[${itemCount}][description]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Quantity</label>
                        <input type="number" class="form-control form-control-sm" name="items[${itemCount}][quantity]" value="1" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Unit Price</label>
                        <input type="number" class="form-control form-control-sm" name="items[${itemCount}][unit_price]" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Discount</label>
                        <input type="number" class="form-control form-control-sm" name="items[${itemCount}][discount]" value="0" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">&nbsp;</label>
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeItem(${itemCount})">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
            $('#itemsContainer').append(html);
        }

        function removeItem(id) {
            $(`#item-${id}`).remove();
        }

        // Click outside to hide search results
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#customerSearch, #customerSearchResults').length) {
                $('#customerSearchResults').hide();
            }
        });
    </script>
@endpush
