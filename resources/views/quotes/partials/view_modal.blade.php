<div class="modal-header bg-info-transparent">
    <h5 class="modal-title">
        <i class="ri-file-text-line me-2"></i> Quote #{{ $quote->quote_number }}
        @if($quote->status === 'draft')
            <span class="badge bg-warning-transparent ms-2">Draft</span>
        @elseif($quote->status === 'sent')
            <span class="badge bg-info-transparent ms-2">Sent</span>
        @elseif($quote->status === 'accepted')
            <span class="badge bg-success-transparent ms-2">Accepted</span>
        @elseif($quote->status === 'declined')
            <span class="badge bg-danger-transparent ms-2">Declined</span>
        @elseif($quote->status === 'expired')
            <span class="badge bg-secondary-transparent ms-2">Expired</span>
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
    
    <!-- Customer & Quote Info Side by Side -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-primary-transparent py-2">
                    <h6 class="mb-0"><i class="ri-user-line me-1"></i>Customer Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="120">Name:</td>
                            <td>{{ $quote->customer->name ?? 'Cash Sale' }}</td>
                        </tr>
                        @if($quote->customer)
                        <tr>
                            <td class="fw-semibold">Email:</td>
                            <td>{{ $quote->customer->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Phone:</td>
                            <td>{{ $quote->customer->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Address:</td>
                            <td>{{ $quote->customer->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Price Tier:</td>
                            <td>
                                <span class="badge bg-info-transparent">{{ ucfirst($quote->customer->price_tier ?? 'normal') }}</span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
                </div>
        
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-file-info-line me-1"></i>Quote Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="120">Quote Number:</td>
                            <td><span class="badge bg-primary">{{ $quote->quote_number }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Created Date:</td>
                            <td>{{ $quote->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Valid Until:</td>
                            <td>
                                @if($quote->valid_until)
                                    @php
                                        $validDate = \Carbon\Carbon::parse($quote->valid_until);
                                        $isExpired = $validDate->isPast();
                                    @endphp
                                    {{ $validDate->format('d M Y') }}
                                    @if($isExpired)
                                        <span class="badge bg-danger-transparent ms-1">Expired</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Created By:</td>
                            <td>{{ $quote->user->name ?? 'System' }}</td>
                        </tr>
                    </table>
                </div>
                </div>
            </div>
        </div>

    <!-- Vehicle Information (if provided) -->
    @if($quote->vehicleMake || $quote->vehicleModel || $quote->vehicleEngine || $quote->vehicle_vin || $quote->vehicle_reg)
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-warning-transparent py-2">
            <h6 class="mb-0"><i class="ri-car-line me-1"></i>Vehicle Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <small class="text-muted">Make</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicleMake->name ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Model</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicleModel->name ?? '-' }}</p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Engine</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicleEngine->code ?? '-' }}</p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Year</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicle_year ?? '-' }}</p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Registration</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicle_reg ?? '-' }}</p>
                </div>
                </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <small class="text-muted">Mileage</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicle_mileage ? number_format($quote->vehicle_mileage) . ' km' : '-' }}</p>
                </div>
                @if($quote->vehicle_vin)
                <div class="col-md-6">
                    <small class="text-muted">VIN Number</small>
                    <p class="mb-0 fw-semibold">{{ $quote->vehicle_vin }}</p>
                </div>
                @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Quote Items -->
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-secondary-transparent py-2">
            <h6 class="mb-0"><i class="ri-shopping-cart-line me-1"></i>Quote Items ({{ $quote->items->count() }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Product</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="15%" class="text-end">Unit Price</th>
                            <th width="15%" class="text-end">Discount</th>
                            <th width="15%" class="text-end">Total</th>
                            <th width="10%" class="text-center">Stock</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($quote->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->name ?? 'Product #' . $item->product_id }}</strong>
                                    @if($item->product)
                                        <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">R {{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">
                                    @if($item->discount > 0)
                                        <span class="text-danger">R {{ number_format($item->discount, 2) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end fw-bold">R {{ number_format($item->total, 2) }}</td>
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
                                    @else
                                        <span class="badge bg-secondary-transparent">N/A</span>
                                    @endif
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                </div>
                </div>
                </div>

    <!-- Totals Summary -->
    <div class="card border shadow-sm">
        <div class="card-header bg-primary-transparent py-2">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="mb-0"><i class="ri-calculator-line me-1"></i>Quote Summary</h6>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="mb-0 text-success">Grand Total: R {{ number_format($quote->grand_total ?? $quote->items->sum('total'), 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-semibold">Subtotal:</td>
                            <td class="text-end">R {{ number_format($quote->items->sum('total'), 2) }}</td>
                        </tr>
                        @if($quote->total_discount && $quote->total_discount > 0)
                        <tr>
                            <td class="fw-semibold">Overall Discount:</td>
                            <td class="text-end text-danger">- R {{ number_format($quote->total_discount, 2) }}</td>
                        </tr>
                        @endif
                        @if($quote->shipping && $quote->shipping > 0)
                        <tr>
                            <td class="fw-semibold">Shipping:</td>
                            <td class="text-end">R {{ number_format($quote->shipping, 2) }}</td>
                        </tr>
                        @endif
                        @if($quote->vat && $quote->vat > 0)
                        <tr>
                            <td class="fw-semibold">VAT (15%):</td>
                            <td class="text-end">R {{ number_format($quote->vat, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold fs-5">GRAND TOTAL:</td>
                            <td class="text-end fw-bold fs-5 text-success">R {{ number_format($quote->grand_total ?? $quote->items->sum('total'), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($quote->notes)
            <div class="alert alert-info mt-3 mb-0">
                <strong><i class="ri-file-text-line me-1"></i>Notes:</strong><br>
                {{ $quote->notes }}
            </div>
            @endif
    </div>
</div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
        <i class="ri-close-line"></i> Close
    </button>
    <button type="button" class="btn btn-primary btn-sm" onclick="printQuote({{ $quote->id }})">
        <i class="ri-printer-line"></i> Print
    </button>
    <button type="button" class="btn btn-success btn-sm openEditQuoteModal" data-id="{{ $quote->id }}">
        <i class="ri-pencil-line"></i> Edit
    </button>
</div>
