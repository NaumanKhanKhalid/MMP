<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-file-earmark-text me-2"></i> Quote #{{ $quote->quote_number }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <ul class="nav nav-tabs mb-3" id="viewQuoteTabs-{{ $quote->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $quote->id }}" data-bs-toggle="tab"
                data-bs-target="#view-basic-{{ $quote->id }}" type="button" role="tab"
                aria-controls="view-basic-{{ $quote->id }}" aria-selected="true">Basic Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vehicle-tab-{{ $quote->id }}" data-bs-toggle="tab"
                data-bs-target="#view-vehicle-{{ $quote->id }}" type="button" role="tab"
                aria-controls="view-vehicle-{{ $quote->id }}" aria-selected="false">Vehicle</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="items-tab-{{ $quote->id }}" data-bs-toggle="tab"
                data-bs-target="#view-items-{{ $quote->id }}" type="button" role="tab"
                aria-controls="view-items-{{ $quote->id }}" aria-selected="false">Items</button>
        </li>
    </ul>
    <div class="tab-content" id="viewQuoteTabContent-{{ $quote->id }}">
        <div class="tab-pane fade show active" id="view-basic-{{ $quote->id }}" role="tabpanel"
            aria-labelledby="basic-tab-{{ $quote->id }}" tabindex="0">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Customer</label>
                    <p class="form-control-static text-muted">{{ $quote->customer->name ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <span class="badge bg-info">{{ ucfirst($quote->status) }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Valid Until</label>
                    <p class="form-control-static text-muted">{{ $quote->valid_until }}</p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Notes</label>
                    <p class="form-control-static text-muted">{{ $quote->notes }}</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="view-vehicle-{{ $quote->id }}" role="tabpanel"
            aria-labelledby="vehicle-tab-{{ $quote->id }}" tabindex="0">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Make</label>
                    <p class="form-control-static text-muted">{{ $quote->vehicle_make }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Model</label>
                    <p class="form-control-static text-muted">{{ $quote->vehicle_model }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">VIN</label>
                    <p class="form-control-static text-muted">{{ $quote->vehicle_vin }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Reg</label>
                    <p class="form-control-static text-muted">{{ $quote->vehicle_reg }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mileage</label>
                    <p class="form-control-static text-muted">{{ $quote->vehicle_mileage }}</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="view-items-{{ $quote->id }}" role="tabpanel"
            aria-labelledby="items-tab-{{ $quote->id }}" tabindex="0">
            <h6>Items</h6>
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
                    @foreach ($quote->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_id }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Totals Block Preview -->
            <div class="row mt-3">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Subtotal</label>
                    <input type="number" class="form-control" value="{{ $quote->items->sum('total') }}" readonly>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Discount</label>
                    <input type="number" class="form-control" value="{{ $quote->total_discount ?? 0 }}" readonly>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Shipping</label>
                    <input type="number" class="form-control" value="{{ $quote->shipping ?? 0 }}" readonly>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">VAT</label>
                    <input type="number" class="form-control" value="{{ $quote->vat ?? 0 }}" readonly>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label fw-bold">Grand Total</label>
                    <input type="number" class="form-control fw-bold"
                        value="{{ $quote->grand_total ?? $quote->items->sum('total') }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('quotes.print', $quote) }}" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer"></i> Print
    </a>
</div>
