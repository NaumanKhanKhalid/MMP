<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-pencil me-2"></i>Edit Invoice {{ $invoice->invoice_number }}
        <span class="badge ms-2 bg-warning">Draft</span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceEditForm">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="editInvoiceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="customer-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-customer" type="button" role="tab">
                    Customer & Vehicle
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="items-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-items" type="button" role="tab">
                    Items
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="totals-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-totals" type="button" role="tab">
                    Totals & Payment
                </button>
            </li>
        </ul>

        <div class="tab-content" id="editInvoiceTabContent">
            <!-- Customer & Vehicle Tab -->
            <div class="tab-pane fade show active" id="edit-customer" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customerSelectEdit" class="form-control" onchange="toggleCustomerFieldsEdit()">
                            <option value="">Select Customer (or leave for cash sale)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" 
                                        data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}" 
                                        data-address="{{ $customer->address }}"
                                        {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash" {{ $invoice->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ $invoice->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="eft" {{ $invoice->payment_method == 'eft' ? 'selected' : '' }}>EFT</option>
                            <option value="credit" {{ $invoice->payment_method == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                </div>

                <div class="row" id="cashSaleFieldsEdit">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="{{ $invoice->customer_name }}" placeholder="For cash sales">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="customer_phone" class="form-control" value="{{ $invoice->customer_phone }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="customer_email" class="form-control" value="{{ $invoice->customer_email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="customer_address" class="form-control" value="{{ $invoice->customer_address }}">
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3">Vehicle Details (Optional)</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Make</label>
                        <input type="text" name="vehicle_make" class="form-control" value="{{ $invoice->vehicle_make }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="vehicle_model" class="form-control" value="{{ $invoice->vehicle_model }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">VIN</label>
                        <input type="text" name="vehicle_vin" class="form-control" value="{{ $invoice->vehicle_vin }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control" value="{{ $invoice->vehicle_reg }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control" value="{{ $invoice->vehicle_mileage }}">
                    </div>
                </div>
            </div>

            <!-- Items Tab -->
            <div class="tab-pane fade" id="edit-items" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Invoice Items</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addInvoiceItemEdit()">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm" id="invoiceItemsTableEdit">
                        <thead>
                            <tr>
                                <th width="30%">Product</th>
                                <th width="15%">Qty</th>
                                <th width="15%">Unit Price</th>
                                <th width="10%">Disc %</th>
                                <th width="10%">Disc Amt</th>
                                <th width="15%">Total</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBodyEdit">
                            <!-- Existing items will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Reference/PO Number</label>
                        <input type="text" name="reference" class="form-control" value="{{ $invoice->reference }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $invoice->notes }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Totals & Payment Tab -->
            <div class="tab-pane fade" id="edit-totals" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Invoice Totals</h6>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label class="form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control" id="subtotalEdit" readonly value="{{ $invoice->subtotal }}" step="0.01">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Shipping</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" name="shipping" class="form-control" value="{{ $invoice->shipping }}" step="0.01" onchange="calculateTotalsEdit()">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vat_enabled" id="vatEnabledEdit" onchange="calculateTotalsEdit()" {{ $invoice->vat_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vatEnabledEdit">Enable VAT</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vat_inclusive" id="vatInclusiveEdit" onchange="calculateTotalsEdit()" {{ $invoice->vat_inclusive ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vatInclusiveEdit">VAT Inclusive</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2" id="vatFieldsEdit" style="display: {{ $invoice->vat_enabled ? 'block' : 'none' }};">
                            <div class="col-6">
                                <label class="form-label">VAT Rate (%)</label>
                                <input type="number" name="vat_rate" class="form-control" value="{{ $invoice->vat_rate }}" step="0.01" onchange="calculateTotalsEdit()">
                            </div>
                            <div class="col-6">
                                <label class="form-label">VAT Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control" id="vatAmountEdit" readonly value="{{ $invoice->vat_amount }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fw-bold">Grand Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control fw-bold" id="grandTotalEdit" readonly value="{{ $invoice->grand_total }}" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Payment Summary</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Payment Method:</strong> <span id="paymentMethodDisplayEdit">{{ ucfirst($invoice->payment_method) }}</span>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> This invoice is currently in draft status and can be edited.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Invoice</button>
    </div>
</form>

<script>
let itemCounterEdit = {{ $invoice->items->count() }};

// Load existing items
document.addEventListener('DOMContentLoaded', function() {
    loadExistingItems();
    toggleCustomerFieldsEdit();
    calculateTotalsEdit();
});

// Load existing invoice items
function loadExistingItems() {
    const tbody = document.getElementById('invoiceItemsBodyEdit');
    tbody.innerHTML = '';
    
    @foreach($invoice->items as $index => $item)
        addInvoiceItemEdit({{ $item->product_id }}, {{ $item->quantity }}, {{ $item->unit_price }}, {{ $item->discount_percentage }}, {{ $item->discount_amount }});
    @endforeach
}

// Toggle customer fields based on selection
function toggleCustomerFieldsEdit() {
    const customerSelect = document.getElementById('customerSelectEdit');
    const cashSaleFields = document.getElementById('cashSaleFieldsEdit');
    
    if (customerSelect.value === '') {
        cashSaleFields.style.display = 'block';
        document.querySelector('input[name="customer_name"]').required = true;
    } else {
        cashSaleFields.style.display = 'none';
        document.querySelector('input[name="customer_name"]').required = false;
        
        // Auto-fill customer details
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        if (selectedOption.value) {
            document.querySelector('input[name="customer_name"]').value = selectedOption.dataset.name || '';
            document.querySelector('input[name="customer_email"]').value = selectedOption.dataset.email || '';
            document.querySelector('input[name="customer_phone"]').value = selectedOption.dataset.phone || '';
            document.querySelector('input[name="customer_address"]').value = selectedOption.dataset.address || '';
        }
    }
}

// Add invoice item (edit mode)
function addInvoiceItemEdit(productId = null, quantity = 1, unitPrice = 0, discountPercentage = 0, discountAmount = 0) {
    const tbody = document.getElementById('invoiceItemsBodyEdit');
    const row = document.createElement('tr');
    row.id = `item_edit_${itemCounterEdit}`;
    
    let productOptions = '<option value="">Select Product</option>';
    @foreach($products as $product)
        productOptions += `<option value="{{ $product->id }}" data-sku="{{ $product->sku }}" data-name="{{ $product->name }}" data-price="{{ $product->price_normal }}" ${productId == {{ $product->id }} ? 'selected' : ''}>{{ $product->name }} ({{ $product->sku }})</option>`;
    @endforeach
    
    row.innerHTML = `
        <td>
            <select name="items[${itemCounterEdit}][product_id]" class="form-control form-control-sm" required onchange="updateProductDetailsEdit(${itemCounterEdit})">
                ${productOptions}
            </select>
            <input type="hidden" name="items[${itemCounterEdit}][product_sku]" class="product-sku">
            <input type="hidden" name="items[${itemCounterEdit}][product_name]" class="product-name">
        </td>
        <td>
            <input type="number" name="items[${itemCounterEdit}][quantity]" class="form-control form-control-sm" 
                   value="${quantity}" min="0.001" step="0.001" required onchange="calculateLineTotalEdit(${itemCounterEdit})">
        </td>
        <td>
            <input type="number" name="items[${itemCounterEdit}][unit_price]" class="form-control form-control-sm" 
                   value="${unitPrice.toFixed(2)}" min="0" step="0.01" required onchange="calculateLineTotalEdit(${itemCounterEdit})">
        </td>
        <td>
            <input type="number" name="items[${itemCounterEdit}][discount_percentage]" class="form-control form-control-sm" 
                   value="${discountPercentage}" min="0" max="100" step="0.01" onchange="calculateLineTotalEdit(${itemCounterEdit})">
        </td>
        <td>
            <input type="number" name="items[${itemCounterEdit}][discount_amount]" class="form-control form-control-sm" 
                   value="${discountAmount.toFixed(2)}" min="0" step="0.01" onchange="calculateLineTotalEdit(${itemCounterEdit})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm line-total-edit" readonly value="0.00">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeInvoiceItemEdit(${itemCounterEdit})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Set product details if productId is provided
    if (productId) {
        const select = row.querySelector('select[name*="product_id"]');
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption.value) {
            const priceInput = row.querySelector('input[name*="unit_price"]');
            const skuInput = row.querySelector('.product-sku');
            const nameInput = row.querySelector('.product-name');
            
            priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
            skuInput.value = selectedOption.dataset.sku;
            nameInput.value = selectedOption.dataset.name;
        }
    }
    
    calculateLineTotalEdit(itemCounterEdit);
    itemCounterEdit++;
}

// Remove invoice item
function removeInvoiceItemEdit(counter) {
    document.getElementById(`item_edit_${counter}`).remove();
    calculateTotalsEdit();
}

// Update product details when selected
function updateProductDetailsEdit(counter) {
    const select = document.querySelector(`#item_edit_${counter} select[name*="product_id"]`);
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const priceInput = document.querySelector(`#item_edit_${counter} input[name*="unit_price"]`);
        const skuInput = document.querySelector(`#item_edit_${counter} .product-sku`);
        const nameInput = document.querySelector(`#item_edit_${counter} .product-name`);
        
        priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
        skuInput.value = selectedOption.dataset.sku;
        nameInput.value = selectedOption.dataset.name;
        
        calculateLineTotalEdit(counter);
    }
}

// Calculate line total
function calculateLineTotalEdit(counter) {
    const row = document.getElementById(`item_edit_${counter}`);
    if (!row) return;
    
    const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
    const unitPrice = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
    const discountPercentage = parseFloat(row.querySelector('input[name*="discount_percentage"]').value) || 0;
    const discountAmount = parseFloat(row.querySelector('input[name*="discount_amount"]').value) || 0;
    
    const subtotal = quantity * unitPrice;
    let finalDiscount = discountAmount;
    
    if (discountPercentage > 0) {
        finalDiscount = subtotal * (discountPercentage / 100);
        row.querySelector('input[name*="discount_amount"]').value = finalDiscount.toFixed(2);
    }
    
    const lineTotal = subtotal - finalDiscount;
    row.querySelector('.line-total-edit').value = lineTotal.toFixed(2);
    
    calculateTotalsEdit();
}

// Calculate invoice totals
function calculateTotalsEdit() {
    let subtotal = 0;
    
    // Sum all line totals
    document.querySelectorAll('.line-total-edit').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const shipping = parseFloat(document.querySelector('input[name="shipping"]').value) || 0;
    const vatEnabled = document.getElementById('vatEnabledEdit').checked;
    const vatInclusive = document.getElementById('vatInclusiveEdit').checked;
    const vatRate = parseFloat(document.querySelector('input[name="vat_rate"]').value) || 15;
    
    document.getElementById('subtotalEdit').value = subtotal.toFixed(2);
    
    let grandTotal = subtotal + shipping;
    let vatAmount = 0;
    
    if (vatEnabled) {
        document.getElementById('vatFieldsEdit').style.display = 'block';
        
        if (vatInclusive) {
            vatAmount = grandTotal - (grandTotal / (1 + vatRate / 100));
        } else {
            vatAmount = grandTotal * (vatRate / 100);
            grandTotal += vatAmount;
        }
        
        document.getElementById('vatAmountEdit').value = vatAmount.toFixed(2);
    } else {
        document.getElementById('vatFieldsEdit').style.display = 'none';
        document.getElementById('vatAmountEdit').value = '0.00';
    }
    
    document.getElementById('grandTotalEdit').value = grandTotal.toFixed(2);
}

// Update payment method display
document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
    const display = document.getElementById('paymentMethodDisplayEdit');
    const method = this.value;
    const icons = {
        'cash': '💵',
        'card': '💳',
        'eft': '🏦',
        'credit': '👤'
    };
    display.textContent = `${icons[method]} ${method.charAt(0).toUpperCase() + method.slice(1)}`;
});
</script>
