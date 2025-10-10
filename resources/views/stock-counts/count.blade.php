@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">{{ $stockCount->count_number }} - {{ $stockCount->count_name }}</h4>
            <p class="fs-13 text-muted mb-0">Count Status: <span class="badge bg-{{ $stockCount->status === 'draft' ? 'secondary' : 'primary' }}">{{ ucfirst($stockCount->status) }}</span></p>
        </div>
        <div class="d-flex gap-2">
            @if($stockCount->isDraft())
                <button type="button" class="btn btn-primary" onclick="startCounting()">
                    <i class="ri-play-line me-1"></i> Start Counting
                </button>
            @endif
            @if($stockCount->isInProgress())
                <button type="button" class="btn btn-success" onclick="completeCounting()">
                    <i class="ri-check-line me-1"></i> Complete & Review
                </button>
            @endif
            <a href="{{ route('stock-counts.index') }}" class="btn btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Progress Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Progress</span>
                    <h3 class="mb-0">{{ $stockCount->counted_products }}/{{ $stockCount->total_products }}</h3>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ $stockCount->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Uncounted</span>
                    <h3 class="mb-0">{{ $stockCount->total_products - $stockCount->counted_products }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Variances</span>
                    <h3 class="mb-0 text-warning">{{ $stockCount->products_with_variance }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Variance Value</span>
                    <h3 class="mb-0 {{ $stockCount->total_variance_value >= 0 ? 'text-success' : 'text-danger' }}">
                        R {{ number_format(abs($stockCount->total_variance_value), 2) }}
                        <i class="ri-arrow-{{ $stockCount->total_variance_value >= 0 ? 'up' : 'down' }}-line fs-16"></i>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Scanner -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <div class="col-md-6">
                            <label class="form-label">Search / Scan Product</label>
                            <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Enter SKU, Barcode, or Name..." autofocus>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter</label>
                            <select id="filterSelect" class="form-select">
                                <option value="all">All Items</option>
                                <option value="uncounted">Uncounted Only</option>
                                <option value="counted">Counted Only</option>
                                <option value="variance">With Variance</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info w-100" onclick="refreshList()">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Count Items Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Products to Count</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th width="100">Status</th>
                                    <th>SKU</th>
                                    <th>Product</th>
                                    <th width="120">System Qty</th>
                                    <th width="150">Counted Qty</th>
                                    <th width="120">Variance</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @foreach($stockCount->items as $item)
                                <tr id="row-{{ $item->id }}" class="{{ $item->is_counted ? 'table-success' : '' }}">
                                    <td>
                                        @if($item->is_counted)
                                            <span class="badge bg-success"><i class="ri-check-line"></i> Counted</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="ri-time-line"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->product->barcode_primary)
                                            <br><small class="text-muted">{{ $item->product->barcode_primary }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info-transparent">{{ number_format($item->system_qty, 2) }}</span>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm count-input" 
                                               data-item-id="{{ $item->id }}"
                                               value="{{ $item->counted_qty }}"
                                               step="0.01" 
                                               min="0"
                                               placeholder="Enter count">
                                    </td>
                                    <td>
                                        <span id="variance-{{ $item->id }}" class="fw-semibold {{ $item->variance_qty > 0 ? 'text-success' : ($item->variance_qty < 0 ? 'text-danger' : 'text-muted') }}">
                                            @if($item->is_counted)
                                                {{ $item->variance_qty > 0 ? '+' : '' }}{{ number_format($item->variance_qty, 2) }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary save-btn" data-item-id="{{ $item->id }}" onclick="saveItem({{ $item->id }})">
                                            <i class="ri-save-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const countId = {{ $stockCount->id }};

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

// Filter functionality
document.getElementById('filterSelect').addEventListener('change', function() {
    const filter = this.value;
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const isCounted = row.classList.contains('table-success');
        const hasVariance = row.querySelector('[id^="variance-"]').textContent.trim() !== '-' && 
                           parseFloat(row.querySelector('[id^="variance-"]').textContent) !== 0;
        
        let show = true;
        if (filter === 'uncounted') show = !isCounted;
        else if (filter === 'counted') show = isCounted;
        else if (filter === 'variance') show = hasVariance;
        
        row.style.display = show ? '' : 'none';
    });
});

// Save item count
function saveItem(itemId) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const countedQty = input.value;

    if (!countedQty || countedQty < 0) {
        alert('Please enter a valid quantity');
        return;
    }

    fetch(`/stock-counts/${countId}/items/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ counted_qty: countedQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update row
            const row = document.getElementById(`row-${itemId}`);
            row.classList.add('table-success');
            
            // Update variance display
            const varianceSpan = document.getElementById(`variance-${itemId}`);
            const variance = data.item.variance_qty;
            varianceSpan.textContent = (variance > 0 ? '+' : '') + parseFloat(variance).toFixed(2);
            varianceSpan.className = 'fw-semibold ' + (variance > 0 ? 'text-success' : (variance < 0 ? 'text-danger' : 'text-muted'));
            
            // Reload page to update summary
            setTimeout(() => location.reload(), 500);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving count');
    });
}

// Handle Enter key on inputs
document.querySelectorAll('.count-input').forEach(input => {
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const itemId = this.getAttribute('data-item-id');
            saveItem(itemId);
        }
    });
});

function startCounting() {
    fetch(`/stock-counts/${countId}/start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function completeCounting() {
    fetch(`/stock-counts/${countId}/complete`, {
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
            const url = '{{ route("stock-counts.variance-report", ":id") }}'.replace(':id', countId);
            window.location.href = url;
        } else {
            alert(data.message);
        }
    });
}

function refreshList() {
    location.reload();
}
</script>
@endpush
