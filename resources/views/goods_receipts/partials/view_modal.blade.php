<!-- resources/views/goods_receipts/partials/view_modal.blade.php -->
<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-eye me-2"></i> GRN Details - {{ $grn->grn_number }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <ul class="nav nav-tabs mb-3" id="viewGrnTabs-{{ $grn->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $grn->id }}" data-bs-toggle="tab" data-bs-target="#view-basic-{{ $grn->id }}" type="button" role="tab" aria-controls="view-basic-{{ $grn->id }}" aria-selected="true">Basic Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="items-tab-{{ $grn->id }}" data-bs-toggle="tab" data-bs-target="#view-items-{{ $grn->id }}" type="button" role="tab" aria-controls="view-items-{{ $grn->id }}" aria-selected="false">Items & Batches</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notes-tab-{{ $grn->id }}" data-bs-toggle="tab" data-bs-target="#view-notes-{{ $grn->id }}" type="button" role="tab" aria-controls="view-notes-{{ $grn->id }}" aria-selected="false">Notes</button>
        </li>
    </ul>
    <div class="tab-content" id="viewGrnTabContent-{{ $grn->id }}">
        <div class="tab-pane fade show active" id="view-basic-{{ $grn->id }}" role="tabpanel" aria-labelledby="basic-tab-{{ $grn->id }}" tabindex="0">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Supplier</label>
                    <p class="form-control-static">{{ $grn->supplier->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">GRN Number</label>
                    <p class="form-control-static">{{ $grn->grn_number }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Linked PO</label>
                    @if($grn->purchaseOrder)
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold">{{ $grn->purchaseOrder->po_number }}</span>
                            <span class="badge bg-info">{{ ucfirst($grn->purchaseOrder->status) }}</span>
                            {{-- <button class="btn btn-outline-primary btn-sm view-po-btn" data-po-id="{{ $grn->purchaseOrder->id }}">View PO</button> --}}
                        </div>
                    @else
                        <span class="text-muted">No PO linked</span>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Received Date</label>
                    <p class="form-control-static">{{ \Carbon\Carbon::parse($grn->received_date)->format('M d, Y') }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <span class="badge @if ($grn->status == 'completed') bg-success @elseif($grn->status == 'pending') bg-warning @elseif($grn->status == 'cancelled') bg-danger @else bg-secondary @endif">{{ ucfirst($grn->status) }}</span>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Invoice Number</label>
                    <p class="form-control-static">{{ $grn->invoice_number ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Total Amount</label>
                    <p class="form-control-static text-success fw-bold">${{ number_format($grn->total_amount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="view-items-{{ $grn->id }}" role="tabpanel" aria-labelledby="items-tab-{{ $grn->id }}" tabindex="0">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Batch Code</th>
                            <th>Quantity Received</th>
                            <th>Quantity Left</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grn->batches as $batch)
                            <tr>
                                <td>{{ $batch->product->name ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $batch->batch_code }}</span></td>
                                <td>{{ number_format($batch->qty_received, 0) }}</td>
                                <td><span class="badge bg-success">{{ number_format($batch->qty_left, 0) }}</span></td>
                                <td>R {{ number_format($batch->landed_unit_cost, 2) }}</td>
                                <td class="fw-bold">R {{ number_format($batch->qty_received * $batch->landed_unit_cost, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">Grand Total:</th>
                            <th class="text-success">${{ number_format($grn->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="view-notes-{{ $grn->id }}" role="tabpanel" aria-labelledby="notes-tab-{{ $grn->id }}" tabindex="0">
            <div class="mb-3">
                <label class="form-label fw-bold">Notes</label>
                <div class="card">
                    <div class="card-body">
                        {!! $grn->notes ? nl2br(e($grn->notes)) : 'No notes available.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Close
    </button>
</div>
