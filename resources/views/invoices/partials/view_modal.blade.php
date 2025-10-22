<div class="modal-header bg-success-transparent">
    <h5 class="modal-title">
        <i class="ri-file-text-line me-2"></i> Invoice #{{ $invoice->invoice_number }}
        @switch($invoice->payment_status)
            @case('draft')
                <span class="badge bg-warning-transparent ms-2">Draft</span>
                @break
            @case('posted')
                <span class="badge bg-info-transparent ms-2">Posted</span>
                @break
            @case('paid')
                <span class="badge bg-success-transparent ms-2">Paid</span>
                @break
            @case('partial')
                <span class="badge bg-warning-transparent ms-2">Partial Payment</span>
                @break
            @case('cancelled')
                <span class="badge bg-danger-transparent ms-2">Cancelled</span>
                @break
        @endswitch
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
    
    <!-- Customer & Invoice Info Side by Side -->
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
                            <td>{{ $invoice->customer->name ?? $invoice->customer_name ?? 'Cash Sale' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Email:</td>
                            <td>{{ $invoice->customer->email ?? $invoice->customer_email ?? '-' }}</td>
                </tr>
                <tr>
                            <td class="fw-semibold">Phone:</td>
                            <td>{{ $invoice->customer->phone ?? $invoice->customer_phone ?? '-' }}</td>
                </tr>
                <tr>
                            <td class="fw-semibold">Address:</td>
                            <td>{{ $invoice->customer->address ?? $invoice->customer_address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-file-info-line me-1"></i>Invoice Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="140">Invoice Number:</td>
                            <td><span class="badge bg-success">{{ $invoice->invoice_number }}</span></td>
                        </tr>
                        @if($invoice->quote_id)
                        <tr>
                            <td class="fw-semibold">From Quote:</td>
                            <td><span class="badge bg-info">{{ $invoice->quote->quote_number ?? 'QT' . $invoice->quote_id }}</span></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="fw-semibold">Invoice Date:</td>
                            <td>{{ $invoice->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                            <td class="fw-semibold">Payment Method:</td>
                    <td>
                        @switch($invoice->payment_method)
                            @case('cash')
                                        <i class="ri-cash-line me-1"></i>Cash
                                @break
                            @case('card')
                                        <i class="ri-bank-card-line me-1"></i>Card
                                @break
                            @case('eft')
                                        <i class="ri-exchange-dollar-line me-1"></i>EFT
                                @break
                            @case('on_account')
                                        <i class="ri-account-box-line me-1"></i>On Account
                                @break
                                    @default
                                        {{ ucfirst($invoice->payment_method ?? '-') }}
                        @endswitch
                    </td>
                </tr>
                        <tr>
                            <td class="fw-semibold">Created By:</td>
                            <td>{{ $invoice->user->name ?? 'System' }}</td>
                </tr>
            </table>
        </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Information (if provided) -->
    @if($invoice->vehicle_make || $invoice->vehicle_model || $invoice->vehicle_vin || $invoice->vehicle_reg)
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-warning-transparent py-2">
            <h6 class="mb-0"><i class="ri-car-line me-1"></i>Vehicle Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <small class="text-muted">Make</small>
                    <p class="mb-0 fw-semibold">{{ $invoice->vehicle_make ?? '-' }}</p>
                </div>
        <div class="col-md-3">
                    <small class="text-muted">Model</small>
                    <p class="mb-0 fw-semibold">{{ $invoice->vehicle_model ?? '-' }}</p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Registration</small>
                    <p class="mb-0 fw-semibold">{{ $invoice->vehicle_reg ?? '-' }}</p>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Mileage</small>
                    <p class="mb-0 fw-semibold">{{ $invoice->vehicle_mileage ? number_format($invoice->vehicle_mileage) . ' km' : '-' }}</p>
                </div>
        </div>
        @if($invoice->vehicle_vin)
            <div class="row mt-2">
                <div class="col-md-12">
                    <small class="text-muted">VIN Number</small>
                    <p class="mb-0 fw-semibold">{{ $invoice->vehicle_vin }}</p>
                </div>
        </div>
        @endif
        </div>
    </div>
    @endif

    <!-- Invoice Items -->
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-secondary-transparent py-2">
            <h6 class="mb-0"><i class="ri-shopping-cart-line me-1"></i>Invoice Items ({{ $invoice->items->count() }})</h6>
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
                            <th width="15%" class="text-end">Line Total</th>
                            @if(auth()->user()->canSeeCosts())
                            <th width="10%" class="text-end">Profit</th>
                            @endif
                </tr>
            </thead>
            <tbody>
                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product_name ?? $item->product->name ?? 'Product #' . $item->product_id }}</strong>
                                    @if($item->product_sku)
                                        <br><small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-end">R {{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">
                                    @if($item->discount_amount > 0)
                                        <span class="text-danger">R {{ number_format($item->discount_amount, 2) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end fw-bold">R {{ number_format($item->line_total, 2) }}</td>
                                @if(auth()->user()->canSeeCosts())
                                <td class="text-end">
                                    @php
                                        $profit = $item->line_profit ?? 0;
                                        $profitClass = $profit > 0 ? 'text-success' : 'text-danger';
                                    @endphp
                                    <span class="{{ $profitClass }}">R {{ number_format($profit, 2) }}</span>
                                </td>
                                @endif
                </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="card border shadow-sm">
        <div class="card-header bg-primary-transparent py-2">
            <div class="row align-items-center">
        <div class="col-md-6">
                    <h6 class="mb-0"><i class="ri-calculator-line me-1"></i>Invoice Summary</h6>
                </div>
                {{-- <div class="col-md-6 text-end">
                    <h5 class="mb-0 text-success">Grand Total: R {{ number_format($invoice->grand_total ?? 0, 2) }}</h5>
                </div> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-semibold">Subtotal:</td>
                            <td class="text-end">R {{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                    </tr>
                        @if($invoice->discount_amount && $invoice->discount_amount > 0)
                    <tr>
                            <td class="fw-semibold">Discount:</td>
                            <td class="text-end text-danger">- R {{ number_format($invoice->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                        @if($invoice->shipping && $invoice->shipping > 0)
                    <tr>
                            <td class="fw-semibold">Shipping:</td>
                        <td class="text-end">R {{ number_format($invoice->shipping, 2) }}</td>
                    </tr>
                    @endif
                        @if($invoice->vat_amount && $invoice->vat_amount > 0)
                    <tr>
                            <td class="fw-semibold">VAT ({{ $invoice->vat_rate ?? 15 }}%):</td>
                        <td class="text-end">R {{ number_format($invoice->vat_amount, 2) }}</td>
                    </tr>
                    @endif
                        <tr class="border-top">
                            <td class="fw-bold fs-5">GRAND TOTAL:</td>
                            <td class="text-end fw-bold fs-5 text-success">R {{ number_format($invoice->grand_total ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                            <td class="fw-semibold text-primary">Amount Paid:</td>
                            <td class="text-end text-primary">R {{ number_format($invoice->amount_paid ?? 0, 2) }}</td>
                    </tr>
                        <tr>
                            <td class="fw-semibold {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">Balance Due:</td>
                            <td class="text-end fw-bold {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                                R {{ number_format($invoice->balance_due ?? 0, 2) }}
                            </td>
                    </tr>
                </table>
                </div>
            </div>

            @if($invoice->notes)
            <div class="alert alert-info mt-3 mb-0">
                <strong><i class="ri-file-text-line me-1"></i>Notes:</strong><br>
                {{ $invoice->notes }}
            </div>
            @endif

            {{-- @if(auth()->user()->canSeeCosts())
            @php
                $totalProfit = $invoice->items->sum('line_profit') ?? 0;
                $profitPercentage = $invoice->subtotal > 0 ? ($totalProfit / $invoice->subtotal) * 100 : 0;
            @endphp
            <div class="alert alert-success mt-3 mb-0">
                <strong><i class="ri-money-dollar-circle-line me-1"></i>Profit Summary:</strong><br>
                Total Profit: <strong>R {{ number_format($totalProfit, 2) }}</strong>
                ({{ number_format($profitPercentage, 2) }}% margin)
            </div>
            @endif --}}
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
        <i class="ri-close-line"></i> Close
    </button>
    {{-- <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn btn-primary btn-sm">
        <i class="ri-printer-line"></i> Print
    </a>
    @if($invoice->payment_status === 'draft')
        <button type="button" class="btn btn-warning btn-sm openEditInvoiceModal" data-id="{{ $invoice->id }}">
            <i class="ri-pencil-line"></i> Edit
        </button>
    @endif --}}
</div>
