@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Stock Counts</h4>
            <p class="fs-13 text-muted mb-0">Physical stock count management</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="openCreateStockCountModal()">
                <i class="bi bi-plus-circle me-1"></i>New Stock Count
            </button>
        </div>
    </div>

    <!-- Stock Counts Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">All Stock Counts</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Count #</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Filters</th>
                                    <th>Progress</th>
                                    <th>Variances</th>
                                    <th>Variance Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($counts as $count)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $count->count_number }}</span>
                                    </td>
                                    <td>{{ $count->count_name }}</td>
                                    <td>{{ $count->count_date->format('d M Y') }}</td>
                                    <td>
                                        @if($count->category)
                                            <span class="badge bg-info-transparent">{{ $count->category->name }}</span>
                                        @endif
                                        @if($count->brand)
                                            <span class="badge bg-primary-transparent">{{ $count->brand->name }}</span>
                                        @endif
                                        @if($count->bin_location)
                                            <span class="badge bg-secondary-transparent">{{ $count->bin_location }}</span>
                                        @endif
                                        @if(!$count->category && !$count->brand && !$count->bin_location)
                                            <span class="text-muted">All Products</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $count->progress_percentage }}%"
                                                 aria-valuenow="{{ $count->progress_percentage }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $count->counted_products }}/{{ $count->total_products }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($count->products_with_variance > 0)
                                            <span class="badge bg-warning">{{ $count->products_with_variance }} items</span>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($count->total_variance_value != 0)
                                            <span class="fw-semibold {{ $count->total_variance_value > 0 ? 'text-success' : 'text-danger' }}">
                                                R {{ number_format(abs($count->total_variance_value), 2) }}
                                                <i class="ri-arrow-{{ $count->total_variance_value > 0 ? 'up' : 'down' }}-line"></i>
                                            </span>
                                        @else
                                            <span class="text-muted">R 0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($count->status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($count->status === 'in_progress')
                                            <span class="badge bg-primary">In Progress</span>
                                        @elseif($count->status === 'completed')
                                            <span class="badge bg-warning">Completed</span>
                                        @elseif($count->status === 'posted')
                                            <span class="badge bg-success">Posted</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            @if($count->canEdit())
                                                <button type="button" class="btn btn-sm btn-primary-light btn-icon" onclick="openCountPage({{ $count->id }})" title="Start Counting">
                                                    <i class="bi bi-calculator"></i>
                                                </button>
                                            @endif
                                            @if($count->isCompleted() || $count->isPosted())
                                                <button type="button" class="btn btn-sm btn-info-light btn-icon" onclick="viewVarianceReport({{ $count->id }})" title="Variance Report">
                                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                                </button>
                                            @endif
                                            @if($count->canPost())
                                                <button type="button" class="btn btn-sm btn-success-light btn-icon" onclick="postCount({{ $count->id }})" title="Post Adjustments">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif
                                            @if(!$count->isPosted() && !$count->isCancelled())
                                                <button type="button" class="btn btn-sm btn-danger-light btn-icon" onclick="cancelCount({{ $count->id }})" title="Cancel Count">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ri-inbox-line fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No stock counts found</p>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateStockCountModal()">
                                            <i class="bi bi-plus-circle me-1"></i>Create First Stock Count
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($counts->hasPages())
                <div class="card-footer">
                    {{ $counts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Stock Count Modal -->
<div class="modal fade" id="createStockCountModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" id="createStockCountModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Open create stock count modal
function openCreateStockCountModal() {
    const url = '{{ route("stock-counts.create") }}';
    
    // Show loading
    document.getElementById('createStockCountModalContent').innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading form...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('createStockCountModal'));
    modal.show();
    
    // Fetch content
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('createStockCountModalContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('createStockCountModalContent').innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">Error loading form. Please try again.</div>
                </div>
            `;
        });
}

// Open counting page (redirects for now - can be modal later)
function openCountPage(id) {
    const url = '{{ route("stock-counts.count", ":id") }}'.replace(':id', id);
    window.location.href = url;
}

// View variance report (redirects for now - can be modal later)
function viewVarianceReport(id) {
    const url = '{{ route("stock-counts.variance-report", ":id") }}'.replace(':id', id);
    window.location.href = url;
}

// Post stock count
function postCount(id) {
    if (!confirm('Are you sure you want to post this stock count? This will create adjustments and update stock levels.')) {
        return;
    }

    const url = '{{ route("stock-counts.post", ":id") }}'.replace(':id', id);
    
    fetch(url, {
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
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error posting stock count');
    });
}

// Cancel stock count
function cancelCount(id) {
    if (!confirm('Are you sure you want to cancel this stock count?')) {
        return;
    }

    const url = '{{ route("stock-counts.cancel", ":id") }}'.replace(':id', id);
    
    fetch(url, {
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
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error cancelling stock count');
    });
}
</script>
@endpush
