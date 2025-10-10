<form action="{{ route('quotes.store') }}" method="POST" id="quoteCreateForm">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-file-earmark-plus me-2"></i> New Quote
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="createQuoteTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-create" data-bs-toggle="tab" data-bs-target="#create-basic" type="button" role="tab" aria-controls="create-basic" aria-selected="true">Basic Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vehicle-tab-create" data-bs-toggle="tab" data-bs-target="#create-vehicle" type="button" role="tab" aria-controls="create-vehicle" aria-selected="false">Vehicle</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="items-tab-create" data-bs-toggle="tab" data-bs-target="#create-items" type="button" role="tab" aria-controls="create-items" aria-selected="false">Items</button>
            </li>
        </ul>
        <div class="tab-content" id="createQuoteTabContent">
            <div class="tab-pane fade show active" id="create-basic" role="tabpanel" aria-labelledby="basic-tab-create" tabindex="0">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer</label>
                        <select name="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="create-vehicle" role="tabpanel" aria-labelledby="vehicle-tab-create" tabindex="0">
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
                        <label class="form-label fw-bold">Reg</label>
                        <input type="text" name="vehicle_reg" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="create-items" role="tabpanel" aria-labelledby="items-tab-create" tabindex="0">
                <label class="form-label fw-bold">Items</label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="quoteItemsBody">
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-control">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="items[0][description]" class="form-control"></td>
                            <td><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1"></td>
                            <td><input type="number" name="items[0][unit_price]" class="form-control" value="0" step="0.01"></td>
                            <td><input type="number" name="items[0][discount]" class="form-control" value="0" step="0.01"></td>
                            <td><input type="number" name="items[0][total]" class="form-control" value="0" step="0.01"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm" id="addQuoteItem">Add Item</button>
                <!-- Totals Block -->
                <div class="row mt-3">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Subtotal</label>
                        <input type="number" name="subtotal" class="form-control" step="0.01" readonly>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Discount</label>
                        <input type="number" name="total_discount" class="form-control" step="0.01">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Shipping</label>
                        <input type="number" name="shipping" class="form-control" step="0.01">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">VAT</label>
                        <div class="input-group">
                            <input type="checkbox" name="vat_enabled" id="vatEnabled" class="form-check-input me-2">
                            <input type="number" name="vat" class="form-control" step="0.01" placeholder="VAT %">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label fw-bold">Grand Total</label>
                        <input type="number" name="grand_total" class="form-control fw-bold" step="0.01" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Quote</button>
    </div>
</form>
<script>
let quoteItemIndex = 1;
$('#addQuoteItem').on('click', function() {
    let row = `<tr>
        <td><select name="items[${quoteItemIndex}][product_id]" class="form-control">@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select></td>
        <td><input type="text" name="items[${quoteItemIndex}][description]" class="form-control"></td>
        <td><input type="number" name="items[${quoteItemIndex}][quantity]" class="form-control" value="1" min="1"></td>
        <td><input type="number" name="items[${quoteItemIndex}][unit_price]" class="form-control" value="0" step="0.01"></td>
        <td><input type="number" name="items[${quoteItemIndex}][discount]" class="form-control" value="0" step="0.01"></td>
        <td><input type="number" name="items[${quoteItemIndex}][total]" class="form-control" value="0" step="0.01"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
    </tr>`;
    $('#quoteItemsBody').append(row);
    quoteItemIndex++;
});
$(document).on('click', '.remove-item', function() {
    $(this).closest('tr').remove();
});
</script>
