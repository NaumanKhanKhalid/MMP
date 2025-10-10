<!-- resources/views/purchase_orders/partials/view_modal.blade.php -->
<div class="modal-header">
    <h5 class="modal-title">
        <i class="bi bi-eye me-2"></i> PO Details - {{ $po->po_number }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <ul class="nav nav-tabs mb-3" id="viewPoTabs-{{ $po->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $po->id }}" data-bs-toggle="tab" data-bs-target="#view-basic-{{ $po->id }}" type="button" role="tab" aria-controls="view-basic-{{ $po->id }}" aria-selected="true">Basic Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="items-tab-{{ $po->id }}" data-bs-toggle="tab" data-bs-target="#view-items-{{ $po->id }}" type="button" role="tab" aria-controls="view-items-{{ $po->id }}" aria-selected="false">Items</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="grns-tab-{{ $po->id }}" data-bs-toggle="tab" data-bs-target="#view-grns-{{ $po->id }}" type="button" role="tab" aria-controls="view-grns-{{ $po->id }}" aria-selected="false">GRNs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notes-tab-{{ $po->id }}" data-bs-toggle="tab" data-bs-target="#view-notes-{{ $po->id }}" type="button" role="tab" aria-controls="view-notes-{{ $po->id }}" aria-selected="false">Notes</button>
        </li>
    </ul>
    <div class="tab-content" id="viewPoTabContent-{{ $po->id }}">
        <div class="tab-pane fade show active" id="view-basic-{{ $po->id }}" role="tabpanel" aria-labelledby="basic-tab-{{ $po->id }}" tabindex="0">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Supplier</label>
                    <p class="form-control-static">{{ $po->supplier->name ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">PO Number</label>
                    <p class="form-control-static">{{ $po->po_number }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Order Date</label>
                    <p class="form-control-static">{{ $po->order_date }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <span class="badge bg-info">{{ ucfirst($po->status) }}</span>
                    @if (!isset($po->locked) || !$po->locked)
                    <form method="POST" action="{{ route('purchase-orders.change-status', $po->id) }}" class="mt-2 d-flex align-items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="draft" @if($po->status=='draft') selected @endif>Draft</option>
                            <option value="sent" @if($po->status=='sent') selected @endif>Sent</option>
                            <option value="partially_received" @if($po->status=='partially_received') selected @endif>Partially Received</option>
                            <option value="completed" @if($po->status=='completed') selected @endif>Completed</option>
                            <option value="cancelled" @if($po->status=='cancelled') selected @endif>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                    @else
                    <div class="text-muted small">PO is locked. Status cannot be changed.</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Expected Date</label>
                    <p class="form-control-static">{{ $po->expected_date ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Total Amount</label>
                    <p class="form-control-static text-success fw-bold">R {{ number_format($po->total_amount, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="view-items-{{ $po->id }}" role="tabpanel" aria-labelledby="items-tab-{{ $po->id }}" tabindex="0">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td>{{ number_format($item->quantity, 0) }}</td>
                                <td>R {{ number_format($item->unit_price, 2) }}</td>
                                <td>R {{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="view-grns-{{ $po->id }}" role="tabpanel" aria-labelledby="grns-tab-{{ $po->id }}" tabindex="0">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>GRN Number</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($po->goodsReceipts as $grn)
                            <tr>
                                <td>{{ $grn->grn_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($grn->received_date)->format('Y-m-d') }}</td>
                                <td><span class="badge @if($grn->status=='completed') bg-success @elseif($grn->status=='pending') bg-warning @elseif($grn->status=='cancelled') bg-danger @else bg-secondary @endif">{{ ucfirst($grn->status) }}</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm view-grn-btn" data-grn-id="{{ $grn->id }}">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No GRNs linked to this PO.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="view-notes-{{ $po->id }}" role="tabpanel" aria-labelledby="notes-tab-{{ $po->id }}" tabindex="0">
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <p class="form-control-static">{{ $po->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>