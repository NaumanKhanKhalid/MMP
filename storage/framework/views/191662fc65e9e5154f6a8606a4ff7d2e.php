<form action="<?php echo e(route('quotes.store')); ?>" method="POST" id="quoteCreateForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="ri-file-add-line me-2"></i> Create New Quote
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

        <!-- Customer & Vehicle Info - Combined in One Card -->
        <div class="card mb-3">
            <div class="card-header bg-primary-transparent py-2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="card-title mb-0">
                            <i class="ri-user-line me-2"></i>Customer Info
                        </h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6 class="card-title mb-0">
                            <i class="ri-car-line me-2"></i>Vehicle Info <small class="text-muted">(Optional)</small>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <!-- LEFT SIDE: Customer Fields -->
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label fw-semibold mb-1 small">Customer <span
                                    class="text-danger">*</span></label>
                            <select name="customer_id" id="customerSelect" class="form-select form-select-sm" required>
                                <option value="">-- Select Customer --</option>
                                <option value="add_new" class="text-success fw-bold" style="background-color: #e8f5e9;">
                                    ➕ Add New Customer
                                </option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" data-name="<?php echo e($customer->name); ?>"
                                        data-email="<?php echo e($customer->email); ?>" data-phone="<?php echo e($customer->phone); ?>"
                                        data-address="<?php echo e($customer->address); ?>"
                                        data-price-tier="<?php echo e($customer->price_tier ?? 'normal'); ?>">
                                        <?php echo e($customer->name); ?> - <?php echo e($customer->email); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Customer Details Display (for selected customer) -->
                        <div id="customerDetails" class="alert alert-light py-2 mb-0 mt-2" style="display: none;">
                            <div class="small">
                                <div class="mb-1"><i class="ri-mail-line me-1 text-primary"></i><span
                                        id="customerEmail"></span></div>
                                <div class="mb-1"><i class="ri-phone-line me-1 text-success"></i><span
                                        id="customerPhone"></span></div>
                                <div class="mb-1"><i class="ri-map-pin-line me-1 text-danger"></i><span
                                        id="customerAddress"></span></div>
                                <div><i class="ri-price-tag-3-line me-1 text-info"></i><span
                                        class="badge bg-info-transparent" id="customerPriceTier"></span></div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control form-control-sm"
                                    value="<?php echo e(date('Y-m-d', strtotime('+30 days'))); ?>">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="draft">Draft</option>
                                    <option value="sent">Sent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Vehicle Fields -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Make</label>
                                <select name="vehicle_make_id" id="vehicleMakeSelect"
                                    class="form-select form-select-sm select2-vehicle-make">
                                    <option value="">Select Make</option>
                                    <?php $__currentLoopData = $makes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $make): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($make->id); ?>" data-name="<?php echo e($make->name); ?>">
                                            <?php echo e($make->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Model</label>
                                <select name="vehicle_model_id" id="vehicleModelSelect"
                                    class="form-select form-select-sm select2-vehicle-model">
                                    <option value="">Select Model</option>
                                    <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($model->id); ?>" data-name="<?php echo e($model->name); ?>"
                                            data-make-id="<?php echo e($model->make_id); ?>"><?php echo e($model->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Engine</label>
                                <select name="vehicle_engine_id" id="vehicleEngineSelect"
                                    class="form-select form-select-sm select2-vehicle-engine">
                                    <option value="">Select Engine</option>
                                    <?php $__currentLoopData = $engines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($engine->id); ?>" data-code="<?php echo e($engine->code); ?>">
                                            <?php echo e($engine->code); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Year</label>
                                <input type="number" name="vehicle_year" class="form-control form-control-sm"
                                    placeholder="e.g., 2020">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label mb-1 small">VIN Number</label>
                                <input type="text" name="vehicle_vin" class="form-control form-control-sm"
                                    placeholder="Vehicle Identification Number">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Registration</label>
                                <input type="text" name="vehicle_reg" class="form-control form-control-sm"
                                    placeholder="e.g., ABC123GP">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label mb-1 small">Mileage</label>
                                <input type="number" name="vehicle_mileage" class="form-control form-control-sm"
                                    placeholder="e.g., 50000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products & Items Section -->
        <div class="card mb-3">
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
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="barcodeScanBtn"
                                title="Barcode">
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
                                    <i class="ri-information-line text-warning ms-1" data-bs-toggle="tooltip"
                                        title="Max <?php echo e(auth()->user()->max_discount_allowed ?? 10); ?>% per line for your role"></i>
                                </th>
                                <th class="border-0 py-2" style="width: 12%;">Total</th>
                                <th class="border-0 py-2" style="width: 8%;">Stock</th>
                                
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

        <!-- Totals & Calculations Section - Compact -->
        <div class="card mb-2">
            <div class="card-header bg-warning-transparent py-2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="card-title mb-0">
                            <i class="ri-calculator-line me-2"></i>Quote Totals
                        </h6>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5 class="mb-0 text-primary" id="grandTotalDisplay">R 0.00</h5>
                        <input type="hidden" name="grand_total" id="grandTotal" value="0.00">
                    </div>
                </div>
            </div>
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label mb-1 small">Subtotal</label>
                        <input type="number" name="subtotal" id="subtotal"
                            class="form-control form-control-sm bg-light" value="0.00" step="0.01" readonly>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label mb-1 small">
                            Discount
                            <i class="ri-information-line text-info ms-1" data-bs-toggle="tooltip"
                                title="Additional discount on entire quote"></i>
                        </label>
                        <input type="number" name="total_discount" id="totalDiscount"
                            class="form-control form-control-sm" value="0.00" step="0.01" min="0"
                            max="999999.99">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label mb-1 small">Shipping</label>
                        <input type="number" name="shipping" id="shipping" class="form-control form-control-sm"
                            value="0.00" step="0.01">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label mb-1 small">
                            VAT (<?php echo e($vatSettings['rate']); ?>%)
                            <span
                                class="badge <?php echo e($vatSettings['inclusive'] ? 'bg-info' : 'bg-warning'); ?> badge-sm"><?php echo e($vatSettings['inclusive'] ? 'Inc' : 'Exc'); ?></span>
                        </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-text">
                                <input type="checkbox" name="vat_enabled" id="vatEnabled" class="form-check-input"
                                    <?php echo e($vatSettings['enabled'] ? 'checked' : ''); ?>>
                            </div>
                            <input type="number" name="vat_amount" id="vatAmount" class="form-control bg-light"
                                value="0.00" step="0.01" readonly>
                            <input type="hidden" id="vatRate" value="<?php echo e($vatSettings['rate']); ?>">
                            <input type="hidden" id="vatInclusive"
                                value="<?php echo e($vatSettings['inclusive'] ? '1' : '0'); ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    </div>
    <div class="modal-footer py-2">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line"></i> Cancel
        </button>
        <button type="button" class="btn btn-sm btn-info" id="previewQuote">
            <i class="ri-eye-line"></i> Preview
        </button>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="ri-save-line"></i> Create Quote
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        let quoteItemIndex = 0;
        let searchTimeout;

        // Initialize Select2 for customer dropdown
        $('#customerSelect').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            dropdownParent: $('#quoteModal')
        });

        // Handle "Add New Customer" option selection
        function openAddCustomerModal() {
            const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'), {
                backdrop: false,
                keyboard: true
            });
            addCustomerModal.show();

            // Auto-focus on name field
            setTimeout(function() {
                $('#customerName').focus();
            }, 300);
        }

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
                url: '<?php echo e(route('customers.store')); ?>',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Customer created successfully!');

                        // Add new customer to dropdown
                        const newOption = new Option(
                            response.customer.name + ' - ' + response.customer.email,
                            response.customer.id,
                            true,
                            true
                        );

                        // Set data attributes
                        $(newOption).attr('data-name', response.customer.name);
                        $(newOption).attr('data-email', response.customer.email);
                        $(newOption).attr('data-phone', response.customer.phone);
                        $(newOption).attr('data-address', response.customer.address);
                        $(newOption).attr('data-price-tier', response.customer.price_tier);

                        $('#customerSelect').append(newOption).trigger('change');

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

        // Customer selection handler
        $('#customerSelect').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const customerId = selectedOption.val();

            // Check if "Add New Customer" was selected
            if (customerId === 'add_new') {
                // Open add customer modal
                openAddCustomerModal();

                // Reset select to empty
                $(this).val('').trigger('change.select2');
                return;
            }

            if (customerId && customerId !== '') {
                // Customer selected - show details
                $('#customerEmail').text(selectedOption.data('email'));
                $('#customerPhone').text(selectedOption.data('phone'));
                $('#customerAddress').text(selectedOption.data('address'));
                $('#customerPriceTier').text(selectedOption.data('price-tier'));
                $('#customerDetails').show();

                // Update price tier indicator
                $('#priceTierIndicator').text('(' + selectedOption.data('price-tier') + ')');

                // Update prices for existing items based on new customer's price tier
                updatePricesForCustomerTier(selectedOption.data('price-tier'));
            } else {
                // No customer selected - hide details
                $('#customerDetails').hide();

                // Reset price tier to normal
                $('#priceTierIndicator').text('(Normal)');
                updatePricesForCustomerTier('normal');
            }
        });

        // Update prices for all items based on customer price tier
        function updatePricesForCustomerTier(priceTier) {
            $('.unit-price').each(function() {
                const row = $(this).closest('tr');
                const productId = row.find('input[name*="[product_id]"]').val();

                if (productId) {
                    // Get the product data from the search results or make an AJAX call
                    // For now, we'll show a message to the user
                    toastr.info('Price tier changed to ' + priceTier +
                        '. You may need to update prices manually or re-add products.');
                }
            });
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
                url: '<?php echo e(route('quotes.search-products')); ?>',
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
                            <div class="bg-light px-3 py-2">
                                <small class="text-muted fw-semibold">
                                    <i class="ri-search-line me-1"></i>
                                    ${response.products.length} Result${response.products.length > 1 ? 's' : ''}
                                </small>
                            </div>
                        `;

                        response.products.forEach(function(product) {
                            let stockBadge;
                            if (product.current_stock > 0) {
                                stockBadge =
                                    `<span class="badge bg-success">${product.current_stock} ${product.unit}</span>`;
                            } else if (product.current_stock < 0) {
                                stockBadge =
                                    `<span class="badge bg-danger">${product.current_stock} NEG</span>`;
                            } else {
                                stockBadge =
                                    `<span class="badge bg-warning text-dark">Out of Stock</span>`;
                            }

                            const priceTier = getPriceTier();
                            const price = product['price_' + priceTier];

                            html += `
                            <div class="product-search-item p-3 mb-2 mx-2 rounded-3 shadow-sm border" data-product='${JSON.stringify(product)}' 
                                 style="background: linear-gradient(to right, #ffffff 0%, #f8f9fa 100%); transition: all 0.3s ease; cursor: pointer;">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div style="position: relative;">
                                            <img src="${product.image_url}" class="rounded-3" 
                                                 style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #e0e0e0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                                                 onerror="this.src='/assets/images/pos-system/1.jpg'">
                                            <div class="position-absolute top-0 end-0 translate-middle">
                                                ${stockBadge}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <h6 class="mb-1 fw-bold text-dark">${product.name}</h6>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="badge bg-light text-dark border">SKU: ${product.sku}</span>
                                                    ${product.brand ? `<span class="badge bg-primary"><i class="ri-bookmark-fill"></i> ${product.brand}</span>` : ''}
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <div class="bg-white rounded-2 p-2 border">
                                                    <div class="small text-muted mb-1">Price</div>
                                                    <div class="h5 mb-0 text-success fw-bold">R ${parseFloat(price).toFixed(2)}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small">
                                                    ${product.oe_numbers.length > 0 ? `
                                                        <div class="mb-1">
                                                            <span class="badge bg-primary-transparent">
                                                                <i class="ri-tools-fill"></i> OE: ${product.oe_numbers.slice(0, 2).join(', ')}
                                                            </span>
                                                        </div>` : ''}
                                                    ${product.cross_refs.length > 0 ? `
                                                        <div>
                                                            <span class="badge bg-info-transparent">
                                                                <i class="ri-links-fill"></i> Cross: ${product.cross_refs.slice(0, 2).join(', ')}
                                                            </span>
                                                        </div>` : ''}
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button type="button" class="btn btn-success add-product-btn w-100 fw-bold" style="box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);">
                                                    <i class="ri-add-circle-fill me-1"></i>Add
                                                </button>
                                                ${product.current_stock <= 0 && !product.allow_negative ? 
                                                    '<div class="badge bg-danger w-100 mt-1"><i class="ri-close-circle-fill"></i> Unavailable</div>' : 
                                                    product.current_stock < 0 ? 
                                                    '<div class="badge bg-warning text-dark w-100 mt-1"><i class="ri-error-warning-fill"></i> Low Stock</div>' : 
                                                    '<div class="badge bg-success-transparent w-100 mt-1"><i class="ri-checkbox-circle-fill"></i> Available</div>'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        $('#productSearchResults').html(html).show();
                    } else {
                        // No results - show create option
                        const searchQuery = query;
                        $('#productSearchResults').html(`
                            <div class="card border-warning">
                                <div class="card-body text-center py-4">
                                    <i class="ri-search-line ri-3x text-muted mb-3"></i>
                                    <h6>No products found matching "${searchQuery}"</h6>
                                    <p class="text-muted mb-3">Would you like to create this product?</p>
                                    <button type="button" class="btn btn-warning" id="createProductFromSearch" data-name="${searchQuery}">
                                        <i class="ri-add-line me-1"></i>Create "${searchQuery}" Now
                                    </button>
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

        // Get current price tier based on customer
        function getPriceTier() {
            const selectedCustomer = $('#customerSelect option:selected');
            if (selectedCustomer.val() && selectedCustomer.val() !== '') {
                return selectedCustomer.data('price-tier') || 'normal';
            } else {
                // Cash Sale / Manual Entry - use normal pricing
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
            const maxDiscountAllowed = <?php echo e(auth()->user()->max_discount_allowed ?? 10); ?>;
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
                if (vatInclusive) {
                    // VAT Inclusive: The prices already include VAT, so we need to show the VAT breakdown
                    // For display purposes, we'll show VAT as if it's exclusive (more common business practice)
                    const vatBase = subtotal - totalDiscount + shipping;
                    vatAmount = vatBase * (vatRate / 100);
                    grandTotal = vatBase + vatAmount;
                } else {
                    // VAT Exclusive: Add VAT on top
                    const vatBase = subtotal - totalDiscount + shipping;
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

            <?php if(auth()->user()->canSeeCosts()): ?>
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
            <?php endif; ?>
        }

        // VAT toggle
        $('#vatEnabled, #totalDiscount, #shipping').on('change input', updateQuoteTotals);

        // Additional discount validation on blur (when field loses focus)
        $(document).on('blur', '.discount', function() {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            let discount = parseFloat($(this).val()) || 0;

            const maxDiscountAllowed = <?php echo e(auth()->user()->max_discount_allowed ?? 10); ?>;
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
        console.log('User Max Discount Allowed:', <?php echo e(auth()->user()->max_discount_allowed ?? 10); ?>, '%');

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
                                endpoint = '<?php echo e(route('car-makes.quick-add')); ?>';
                            } else if ($select.hasClass('select2-fitment-model')) {
                                endpoint = '<?php echo e(route('car-models.quick-add')); ?>';
                            } else if ($select.hasClass('select2-fitment-engine')) {
                                endpoint = '<?php echo e(route('car-engines.quick-add')); ?>';
                            }

                            // AJAX call to save
                            $.ajax({
                                url: endpoint,
                                method: 'POST',
                                data: {
                                    name: newName,
                                    _token: '<?php echo e(csrf_token()); ?>'
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
                                endpoint = '<?php echo e(route('car-makes.quick-add')); ?>';
                            } else if ($select.hasClass('select2-vehicle-model')) {
                                endpoint = '<?php echo e(route('car-models.quick-add')); ?>';
                            } else if ($select.hasClass('select2-vehicle-engine')) {
                                endpoint = '<?php echo e(route('car-engines.quick-add')); ?>';
                            }

                            // AJAX call to save
                            $.ajax({
                                url: endpoint,
                                method: 'POST',
                                data: {
                                    name: newName,
                                    _token: '<?php echo e(csrf_token()); ?>'
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
                         <?php $__currentLoopData = $makes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $make): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="<?php echo e($make->id); ?>" data-name="<?php echo e($make->name); ?>"><?php echo e($make->name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Model</label>
                     <select class="form-select form-select-sm select2-fitment-model">
                         <option value="">Select Model</option>
                         <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="<?php echo e($model->id); ?>" data-name="<?php echo e($model->name); ?>" data-make-id="<?php echo e($model->make_id); ?>"><?php echo e($model->name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </select>
                 </div>
                 <div class="col-md-2">
                     <label class="form-label mb-1 small text-muted">Engine</label>
                     <select class="form-select form-select-sm select2-fitment-engine">
                         <option value="">Optional</option>
                         <?php $__currentLoopData = $engines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="<?php echo e($engine->id); ?>" data-code="<?php echo e($engine->code); ?>"><?php echo e($engine->code); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            '<?php echo e(route('car-makes.quick-add')); ?>';
                                    } else if ($select.hasClass(
                                            'select2-fitment-model')) {
                                        endpoint =
                                            '<?php echo e(route('car-models.quick-add')); ?>';
                                    } else if ($select.hasClass(
                                            'select2-fitment-engine')) {
                                        endpoint =
                                            '<?php echo e(route('car-engines.quick-add')); ?>';
                                    }

                                    // AJAX call to save
                                    $.ajax({
                                        url: endpoint,
                                        method: 'POST',
                                        data: {
                                            name: newName,
                                            _token: '<?php echo e(csrf_token()); ?>'
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
                url: '<?php echo e(route('products.quickAdd')); ?>',
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
            const customerSelect = $('#customerSelect');
            const selectedCustomer = customerSelect.find('option:selected');
            const customerName = selectedCustomer.data('name') || 'Cash Sale';
            const customerEmail = selectedCustomer.data('email') || '-';
            const customerPhone = selectedCustomer.data('phone') || '-';
            const customerAddress = selectedCustomer.data('address') || '-';

            // Get vehicle info
            const vehicleMake = $('select[name="vehicle_make"] option:selected').text() || '-';
            const vehicleModel = $('select[name="vehicle_model"] option:selected').text() || '-';
            const vehicleYear = $('input[name="vehicle_year"]').val() || '-';
            const vehicleReg = $('input[name="vehicle_reg"]').val() || '-';

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
                                <h5 class="mb-0"><?php echo e(auth()->user()->company_name ?? 'Your Company'); ?></h5>
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
                                    <tr><td class="fw-semibold" width="80">Make:</td><td>${vehicleMake}</td></tr>
                                    <tr><td class="fw-semibold">Model:</td><td>${vehicleModel}</td></tr>
                                    <tr><td class="fw-semibold">Year:</td><td>${vehicleYear}</td></tr>
                                    <tr><td class="fw-semibold">Reg:</td><td>${vehicleReg}</td></tr>
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
    });
</script>

<!-- Quick Add Product Modal -->
<div class="modal fade" id="quickAddProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false"
    data-bs-keyboard="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning" style="border-width: 3px;">
            <form id="quickAddProductForm">
                <?php echo csrf_field(); ?>
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
                <?php echo csrf_field(); ?>
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

<?php $__env->startPush('styles'); ?>
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
            background: linear-gradient(to right, #f0f7ff 0%, #e8f4f8 100%) !important;
            border-color: #4dabf7 !important;
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(77, 171, 247, 0.2) !important;
        }

        .product-search-item:hover .add-product-btn {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(25, 135, 84, 0.4) !important;
        }

        .product-search-item:hover img {
            border-color: #4dabf7 !important;
            transform: scale(1.05);
        }
    </style>
<?php $__env->stopPush(); ?>

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

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/quotes/partials/create_modal.blade.php ENDPATH**/ ?>