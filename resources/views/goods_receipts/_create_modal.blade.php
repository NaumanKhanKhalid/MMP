{{-- resources/views/goods_receipts/_create_modal.blade.php --}}

<div class="modal fade" id="createGoodsReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-add-line me-2"></i> Create New Goods Receipt (GRN)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="grnCreateForm">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Mode Selection -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-settings-3-line me-1"></i> GRN Type
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="grn_mode" id="grnModePO"
                                            value="from_po" checked>
                                        <label class="form-check-label" for="grnModePO">
                                            <i class="ri-file-list-line me-1"></i> From Purchase Order
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="grn_mode"
                                            id="grnModeDirect" value="direct">
                                        <label class="form-check-label" for="grnModeDirect">
                                            <i class="ri-add-circle-line me-1"></i> Direct GRN (No PO)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-information-line me-1"></i> Basic Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- From PO Mode -->
                                    <div class="col-md-6 mb-3" id="fromPOFields">
                                        <label class="form-label">Select Purchase Order <span
                                                class="text-danger">*</span></label>
                                        <select name="purchase_order_id" id="grnPoSelect" class="form-select">
                                            <option value="">-- Select PO --</option>
                                            @foreach ($purchaseOrders as $po)
                                                <option value="{{ $po->id }}"
                                                    data-supplier="{{ $po->supplier->id ?? '' }}">
                                                    {{ $po->po_number }} ({{ $po->supplier->name ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                <!-- Direct GRN Mode -->
                                    <div class="col-md-6 mb-3" id="directGRNFields" style="display: none;">
                                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier_id" id="grnSupplierSelect" class="form-select">
                                            <option value="">-- Select Supplier --</option>
                                            @foreach ($suppliers as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Received Date <span class="text-danger">*</span></label>
                                    <input type="date" name="received_date" id="grnReceivedDate" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Search Section (Only for Direct GRN) -->
                    <div class="card mb-3" id="productSearchSection" style="display: none;">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-search-line me-1"></i> Product Search
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="position-relative">
                                <input type="text" id="grnProductSearch" class="form-control"
                                    placeholder="Search products by name, SKU... (Press F2 to focus)">
                                <div id="grnSearchResults" class="search-results-dropdown" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section - From PO Mode -->
                    <div class="card mb-3" id="fromPOItemsSection">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="ri-inbox-line me-1"></i> Items to Receive
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="fromPOItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Product</th>
                                            <th width="10%" class="text-center">Ordered</th>
                                            <th width="10%" class="text-center">Received</th>
                                            <th width="10%" class="text-center">Outstanding</th>
                                            <th width="12%">Receiving Now</th>
                                            <th width="12%">Unit Cost</th>
                                            <th width="16%" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="fromPOItemsBody">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section - Direct GRN Mode -->
                    <div class="card mb-3" id="directGRNItemsSection" style="display: none;">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="ri-shopping-cart-line me-1"></i> Goods Receipt Items
                            </h6>
                            <span class="badge bg-primary" id="directGRNItemCount">0 items</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="directGRNItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">Product</th>
                                            <th width="15%">Received Qty</th>
                                            <th width="15%">Unit Cost</th>
                                            <th width="15%" class="text-end">Total</th>
                                            <th width="15%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="directGRNItemsBody">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td class="text-end fw-semibold">Total Amount:</td>
                                            <td class="text-end fw-bold fs-18" id="grnTotalAmountDisplay">R 0.00</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveGrnBtn">
                        <i class="ri-save-line me-1"></i> Create GRN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let grnItems = [];
        let currentMode = 'from_po';

        // Initialize Select2 for supplier dropdown
        $('#grnSupplierSelect').select2({
            dropdownParent: $('#createGoodsReceiptModal'),
            placeholder: '-- Select Supplier --',
            allowClear: true
        });

        // Initialize Select2 for PO dropdown
        $('#grnPoSelect').select2({
            dropdownParent: $('#createGoodsReceiptModal'),
            placeholder: '-- Select PO --',
            allowClear: true
        });

        // Mode Toggle Handler
        $('input[name="grn_mode"]').on('change', function() {
            currentMode = $(this).val();

            if (currentMode === 'from_po') {
                // From PO Mode
                $('#fromPOFields').show();
                $('#directGRNFields').hide();
                $('#productSearchSection').hide();
                $('#fromPOItemsSection').show();
                $('#directGRNItemsSection').hide();

                // Clear direct GRN fields
                $('#grnSupplierSelect').val(null).trigger('change');
                clearItems();
            } else {
                // Direct GRN Mode
                $('#fromPOFields').hide();
                $('#directGRNFields').show();
                $('#productSearchSection').show();
                $('#fromPOItemsSection').hide();
                $('#directGRNItemsSection').show();

                // Clear PO fields
                $('#grnPoSelect').val(null).trigger('change');
                clearItems();
            }
        });

        // PO Selection Handler (From PO Mode)
        $('#grnPoSelect').on('change', function() {
            const poId = $(this).val();
            if (poId) {
                loadPOItems(poId);
            } else {
                clearItems();
            }
        });

        // Product Search Handler (Direct GRN Mode)
        let searchTimeout;
        $('#grnProductSearch').on('input', function() {
            const searchTerm = $(this).val();

            if (searchTerm.length < 2) {
                $('#grnSearchResults').hide().empty();
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                searchProducts(searchTerm);
            }, 300);
        });

        // Focus Product Search on F2
        $(document).on('keydown', function(e) {
            if (e.key === 'F2' && currentMode === 'direct') {
                e.preventDefault();
                $('#grnProductSearch').focus();
            }
        });

        // Load PO Items
        function loadPOItems(poId) {
            $.ajax({
                url: '{{ route('goods-receipts.get-po-items', ':id') }}'.replace(':id', poId),
                method: 'GET',
                beforeSend: function() {
                    $('#fromPOItemsBody').html(
                        '<tr><td colspan="7" class="text-center"><div class="spinner-border"></div></td></tr>'
                        );
                },
                success: function(response) {
                    if (response.success) {
                        console.log('PO Items Response:', response.items);
                        grnItems = response.items.map(item => ({
                            purchase_order_item_id: item.id,
                            product_id: item.product_id,
                            product_name: item.product_name,
                            product_sku: item.product_sku,
                            ordered_qty: item.quantity,
                            received_qty: item.received_qty || 0,
                            outstanding_qty: item.remaining_qty,
                            receiving_now: item.remaining_qty,
                            unit_cost: item.unit_price,
                            line_total: item.remaining_qty * item.unit_price
                        }));
                        console.log('GRN Items:', grnItems);
                        renderFromPOItems();
                    }
                },
                error: function() {
                    toastr.error('Failed to load PO items');
                }
            });
        }

        // Search Products
        function searchProducts(searchTerm) {
            $.ajax({
                url: '{{ route('goods-receipts.search-products') }}',
                method: 'GET',
                data: {
                    search: searchTerm
                },
                success: function(response) {
                    if (response.success && response.products.length > 0) {
                        displaySearchResults(response.products);
                    } else {
                        $('#grnSearchResults').hide().empty();
                    }
                },
                error: function() {
                    $('#grnSearchResults').hide().empty();
                }
            });
        }

        // Display Search Results
        function displaySearchResults(products) {
            const resultsDiv = $('#grnSearchResults');
            resultsDiv.empty();

            products.forEach(product => {
                const item = `
                <div class="search-result-item" data-product-id="${product.id}" 
                     data-product-name="${product.name}" 
                     data-product-sku="${product.sku}"
                     data-product-cost="${product.cost || 0}">
                    <div class="fw-semibold">${product.name}</div>
                    <small class="text-muted">SKU: ${product.sku} | Cost: R ${parseFloat(product.cost || 0).toFixed(2)}</small>
                </div>
            `;
                resultsDiv.append(item);
            });

            resultsDiv.show();
        }

        // Add Product from Search Results
        $(document).on('click', '.search-result-item', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productSku = $(this).data('product-sku');
            const productCost = $(this).data('product-cost');

            // Check if product already exists
            const exists = grnItems.find(item => item.product_id == productId);
            if (exists) {
                toastr.warning('Product already added');
                return;
            }

            // Add product
            grnItems.push({
                purchase_order_item_id: null,
                product_id: productId,
                product_name: productName,
                product_sku: productSku,
                ordered_qty: 0,
                received_qty: 0,
                outstanding_qty: 0,
                receiving_now: 1,
                unit_cost: productCost,
                line_total: 1 * productCost
            });

            renderDirectGRNItems();
            $('#grnProductSearch').val('');
            $('#grnSearchResults').hide().empty();
        });

        // Render From PO Items
        function renderFromPOItems() {
            const tbody = $('#fromPOItemsBody');
            tbody.empty();

            if (grnItems.length === 0) {
                tbody.append(
                '<tr><td colspan="7" class="text-center text-muted">No items to receive</td></tr>');
            } else {
                grnItems.forEach((item, index) => {
                    const row = `
                    <tr data-index="${index}">
                        <td>
                            <div class="fw-semibold">${item.product_name}</div>
                            <small class="text-muted">SKU: ${item.product_sku}</small>
                            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${index}][purchase_order_item_id]" value="${item.purchase_order_item_id}">
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">${item.ordered_qty}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-transparent text-dark">${item.received_qty}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning-transparent text-dark">${item.outstanding_qty}</span>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm receiving-now" 
                                   value="${item.receiving_now}" min="0" max="${item.outstanding_qty}" data-index="${index}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm unit-cost" 
                                   value="${item.unit_cost}" min="0" step="0.01" data-index="${index}">
                                </td>
                        <td class="text-end fw-semibold line-total">R ${parseFloat(item.line_total).toFixed(2)}</td>
                    </tr>
                `;
                    tbody.append(row);
                });
            }

            updateTotals();
        }

        // Render Direct GRN Items
        function renderDirectGRNItems() {
            const tbody = $('#directGRNItemsBody');
            tbody.empty();

            if (grnItems.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted">No items added</td></tr>');
            } else {
                grnItems.forEach((item, index) => {
                    const row = `
                    <tr data-index="${index}">
                        <td class="text-center">${index + 1}</td>
                        <td>
                            <div class="fw-semibold">${item.product_name}</div>
                            <small class="text-muted">SKU: ${item.product_sku}</small>
                            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="items[${index}][purchase_order_item_id]" value="${item.purchase_order_item_id || ''}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm received-qty" 
                                   value="${item.receiving_now}" min="0" data-index="${index}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm unit-cost" 
                                   value="${item.unit_cost}" min="0" step="0.01" data-index="${index}">
                        </td>
                        <td class="text-end fw-semibold line-total">R ${parseFloat(item.line_total).toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                `;
                    tbody.append(row);
                });
            }

            updateTotals();
            updateDirectGRNItemCount();
        }

        // Update Totals
        function updateTotals() {
            let total = 0;
            grnItems.forEach(item => {
                total += item.line_total;
            });
            $('#grnTotalAmountDisplay').text('R ' + total.toFixed(2));
        }

        // Update Direct GRN Item Count
        function updateDirectGRNItemCount() {
            $('#directGRNItemCount').text(grnItems.length + ' items');
        }

        // Clear Items
        function clearItems() {
            grnItems = [];
            renderFromPOItems();
            renderDirectGRNItems();
        }

        // Handle Receiving Now Qty Change (From PO Mode)
        $(document).on('input', '.receiving-now', function() {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;
            grnItems[index].receiving_now = qty;
            grnItems[index].line_total = qty * grnItems[index].unit_cost;
            $(this).closest('tr').find('.line-total').text('R ' + grnItems[index].line_total.toFixed(
            2));
            updateTotals();
        });

        // Handle Received Qty Change (Direct GRN Mode)
        $(document).on('input', '.received-qty', function() {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;
            grnItems[index].receiving_now = qty;
            grnItems[index].line_total = qty * grnItems[index].unit_cost;
            $(this).closest('tr').find('.line-total').text('R ' + grnItems[index].line_total.toFixed(
            2));
            updateTotals();
        });

        // Handle Unit Cost Change
        $(document).on('input', '.unit-cost', function() {
            const index = $(this).data('index');
            const cost = parseFloat($(this).val()) || 0;
            grnItems[index].unit_cost = cost;
            grnItems[index].line_total = grnItems[index].receiving_now * cost;
            $(this).closest('tr').find('.line-total').text('R ' + grnItems[index].line_total.toFixed(
            2));
            updateTotals();
        });

        // Remove Item
        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            grnItems.splice(index, 1);
            renderDirectGRNItems();
        });

        // Form Submit
        $('#grnCreateForm').on('submit', function(e) {
            e.preventDefault();

            if (grnItems.length === 0) {
                toastr.error('Please add at least one item');
                return;
            }

            let formData;

            if (currentMode === 'from_po') {
                // From PO Mode
                const poId = $('#grnPoSelect').val();
                if (!poId) {
                    toastr.error('Please select a Purchase Order');
                    return;
                }

                formData = {
                    purchase_order_id: poId,
                    received_date: $('#grnReceivedDate').val(),
                    notes: $('textarea[name="notes"]').val(),
                    items: grnItems.map(item => ({
                        product_id: item.product_id,
                        purchase_order_item_id: item.purchase_order_item_id,
                        ordered_qty: item.ordered_qty,
                        received_qty: item.receiving_now,
                        unit_cost: item.unit_cost,
                        line_total: item.line_total
                    }))
                };

                console.log('Form Data (From PO):', formData);
            } else {
                // Direct GRN Mode
                const supplierId = $('#grnSupplierSelect').val();
                console.log('Supplier ID:', supplierId);

                if (!supplierId) {
                    toastr.error('Please select a supplier');
                    return;
                }

                formData = {
                    supplier_id: supplierId,
                    received_date: $('#grnReceivedDate').val(),
                    notes: $('textarea[name="notes"]').val(),
                    items: grnItems.map(item => ({
                        product_id: item.product_id,
                        purchase_order_item_id: null,
                        ordered_qty: 0,
                        received_qty: item.receiving_now,
                        unit_cost: item.unit_cost,
                        line_total: item.line_total
                    }))
                };

                console.log('Form Data:', formData);
            }

            $.ajax({
                url: '{{ route('goods-receipts.store') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#saveGrnBtn').prop('disabled', true).html(
                        '<i class="ri-loader-4-line ri-spin me-1"></i> Creating...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#createGoodsReceiptModal').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Failed to create GRN');
                    }
                },
                complete: function() {
                    $('#saveGrnBtn').prop('disabled', false).html(
                        '<i class="ri-save-line me-1"></i> Create GRN');
                }
            });
        });

        // Reset form when modal is closed
        $('#createGoodsReceiptModal').on('hidden.bs.modal', function() {
            $('#grnCreateForm')[0].reset();
            $('input[name="grn_mode"][value="from_po"]').prop('checked', true);
            currentMode = 'from_po';
            $('#fromPOFields').show();
            $('#directGRNFields').hide();
            $('#productSearchSection').hide();
            $('#fromPOItemsSection').show();
            $('#directGRNItemsSection').hide();
            clearItems();

            // Reset Select2 dropdowns
            $('#grnSupplierSelect').val(null).trigger('change');
            $('#grnPoSelect').val(null).trigger('change');
        });
    });
</script>

<style>
    .search-results-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        margin-top: 2px;
    }

    .search-result-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .search-result-item:hover {
        background-color: #f8f9fa;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }
</style>
