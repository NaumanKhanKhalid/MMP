<form action="{{ route('quotes.update', $quote) }}" method="POST" id="quoteEditForm">
    @csrf
    @method('PUT')
    
    <div class="modal-header bg-warning-transparent">
        <h5 class="modal-title">
            <i class="ri-pencil-line me-2"></i> Edit Quote #{{ $quote->quote_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    
    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
        
        <!-- Customer & Basic Info Section -->
        <div class="card mb-3 border shadow-sm">
            <div class="card-header bg-primary-transparent py-2">
                <h6 class="card-title mb-0">
                    <i class="ri-user-line me-2"></i>Customer & Quote Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="editCustomerSelect" class="form-select" required style="width: 100%;">
                            <option value="">-- Cash Sale / Manual Entry --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        data-email="{{ $customer->email }}"
                                        data-phone="{{ $customer->phone }}"
                                        data-address="{{ $customer->address }}"
                                        data-price-tier="{{ $customer->price_tier }}"
                                        @if($quote->customer_id == $customer->id) selected @endif>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control form-control-sm" value="{{ $quote->valid_until }}">
                        <div class="form-text">Quote expiry date</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Quote Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="draft" @if($quote->status=='draft') selected @endif>Draft</option>
                            <option value="sent" @if($quote->status=='sent') selected @endif>Sent</option>
                            <option value="accepted" @if($quote->status=='accepted') selected @endif>Accepted</option>
                            <option value="declined" @if($quote->status=='declined') selected @endif>Declined</option>
                            <option value="expired" @if($quote->status=='expired') selected @endif>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Any special notes...">{{ $quote->notes }}</textarea>
                    </div>
                    </div>
                </div>
            </div>

        <!-- Vehicle Information Section -->
        <div class="card mb-3 border shadow-sm">
            <div class="card-header bg-warning-transparent py-2">
                <h6 class="card-title mb-0">
                    <i class="ri-car-line me-2"></i>Vehicle Information (Optional)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Make</label>
                        <select name="vehicle_make_id" class="form-select form-select-sm select2-vehicle-make-edit" style="width: 100%;">
                            <option value="">Select Make</option>
                            @foreach($makes as $make)
                                <option value="{{ $make->id }}" data-name="{{ $make->name }}" @if($quote->vehicle_make_id == $make->id) selected @endif>
                                    {{ $make->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Model</label>
                        <select name="vehicle_model_id" class="form-select form-select-sm select2-vehicle-model-edit" style="width: 100%;">
                            <option value="">Select Model</option>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}" data-name="{{ $model->name }}" data-make-id="{{ $model->make_id ?? '' }}" @if($quote->vehicle_model_id == $model->id) selected @endif>
                                    {{ $model->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Engine</label>
                        <select name="vehicle_engine_id" class="form-select form-select-sm select2-vehicle-engine-edit" style="width: 100%;">
                            <option value="">Optional</option>
                            @foreach($engines as $engine)
                                <option value="{{ $engine->id }}" data-code="{{ $engine->code }}" @if($quote->vehicle_engine_id == $engine->id) selected @endif>
                                    {{ $engine->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Year</label>
                        <input type="number" name="vehicle_year" class="form-control form-control-sm" value="{{ $quote->vehicle_year }}" placeholder="e.g., 2020">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">VIN Number</label>
                        <input type="text" name="vehicle_vin" class="form-control form-control-sm" value="{{ $quote->vehicle_vin }}" placeholder="Vehicle Identification Number">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control form-control-sm" value="{{ $quote->vehicle_reg }}" placeholder="e.g., ABC123">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Mileage</label>
                        <input type="number" name="vehicle_mileage" class="form-control form-control-sm" value="{{ $quote->vehicle_mileage }}" placeholder="e.g., 50000">
                    </div>
                    </div>
                    </div>
                    </div>

        <!-- Products & Items Section -->
        <div class="card mb-3 border shadow-sm">
            <div class="card-header bg-success-transparent py-2">
                <h6 class="card-title mb-0">
                    <i class="ri-shopping-cart-line me-2"></i>Products & Items
                </h6>
                    </div>
            <div class="card-body">
                
                <!-- Product Search for Adding More Items -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" id="editProductSearch" class="form-control" placeholder="🔍 Type SKU, Barcode, or Product Name... (Press Enter to search)" autofocus>
                            <button type="button" class="btn btn-warning" id="editQuickAddProduct" title="Quick Add">
                                <i class="ri-flashlight-line"></i>  
                            </button>
                    </div>
                </div>
            </div>

                <!-- Search Results -->
                <div id="editProductSearchResults" class="mb-3" style="display: none;">
                    <!-- Search results will be populated here -->
                </div>

                <!-- Quote Items Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Product</th>
                                <th width="10%">Qty</th>
                                <th width="12%">Unit Price</th>
                                <th width="10%">Discount</th>
                                <th width="12%">Total</th>
                                <th width="10%">Stock</th>
                                <th width="5%">Actions</th>
                        </tr>
                    </thead>
                        <tbody id="editQuoteItemsBody">
                            @foreach($quote->items as $index => $item)
                                <tr data-item-index="{{ $index }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                        <strong>{{ $item->product->name ?? 'Product #' . $item->product_id }}</strong>
                                        @if($item->product)
                                            <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control form-control-sm item-quantity" value="{{ $item->quantity }}" min="1" step="1">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][unit_price]" class="form-control form-control-sm item-unit-price" value="{{ $item->unit_price }}" min="0" step="0.01">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][discount]" class="form-control form-control-sm item-discount" value="{{ $item->discount }}" min="0" step="0.01" data-max-discount="{{ auth()->user()->max_discount_allowed }}">
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][total]" class="form-control form-control-sm bg-light item-total" value="{{ $item->total }}" step="0.01" readonly>
                                    </td>
                                    <td class="text-center">
                                        @if($item->product)
                                            @php $stock = $item->product->stockBatches->sum('qty_left'); @endphp
                                            @if($stock > 0)
                                                <span class="badge bg-success-transparent">{{ $stock }}</span>
                                            @elseif($stock < 0)
                                                <span class="badge bg-danger-transparent">{{ $stock }}</span>
                                            @else
                                                <span class="badge bg-warning-transparent">0</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-edit-item" data-index="{{ $index }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

            </div>
        </div>

        <!-- Totals & Calculations Section -->
        <div class="card mb-3 border shadow-sm">
            <div class="card-header bg-info-transparent py-2">
                <h6 class="card-title mb-0">
                    <i class="ri-calculator-line me-2"></i>Quote Totals
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Subtotal</label>
                        <input type="number" name="subtotal" id="editSubtotal" class="form-control form-control-sm bg-light" value="{{ $quote->items->sum('total') }}" step="0.01" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Total Discount</label>
                        <input type="number" name="total_discount" id="editTotalDiscount" class="form-control form-control-sm" value="{{ $quote->total_discount ?? 0 }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Shipping</label>
                        <input type="number" name="shipping" id="editShipping" class="form-control form-control-sm" value="{{ $quote->shipping ?? 0 }}" step="0.01">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">
                            VAT ({{ $vatSettings['rate'] }}%)
                            @if($vatSettings['inclusive'])
                                <span class="badge bg-info text-white ms-1">Inclusive</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1">Exclusive</span>
                            @endif
                        </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-text">
                                <input type="checkbox" name="vat_enabled" id="editVatEnabled" class="form-check-input" @if(!empty($quote->vat)) checked @endif>
                    </div>
                            <input type="number" name="vat_amount" id="editVatAmount" class="form-control bg-light" value="{{ $quote->vat ?? 0 }}" step="0.01" readonly>
                            <input type="hidden" id="editVatRate" value="{{ $vatSettings['rate'] }}">
                            <input type="hidden" id="editVatInclusive" value="{{ $vatSettings['inclusive'] ? 1 : 0 }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">Grand Total: <span class="text-primary" id="editGrandTotalDisplay">R {{ number_format($quote->grand_total ?? $quote->items->sum('total'), 2) }}</span></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <input type="hidden" name="grand_total" id="editGrandTotal" value="{{ $quote->grand_total ?? $quote->items->sum('total') }}">
                                <small class="text-muted">Including VAT and shipping</small>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="ri-close-line"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="ri-save-line"></i> Update Quote
        </button>
    </div>
</form>

<script>
// Initialize Select2 immediately when this script runs (after modal content is loaded)
$(document).ready(function() {
    // Small delay to ensure DOM is ready
    setTimeout(function() {
        // Destroy existing Select2 instances to prevent conflicts
        if ($('#editCustomerSelect').hasClass('select2-hidden-accessible')) {
            $('#editCustomerSelect').select2('destroy');
        }
        if ($('.select2-vehicle-make-edit').hasClass('select2-hidden-accessible')) {
            $('.select2-vehicle-make-edit').select2('destroy');
        }
        if ($('.select2-vehicle-model-edit').hasClass('select2-hidden-accessible')) {
            $('.select2-vehicle-model-edit').select2('destroy');
        }
        if ($('.select2-vehicle-engine-edit').hasClass('select2-hidden-accessible')) {
            $('.select2-vehicle-engine-edit').select2('destroy');
        }

        // Initialize Select2 instances
        if ($('#editCustomerSelect').length) {
            $('#editCustomerSelect').select2({
                dropdownParent: $('#quoteModal'),
                placeholder: 'Select Customer',
                allowClear: true,
                width: '100%'
            });
        }

        // Vehicle Make/Model/Engine cascading for Edit Modal
        if ($('.select2-vehicle-make-edit').length) {
            $('.select2-vehicle-make-edit').select2({
                dropdownParent: $('#quoteModal'),
                placeholder: 'Select Make',
                allowClear: true
            });
        }
        if ($('.select2-vehicle-model-edit').length) {
            $('.select2-vehicle-model-edit').select2({
                dropdownParent: $('#quoteModal'),
                placeholder: 'Select Model',
                allowClear: true
            });
        }
        if ($('.select2-vehicle-engine-edit').length) {
            $('.select2-vehicle-engine-edit').select2({
                dropdownParent: $('#quoteModal'),
                placeholder: 'Optional',
                allowClear: true
            });
        }
    }, 200);
});

$(document).ready(function() {
    let editSearchTimeout;
    let editItemCounter = {{ count($quote->items) }};

    // Product search functionality with Enter key (same as create modal)
    $('#editProductSearch').on('keydown', function(e) {
        // Enter key - immediate search
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = $(this).val().trim();
            if (query.length >= 1) {
                editSearchProducts(query);
            }
        }
        // ESC key - close results
        else if (e.key === 'Escape') {
            $('#editProductSearchResults').hide();
            $(this).val('');
        }
        // F2 key - Quick Add
        else if (e.key === 'F2') {
            e.preventDefault();
            $('#editQuickAddProduct').click();
        }
    });

    // Also search on input (with debounce for auto-search - same as create modal)
    $('#editProductSearch').on('input', function() {
        const query = $(this).val().trim();

        clearTimeout(editSearchTimeout);
        if (query.length >= 2) {
            editSearchTimeout = setTimeout(function() {
                editSearchProducts(query);
            }, 500); // Same delay as create modal
        } else {
            $('#editProductSearchResults').hide();
        }
    });

    // Quick Add button in edit modal
    $('#editQuickAddProduct').on('click', function() {
        $('#quickAddModal').modal('show');
    });

    // Recalculate totals when quantities, prices, or discounts change
    $(document).on('input change', '#editQuoteItemsBody .item-quantity, #editQuoteItemsBody .item-unit-price, #editQuoteItemsBody .item-discount', function() {
        recalculateEditItemRow($(this).closest('tr'));
        recalculateEditTotals();
    });

    // Remove item from edit modal
    $(document).on('click', '.remove-edit-item', function() {
        $(this).closest('tr').remove();
        recalculateEditTotals();
        
        // Reindex items
        $('#editQuoteItemsBody tr').each(function(index) {
            $(this).attr('data-item-index', index);
            $(this).find('td:first').text(index + 1);
            $(this).find('input[name^="items"]').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/items\[\d+\]/, 'items[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    });

    // Recalculate on discount, shipping, VAT change
    $(document).on('input change', '#editTotalDiscount, #editShipping, #editVatEnabled', function() {
        recalculateEditTotals();
    });

    // Vehicle cascading dropdowns for edit modal
    $(document).on('change', '.select2-vehicle-make-edit', function() {
        const makeId = $(this).val();
        const $modelSelect = $('.select2-vehicle-model-edit');
        
        if (makeId) {
            $modelSelect.find('option').each(function() {
                const optionMakeId = $(this).data('make-id');
                if (optionMakeId && optionMakeId != makeId) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        } else {
            $modelSelect.find('option').show();
        }
        
        $modelSelect.val('').trigger('change');
        $('.select2-vehicle-engine-edit').val('').trigger('change');
    });

    // Search products function
    function editSearchProducts(query) {
        $.ajax({
            url: '{{ route("quotes.search-products") }}',
            method: 'GET',
            data: { q: query },
            beforeSend: function() {
                $('#editProductSearchResults').html(
                    '<div class="text-center p-3"><i class="ri-loader-4-line ri-spin"></i> Searching...</div>'
                ).show();
            },
            success: function(response) {
                displayEditSearchResults(response.products, query);
            },
            error: function(xhr) {
                $('#editProductSearchResults').html(
                    '<div class="alert alert-danger text-center">Error searching products. Please try again.</div>'
                ).show();
                toastr.error('Failed to search products');
                console.error('Search error:', xhr);
            }
        });
    }

    // Display search results with same styling as create modal
    function displayEditSearchResults(products, query) {
        const $resultsDiv = $('#editProductSearchResults');
        
        if (products.length === 0) {
            $resultsDiv.html(`
                <div class="card border-warning">
                    <div class="card-body text-center py-4">
                        <i class="ri-search-line ri-3x text-muted mb-3"></i>
                        <h6>No products found matching "${query}"</h6>
                        <p class="text-muted mb-0">Try a different search term or SKU</p>
                    </div>
                </div>
            `).show();
            return;
        }

        let html = `
            <div class="bg-light px-3 py-2 rounded-top">
                <small class="text-muted fw-semibold">
                    <i class="ri-search-line me-1"></i>
                    ${products.length} Result${products.length > 1 ? 's' : ''}
                </small>
            </div>
        `;

        products.forEach(function(product) {
            let stockBadge;
            if (product.current_stock > 0) {
                stockBadge = `<span class="badge bg-success">${product.current_stock} ${product.unit || 'pcs'}</span>`;
            } else if (product.current_stock < 0) {
                stockBadge = `<span class="badge bg-danger">${product.current_stock} NEG</span>`;
            } else {
                stockBadge = `<span class="badge bg-warning text-dark">Out of Stock</span>`;
            }

            const priceTier = getEditPriceTier();
            const price = product['price_' + priceTier];

            html += `
            <div class="product-search-item p-3 mb-2 mx-2 rounded-3 shadow-sm border" data-product-id="${product.id}" 
                 style="background: linear-gradient(to right, #ffffff 0%, #f8f9fa 100%); transition: all 0.3s ease; cursor: pointer;">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div style="position: relative;">
                            <img src="${product.image_url || '/assets/images/pos-system/1.jpg'}" class="rounded-3" 
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
                                    ${product.oe_numbers && product.oe_numbers.length > 0 ? `
                                        <div class="mb-1">
                                            <span class="badge bg-primary-transparent">
                                                <i class="ri-tools-fill"></i> OE: ${product.oe_numbers.slice(0, 2).join(', ')}
                                            </span>
                                        </div>` : ''}
                                    ${product.cross_refs && product.cross_refs.length > 0 ? `
                                        <div>
                                            <span class="badge bg-info-transparent">
                                                <i class="ri-links-fill"></i> Cross: ${product.cross_refs.slice(0, 2).join(', ')}
                                            </span>
                                        </div>` : ''}
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-success add-edit-product-btn w-100 fw-bold" 
                                        data-product='${JSON.stringify(product)}'
                                        style="box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);">
                                    <i class="ri-add-circle-fill me-1"></i>Add
                                </button>
                                ${product.current_stock > 0 ? 
                                    '<div class="badge bg-success-transparent w-100 mt-1"><i class="ri-checkbox-circle-fill"></i> Available</div>' :
                                    product.current_stock < 0 ? 
                                    '<div class="badge bg-warning text-dark w-100 mt-1"><i class="ri-error-warning-fill"></i> Low Stock</div>' :
                                    '<div class="badge bg-danger w-100 mt-1"><i class="ri-close-circle-fill"></i> Out of Stock</div>'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });

        $resultsDiv.html(html).show();
    }

    // Get current price tier based on customer
    function getEditPriceTier() {
        const selectedCustomer = $('#editCustomerSelect option:selected');
        if (selectedCustomer.val() && selectedCustomer.val() !== '') {
            return selectedCustomer.data('price-tier') || 'normal';
        } else {
            return 'normal';
        }
    }

    // Add product to quote from search results
    $(document).on('click', '.add-edit-product-btn', function() {
        const productData = JSON.parse($(this).attr('data-product'));
        const priceTier = getEditPriceTier();
        const price = productData['price_' + priceTier];

        // Check if product is already in quote
        let existingRow = null;
        $('#editQuoteItemsBody tr').each(function() {
            const existingProductId = $(this).find('input[name$="[product_id]"]').val();
            if (existingProductId == productData.id) {
                existingRow = $(this);
                return false;
            }
        });

        if (existingRow) {
            // Increment quantity
            const $qtyInput = existingRow.find('.item-quantity');
            const currentQty = parseFloat($qtyInput.val()) || 0;
            $qtyInput.val(currentQty + 1).trigger('change');
            toastr.info('Quantity updated for existing product');
            
            // Clear search
            $('#editProductSearch').val('');
            $('#editProductSearchResults').hide();
            return;
        }

        // Show stock warnings
        if (productData.current_stock < 0) {
            toastr.warning(
                `⚠️ NEGATIVE stock (${productData.current_stock}). Added to quote - will need stock when converting to invoice.`,
                'Low Stock Warning',
                { timeOut: 4000 }
            );
        } else if (productData.current_stock === 0) {
            toastr.info(
                '📦 OUT OF STOCK. Added to quote - you can source stock before invoice.',
                'Stock Info',
                { timeOut: 4000 }
            );
        } else if (productData.current_stock < 5) {
            toastr.info(`ℹ️ Low stock: Only ${productData.current_stock} units available.`, 'Stock Info');
        }

        // Add new row
        const index = editItemCounter++;
        
        const stock = productData.current_stock;
        const stockBadge = stock > 0 
            ? `<span class="badge bg-success-transparent">${stock}</span>` 
            : stock < 0 
                ? `<span class="badge bg-danger-transparent">${stock}</span>`
                : `<span class="badge bg-warning-transparent">0</span>`;

        const newRow = `
            <tr data-item-index="${index}">
                <td class="text-center">${$('#editQuoteItemsBody tr').length + 1}</td>
                <td>
                    <input type="hidden" name="items[${index}][product_id]" value="${productData.id}">
                    <strong>${productData.name}</strong><br>
                    <small class="text-muted">SKU: ${productData.sku}</small>
                </td>
                <td>
                    <input type="number" name="items[${index}][quantity]" class="form-control form-control-sm item-quantity" value="1" min="1" step="1">
                </td>
                <td>
                    <input type="number" name="items[${index}][unit_price]" class="form-control form-control-sm item-unit-price" value="${price}" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" name="items[${index}][discount]" class="form-control form-control-sm item-discount" value="0" min="0" step="0.01" data-max-discount="{{ auth()->user()->max_discount_allowed }}">
                </td>
                <td>
                    <input type="number" name="items[${index}][total]" class="form-control form-control-sm bg-light item-total" value="${price}" step="0.01" readonly>
                </td>
                    <td class="text-center">${stockBadge}</td>
                    <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-edit-item" data-index="${index}">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#editQuoteItemsBody').append(newRow);
        recalculateEditTotals();
        toastr.success('Product added to quote');

        // Clear search
        $('#editProductSearch').val('');
        $('#editProductSearchResults').hide();
    });
});

function recalculateEditItemRow($row) {
    const qty = parseFloat($row.find('.item-quantity').val()) || 0;
    const unitPrice = parseFloat($row.find('.item-unit-price').val()) || 0;
    const discount = parseFloat($row.find('.item-discount').val()) || 0;
    
    const total = (qty * unitPrice) - discount;
    $row.find('.item-total').val(total.toFixed(2));
}

function recalculateEditTotals() {
    let subtotal = 0;
    $('#editQuoteItemsBody tr').each(function() {
        const itemTotal = parseFloat($(this).find('.item-total').val()) || 0;
        subtotal += itemTotal;
    });

    $('#editSubtotal').val(subtotal.toFixed(2));

    const totalDiscount = parseFloat($('#editTotalDiscount').val()) || 0;
    const shipping = parseFloat($('#editShipping').val()) || 0;
    const vatEnabled = $('#editVatEnabled').is(':checked');
    const vatRate = parseFloat($('#editVatRate').val()) || 0;
    const vatInclusive = parseInt($('#editVatInclusive').val()) || 0;

    let vatAmount = 0;
    let grandTotal = subtotal - totalDiscount + shipping;

    if (vatEnabled) {
        if (vatInclusive) {
            // VAT is already included in prices
            vatAmount = grandTotal - (grandTotal / (1 + (vatRate / 100)));
        } else {
            // VAT is exclusive, add it on top
            vatAmount = grandTotal * (vatRate / 100);
            grandTotal += vatAmount;
        }
    }

    $('#editVatAmount').val(vatAmount.toFixed(2));
    $('#editGrandTotal').val(grandTotal.toFixed(2));
    $('#editGrandTotalDisplay').text('R ' + grandTotal.toLocaleString('en-ZA', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>
