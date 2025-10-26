<form action="{{ route('quotes.update', $quote) }}" method="POST" id="quoteEditForm">
    @csrf
    @method('PUT')
    <div class="modal-header bg-warning-transparent">
        <h5 class="modal-title">
            <i class="ri-pencil-line me-2"></i> Edit Quote #{{ $quote->quote_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-0" style="max-height: 80vh; overflow: hidden;">
        <div class="row g-0" style="height: 80vh;">

            <!-- Customer Column (Will show on RIGHT via order-2) -->
            <div class="col-md-5 order-2" style="background: #f8f9fa; overflow-y: auto; height: 100%;">
                <div class="p-3">

                    <!-- Customer Section -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header py-2">
                        <h6 class="card-title mb-0">
                                <i class="ri-user-line me-2"></i>Customer & Vehicle
                        </h6>
            </div>
            <div class="card-body pb-2">
                            <!-- Customer Search (POS Style) -->
                        <div class="mb-2">
                                <label class="form-label fw-semibold mb-1 small">
                                    <i class="ri-user-line me-1"></i>Customer
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="quoteCustomerSearch"
                                        placeholder="Search by name, phone, email..." autocomplete="off">
                                    <button class="btn btn-warning" type="button" id="quoteClearCustomerBtn"
                                        style="display: none;" title="Switch to Walk-in">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <button class="btn btn-success" type="button" onclick="openAddCustomerModal()"
                                        title="Add Customer">
                                        <i class="ri-user-add-line"></i>
                                    </button>
                                </div>
                                <div id="quoteCustomerSearchResults" class="list-group mt-2 shadow-sm"
                                    style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1050; width: calc(50% - 30px);">
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="ri-information-line"></i> Leave empty for Walk-in Customer
                                </small>
                        </div>

                            <!-- Selected Customer Info -->
                            <div id="quoteCustomerInfo" class="card border-primary shadow-sm mb-2"
                                style="display: none;">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-fill fs-4 me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0" id="quoteSelectedCustomerName"></p>
                                            <p class="fs-11 text-muted mb-0">
                                                <span id="quoteSelectedCustomerContact"></span>
                                            </p>
                                            <span class="badge badge-sm" id="quoteSelectedCustomerType"></span>
                                        </div>
                                    </div>
                            </div>
                        </div>

                            <!-- Walk-in Customer Form (POS Style) -->
                            <div id="quoteWalkInCustomerForm" class="mb-2">
                                <div class="card border-info shadow-sm">
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-2 small">
                                            <i class="ri-walk-line me-1"></i>
                                            <strong class="text-info">Walk-in Customer</strong>
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small mb-1">Name (Optional)</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="quoteWalkInName" name="customer_name"
                                                    placeholder="Enter name...">
                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1">
                                                    Phone <span class="text-danger">*</span>
                                                    <small class="text-muted">(Required if no email)</small>
                                                </label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="quoteWalkInPhone" name="customer_phone"
                                                    placeholder="Enter phone...">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small mb-1">
                                                    Email <span class="text-danger">*</span>
                                                    <small class="text-muted">(Required if no phone)</small>
                                                </label>
                                                <input type="email" class="form-control form-control-sm"
                                                    id="quoteWalkInEmail" name="customer_email" 
                                                    placeholder="Enter email...">
                                                <small class="text-warning">
                                                    <i class="ri-information-line me-1"></i>
                                                    Please provide either email or phone number
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden field for selected customer ID -->
                            <input type="hidden" name="customer_id" id="quoteCustomerId" value="">

                            <!-- Registered Customer Vehicle Selection -->
                            <div id="vehicleSection" style="display: none;">
                                <label class="form-label fw-semibold mb-1 small">
                                    <i class="ri-car-line me-1"></i>Vehicle
                                </label>
                                <div class="input-group input-group-sm mb-2">
                                    <select class="form-select form-select-sm" id="vehicleSelect" name="vehicle_id"
                                        onchange="selectQuoteVehicle()">
                                        <option value="">Select Vehicle</option>
                                </select>
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="showAddQuoteVehicleModal()" title="Add Vehicle">
                                        <i class="ri-add-line"></i>
                                    </button>
                            </div>
                                <div id="vehicleInfo" style="display: none;">
                                    <!-- Vehicle details shown here -->
                        </div>
                    </div>

                            <!-- Walk-in Customer Vehicle (Manual Entry) -->
                            <div id="quoteWalkInVehicleForm" class="mb-2">
                                <label class="form-label fw-semibold mb-1 small">
                                    <i class="ri-car-line me-1"></i>Vehicle Info <small
                                        class="text-muted">(Optional)</small>
                                </label>
                                <div class="card border-info shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-road-map-line text-danger me-1"></i>Registration
                                                </label>
                                                <input type="text" class="form-control form-control-sm shadow-sm"
                                                    name="vehicle_reg" placeholder="e.g., ABC123GP">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-car-line text-primary me-1"></i>Make
                                                </label>
                                                <select class="form-select form-select-sm select2-walk-in-make"
                                                    id="quoteWalkInVehicleMake" name="vehicle_make_id">
                                    <option value="">Select Make</option>
                                    @foreach ($makes as $make)
                                                        <option value="{{ $make->id }}">{{ $make->name }}
                                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-car-line text-success me-1"></i>Model
                                                </label>
                                                <select class="form-select form-select-sm select2-walk-in-model"
                                                    id="quoteWalkInVehicleModel" name="vehicle_model_id">
                                    <option value="">Select Model</option>
                                </select>
                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-calendar-line text-info me-1"></i>Year
                                                </label>
                                                <select class="form-select form-select-sm select2-walk-in-year"
                                                    id="quoteWalkInVehicleYear" name="vehicle_year">
                                                    <option value="">Select Year</option>
                                                    @for ($year = date('Y') + 1; $year >= 1980; $year--)
                                                        <option value="{{ $year }}">{{ $year }}
                                                        </option>
                                                    @endfor
                                </select>
                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-settings-3-line text-warning me-1"></i>Engine
                                                </label>
                                                <input type="text" class="form-control form-control-sm shadow-sm"
                                                    name="vehicle_engine" placeholder="e.g., 2.0L Turbo">
                            </div>
                                           
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-speed-line text-warning me-1"></i>Mileage
                                                </label>
                                                <input type="number" class="form-control form-control-sm shadow-sm"
                                                    name="vehicle_mileage" placeholder="km">
                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 fw-semibold">
                                                    <i class="ri-barcode-line text-secondary me-1"></i>VIN
                                                </label>
                                                <input type="text" class="form-control form-control-sm shadow-sm"
                                                    name="vehicle_vin" placeholder="Optional">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                    <!-- Quote Details -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-info text-white py-2">
                            <h6 class="card-title mb-0">
                                <i class="ri-file-list-line me-2"></i>Quote Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Valid Until</label>
                                    <input type="date" name="valid_until" class="form-control form-control-sm"
                                        value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="draft">Draft</option>
                                        <option value="sent">Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Products Column (Will show on LEFT via order-1) -->
            <div class="col-md-7 order-1 border-end" style="background: white; overflow-y: auto; height: 100%;">
                <div class="p-3">

        <!-- Products & Items Section -->
                    <div class="card mb-3 shadow-sm">
            <div class="card-header bg-success-transparent py-2">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <h6 class="card-title mb-0">
                            <i class="ri-shopping-cart-line me-2"></i>Products & Items
                        </h6>
                    </div>
                    <div class="col-md-10">
                        <!-- Product Search - Inline in Header -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" id="productSearch" class="form-control form-control-sm"
                                placeholder="SKU, Barcode, or Name..." autofocus>
                            <button type="button" class="btn btn-warning btn-sm" id="quickAddProduct"
                                title="Quick Add (F2)">
                                <i class="ri-flashlight-line"></i>
                            </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            id="barcodeScanBtn" title="Barcode">
                                <i class="ri-barcode-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Search Results -->
                <div id="productSearchResults" class="border-bottom" style="display: none;">
                    <!-- Search results will be populated here -->
                </div>

                <!-- Quote Items Table - Better Design -->
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="border-0 py-2" style="width: 3%;">#</th>
                                <th class="border-0 py-2" style="width: 30%;">Product</th>
                                <th class="border-0 py-2" style="width: 8%;">Qty</th>
                                <th class="border-0 py-2" style="width: 12%;">
                                    Price <small id="priceTierIndicator"
                                        class="badge bg-info-transparent">Normal</small>
                                </th>
                                <th class="border-0 py-2" style="width: 10%;">
                                    Discount
                                                <i class="ri-information-line text-warning ms-1"
                                                    data-bs-toggle="tooltip"
                                        title="Max {{ auth()->user()->max_discount_allowed ?? 10 }}% per line for your role"></i>
                                </th>
                                <th class="border-0 py-2" style="width: 12%;">Total</th>
                                <th class="border-0 py-2" style="width: 8%;">Stock</th>
                                {{-- @if (auth()->user()->canSeeCosts())
                                    <th class="border-0 py-2" style="width: 10%;">Cost</th>
                                @endif --}}
                                <th class="border-0 py-2" style="width: 7%;"></th>
                            </tr>
                        </thead>
                        <tbody id="quoteItemsBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty state message when no items -->
                <div id="emptyQuoteItems" class="text-center py-5" style="display: none;">
                    <div class="mb-3">
                        <i class="ri-shopping-cart-line" style="font-size: 4rem; color: #e0e0e0;"></i>
                    </div>
                    <h6 class="text-muted mb-2">No Products Added Yet</h6>
                    <p class="text-muted small mb-0">
                        <i class="ri-search-line me-1"></i>Search above to add products
                        <span class="mx-2">|</span>
                        <i class="ri-flashlight-line me-1"></i>Press <kbd>F2</kbd> for Quick Add
                    </p>
                </div>
            </div>
        </div>

                    <!-- Totals & Calculations Section (POS Style - Bottom of Right Column) -->
                    <div class="card shadow-sm border-primary" style="position: sticky; bottom: 0; z-index: 10;">
                        <div class="card-header py-2">
                            <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">
                            <i class="ri-calculator-line me-2"></i>Quote Totals
                        </h6>
                                <h4 class="mb-0" id="grandTotalDisplay">R 0.00</h4>
                        <input type="hidden" name="grand_total" id="grandTotal" value="0.00">
                    </div>
                </div>
                        <div class="card-body py-2 bg-light">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label mb-1 small">Subtotal</label>
                        <input type="number" name="subtotal" id="subtotal"
                                        class="form-control form-control-sm bg-light" value="0.00" step="0.01"
                                        readonly>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label mb-1 small">
                            Discount
                            <i class="ri-information-line text-info ms-1" data-bs-toggle="tooltip"
                                title="Additional discount on entire quote"></i>
                        </label>
                        <input type="number" name="total_discount" id="totalDiscount"
                                        class="form-control form-control-sm" value="0.00" step="0.01"
                                        min="0" max="999999.99">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label mb-1 small">Shipping</label>
                                    <input type="number" name="shipping" id="shipping"
                                        class="form-control form-control-sm" value="0.00" step="0.01">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label mb-1 small">
                            VAT ({{ $vatSettings['rate'] }}%)
                            <span
                                class="badge {{ $vatSettings['inclusive'] ? 'bg-info' : 'bg-warning' }} badge-sm">{{ $vatSettings['inclusive'] ? 'Inc' : 'Exc' }}</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-text">
                                            <input type="checkbox" name="vat_enabled" id="vatEnabled"
                                                class="form-check-input"
                                    {{ $vatSettings['enabled'] ? 'checked' : '' }}>
                            </div>
                                        <input type="number" name="vat_amount" id="vatAmount"
                                            class="form-control bg-light" value="0.00" step="0.01" readonly>
                            <input type="hidden" id="vatRate" value="{{ $vatSettings['rate'] }}">
                            <input type="hidden" id="vatInclusive"
                                value="{{ $vatSettings['inclusive'] ? '1' : '0' }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
            </div>
            <!-- End Right Column -->

    </div>
        <!-- End Row -->
    </div>
    <!-- End Modal Body -->

    <div class="modal-footer py-2 bg-light border-top">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line"></i> Cancel
        </button>
        <button type="button" class="btn btn-sm btn-info" id="previewQuote">
            <i class="ri-eye-line"></i> Preview
        </button>
        <button type="submit" class="btn btn-sm btn-warning">
            <i class="ri-save-line"></i> Update Quote
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // ═══════════════════════════════════════════════════════════════════════
        // EDIT MODE: Pre-populate existing quote data
        // ═══════════════════════════════════════════════════════════════════════
        
        const editMode = true;
        const existingQuote = @json($quote);
        
        console.log('Edit mode - Loading quote:', existingQuote);
        let quoteItemIndex = 0;
        let searchTimeout;

        // POS-Style Customer Search
        let quoteCustomerSearchTimeout;
        let quoteSelectedCustomer = null;
        let quoteCustomers = []; // Local customers array (same as POS)

        // Show walk-in form by default
        $('#quoteWalkInCustomerForm').show();
        $('#quoteWalkInVehicleForm').show();
        $('#quoteCustomerInfo').hide();
        $('#vehicleSection').hide();

        // Load customers on page load (same as POS)
        loadQuoteCustomers();

        // Initialize Select2 for Walk-in Vehicle Fields (POS Style)
        function initWalkInVehicleSelect2() {
            $('.select2-walk-in-make').select2({
                placeholder: 'Select Make',
            allowClear: true,
                dropdownParent: $('#quoteModal'),
                width: '100%'
            });

            $('.select2-walk-in-model').select2({
                placeholder: 'Select Model',
                allowClear: true,
                dropdownParent: $('#quoteModal'),
                width: '100%'
            });

            $('.select2-walk-in-year').select2({
                placeholder: 'Select Year',
                allowClear: true,
                dropdownParent: $('#quoteModal'),
                width: '100%'
            });
        }

        // Initialize after modal opens
        setTimeout(function() {
            initWalkInVehicleSelect2();
        }, 200);

        // Walk-in Vehicle: Cascading Make → Model
        $('#quoteWalkInVehicleMake').on('change', function() {
            const makeId = $(this).val();
            const modelSelect = $('#quoteWalkInVehicleModel');

            if (makeId) {
                const url = "{{ route('api.vehicle-makes.models', ':makeId') }}".replace(':makeId',
                    makeId);
                $.get(url, function(models) {
                    let html = '<option value="">Select Model</option>';
                    models.forEach(model => {
                        html += `<option value="${model.id}">${model.name}</option>`;
                    });
                    modelSelect.html(html).trigger('change'); // Trigger to update Select2
                });
            } else {
                modelSelect.html('<option value="">Select Model</option>').trigger('change');
            }
        });

        // Handle "Add New Customer" option selection
        window.openAddCustomerModal = function() {
            const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'), {
                backdrop: false,
                keyboard: true
            });
            addCustomerModal.show();

            // Auto-focus on name field
            setTimeout(function() {
                $('#customerName').focus();
            }, 300);
        };

        // Close customer modal when clicking outside
        $(document).on('click', function(e) {
            const customerModal = document.getElementById('addCustomerModal');
            if (customerModal && customerModal.classList.contains('show')) {
                if (!$(e.target).closest('.modal-content').length) {
                    const modalInstance = bootstrap.Modal.getInstance(customerModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }
        });

        // ESC key to close customer modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#addCustomerModal').hasClass('show')) {
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById(
                    'addCustomerModal'));
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });

        // Enter key to submit customer form
        $('#addCustomerForm input, #addCustomerForm textarea, #addCustomerForm select').on('keydown', function(
            e) {
            if (e.key === 'Enter' && !e.shiftKey && this.tagName !== 'TEXTAREA') {
                e.preventDefault();
                $('#addCustomerForm').submit();
            }
        });

        // Add Customer Form Submit
        $('#addCustomerForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize() + '&quick_add=1';
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span>Creating...');

            $.ajax({
                url: '{{ route('customers.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Customer created successfully!');

                        // Auto-select the new customer (POS style)
                        const customer = {
                            id: response.customer.id,
                            name: response.customer.name,
                            email: response.customer.email,
                            phone: response.customer.phone,
                            terms: response.customer.terms || 'cash',
                            price_tier: response.customer.price_tier || 'normal'
                        };

                        selectQuoteCustomer(customer);

                        // Close modal and reset form
                        const addCustomerModal = bootstrap.Modal.getInstance(document
                            .getElementById('addCustomerModal'));
                        addCustomerModal.hide();
                        $('#addCustomerForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to create customer.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                            '<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Customer Search Input Handler (POS Style)
        $('#quoteCustomerSearch').on('input', function() {
            const searchTerm = $(this).val().trim();

            clearTimeout(quoteCustomerSearchTimeout);

            if (searchTerm.length === 0) {
                // Walk-in customer (no customer selected)
                $('#quoteCustomerSearchResults').hide();
                $('#quoteClearCustomerBtn').hide();
                $('#quoteCustomerInfo').hide();
                $('#quoteWalkInCustomerForm').show();
                $('#quoteWalkInVehicleForm').show(); // Show walk-in vehicle form
                $('#vehicleSection').hide(); // Hide registered customer vehicle section
                $('#quoteCustomerId').val('');
                quoteSelectedCustomer = null;
                $('#priceTierIndicator').text('(Normal)');
                return;
            }

            if (searchTerm.length < 2) {
                $('#quoteCustomerSearchResults').hide();
                return;
            }

            quoteCustomerSearchTimeout = setTimeout(() => {
                searchQuoteCustomers(searchTerm);
            }, 300);
        });

        // Load Customers (Same as POS)
        function loadQuoteCustomers() {
            fetch('{{ route('pos.customers') }}')
                .then(response => response.json())
                .then(data => {
                    quoteCustomers = data;
                    console.log('Loaded ' + quoteCustomers.length + ' customers for quotation');
                })
                .catch(error => {
                    console.error('Error loading customers:', error);
                });
        }

        // Search Customers Function (Local Search - Same as POS)
        function searchQuoteCustomers(searchTerm) {
            const searchLower = searchTerm.toLowerCase();

            // Filter customers locally (faster than API)
            const results = quoteCustomers.filter(customer => {
                return customer.name.toLowerCase().includes(searchLower) ||
                    (customer.customer_code && customer.customer_code.toLowerCase().includes(
                        searchLower)) ||
                    (customer.phone && customer.phone.includes(searchTerm)) ||
                    (customer.email && customer.email.toLowerCase().includes(searchLower));
            });

            displayQuoteCustomerResults(results);
        }

        // Display Customer Results
        function displayQuoteCustomerResults(results) {
            const resultsDiv = $('#quoteCustomerSearchResults');

            if (results.length === 0) {
                resultsDiv.html('<div class="list-group-item text-muted small">No customers found</div>')
                    .show();
                return;
            }

            let html = '';
            results.forEach(customer => {
                const termsLabel = customer.terms === 'credit' ?
                    '<span class="badge bg-warning-transparent text-warning ms-1">Credit</span>' :
                    '<span class="badge bg-success-transparent text-success ms-1">Cash</span>';

                html += `
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action quote-customer-result" 
                       data-id="${customer.id}"
                       data-name="${customer.name}"
                       data-email="${customer.email || ''}"
                       data-phone="${customer.phone || ''}"
                       data-terms="${customer.terms}"
                       data-price-tier="${customer.price_tier || 'normal'}">
                        <div class="d-flex align-items-center">
                            <i class="ri-user-line me-2 text-primary"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">${customer.name} ${termsLabel}</div>
                                <div class="text-muted" style="font-size: 11px;">
                                    ${customer.phone || ''} ${customer.email ? '• ' + customer.email : ''}
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            });

            resultsDiv.html(html).show();
        }

        // Select Customer from Search Results
        $(document).on('click', '.quote-customer-result', function() {
            const customer = {
                id: $(this).data('id'),
                name: $(this).data('name'),
                email: $(this).data('email'),
                phone: $(this).data('phone'),
                terms: $(this).data('terms'),
                price_tier: $(this).data('price-tier')
            };

            selectQuoteCustomer(customer);
        });

        // Select Customer Function
        function selectQuoteCustomer(customer) {
            quoteSelectedCustomer = customer;

            // Update UI
            $('#quoteCustomerSearch').val(customer.name);
            $('#quoteCustomerSearchResults').hide();
            $('#quoteClearCustomerBtn').show();
            $('#quoteCustomerId').val(customer.id);

            // Hide walk-in forms, show customer info
            $('#quoteWalkInCustomerForm').hide();
            $('#quoteWalkInVehicleForm').hide(); // Hide walk-in vehicle form
            $('#quoteCustomerInfo').show();

            // Display customer details
            $('#quoteSelectedCustomerName').text(customer.name);
            $('#quoteSelectedCustomerContact').text(
                (customer.phone || '') + (customer.phone && customer.email ? ' • ' : '') + (customer
                    .email || '')
            );

            // Customer type badge
            const typeHtml = customer.terms === 'credit' ?
                '<span class="badge bg-warning">Credit Customer</span>' :
                '<span class="badge bg-success">Cash Customer</span>';
            $('#quoteSelectedCustomerType').html(typeHtml);

            // Update price tier
            $('#priceTierIndicator').text(`(${customer.price_tier || 'normal'})`);
            updatePricesForCustomerTier(customer.price_tier || 'normal');

            // Load customer vehicles (registered customer)
            loadQuoteCustomerVehicles(customer.id);
        }

        // Clear Customer (Back to Walk-in)
        $('#quoteClearCustomerBtn').on('click', function() {
            $('#quoteCustomerSearch').val('');
            $('#quoteClearCustomerBtn').hide();
            $('#quoteCustomerInfo').hide();
            $('#quoteWalkInCustomerForm').show();
            $('#quoteWalkInVehicleForm').show(); // Show walk-in vehicle form
            $('#vehicleSection').hide(); // Hide registered customer vehicle section
            $('#quoteCustomerId').val('');
            $('#quoteCustomerSearchResults').hide();
            quoteSelectedCustomer = null;
                $('#priceTierIndicator').text('(Normal)');
                updatePricesForCustomerTier('normal');
        });

        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#quoteCustomerSearch, #quoteCustomerSearchResults').length) {
                $('#quoteCustomerSearchResults').hide();
            }
        });

        // Update prices for all items based on customer price tier
        function updatePricesForCustomerTier(priceTier) {
            let hasProducts = false;
            $('.unit-price').each(function() {
                const row = $(this).closest('tr');
                const productId = row.find('input[name*="[product_id]"]').val();

                if (productId) {
                    hasProducts = true;
                }
            });
            
            // Show message only once if there are products
            if (hasProducts) {
                toastr.info('Price tier changed to ' + priceTier +
                    '. You may need to update prices manually or re-add products.');
            }
        }


        // Product search functionality with Enter key
        $('#productSearch').on('keydown', function(e) {
            // Enter key - immediate search
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = $(this).val().trim();
                if (query.length >= 1) {
                    searchProducts(query);
                }
            }
            // ESC key - close results
            else if (e.key === 'Escape') {
                $('#productSearchResults').hide();
                $(this).val('');
            }
            // F2 key - Quick Add
            else if (e.key === 'F2') {
                e.preventDefault();
                $('#quickAddProduct').click();
            }
        });

        // Also search on input (with debounce for auto-search)
        $('#productSearch').on('input', function() {
            const query = $(this).val().trim();

            clearTimeout(searchTimeout);
            if (query.length >= 2) {
                searchTimeout = setTimeout(function() {
                    searchProducts(query);
                }, 500); // Longer delay for auto-search
            } else {
                $('#productSearchResults').hide();
            }
        });

        // Product search function
        function searchProducts(query) {
            $.ajax({
                url: '{{ route('quotes.search-products') }}',
                method: 'GET',
                data: {
                    q: query
                },
                beforeSend: function() {
                    $('#productSearchResults').html(
                        '<div class="text-center p-3"><i class="ri-loader-4-line ri-spin"></i> Searching...</div>'
                    ).show();
                },
                success: function(response) {
                    if (response.products.length > 0) {
                        let html = `
                            <div class="bg-success-transparent px-3 py-2 border-bottom border-success">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-success">
                                    <i class="ri-search-line me-1"></i>
                                    ${response.products.length} Result${response.products.length > 1 ? 's' : ''}
                                    </span>
                                    <small class="text-muted">
                                        <i class="ri-information-line"></i> Click to add to quote
                                </small>
                                </div>
                            </div>
                        `;

                        response.products.forEach(function(product) {
                            let stockBadge;
                            if (product.current_stock > 0) {
                                stockBadge =
                                    `<span class="badge bg-success text-white fw-bold">${product.current_stock} ${product.unit}</span>`;
                            } else if (product.current_stock < 0) {
                                stockBadge =
                                    `<span class="badge bg-danger text-white fw-bold">${product.current_stock} NEG</span>`;
                            } else {
                                stockBadge =
                                    `<span class="badge bg-warning text-dark fw-bold">0 OUT</span>`;
                            }

                            const priceTier = getPriceTier();
                            const price = product['price_' + priceTier];

                            html += `
                            <div class="product-search-item add-product-btn p-2 mb-2 rounded-3 shadow-sm border bg-white" data-product='${JSON.stringify(product)}' 
                                 style="transition: all 0.2s ease; cursor: pointer;">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Image with Stock Badge -->
                                    <div style="position: relative; flex-shrink: 0;">
                                        <img src="${product.image_url}" class="rounded-2" 
                                             style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #dee2e6;" 
                                                 onerror="this.src='/assets/images/pos-system/1.jpg'">
                                        <div class="position-absolute top-0 start-0" style="margin: -6px 0 0 -6px;">
                                               
                                            </div>
                                        </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 14px;">${product.name}</div>
                                                        <div class="mb-1">
                                            <span class="badge bg-light text-dark border small">SKU: ${product.sku}</span>
                                                </div>
                                        ${product.supplier ? `<div class="mb-1"><span class="badge bg-info-transparent small"><i class="ri-store-line"></i> Supplier: ${product.supplier}</span></div>` : ''}
                                        ${product.bin_location ? `<div><span class="badge bg-primary-transparent small"><i class="ri-inbox-line"></i> Bin: ${product.bin_location}</span></div>` : ''}
                                            </div>
                                    
                                    <!-- Price & Action -->
                                    <div class="text-end" style="flex-shrink: 0; min-width: 120px;">
                                        <div class="fw-bold text-primary mb-2" style="font-size: 16px;">R ${parseFloat(price).toFixed(2)}</div>
                                        
                                                ${product.current_stock <= 0 && !product.allow_negative ? 
                                            '<div class="badge bg-danger w-100 small">Unavailable</div>' : 
                                                    product.current_stock < 0 ? 
                                            '<div class="badge bg-warning text-dark w-100 small">Low: ' + Math.abs(product.current_stock) + '</div>' : 
                                            product.current_stock <= 10 ?
                                            '<div class="badge bg-warning text-dark w-100 small">Low: ' + product.current_stock + '</div>' :
                                            '<div class="badge bg-success-transparent w-100 small">Available: ' + product.current_stock + '</div>'}
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        $('#productSearchResults').html(html).show();
                    } else {
                        // No results - show Quick Add (POS Style)
                        const searchQuery = query;
                        $('#productSearchResults').html(`
                            <div class="p-3 mx-2">
                                <div class="card border-success shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-5">
                                                <label class="form-label small fw-bold mb-1">
                                                    <i class="ri-product-hunt-line text-primary me-1"></i>Product Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm" id="quickProductName" value="${searchQuery}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold mb-1">
                                                    <i class="ri-price-tag-3-line text-success me-1"></i>Price <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm" id="quickProductPrice" value="0.00" step="0.01">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold mb-1">
                                                    <i class="ri-stack-line text-warning me-1"></i>Qty <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm" id="quickProductQty" value="1" min="1">
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success flex-grow-1" id="quickAddProductBtn">
                                                <i class="ri-check-line me-1"></i>Create & Add to Quote
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="$('#productSearchResults').hide(); $('#productSearch').val('').focus();">
                                                <i class="ri-close-line me-1"></i>Cancel
                                    </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).show();
                    }
                },
                error: function() {
                    $('#productSearchResults').html(
                        '<div class="alert alert-danger text-center">Error searching products. Please try again.</div>'
                    ).show();
                }
            });
        }

        // Quick Add Product Button (Inline - POS Style)
        $(document).on('click', '#quickAddProductBtn', function() {
            const productName = $('#quickProductName').val().trim();
            const productPrice = parseFloat($('#quickProductPrice').val()) || 0;
            const productQty = parseInt($('#quickProductQty').val()) || 1;

            if (!productName || productPrice <= 0) {
                toastr.error('Please enter product name and valid price');
                return;
            }

            // Create product via AJAX
            const formData = {
                name: productName,
                price_normal: productPrice,
                qty: productQty,
                _token: '{{ csrf_token() }}'
            };

            $(this).prop('disabled', true).html(
                '<i class="ri-loader-4-line ri-spin me-1"></i>Creating...');

            $.post('{{ route('products.quickAdd') }}', formData, function(response) {
                if (response.success) {
                    toastr.success('Product created & added to quote!');

                    const product = response.product;
                    const priceTier = getPriceTier();
                    const unitPrice = product['price_' + priceTier] || product.price_normal;

                    // Add to quote
                    addQuoteItemRow(
                        product.id,
                        product.name,
                        unitPrice,
                        product.on_hand || productQty,
                        0
                    );

                    // Clear search and hide results
                    $('#productSearchResults').hide();
                    $('#productSearch').val('').focus();
                }
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to create product');
                $('#quickAddProductBtn').prop('disabled', false).html(
                    '<i class="ri-check-line me-1"></i>Create & Add to Quote');
            });
        });

        // Get current price tier based on customer
        function getPriceTier() {
            if (quoteSelectedCustomer) {
                return quoteSelectedCustomer.price_tier || 'normal';
            } else {
                return 'normal';
            }
        }

        // Add product to quote
        $(document).on('click', '.add-product-btn', function() {
            const productData = $(this).closest('.product-search-item').data('product');
            const priceTier = getPriceTier();
            const price = productData['price_' + priceTier];

            // Check if product is already in quote
            const existingRow = $(`input[value="${productData.id}"]`).closest('tr');
            if (existingRow.length > 0) {
                toastr.warning('Product already added to quote');
                return;
            }

            // Show stock warnings (but ALLOW adding - it's just a quote!)
            // Quotes don't consume stock, so we allow any quantity
            if (productData.current_stock < 0) {
                toastr.warning(
                    `⚠️ NEGATIVE stock (${productData.current_stock}). Added to quote - will need stock when converting to invoice.`,
                    'Low Stock Warning', {
                        timeOut: 4000
                    }
                );
            } else if (productData.current_stock === 0) {
                toastr.info(
                    '📦 OUT OF STOCK. Added to quote - you can source stock before invoice.',
                    'Stock Info', {
                        timeOut: 4000
                    }
                );
            } else if (productData.current_stock > 0 && productData.current_stock < 5) {
                toastr.info(
                    `📦 Low stock: Only ${productData.current_stock} units available.`,
                    'Stock Info', {
                        timeOut: 3000
                    }
                );
            }

            addQuoteItemRow(productData.id, productData.name, price, productData.current_stock,
                productData.fifo_cost || 0);
            $('#productSearchResults').hide();
            $('#productSearch').val('').focus(); // Clear and refocus for next product
            toastr.success('Product added! Ready for next...');
        });

        // Show/hide empty state message
        function updateEmptyState() {
            if ($('#quoteItemsBody tr').length === 0) {
                $('#emptyQuoteItems').show();
                $('.table-responsive').hide();
            } else {
                $('#emptyQuoteItems').hide();
                $('.table-responsive').show();
            }
        }

        // Initial empty state
        updateEmptyState();

        // Add quote item row function
        function addQuoteItemRow(productId = '', productName = '', unitPrice = 0, stock = 0, fifoCost = 0) {
            const row = `
            <tr data-index="${quoteItemIndex}" data-product-id="${productId}">
                <td>${quoteItemIndex + 1}</td>
                <td>
                    <input type="hidden" name="items[${quoteItemIndex}][product_id]" value="${productId}">
                    <strong>${productName}</strong>
                    <br><small class="text-muted">SKU: ${productId || 'N/A'}</small>
                </td>
                <td>
                    <input type="number" name="items[${quoteItemIndex}][quantity]" class="form-control form-control-sm quantity" 
                           value="1" min="1" step="1">
                </td>
                <td>
                    <input type="number" name="items[${quoteItemIndex}][unit_price]" class="form-control form-control-sm unit-price" 
                           value="${unitPrice}" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" name="items[${quoteItemIndex}][discount]" class="form-control form-control-sm discount" 
                           value="0" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" name="items[${quoteItemIndex}][total]" class="form-control form-control-sm total" 
                           value="${unitPrice}" min="0" step="0.01" readonly>
                </td>
                <td>
                    <span class="badge ${stock > 0 ? 'bg-success-transparent' : stock < 0 ? 'bg-danger-transparent' : 'bg-warning-transparent'}">
                        ${stock}
                    </span>
                    ${stock < 0 ? '<br><small class="text-danger">NEGATIVE</small>' : ''}
                </td>
               
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
        `;
            $('#quoteItemsBody').append(row);
            quoteItemIndex++;
            updateQuoteTotals();
            updateEmptyState();
        }

        // Remove item
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
            updateQuoteTotals();
            updateEmptyState();
        });

        // Update row numbers
        function updateRowNumbers() {
            $('#quoteItemsBody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Calculate totals
        $(document).on('input change', '.quantity, .unit-price, .discount', function() {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            // Validate discount limits
            const maxDiscountAllowed = {{ auth()->user()->max_discount_allowed ?? 10 }};
            const lineTotal = quantity * unitPrice;
            const maxDiscountAmount = (lineTotal * maxDiscountAllowed) / 100;

            if (discount > maxDiscountAmount) {
                toastr.warning(
                    `Discount cannot exceed ${maxDiscountAllowed}% (R${maxDiscountAmount.toFixed(2)}) for your role`,
                    'Discount Limit Exceeded'
                );
                row.find('.discount').val(maxDiscountAmount.toFixed(2));
                discount = maxDiscountAmount;
            }

            const total = (quantity * unitPrice) - discount;
            row.find('.total').val(total.toFixed(2));

            updateQuoteTotals();
        });

        // Update quote totals
        function updateQuoteTotals() {
            let subtotal = 0;
            $('.total').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            const totalDiscount = parseFloat($('#totalDiscount').val()) || 0;
            const shipping = parseFloat($('#shipping').val()) || 0;
            const vatEnabled = $('#vatEnabled').is(':checked');
            const vatRate = parseFloat($('#vatRate').val()) || 15;
            const vatInclusive = $('#vatInclusive').val() === '1';

            let vatAmount = 0;
            let grandTotal = 0;

            if (vatEnabled) {
                    const vatBase = subtotal - totalDiscount + shipping;

                if (vatInclusive) {
                    // VAT Inclusive: VAT is already in the price
                    // Extract VAT: VAT = Total × (Rate / (100 + Rate))
                    vatAmount = vatBase * (vatRate / (100 + vatRate));
                    grandTotal = vatBase; // Total stays same (VAT already included)
                } else {
                    // VAT Exclusive: Add VAT on top
                    vatAmount = vatBase * (vatRate / 100);
                    grandTotal = vatBase + vatAmount;
                }
            } else {
                grandTotal = subtotal - totalDiscount + shipping;
            }

            $('#subtotal').val(subtotal.toFixed(2));
            $('#vatAmount').val(vatAmount.toFixed(2));
            $('#grandTotal').val(grandTotal.toFixed(2));
            $('#grandTotalDisplay').text('R ' + grandTotal.toFixed(2));

            @if (auth()->user()->canSeeCosts())
                // Calculate total FIFO cost for Owner
                let totalCost = 0;
                let totalQty = 0;
                $('tr[data-product-id]').each(function() {
                    const qty = parseFloat($(this).find('.quantity').val()) || 0;
                    const fifoCost = parseFloat($(this).find('input[name*="[fifo_cost]"]').val()) || 0;
                    totalCost += (qty * fifoCost);
                    totalQty += qty;
                });

                const estimatedProfit = grandTotal - totalCost;
                const profitMargin = grandTotal > 0 ? ((estimatedProfit / grandTotal) * 100) : 0;

                $('#totalCostDisplay').text('R ' + totalCost.toFixed(2));
                $('#estimatedProfitDisplay').text('R ' + estimatedProfit.toFixed(2));
                $('#profitMarginDisplay').text(profitMargin.toFixed(1) + '%')
                    .removeClass('bg-success-transparent bg-warning-transparent bg-danger-transparent')
                    .addClass(profitMargin > 30 ? 'bg-success-transparent' : profitMargin > 15 ?
                        'bg-warning-transparent' : 'bg-danger-transparent');
            @endif
        }

        // VAT toggle
        $('#vatEnabled, #totalDiscount, #shipping').on('change input', updateQuoteTotals);

        // Additional discount validation on blur (when field loses focus)
        $(document).on('blur', '.discount', function() {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            let discount = parseFloat($(this).val()) || 0;

            const maxDiscountAllowed = {{ auth()->user()->max_discount_allowed ?? 10 }};
            const lineTotal = quantity * unitPrice;
            const maxDiscountAmount = (lineTotal * maxDiscountAllowed) / 100;

            if (discount > maxDiscountAmount) {
                toastr.error(
                    `Your role allows maximum ${maxDiscountAllowed}% discount (R${maxDiscountAmount.toFixed(2)}). Discount has been corrected.`,
                    'Discount Limit Exceeded', {
                        timeOut: 5000
                    }
                );
                $(this).val(maxDiscountAmount.toFixed(2));
                $(this).trigger('input'); // Trigger recalculation
            }
        });

        // Validate overall discount field (cannot exceed subtotal) - REAL-TIME
        let discountValidationTimeout;
        $(document).on('input keyup blur change', '#totalDiscount', function() {
            const $field = $(this);
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            let totalDiscount = parseFloat($field.val()) || 0;

            // Clear previous timeout for debounce
            clearTimeout(discountValidationTimeout);

            // Immediate validation for negative or crazy numbers
            if (totalDiscount < 0) {
                $field.val(0);
                toastr.error('Discount cannot be negative', 'Invalid Discount');
                updateQuoteTotals();
                return;
            }

            // Immediate check for discount > subtotal (instant correction) - PRIORITY CHECK
            if (totalDiscount > subtotal && subtotal > 0) {
                $field.val(subtotal.toFixed(2));
                toastr.error(
                    `Discount corrected to maximum allowed (R${subtotal.toFixed(2)})`,
                    'Discount Exceeds Subtotal'
                );
                updateQuoteTotals();
                return; // STOP HERE - Don't show warning if already exceeded
            }

            // Warning for large discounts (50-100%) - only if NOT exceeding subtotal
            if ($(this).is(':focus') === false && subtotal > 0) {
                const discountPercent = (totalDiscount / subtotal) * 100;
                if (discountPercent > 50 && discountPercent <= 100) {
                    toastr.warning(
                        `⚠️ Large discount: ${discountPercent.toFixed(1)}% of subtotal. Please verify.`,
                        'High Discount Alert', {
                            timeOut: 3000
                        }
                    );
                }
            }
        });

        // Prevent paste of crazy numbers in discount field
        $(document).on('paste', '#totalDiscount', function(e) {
            const $field = $(this);

            // Get pasted data
            setTimeout(function() {
                const subtotal = parseFloat($('#subtotal').val()) || 0;
                let pastedValue = parseFloat($field.val()) || 0;

                // Check if pasted value is too large
                if (pastedValue > subtotal && subtotal > 0) {
                    $field.val(subtotal.toFixed(2));
                    toastr.error(
                        `Pasted discount (R${pastedValue.toFixed(2)}) exceeds subtotal (R${subtotal.toFixed(2)}). Corrected automatically.`,
                        'Invalid Paste'
                    );
                    updateQuoteTotals();
                } else if (pastedValue > 999999.99) {
                    $field.val(999999.99);
                    toastr.error('Discount too large. Maximum is R999,999.99', 'Invalid Paste');
                    updateQuoteTotals();
                } else if (pastedValue < 0) {
                    $field.val(0);
                    toastr.error('Discount cannot be negative', 'Invalid Paste');
                    updateQuoteTotals();
                }
            }, 10);
        });

        // Debug: Log max discount on page load
        console.log('User Max Discount Allowed:', {{ auth()->user()->max_discount_allowed ?? 10 }}, '%');

        // Initialize Select2 for fitment dropdowns
        function initFitmentSelect2() {
            $('.select2-fitment-make, .select2-fitment-model, .select2-fitment-engine').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    const $element = $(this);
                    $element.select2({
                        dropdownParent: $('#quoteModal'),
                        width: '100%',
                        placeholder: $element.find('option:first').text(),
                        allowClear: true,
                        tags: true,
                        createTag: function(params) {
                            const term = $.trim(params.term);
                            if (term === '') return null;
                            return {
                                id: 'new:' + term,
                                text: term + ' (Press Enter to add)',
                                newTag: true
                            };
                        }
                    }).on('select2:select', function(e) {
                        const data = e.params.data;
                        if (data.newTag) {
                            const newName = data.text.replace(' (Press Enter to add)', '');
                            const $select = $(this);
                            let endpoint = '';

                            // Determine endpoint based on field type
                            if ($select.hasClass('select2-fitment-make')) {
                                endpoint = '{{ route('car-makes.quick-add') }}';
                            } else if ($select.hasClass('select2-fitment-model')) {
                                endpoint = '{{ route('car-models.quick-add') }}';
                            } else if ($select.hasClass('select2-fitment-engine')) {
                                endpoint = '{{ route('car-engines.quick-add') }}';
                            }

                            // AJAX call to save
                            $.ajax({
                                url: endpoint,
                                method: 'POST',
                                data: {
                                    name: newName,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Add new option
                                        const newOption = new Option(response.data
                                            .name, response.data.id, true, true);
                                        $select.append(newOption);
                                        $select.val(response.data.id).trigger(
                                            'change');

                                        // Show success message
                                        toastr.success(response.data.name +
                                            ' added successfully!');
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error(
                                        'Failed to add. Please try again.');
                                    $select.val('').trigger('change');
                                }
                            });
                        }
                    });
                }
            });
        }

        // Initialize Select2 for vehicle information dropdowns
        function initVehicleSelect2() {
            $('.select2-vehicle-make, .select2-vehicle-model, .select2-vehicle-engine').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    const $element = $(this);
                    $element.select2({
                        dropdownParent: $('#quoteModal'),
                        width: '100%',
                        placeholder: $element.find('option:first').text(),
                        allowClear: true,
                        tags: true,
                        createTag: function(params) {
                            const term = $.trim(params.term);
                            if (term === '') return null;
                            return {
                                id: 'new:' + term,
                                text: term + ' (Press Enter to add)',
                                newTag: true
                            };
                        }
                    }).on('select2:select', function(e) {
                        const data = e.params.data;
                        if (data.newTag) {
                            const newName = data.text.replace(' (Press Enter to add)', '');
                            const $select = $(this);
                            let endpoint = '';

                            // Determine endpoint based on field type
                            if ($select.hasClass('select2-vehicle-make')) {
                                endpoint = '{{ route('car-makes.quick-add') }}';
                            } else if ($select.hasClass('select2-vehicle-model')) {
                                endpoint = '{{ route('car-models.quick-add') }}';
                            } else if ($select.hasClass('select2-vehicle-engine')) {
                                endpoint = '{{ route('car-engines.quick-add') }}';
                            }

                            // AJAX call to save
                            $.ajax({
                                url: endpoint,
                                method: 'POST',
                                data: {
                                    name: newName,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Add new option
                                        const newOption = new Option(response.data
                                            .name, response.data.id, true, true);
                                        $select.append(newOption);
                                        $select.val(response.data.id).trigger(
                                            'change');

                                        // Show success message
                                        toastr.success(response.data.name +
                                            ' added successfully!');
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error(
                                        'Failed to add. Please try again.');
                                    $select.val('').trigger('change');
                                }
                            });
                        }
                    });
                }
            });
        }

        // Initialize after modal content is loaded
        setTimeout(function() {
            initFitmentSelect2();
            initVehicleSelect2();
        }, 200);


        // Make selection handler - filter models by selected make (for fitment search)
        $('#fitmentMake').on('change', function() {
            const makeId = $(this).val();
            const modelSelect = $('#fitmentModel');
            const engineSelect = $('#fitmentEngine');

            // Filter models by selected make
            modelSelect.find('option').each(function() {
                const option = $(this);
                const makeIdAttr = option.data('make-id');

                if (option.val() === '' || makeIdAttr == makeId || makeId === '') {
                    option.show();
                } else {
                    option.hide();
                }
            });

            // Reset model and engine selections
            modelSelect.val('');
            engineSelect.val('');

            if (makeId) {
                modelSelect.prop('disabled', false);
            } else {
                modelSelect.prop('disabled', false);
            }
        });

        // Vehicle information make selection handler - filter models by selected make
        $('.select2-vehicle-make').on('change', function() {
            const makeId = $(this).val();
            const modelSelect = $('.select2-vehicle-model');
            const engineSelect = $('.select2-vehicle-engine');

            // Filter models by selected make
            modelSelect.find('option').each(function() {
                const option = $(this);
                const makeIdAttr = option.data('make-id');

                if (option.val() === '' || makeIdAttr == makeId || makeId === '') {
                    option.show();
                } else {
                    option.hide();
                }
            });

            // Reset model and engine selections
            modelSelect.val('').trigger('change');
            engineSelect.val('').trigger('change');
        });

        // Model selection handler
        $('#fitmentModel').on('change', function() {
            const modelId = $(this).val();
            const engineSelect = $('#fitmentEngine');

            if (modelId) {
                engineSelect.prop('disabled', false);
            } else {
                engineSelect.val('');
            }
        });

        // Search by fitment

        // Add another fitment row
        $('#addAnotherFitment').on('click', function() {
            const currentYear = new Date().getFullYear();
            let yearOptions = '';
            for (let year = currentYear + 2; year >= 1980; year--) {
                yearOptions += `<option value="${year}">${year}</option>`;
            }

            const fitmentRow = `
             <div class="row mb-2 fitment-search-row border p-2 rounded bg-light align-items-center">
                 <div class="col-md-3">
                     <label class="form-label mb-1 small text-muted">Make</label>
                     <select class="form-select form-select-sm select2-fitment-make">
                         <option value="">Select Make</option>
                         @foreach ($makes as $make)
                             <option value="{{ $make->id }}" data-name="{{ $make->name }}">{{ $make->name }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Model</label>
                     <select class="form-select form-select-sm select2-fitment-model">
                         <option value="">Select Model</option>
                         @foreach ($models as $model)
                             <option value="{{ $model->id }}" data-name="{{ $model->name }}" data-make-id="{{ $model->make_id }}">{{ $model->name }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Engine</label>
                     <select class="form-select form-select-sm select2-fitment-engine">
                         <option value="">Optional</option>
                         @foreach ($engines as $engine)
                             <option value="{{ $engine->id }}" data-code="{{ $engine->code }}">{{ $engine->code }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Year From</label>
                     <select class="form-select form-select-sm fitment-year-from-select">
                         <option value="">From</option>
                         ${yearOptions}
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Year To</label>
                     <select class="form-select form-select-sm fitment-year-to-select">
                         <option value="">To</option>
                         ${yearOptions}
                     </select>
                 </div>
                 <div class="col-md-1">
                     <label class="form-label mb-1 small text-muted">&nbsp;</label>
                     <button type="button" class="btn btn-sm btn-danger w-100 remove-fitment-row">
                         <i class="ri-delete-bin-line"></i>
                     </button>
                 </div>
             </div>
         `;

            $('#fitmentSearchContainer').append(fitmentRow);

            // Initialize Select2 for newly added fitment dropdowns
            setTimeout(function() {
                // Initialize Select2 for the newly added row
                $('.select2-fitment-make, .select2-fitment-model, .select2-fitment-engine')
                    .each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            const $element = $(this);
                            $element.select2({
                                dropdownParent: $('#quoteModal'),
                                width: '100%',
                                placeholder: $element.find('option:first').text(),
                                allowClear: true,
                                tags: true,
                                createTag: function(params) {
                                    const term = $.trim(params.term);
                                    if (term === '') return null;
                                    return {
                                        id: 'new:' + term,
                                        text: term + ' (Press Enter to add)',
                                        newTag: true
                                    };
                                }
                            }).on('select2:select', function(e) {
                                const data = e.params.data;
                                if (data.newTag) {
                                    const newName = data.text.replace(
                                        ' (Press Enter to add)', '');
                                    const $select = $(this);
                                    let endpoint = '';

                                    // Determine endpoint based on field type
                                    if ($select.hasClass('select2-fitment-make')) {
                                        endpoint =
                                            '{{ route('car-makes.quick-add') }}';
                                    } else if ($select.hasClass(
                                            'select2-fitment-model')) {
                                        endpoint =
                                            '{{ route('car-models.quick-add') }}';
                                    } else if ($select.hasClass(
                                            'select2-fitment-engine')) {
                                        endpoint =
                                            '{{ route('car-engines.quick-add') }}';
                                    }

                                    // AJAX call to save
                                    $.ajax({
                                        url: endpoint,
                                        method: 'POST',
                                        data: {
                                            name: newName,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                // Add new option
                                                const newOption =
                                                    new Option(response
                                                        .data.name,
                                                        response.data
                                                        .id, true, true
                                                    );
                                                $select.append(
                                                    newOption);
                                                $select.val(response
                                                        .data.id)
                                                    .trigger('change');

                                                // Show success message
                                                toastr.success(response
                                                    .data.name +
                                                    ' added successfully!'
                                                );
                                            }
                                        },
                                        error: function(xhr) {
                                            toastr.error(
                                                'Failed to add. Please try again.'
                                            );
                                            $select.val('').trigger(
                                                'change');
                                        }
                                    });
                                }
                            });
                        }
                    });
            }, 100);
        });

        // Remove fitment row
        $(document).on('click', '.remove-fitment-row', function() {
            $(this).closest('.fitment-search-row').remove();
        });

        // Quick Add Product Button
        $(document).on('click', '#quickAddProduct', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Show modal without backdrop (since it's nested in quote modal)
            const quickModal = new bootstrap.Modal(document.getElementById('quickAddProductModal'), {
                backdrop: false,
                keyboard: true
            });
            quickModal.show();

            // Auto-focus on product name field
            setTimeout(function() {
                $('#quickProductName').focus();
            }, 300);
        });

        // Global F2 shortcut for Quick Add
        $(document).on('keydown', function(e) {
            if (e.key === 'F2' && !$('#quickAddProductModal').hasClass('show')) {
                e.preventDefault();
                $('#quickAddProduct').click();
            }
        });

        // Create Product From Search (when no results found)
        $(document).on('click', '#createProductFromSearch', function() {
            const productName = $(this).data('name');

            // Open Quick Add modal with pre-filled name
            const quickModal = new bootstrap.Modal(document.getElementById('quickAddProductModal'), {
                backdrop: false,
                keyboard: true
            });
            quickModal.show();

            // Pre-fill product name
            setTimeout(function() {
                $('#quickProductName').val(productName).focus();
                // Move cursor to end of text
                const input = document.getElementById('quickProductName');
                input.setSelectionRange(input.value.length, input.value.length);
                // Focus on price field instead
                setTimeout(function() {
                    $('#quickProductPrice').focus();
                }, 100);
            }, 300);

            // Hide search results
            $('#productSearchResults').hide();
        });

        // Quick Add Product Form Submit
        $('#quickAddProductForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span>Creating...');

            $.ajax({
                url: '{{ route('products.quickAdd') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Product created & added to quote!');

                        // Get product data
                        const product = response.product;

                        // Get customer price tier
                        const customerSelect = $('#customerSelect');
                        const selectedOption = customerSelect.find('option:selected');
                        const priceTier = selectedOption.data('price-tier') || 'normal';

                        // Determine unit price based on tier
                        let unitPrice = product.price_normal;
                        if (priceTier === 'workshop') {
                            unitPrice = product.price_workshop || product.price_normal;
                        } else if (priceTier === 'online') {
                            unitPrice = product.price_online || product.price_normal;
                        }

                        // Add product to quote using standard function
                        addQuoteItemRow(
                            product.id,
                            product.name,
                            unitPrice,
                            product.on_hand || 0,
                            0 // FIFO cost (new product, no cost yet)
                        );

                        // Close modal and reset form
                        const quickModal = bootstrap.Modal.getInstance(document
                            .getElementById('quickAddProductModal'));
                        if (quickModal) {
                            quickModal.hide();
                        }
                        $('#quickAddProductForm')[0].reset();

                        // Refocus on search for next product
                        setTimeout(function() {
                            $('#productSearch').focus();
                        }, 300);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to create product');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Toggle Advanced Options in Quick Add
        $(document).on('click', '#toggleAdvancedQuickAdd', function(e) {
            e.preventDefault();
            const $advanced = $('#advancedQuickAddFields');
            const $icon = $(this).find('i');

            if ($advanced.is(':visible')) {
                $advanced.slideUp(200);
                $icon.removeClass('ri-arrow-up-s-line').addClass('ri-arrow-down-s-line');
                $(this).html('<i class="ri-arrow-down-s-line"></i> Advanced Options (Stock & Cost)');
            } else {
                $advanced.slideDown(200);
                $icon.removeClass('ri-arrow-down-s-line').addClass('ri-arrow-up-s-line');
                $(this).html('<i class="ri-arrow-up-s-line"></i> Hide Advanced Options');
            }
        });

        // Reset form when modal is closed
        $('#quickAddProductModal').on('hidden.bs.modal', function() {
            $('#quickAddProductForm')[0].reset();
            // Reset advanced options to collapsed
            $('#advancedQuickAddFields').hide();
            $('#toggleAdvancedQuickAdd').html(
                '<i class="ri-arrow-down-s-line"></i> Advanced Options (Stock & Cost)');
        });

        // Close Quick Add modal when clicking outside of it
        $(document).on('click', function(e) {
            const quickAddModal = document.getElementById('quickAddProductModal');
            if (quickAddModal && quickAddModal.classList.contains('show')) {
                if (!$(e.target).closest('.modal-content').length && !$(e.target).closest(
                        '#quickAddProduct').length) {
                    const modalInstance = bootstrap.Modal.getInstance(quickAddModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }
        });

        // Preview Quote Button
        $('#previewQuote').on('click', function() {
            // Validate that at least one item is added
            if ($('#quoteItemsBody tr').length === 0) {
                toastr.warning('Please add at least one product to preview the quote');
                return;
            }

            // Get form data
            const isWalkIn = $('#quoteWalkInName').val();
            let customerName, customerEmail, customerPhone, customerAddress;
            
            if (isWalkIn) {
                // Walk-in customer
                customerName = $('#quoteWalkInName').val() || 'Walk-in Customer';
                customerEmail = $('#quoteWalkInEmail').val() || '-';
                customerPhone = $('#quoteWalkInPhone').val() || '-';
                customerAddress = $('#quoteWalkInAddress').val() || '-';
            } else if (quoteSelectedCustomer) {
                // Selected customer
                customerName = quoteSelectedCustomer.name || '-';
                customerEmail = quoteSelectedCustomer.email || '-';
                customerPhone = quoteSelectedCustomer.phone || '-';
                customerAddress = quoteSelectedCustomer.address || '-';
            } else {
                customerName = 'Walk-in Customer';
                customerEmail = '-';
                customerPhone = '-';
                customerAddress = '-';
            }

            // Get vehicle info (check both walk-in and selected vehicle)
            let vehicleMake, vehicleModel, vehicleYear, vehicleEngine, vehicleReg, vehicleVin, vehicleMileage;
            
            if (quoteCurrentVehicle) {
                // Selected vehicle from customer
                vehicleMake = quoteCurrentVehicle.make_name || '-';
                vehicleModel = quoteCurrentVehicle.model_name || '-';
                vehicleYear = quoteCurrentVehicle.year || '-';
                vehicleEngine = quoteCurrentVehicle.engine || '-';
                vehicleReg = quoteCurrentVehicle.registration_number || '-';
                vehicleVin = quoteCurrentVehicle.vin_number || '-';
                vehicleMileage = quoteCurrentVehicle.mileage ? quoteCurrentVehicle.mileage + ' km' : '-';
            } else {
                // Walk-in vehicle (manual entry)
                vehicleMake = $('#quoteWalkInVehicleMake option:selected').text() || '-';
                vehicleModel = $('#quoteWalkInVehicleModel option:selected').text() || '-';
                vehicleYear = $('#quoteWalkInVehicleYear').val() || '-';
                vehicleEngine = $('input[name="vehicle_engine"]').val() || '-';
                vehicleReg = $('input[name="vehicle_reg"]').val() || '-';
                vehicleVin = $('input[name="vehicle_vin"]').val() || '-';
                vehicleMileage = $('input[name="vehicle_mileage"]').val() ? $('input[name="vehicle_mileage"]').val() + ' km' : '-';
            }

            // Get quote items
            let itemsHtml = '';
            let itemNumber = 1;
            $('#quoteItemsBody tr').each(function() {
                const productName = $(this).find('strong').text();
                const qty = $(this).find('.quantity').val();
                const unitPrice = parseFloat($(this).find('.unit-price').val());
                const discount = parseFloat($(this).find('.discount').val());
                const total = parseFloat($(this).find('.total').val());

                itemsHtml += `
                    <tr>
                        <td class="text-center">${itemNumber++}</td>
                        <td>${productName}</td>
                        <td class="text-center">${qty}</td>
                        <td class="text-end">R ${unitPrice.toFixed(2)}</td>
                        <td class="text-end">R ${discount.toFixed(2)}</td>
                        <td class="text-end fw-bold">R ${total.toFixed(2)}</td>
                    </tr>
                `;
            });

            // Get totals
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const totalDiscount = parseFloat($('#totalDiscount').val()) || 0;
            const shipping = parseFloat($('#shipping').val()) || 0;
            const vatEnabled = $('#vatEnabled').is(':checked');
            const vatAmount = parseFloat($('#vatAmount').val()) || 0;
            const grandTotal = parseFloat($('#grandTotal').val()) || 0;

            // Generate preview HTML
            const previewHtml = `
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">QUOTATION</h4>
                                <small>Draft Preview</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <h5 class="mb-0">{{ auth()->user()->company_name ?? 'Your Company' }}</h5>
                                <small>Date: ${new Date().toLocaleDateString()}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-2"><i class="ri-user-line me-1"></i>Customer Details</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td class="fw-semibold" width="80">Name:</td><td>${customerName}</td></tr>
                                    <tr><td class="fw-semibold">Email:</td><td>${customerEmail}</td></tr>
                                    <tr><td class="fw-semibold">Phone:</td><td>${customerPhone}</td></tr>
                                    <tr><td class="fw-semibold">Address:</td><td>${customerAddress}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-info mb-2"><i class="ri-car-line me-1"></i>Vehicle Details</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td class="fw-semibold" width="110">Registration:</td><td>${vehicleReg}</td></tr>
                                    <tr><td class="fw-semibold">Make & Model:</td><td>${vehicleMake} ${vehicleModel}</td></tr>
                                    <tr><td class="fw-semibold">Year:</td><td>${vehicleYear}</td></tr>
                                    ${vehicleEngine !== '-' ? `<tr><td class="fw-semibold">Engine:</td><td>${vehicleEngine}</td></tr>` : ''}
                                    ${vehicleVin !== '-' ? `<tr><td class="fw-semibold">VIN:</td><td>${vehicleVin}</td></tr>` : ''}
                                    ${vehicleMileage !== '-' ? `<tr><td class="fw-semibold">Mileage:</td><td>${vehicleMileage}</td></tr>` : ''}
                                </table>
                            </div>
                        </div>
                        
                        <!-- Items Table -->
                        <h6 class="text-success mb-2"><i class="ri-shopping-cart-line me-1"></i>Quote Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="40%">Product</th>
                                        <th width="10%" class="text-center">Qty</th>
                                        <th width="15%" class="text-end">Unit Price</th>
                                        <th width="15%" class="text-end">Discount</th>
                                        <th width="15%" class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Totals -->
                        <div class="row mt-4">
                            <div class="col-md-7"></div>
                            <div class="col-md-5">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-semibold">Subtotal:</td>
                                        <td class="text-end">R ${subtotal.toFixed(2)}</td>
                                    </tr>
                                    ${totalDiscount > 0 ? `
                                    <tr>
                                        <td class="fw-semibold">Discount:</td>
                                        <td class="text-end text-danger">- R ${totalDiscount.toFixed(2)}</td>
                                    </tr>` : ''}
                                    ${shipping > 0 ? `
                                    <tr>
                                        <td class="fw-semibold">Shipping:</td>
                                        <td class="text-end">R ${shipping.toFixed(2)}</td>
                                    </tr>` : ''}
                                    ${vatEnabled ? `
                                    <tr>
                                        <td class="fw-semibold">VAT (15%):</td>
                                        <td class="text-end">R ${vatAmount.toFixed(2)}</td>
                                    </tr>` : ''}
                                    <tr class="table-primary">
                                        <td class="fw-bold fs-5">GRAND TOTAL:</td>
                                        <td class="text-end fw-bold fs-5">R ${grandTotal.toFixed(2)}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Terms -->
                        <div class="alert alert-info mt-4">
                            <small>
                                <strong>Terms & Conditions:</strong><br>
                                • This quotation is valid for 30 days from the date of issue.<br>
                                • Prices are subject to change without notice.<br>
                                • Payment terms: As agreed.
                            </small>
                        </div>
                    </div>
                </div>
            `;

            // Show preview modal
            $('#quotePreviewContent').html(previewHtml);
            const previewModal = new bootstrap.Modal(document.getElementById('quotePreviewModal'));
            previewModal.show();
        });

        // Don't initialize with empty row - let user search/add products
        // addQuoteItemRow(); // Commented out - no manual row on load

        // POS Style: Vehicle Management Functions
        let quoteCustomerVehicles = [];
        let quoteCurrentVehicle = null;
        let quoteCurrentCustomer = null;

        // Load Customer Vehicles (Same as POS)
        function loadQuoteCustomerVehicles(customerId) {
            quoteCurrentCustomer = customerId;

            // Clear previous vehicle selection and details
            quoteCurrentVehicle = null;
            $('#vehicleSelect').val('');
            $('#vehicleInfo').hide().html('');

            const url = "{{ route('api.customers.vehicles', ':id') }}".replace(':id', customerId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    quoteCustomerVehicles = data;
                    if (data.length > 0) {
                        $('#vehicleSection').show();
                        $('#noVehicleMessage').hide();
                        populateQuoteVehicleDropdown();

                        // Auto-select primary vehicle if exists
                        const primaryVehicle = data.find(v => v.is_primary);
                        if (primaryVehicle) {
                            $('#vehicleSelect').val(primaryVehicle.id);
                            selectQuoteVehicle();
                        }
                    } else {
                        // No vehicles - clear everything and show message
                        $('#vehicleSection').show();
                        $('#noVehicleMessage').hide();
                        $('#vehicleSelect').html('<option value="">No vehicles - Click + to add</option>');
                        $('#vehicleInfo').hide().html(''); // Clear vehicle details
                    }
                })
                .catch(error => {
                    console.error('Error loading vehicles:', error);
                    $('#vehicleSection').show();
                    $('#noVehicleMessage').show();
                    $('#vehicleInfo').hide().html(''); // Clear on error too
                });
        }

        // Populate Vehicle Dropdown
        function populateQuoteVehicleDropdown() {
            const select = $('#vehicleSelect');
            select.html('<option value="">Select Vehicle</option>');

            quoteCustomerVehicles.forEach(vehicle => {
                select.append(`<option value="${vehicle.id}" 
                    data-make="${vehicle.make_name}" 
                    data-model="${vehicle.model_name}"
                    data-reg="${vehicle.registration_number}"
                    data-mileage="${vehicle.mileage}">
                    ${vehicle.display_name}
                </option>`);
            });
        }

        // Select Vehicle (Show Details)
        window.selectQuoteVehicle = function() {
            const vehicleId = $('#vehicleSelect').val();

            if (vehicleId) {
                quoteCurrentVehicle = quoteCustomerVehicles.find(v => v.id == vehicleId);

                if (quoteCurrentVehicle) {
                    const html = `
                        <div class="card border-info shadow-sm mt-2">
                            <div class="card-body p-2">
                                <div class="row g-2">
                                       <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-road-map-line text-danger me-1"></i>Registration
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.registration_number || 'N/A'}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-car-line text-primary me-1"></i>Make
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.make_name}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-car-line text-success me-1"></i>Model
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.model_name}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-calendar-line text-info me-1"></i>Year
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.year || 'N/A'}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-settings-3-line text-warning me-1"></i>Engine
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.engine || 'N/A'}" disabled>
                                    </div>
                                 
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-barcode-line text-secondary me-1"></i>VIN
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.vin_number || 'N/A'}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-speed-line text-warning me-1"></i>Mileage
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="${quoteCurrentVehicle.mileage ? quoteCurrentVehicle.mileage + ' km' : 'N/A'}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#vehicleInfo').html(html).show();
                }
            } else {
                quoteCurrentVehicle = null;
                $('#vehicleInfo').hide();
            }
        };

        // Show Add Vehicle Modal
        window.showAddQuoteVehicleModal = function() {
            if (!quoteCurrentCustomer) {
                toastr.error('Please select a customer first');
                return;
            }

            // Open POS add vehicle modal (we'll create this)
            const addVehicleModal = new bootstrap.Modal(document.getElementById('addQuoteVehicleModal'), {
                backdrop: false,
                keyboard: true
            });
            addVehicleModal.show();
        };
        
        // ═══════════════════════════════════════════════════════════════════════
        // EDIT MODE: Load existing quote data
        // ═══════════════════════════════════════════════════════════════════════
        
        if (editMode && existingQuote) {
            console.log('Loading existing quote data...');
            
            // Load customer if exists
            if (existingQuote.customer) {
                quoteSelectedCustomer = existingQuote.customer;
                
                // Hide walk-in, show selected customer
                $('#quoteWalkInCustomerForm').hide();
                $('#quoteWalkInVehicleForm').hide();
                $('#quoteCustomerInfo').show();
                
                // Populate customer info
                $('#quoteSelectedCustomerName').text(existingQuote.customer.name);
                $('#quoteSelectedCustomerContact').text(existingQuote.customer.email || existingQuote.customer.phone || 'N/A');
                $('#quoteCustomerId').val(existingQuote.customer.id);
                
                // Set customer type badge
                const customerType = existingQuote.customer.terms === 'credit' ? 'Credit' : 'Cash';
                const badgeClass = existingQuote.customer.terms === 'credit' ? 'bg-warning' : 'bg-success';
                $('#quoteSelectedCustomerType').html(`<span class="badge ${badgeClass}">${customerType}</span>`);
                
                // Set price tier
                const priceTier = existingQuote.customer.price_tier || 'normal';
                $('#priceTierIndicator').text('(' + priceTier.charAt(0).toUpperCase() + priceTier.slice(1) + ')');
                updatePricesForCustomerTier(priceTier);
                
                // Load customer vehicles
                if (existingQuote.customer.id) {
                    loadQuoteCustomerVehicles(existingQuote.customer.id);
                    
                    // If quote has vehicle, select it after loading
                    setTimeout(function() {
                        if (existingQuote.vehicle_make_id && existingQuote.vehicle_model_id) {
                            // Find vehicle by make/model
                            const vehicle = quoteCustomerVehicles.find(v => 
                                v.make_id == existingQuote.vehicle_make_id && 
                                v.model_id == existingQuote.vehicle_model_id
                            );
                            
                            if (vehicle) {
                                $('#vehicleSelect').val(vehicle.id);
                                selectQuoteVehicle();
                            }
                        }
                    }, 500);
                }
            } else {
                // Walk-in customer - populate walk-in form
                $('#quoteWalkInName').val(existingQuote.customer_name || '');
                $('#quoteWalkInPhone').val(existingQuote.customer_phone || '');
                $('#quoteWalkInEmail').val(existingQuote.customer_email || '');
                $('#quoteWalkInAddress').val(existingQuote.customer_address || '');
                
                // Walk-in vehicle data
                if (existingQuote.vehicle_make_id) {
                    $('#quoteWalkInVehicleMake').val(existingQuote.vehicle_make_id).trigger('change');
                    
                    setTimeout(function() {
                        if (existingQuote.vehicle_model_id) {
                            $('#quoteWalkInVehicleModel').val(existingQuote.vehicle_model_id);
                        }
                    }, 300);
                }
                
                if (existingQuote.vehicle_year) {
                    $('#quoteWalkInVehicleYear').val(existingQuote.vehicle_year);
                }
                
                $('input[name="vehicle_engine"]').val(existingQuote.vehicle_engine || '');
                $('input[name="vehicle_reg"]').val(existingQuote.vehicle_reg || '');
                $('input[name="vehicle_vin"]').val(existingQuote.vehicle_vin || '');
                $('input[name="vehicle_mileage"]').val(existingQuote.vehicle_mileage || '');
            }
            
            // Load existing quote items
            if (existingQuote.items && existingQuote.items.length > 0) {
                existingQuote.items.forEach(function(item) {
                    if (item.product) {
                        addQuoteItemRow(
                            item.product.id,
                            item.product.name,
                            item.product.sku,
                            parseFloat(item.unit_price),
                            parseInt(item.quantity),
                            item.product.qty || 0,
                            parseFloat(item.discount || 0)
                        );
                    }
                });
                
                // Update totals after loading items
                setTimeout(function() {
                    updateQuoteTotals();
                }, 100);
            }
            
            // Set totals
            $('#totalDiscount').val(parseFloat(existingQuote.total_discount || 0).toFixed(2));
            $('#shipping').val(parseFloat(existingQuote.shipping || 0).toFixed(2));
            
            // Set VAT
            if (existingQuote.vat && existingQuote.vat > 0) {
                $('#vatEnabled').prop('checked', true);
            }
            
            // Trigger total calculation
            updateQuoteTotals();
        }
        
        // ═══════════════════════════════════════════════════════════════════════
        // END EDIT MODE INITIALIZATION
        // ═══════════════════════════════════════════════════════════════════════
    });
</script>

<!-- Quick Add Product Modal -->
<div class="modal fade" id="quickAddProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false"
    data-bs-keyboard="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning" style="border-width: 3px;">
            <form id="quickAddProductForm">
                @csrf
                <div class="modal-header bg-warning-transparent">
                    <h5 class="modal-title">
                        <i class="ri-flashlight-line text-warning me-2"></i>⚡ Quick Add Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" tabindex="-1"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" id="quickProductName" name="name"
                            class="form-control form-control-lg" placeholder="Enter product name" required
                            tabindex="1" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Selling Price (R) <span
                                class="text-danger">*</span></label>
                        <input type="number" id="quickProductPrice" name="price_normal"
                            class="form-control form-control-lg" placeholder="0.00" step="0.01" required
                            tabindex="2">
                    </div>

                    <!-- Collapsible Advanced Options -->
                    <div class="mb-2">
                        <a href="#" id="toggleAdvancedQuickAdd" class="text-muted small">
                            <i class="ri-arrow-down-s-line"></i> Advanced Options (Stock & Cost)
                        </a>
                    </div>

                    <div id="advancedQuickAddFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Initial Quantity</label>
                                <input type="number" id="quickProductQty" name="qty" class="form-control"
                                    placeholder="0" min="0" tabindex="3">
                                <small class="text-muted d-block">Leave blank if no stock</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Unit Cost (R)</label>
                                <input type="number" id="quickProductCost" name="unit_cost" class="form-control"
                                    placeholder="0.00" step="0.01" min="0" tabindex="4">
                                <small class="text-muted d-block">Your purchase/cost price</small>
                            </div>
                        </div>
                        <div class="alert alert-info-transparent py-2 mb-3">
                            <small>
                                <i class="ri-information-line me-1"></i>
                                <strong>Optional:</strong> Leave blank for special order items. You can add stock & cost
                                later from Products page.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="6">
                        <i class="ri-close-line me-1"></i>Cancel (ESC)
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" tabindex="5">
                        <i class="ri-add-circle-line me-1"></i>Create & Add to Quote (Enter)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Customer Modal (Quick Add Style) -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="true"
    style="z-index: 1065;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success" style="border-width: 3px;">
            <form id="addCustomerForm">
                @csrf
                <div class="modal-header bg-success-transparent">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line text-success me-2"></i>⚡ Quick 
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" tabindex="-1"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Customer Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="customerName" name="name"
                                class="form-control form-control-lg" placeholder="Enter full name" required autofocus
                                tabindex="1">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" id="customerEmail" name="email"
                                class="form-control form-control-lg" placeholder="customer@example.com"
                                tabindex="2">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" id="customerPhone" name="phone" class="form-control"
                                placeholder="123-456-7890" tabindex="3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price Tier</label>
                            <select name="price_tier" class="form-select" tabindex="4">
                                <option value="normal">Normal (Retail)</option>
                                <option value="workshop">Workshop</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" tabindex="7">
                        <i class="ri-close-line me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success" tabindex="6">
                        <i class="ri-user-add-line me-1"></i>Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal (Same as POS) -->
<div class="modal fade" id="addQuoteVehicleModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="true"
    style="z-index: 1070;">
    <div class="modal-dialog">
        <div class="modal-content border-primary" style="border-width: 3px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ri-car-line me-2"></i>Add New Vehicle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addQuoteVehicleForm">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-road-map-line text-danger me-1"></i>Registration Number
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-sm shadow-sm select2-add-vehicle-reg"
                                id="newQuoteVehicleReg" placeholder="e.g., ABC123GP" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-car-line text-primary me-1"></i>Make
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm shadow-sm select2-add-vehicle-make"
                                id="newQuoteVehicleMake" required>
                                <option value="">Select Make</option>
                                @foreach ($makes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-car-line text-success me-1"></i>Model
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm shadow-sm select2-add-vehicle-model"
                                id="newQuoteVehicleModel" required>
                                <option value="">Select Model</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-calendar-line text-info me-1"></i>Year
                            </label>
                            <select class="form-select form-select-sm shadow-sm select2-add-vehicle-year"
                                id="newQuoteVehicleYear">
                                <option value="">Select Year</option>
                                @for ($year = date('Y') + 1; $year >= 1980; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-settings-3-line text-warning me-1"></i>Engine
                            </label>
                            <input type="text" class="form-control form-control-sm shadow-sm"
                                id="newQuoteVehicleEngine" placeholder="e.g., 2.0L Turbo">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-barcode-line text-secondary me-1"></i>VIN Number
                            </label>
                            <input type="text" class="form-control form-control-sm shadow-sm"
                                id="newQuoteVehicleVin" placeholder="Optional">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1 fw-semibold">
                                <i class="ri-speed-line text-warning me-1"></i>Mileage
                            </label>
                            <input type="number" class="form-control form-control-sm shadow-sm"
                                id="newQuoteVehicleMileage" placeholder="km">
                        </div>
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

<script>
    // Initialize Select2 for Add Vehicle Modal
    $(document).ready(function() {
        // Initialize Select2 on modal show
        $('#addQuoteVehicleModal').on('shown.bs.modal', function() {
            if (!$('.select2-add-vehicle-make').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-make').select2({
                    dropdownParent: $('#addQuoteVehicleModal'),
                    placeholder: 'Select Make',
                    allowClear: true,
                    width: '100%'
                });
            }

            if (!$('.select2-add-vehicle-model').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-model').select2({
                    dropdownParent: $('#addQuoteVehicleModal'),
                    placeholder: 'Select Model',
                    allowClear: true,
                    width: '100%'
                });
            }

            if (!$('.select2-add-vehicle-year').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-year').select2({
                    dropdownParent: $('#addQuoteVehicleModal'),
                    placeholder: 'Select Year',
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        // Clean up on modal hide
        $('#addQuoteVehicleModal').on('hidden.bs.modal', function() {
            if ($('.select2-add-vehicle-make').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-make').select2('destroy');
            }
            if ($('.select2-add-vehicle-model').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-model').select2('destroy');
            }
            if ($('.select2-add-vehicle-year').hasClass('select2-hidden-accessible')) {
                $('.select2-add-vehicle-year').select2('destroy');
            }
        });
    });

    // Load models when make is selected (for add vehicle modal)
    $('#newQuoteVehicleMake').on('change', function() {
        const makeId = $(this).val();
        if (makeId) {
            const url = "{{ route('api.vehicle-makes.models', ':makeId') }}".replace(':makeId', makeId);
            $.get(url, function(models) {
                let html = '<option value="">Select Model</option>';
                models.forEach(model => {
                    html += `<option value="${model.id}">${model.name}</option>`;
                });
                $('#newQuoteVehicleModel').html(html);
            });
        }
    });

    // Add Vehicle Form Submit (Same as POS)
    $('#addQuoteVehicleForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            customer_id: quoteCurrentCustomer,
            registration_number: $('#newQuoteVehicleReg').val(),
            make_id: $('#newQuoteVehicleMake').val(),
            model_id: $('#newQuoteVehicleModel').val(),
            year: $('#newQuoteVehicleYear').val(),
            engine: $('#newQuoteVehicleEngine').val(),
            vin_number: $('#newQuoteVehicleVin').val(),
            mileage: $('#newQuoteVehicleMileage').val(),
            _token: '{{ csrf_token() }}'
        };

        $.post('/vehicles', formData, function(response) {
            if (response.success) {
                toastr.success('Vehicle added successfully');
                $('#addQuoteVehicleModal .btn-close').click();
                $('#addQuoteVehicleForm')[0].reset();
                loadQuoteCustomerVehicles(quoteCurrentCustomer);
            }
        }).fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Error adding vehicle');
        });
    });
</script>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Fix Select2 in input-group to work properly with button */
        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .select2-container .select2-selection {
            height: 100%;
            border-radius: 0;
        }

        .input-group .select2-container:first-child .select2-selection {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        #addNewCustomerBtn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Product search item hover effect */
        .product-search-item {
            border: 1px solid #e0e0e0 !important;
        }

        .product-search-item:hover {
            background: #f8f9fa !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15) !important;
        }

        .product-search-item:hover .add-product-btn {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3) !important;
        }
    </style>
@endpush

<!-- Quote Preview Modal -->
<div class="modal fade" id="quotePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info-transparent">
                <h5 class="modal-title">
                    <i class="ri-eye-line me-2"></i>Quote Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quotePreviewContent">
                <!-- Preview content will be generated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Vehicle selection change handlers (for cascading dropdowns)
            $(document).on('change', '#vehicleMakeSelect', function() {
                const selectedMakeId = $(this).val();
                console.log('Make selected:', selectedMakeId);

                // Filter models based on selected make
                $('#vehicleModelSelect option').show();
                if (selectedMakeId) {
                    $('#vehicleModelSelect option[data-make-id]:not([data-make-id="' + selectedMakeId +
                        '"])').hide();
                }
            });

            // Debug: Log vehicle IDs on form submission
            $('#quoteCreateForm').on('submit', function(e) {
                console.log('Form submission - Vehicle IDs:');
                console.log('Make ID:', $('#vehicleMakeSelect').val());
                console.log('Model ID:', $('#vehicleModelSelect').val());
                console.log('Engine ID:', $('#vehicleEngineSelect').val());

                // Allow form submission to proceed
                return true;
            });
        });
    </script>
@endpush
