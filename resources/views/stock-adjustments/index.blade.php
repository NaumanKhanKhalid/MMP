@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Stock Adjustments</h4>
            <p class="fs-13 text-muted mb-0">Manual stock adjustments history</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdjustmentModal">
                <i class="ri-add-line me-1"></i> New Adjustment
            </button>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Adjustment #</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Product</th>
                            <th>Qty Before</th>
                            <th>Adjustment</th>
                            <th>Qty After</th>
                            <th>Reason</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adj)
                        <tr>
                            <td>{{ $adj->adjustment_number }}</td>
                            <td>{{ $adj->adjustment_date->format('d M Y') }}</td>
                            <td><span class="badge bg-info-transparent">{{ $adj->getAdjustmentTypeLabel() }}</span></td>
                            <td>{{ $adj->product->name }}</td>
                            <td>{{ number_format($adj->quantity_before, 0) }}</td>
                            <td class="{{ $adj->isIncrease() ? 'text-success' : 'text-danger' }} fw-semibold">
                                {{ $adj->adjustment_qty > 0 ? '+' : '' }}{{ number_format($adj->adjustment_qty, 2) }}
                            </td>
                            <td>{{ number_format($adj->quantity_after, 0) }}</td>
                            <td>{{ $adj->reason }}</td>
                            <td>{{ $adj->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No adjustments found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($adjustments->hasPages())
        <div class="card-footer">{{ $adjustments->links() }}</div>
        @endif
    </div>
</div>

<!-- Create Adjustment Modal -->
<div class="modal fade" id="createAdjustmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createAdjustmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product *</label>
                        <select name="product_id" id="productSelect" class="form-select" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                            @endforeach
                        </select>
                        <small id="currentStock" class="text-muted"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="manual">Manual Adjustment</option>
                            <option value="damage">Damaged Stock</option>
                            <option value="loss">Lost/Stolen</option>
                            <option value="found">Found/Recovered</option>
                            <option value="correction">Correction</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adjustment Qty * (+ve = increase, -ve = decrease)</label>
                        <input type="number" name="adjustment_qty" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="adjustment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason *</label>
                        <input type="text" name="reason" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('productSelect').addEventListener('change', function() {
    const productId = this.value;
    if (productId) {
        fetch(`/stock-adjustments/product/${productId}`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('currentStock').textContent = 
                    `Current Stock: ${d.product.on_hand}`;
            });
    }
});

document.getElementById('createAdjustmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route('stock-adjustments.store') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            alert(d.message);
            location.reload();
        } else {
            alert(d.message);
        }
    });
});
</script>
@endsection
