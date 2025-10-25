<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-receipt me-2"></i>New Invoice
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('invoices.store') }}" method="POST" id="invoiceCreateForm">
    @csrf
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="createInvoiceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="customer-tab-create" data-bs-toggle="tab" data-bs-target="#create-customer" type="button" role="tab">
                    Customer & Vehicle
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="items-tab-create" data-bs-toggle="tab" data-bs-target="#create-items" type="button" role="tab">
                    Items
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="totals-tab-create" data-bs-toggle="tab" data-bs-target="#create-totals" type="button" role="tab">
                    Totals & Payment
                </button>
            </li>
        </ul>

        <div class="tab-content" id="createInvoiceTabContent">
            <!-- Customer & Vehicle Tab -->
            <div class="tab-pane fade show active" id="create-customer" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customerSelect" class="form-control" onchange="toggleCustomerFields()">
                            <option value="">Select Customer (or leave for cash sale)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" 
                                        data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}" 
                                        data-address="{{ $customer->address }}">
                                    {{ $customer->name }} ({{ $customer->phone ?? 'No phone' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="eft">EFT</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                </div>

                <div class="row" id="cashSaleFields">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" placeholder="For cash sales">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="customer_phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="customer_email" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="customer_address" class="form-control">
                    </div>
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3">Vehicle Details (Optional)</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Make</label>
                        <input type="text" name="vehicle_make" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" name="vehicle_model" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">VIN</label>
                        <input type="text" name="vehicle_vin" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Items Tab -->
            <div class="tab-pane fade" id="create-items" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Invoice Items</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addInvoiceItem()">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm" id="invoiceItemsTable">
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
                        <tbody id="invoiceItemsBody">
                            <!-- Items will be added here -->
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Reference/PO Number</label>
                        <input type="text" name="reference" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Totals & Payment Tab -->
            <div class="tab-pane fade" id="create-totals" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Invoice Totals</h6>
                        <div class="row mb-2">
                            <div class="col-6">
                                <label class="form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control" id="subtotal" readonly value="0.00" step="0.01">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Shipping</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" name="shipping" class="form-control" value="0.00" step="0.01" onchange="calculateTotals()">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vat_enabled" id="vatEnabled" onchange="calculateTotals()">
                                    <label class="form-check-label" for="vatEnabled">Enable VAT</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="vat_inclusive" id="vatInclusive" onchange="calculateTotals()">
                                    <label class="form-check-label" for="vatInclusive">VAT Inclusive</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2" id="vatFields" style="display: none;">
                            <div class="col-6">
                                <label class="form-label">VAT Rate (%)</label>
                                <input type="number" name="vat_rate" class="form-control" value="15.00" step="0.01" onchange="calculateTotals()">
                            </div>
                            <div class="col-6">
                                <label class="form-label">VAT Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control" id="vatAmount" readonly value="0.00" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fw-bold">Grand Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">R</span>
                                    <input type="number" class="form-control fw-bold" id="grandTotal" readonly value="0.00" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Payment Summary</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Payment Method:</strong> <span id="paymentMethodDisplay">Cash</span>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="saveAsDraft" name="save_as_draft" checked>
                            <label class="form-check-label" for="saveAsDraft">
                                Save as Draft (don't post to stock)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning" onclick="saveAsDraft()">Save as Draft</button>
        <button type="button" class="btn btn-success" onclick="saveAndPost()">Save & Post</button>
    </div>
</form>

<script>
let itemCounter = 0;

// Toggle customer fields based on selection
function toggleCustomerFields() {
    const customerSelect = document.getElementById('customerSelect');
    const cashSaleFields = document.getElementById('cashSaleFields');
    
    if (customerSelect.value === '') {
        cashSaleFields.style.display = 'block';
        document.querySelector('input[name="customer_name"]').required = true;
    } else {
        cashSaleFields.style.display = 'none';
        document.querySelector('input[name="customer_name"]').required = false;
        
        // Auto-fill customer details
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        document.querySelector('input[name="customer_name"]').value = selectedOption.dataset.name || '';
        document.querySelector('input[name="customer_email"]').value = selectedOption.dataset.email || '';
        document.querySelector('input[name="customer_phone"]').value = selectedOption.dataset.phone || '';
        document.querySelector('input[name="customer_address"]').value = selectedOption.dataset.address || '';
    }
}

// Add invoice item
function addInvoiceItem() {
    const tbody = document.getElementById('invoiceItemsBody');
    const row = document.createElement('tr');
    row.id = `item_${itemCounter}`;
    
    row.innerHTML = `
        <td>
            <select name="items[${itemCounter}][product_id]" class="form-control form-control-sm" required onchange="updateProductDetails(${itemCounter})">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-sku="{{ $product->sku }}" data-name="{{ $product->name }}" 
                            data-price="{{ $product->price_normal }}" data-stock="{{ $product->on_hand }}">
                        {{ $product->name }} ({{ $product->sku }})
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="items[${itemCounter}][product_sku]" class="product-sku">
            <input type="hidden" name="items[${itemCounter}][product_name]" class="product-name">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][quantity]" class="form-control form-control-sm" 
                   value="1" min="0.001" step="0.001" required onchange="calculateLineTotal(${itemCounter})">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][unit_price]" class="form-control form-control-sm" 
                   value="0.00" min="0" step="0.01" required onchange="calculateLineTotal(${itemCounter})">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][discount_percentage]" class="form-control form-control-sm" 
                   value="0" min="0" max="100" step="0.01" onchange="calculateLineTotal(${itemCounter})">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][discount_amount]" class="form-control form-control-sm" 
                   value="0.00" min="0" step="0.01" onchange="calculateLineTotal(${itemCounter})">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm line-total" readonly value="0.00">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeInvoiceItem(${itemCounter})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    itemCounter++;
}

// Remove invoice item
function removeInvoiceItem(counter) {
    document.getElementById(`item_${counter}`).remove();
    calculateTotals();
}

// Update product details when selected
function updateProductDetails(counter) {
    const select = document.querySelector(`#item_${counter} select[name*="product_id"]`);
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const priceInput = document.querySelector(`#item_${counter} input[name*="unit_price"]`);
        const skuInput = document.querySelector(`#item_${counter} .product-sku`);
        const nameInput = document.querySelector(`#item_${counter} .product-name`);
        
        priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
        skuInput.value = selectedOption.dataset.sku;
        nameInput.value = selectedOption.dataset.name;
        
        calculateLineTotal(counter);
    }
}

// Calculate line total
function calculateLineTotal(counter) {
    const row = document.getElementById(`item_${counter}`);
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
    row.querySelector('.line-total').value = lineTotal.toFixed(2);
    
    calculateTotals();
}

// Calculate invoice totals
function calculateTotals() {
    let subtotal = 0;
    
    // Sum all line totals
    document.querySelectorAll('.line-total').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const shipping = parseFloat(document.querySelector('input[name="shipping"]').value) || 0;
    const vatEnabled = document.getElementById('vatEnabled').checked;
    const vatInclusive = document.getElementById('vatInclusive').checked;
    const vatRate = parseFloat(document.querySelector('input[name="vat_rate"]').value) || 15;
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    
    let grandTotal = subtotal + shipping;
    let vatAmount = 0;
    
    if (vatEnabled) {
        document.getElementById('vatFields').style.display = 'block';
        
        if (vatInclusive) {
            vatAmount = grandTotal - (grandTotal / (1 + vatRate / 100));
        } else {
            vatAmount = grandTotal * (vatRate / 100);
            grandTotal += vatAmount;
        }
        
        document.getElementById('vatAmount').value = vatAmount.toFixed(2);
    } else {
        document.getElementById('vatFields').style.display = 'none';
        document.getElementById('vatAmount').value = '0.00';
    }
    
    document.getElementById('grandTotal').value = grandTotal.toFixed(2);
}

// Update payment method display
document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
    const display = document.getElementById('paymentMethodDisplay');
    const method = this.value;
    const icons = {
        'cash': '💵',
        'card': '💳',
        'eft': '🏦',
        'credit': '👤'
    };
    display.textContent = `${icons[method]} ${method.charAt(0).toUpperCase() + method.slice(1)}`;
});

// Save as draft
function saveAsDraft() {
    document.getElementById('saveAsDraft').checked = true;
    document.getElementById('invoiceCreateForm').submit();
}

// Save and post
function saveAndPost() {
    document.getElementById('saveAsDraft').checked = false;
    document.getElementById('invoiceCreateForm').submit();
}

// Initialize with one item
document.addEventListener('DOMContentLoaded', function() {
    addInvoiceItem();
    toggleCustomerFields();
});
</script>
