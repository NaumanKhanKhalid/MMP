<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-receipt me-2"></i>Invoice {{ $invoice->invoice_number }}
        <span class="badge ms-2 {{ $invoice->payment_status === 'draft' ? 'bg-warning' : ($invoice->payment_status === 'posted' ? 'bg-info' : ($invoice->payment_status === 'paid' ? 'bg-success' : 'bg-danger')) }}">
            {{ ucfirst($invoice->payment_status) }}
        </span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">
        <!-- Invoice Details -->
        <div class="col-md-6">
            <h6 class="fw-bold mb-3">Invoice Details</h6>
            <table class="table table-sm">
                <tr>
                    <td class="fw-bold">Invoice Number:</td>
                    <td>{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Date:</td>
                    <td>{{ $invoice->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Created by:</td>
                    <td>{{ $invoice->user->name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Payment Method:</td>
                    <td>
                        @switch($invoice->payment_method)
                            @case('cash')
                                <i class="bi bi-cash me-1"></i>Cash
                                @break
                            @case('card')
                                <i class="bi bi-credit-card me-1"></i>Card
                                @break
                            @case('eft')
                                <i class="bi bi-bank me-1"></i>EFT
                                @break
                            @case('on_account')
                                <i class="bi bi-person-check me-1"></i>On Account
                                @break
                        @endswitch
                    </td>
                </tr>
                @if($invoice->reference)
                <tr>
                    <td class="fw-bold">Reference:</td>
                    <td>{{ $invoice->reference }}</td>
                </tr>
                @endif
                @if($invoice->quote)
                <tr>
                    <td class="fw-bold">From Quote:</td>
                    <td>{{ $invoice->quote->quote_number }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Customer Details -->
        <div class="col-md-6">
            <h6 class="fw-bold mb-3">Customer Details</h6>
            @if($invoice->customer)
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Name:</td>
                        <td>{{ $invoice->customer->name }}</td>
                    </tr>
                    @if($invoice->customer->email)
                    <tr>
                        <td class="fw-bold">Email:</td>
                        <td>{{ $invoice->customer->email }}</td>
                    </tr>
                    @endif
                    @if($invoice->customer->phone)
                    <tr>
                        <td class="fw-bold">Phone:</td>
                        <td>{{ $invoice->customer->phone }}</td>
                    </tr>
                    @endif
                    @if($invoice->customer->address)
                    <tr>
                        <td class="fw-bold">Address:</td>
                        <td>{{ $invoice->customer->address }}</td>
                    </tr>
                    @endif
                </table>
            @else
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Name:</td>
                        <td>{{ $invoice->customer_name ?? 'Cash Sale' }}</td>
                    </tr>
                    @if($invoice->customer_email)
                    <tr>
                        <td class="fw-bold">Email:</td>
                        <td>{{ $invoice->customer_email }}</td>
                    </tr>
                    @endif
                    @if($invoice->customer_phone)
                    <tr>
                        <td class="fw-bold">Phone:</td>
                        <td>{{ $invoice->customer_phone }}</td>
                    </tr>
                    @endif
                </table>
            @endif
        </div>
    </div>

    <!-- Vehicle Details -->
    @if($invoice->vehicle_make || $invoice->vehicle_model || $invoice->vehicle_vin || $invoice->vehicle_reg)
    <hr>
    <h6 class="fw-bold mb-3">Vehicle Details</h6>
    <div class="row">
        @if($invoice->vehicle_make)
        <div class="col-md-3">
            <strong>Make:</strong> {{ $invoice->vehicle_make }}
        </div>
        @endif
        @if($invoice->vehicle_model)
        <div class="col-md-3">
            <strong>Model:</strong> {{ $invoice->vehicle_model }}
        </div>
        @endif
        @if($invoice->vehicle_vin)
        <div class="col-md-3">
            <strong>VIN:</strong> {{ $invoice->vehicle_vin }}
        </div>
        @endif
        @if($invoice->vehicle_reg)
        <div class="col-md-3">
            <strong>Registration:</strong> {{ $invoice->vehicle_reg }}
        </div>
        @endif
    </div>
    @endif

    <!-- Invoice Items -->
    <hr>
    <h6 class="fw-bold mb-3">Invoice Items</h6>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-center">Disc %</th>
                    <th class="text-end">Disc Amount</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_sku }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-end">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->discount_percentage > 0 ? number_format($item->discount_percentage, 1) . '%' : '-' }}</td>
                    <td class="text-end">{{ $item->discount_amount > 0 ? 'R ' . number_format($item->discount_amount, 2) : '-' }}</td>
                    <td class="text-end"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Invoice Totals -->
    <div class="row">
        <div class="col-md-6">
            @if($invoice->notes)
            <h6 class="fw-bold mb-2">Notes</h6>
            <div class="alert alert-light">
                {{ $invoice->notes }}
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Subtotal:</td>
                        <td class="text-end">R {{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                    <tr>
                        <td class="fw-bold">Discount:</td>
                        <td class="text-end text-danger">-R {{ number_format($invoice->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->shipping > 0)
                    <tr>
                        <td class="fw-bold">Shipping:</td>
                        <td class="text-end">R {{ number_format($invoice->shipping, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->vat_amount > 0)
                    <tr>
                        <td class="fw-bold">VAT ({{ $invoice->vat_rate }}%):</td>
                        <td class="text-end">R {{ number_format($invoice->vat_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="table-primary">
                        <td class="fw-bold">Grand Total:</td>
                        <td class="text-end fw-bold">R {{ number_format($invoice->grand_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Amount Paid:</td>
                        <td class="text-end">R {{ number_format($invoice->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="{{ $invoice->balance_due > 0 ? 'table-danger' : 'table-success' }}">
                        <td class="fw-bold">Balance Due:</td>
                        <td class="text-end fw-bold">R {{ number_format($invoice->balance_due, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i>Print Invoice
    </a>
    @if($invoice->isDraft())
        <button type="button" class="btn btn-warning" onclick="editInvoice({{ $invoice->id }})">
            <i class="bi bi-pencil me-1"></i>Edit
        </button>
        <button type="button" class="btn btn-success" onclick="postInvoice({{ $invoice->id }})">
            <i class="bi bi-check-circle me-1"></i>Post Invoice
        </button>
    @endif
</div>
