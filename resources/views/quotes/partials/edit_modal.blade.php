<form action="{{ route('quotes.update', $quote) }}" method="POST" id="quoteEditForm">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Quote #{{ $quote->quote_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" id="editQuoteTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-basic" type="button" role="tab" aria-controls="edit-basic" aria-selected="true">Basic Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vehicle-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-vehicle" type="button" role="tab" aria-controls="edit-vehicle" aria-selected="false">Vehicle</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="items-tab-edit" data-bs-toggle="tab" data-bs-target="#edit-items" type="button" role="tab" aria-controls="edit-items" aria-selected="false">Items</button>
            </li>
        </ul>
        <div class="tab-content" id="editQuoteTabContent">
            <div class="tab-pane fade show active" id="edit-basic" role="tabpanel" aria-labelledby="basic-tab-edit" tabindex="0">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Customer</label>
                        <select name="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @if($quote->customer_id == $customer->id) selected @endif>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Valid Until</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ $quote->valid_until }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" @if($quote->status=='draft') selected @endif>Draft</option>
                            <option value="final" @if($quote->status=='final') selected @endif>Final</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control">{{ $quote->notes }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="edit-vehicle" role="tabpanel" aria-labelledby="vehicle-tab-edit" tabindex="0">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Make</label>
                        <input type="text" name="vehicle_make" class="form-control" value="{{ $quote->vehicle_make }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Model</label>
                        <input type="text" name="vehicle_model" class="form-control" value="{{ $quote->vehicle_model }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">VIN</label>
                        <input type="text" name="vehicle_vin" class="form-control" value="{{ $quote->vehicle_vin }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Reg</label>
                        <input type="text" name="vehicle_reg" class="form-control" value="{{ $quote->vehicle_reg }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="text" name="vehicle_mileage" class="form-control" value="{{ $quote->vehicle_mileage }}">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="edit-items" role="tabpanel" aria-labelledby="items-tab-edit" tabindex="0">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quote->items as $i => $item)
                            <tr>
                                <td>
                                    <select name="items[{{ $i }}][product_id]" class="form-control">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" @if($item->product_id == $product->id) selected @endif>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="items[{{ $i }}][description]" class="form-control" value="{{ $item->description }}"></td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1"></td>
                                <td><input type="number" name="items[{{ $i }}][unit_price]" class="form-control" value="{{ $item->unit_price }}" step="0.01"></td>
                                <td><input type="number" name="items[{{ $i }}][discount]" class="form-control" value="{{ $item->discount }}" step="0.01"></td>
                                <td><input type="number" name="items[{{ $i }}][total]" class="form-control" value="{{ $item->total }}" step="0.01"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Totals Block -->
                <div class="row mt-3">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Subtotal</label>
                        <input type="number" name="subtotal" class="form-control" step="0.01" value="{{ $quote->items->sum('total') }}" readonly>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Discount</label>
                        <input type="number" name="total_discount" class="form-control" step="0.01" value="{{ $quote->total_discount ?? 0 }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Shipping</label>
                        <input type="number" name="shipping" class="form-control" step="0.01" value="{{ $quote->shipping ?? 0 }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">VAT</label>
                        <div class="input-group">
                            <input type="checkbox" name="vat_enabled" id="vatEnabledEdit" class="form-check-input me-2" @if(!empty($quote->vat)) checked @endif>
                            <input type="number" name="vat" class="form-control" step="0.01" placeholder="VAT %" value="{{ $quote->vat ?? 0 }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label fw-bold">Grand Total</label>
                        <input type="number" name="grand_total" class="form-control fw-bold" step="0.01" value="{{ $quote->grand_total ?? $quote->items->sum('total') }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Quote</button>
    </div>
</form>
