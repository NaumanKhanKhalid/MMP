@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Variance Report - {{ $stockCount->count_number }}</h4>
            <p class="fs-13 text-muted mb-0">{{ $stockCount->count_name }}</p>
        </div>
        <div class="d-flex gap-2">
            @if($stockCount->canPost())
                <button type="button" class="btn btn-success" onclick="postCount()">
                    <i class="ri-check-line me-1"></i> Post Adjustments
                </button>
            @endif
            <a href="{{ route('stock-counts.index') }}" class="btn btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Variances</span>
                    <h3 class="mb-0 text-warning">{{ $stockCount->products_with_variance }} products</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Variance Value</span>
                    <h3 class="mb-0 {{ $stockCount->total_variance_value >= 0 ? 'text-success' : 'text-danger' }}">
                        R {{ number_format(abs($stockCount->total_variance_value), 2) }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Status</span>
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $stockCount->status === 'posted' ? 'success' : 'warning' }}">
                            {{ ucfirst($stockCount->status) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Variance Table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Products with Variances</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>System Qty</th>
                            <th>Counted Qty</th>
                            <th>Variance</th>
                            <th>Variance %</th>
                            <th>Unit Cost</th>
                            <th>Variance Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockCount->items as $item)
                        <tr>
                            <td>{{ $item->product->sku }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ number_format($item->system_qty, 2) }}</td>
                            <td>{{ number_format($item->counted_qty, 2) }}</td>
                            <td class="fw-semibold {{ $item->variance_qty > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $item->variance_qty > 0 ? '+' : '' }}{{ number_format($item->variance_qty, 2) }}
                            </td>
                            <td>{{ number_format($item->variance_percentage, 2) }}%</td>
                            <td>R {{ number_format($item->unit_cost, 2) }}</td>
                            <td class="fw-semibold {{ $item->variance_value > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $item->variance_value > 0 ? '+' : '' }}R {{ number_format(abs($item->variance_value), 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                <p class="text-muted mt-2">No variances found - all counts match system quantities!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function postCount() {
    if (!confirm('Are you sure you want to post this stock count? This will create {{ $stockCount->products_with_variance }} adjustments and update stock levels.')) {
        return;
    }

    fetch('/stock-counts/{{ $stockCount->id }}/post', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '{{ route("stock-counts.index") }}';
        } else {
            alert(data.message);
        }
    });
}
</script>
@endsection
