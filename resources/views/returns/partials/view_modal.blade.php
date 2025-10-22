<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-arrow-return-left me-2"></i>Return Details - {{ $return->return_number }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <!-- Return Information -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Return Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-bold" width="40%">Return Number:</td>
                            <td>{{ $return->return_number }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Original Invoice:</td>
                            <td>
                                <a href="javascript:void(0)" onclick="viewInvoice({{ $return->invoice_id }})" class="text-primary">
                                    {{ $return->invoice->invoice_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Return Type:</td>
                            <td>
                                <span class="badge bg-{{ $return->return_type_badge }}">
                                    {{ ucfirst($return->return_type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                <span class="badge bg-{{ $return->status_badge }}">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Refund Method:</td>
                            <td>
                                <span class="badge bg-{{ $return->refund_method_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $return->refund_method)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Stock Handling:</td>
                            <td>
                                @if($return->stock_handling_type === 'restock')
                                    <span class="badge bg-success">Restocked (FIFO)</span>
                                @elseif($return->stock_handling_type === 'writeoff')
                                    <span class="badge bg-warning">Write-off</span>
                                @else
                                    <span class="badge bg-info">Credit Only</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Created:</td>
                            <td>{{ $return->created_at->format('M d, Y H:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Created by:</td>
                            <td>{{ $return->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    @if($return->customer)
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="fw-bold" width="40%">Name:</td>
                            <td>{{ $return->customer->name }}</td>
                        </tr>
                        @if($return->customer->email)
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $return->customer->email }}</td>
                        </tr>
                        @endif
                        @if($return->customer->phone)
                        <tr>
                            <td class="fw-bold">Phone:</td>
                            <td>{{ $return->customer->phone }}</td>
                        </tr>
                        @endif
                        @if($return->customer->address)
                        <tr>
                            <td class="fw-bold">Address:</td>
                            <td>{{ $return->customer->address }}</td>
                        </tr>
                        @endif
                    </table>
                    @else
                    <p class="text-muted">Walk-in Customer</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Return Reason -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">Return Reason</h6>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $return->reason }}</p>
            @if($return->notes)
            <hr>
            <p class="mb-0"><strong>Notes:</strong> {{ $return->notes }}</p>
            @endif
        </div>
    </div>

    <!-- Returned Items -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0">Returned Items</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th class="text-center">Qty Returned</th>
                            <th class="text-center">Condition</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($return->items as $item)
                        <tr>
                            <td>{{ $item->product->sku ?? 'N/A' }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ number_format($item->quantity_returned, 0) }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $item->condition === 'new' ? 'success' : ($item->condition === 'damaged' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($item->condition) }}
                                </span>
                            </td>
                            <td class="text-end">R {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Totals -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold">Return Total:</td>
                            <td class="text-end"><h5 class="mb-0 text-danger">R {{ number_format($return->total_amount, 2) }}</h5></td>
                        </tr>
                        @if($return->creditNote && $return->creditNote->count() > 0)
                        <tr>
                            <td class="fw-bold">Credit Note:</td>
                            <td class="text-end">
                                <span class="badge bg-success">{{ $return->creditNote->first()->credit_note_number }}</span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i>Close
    </button>
    
    @if($return->status === 'pending')
    <button type="button" class="btn btn-success" onclick="approveReturn({{ $return->id }})">
        <i class="bi bi-check-circle me-1"></i>Approve
    </button>
    <button type="button" class="btn btn-danger" onclick="rejectReturn({{ $return->id }})">
        <i class="bi bi-x-circle me-1"></i>Reject
    </button>
    @endif
    
    @if($return->status === 'approved')
    <button type="button" class="btn btn-primary" onclick="completeReturn({{ $return->id }})">
        <i class="bi bi-check-double me-1"></i>Complete Return
    </button>
    @endif
    
    @if($return->status === 'completed' && $return->creditNote && $return->creditNote->count() > 0)
    <button type="button" class="btn btn-info" onclick="downloadCreditNote({{ $return->creditNote->first()->id }})">
        <i class="bi bi-file-earmark-pdf me-1"></i>Download Credit Note
    </button>
    @endif
</div>


