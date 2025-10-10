@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Payments</h4>
            <p class="fs-13 text-muted mb-0">Manage customer and supplier payments</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" onclick="openPaymentModal('customer')">
                <i class="ri-add-line me-1"></i> Customer Payment
            </button>
            <button type="button" class="btn btn-success" onclick="openPaymentModal('supplier')">
                <i class="ri-add-line me-1"></i> Supplier Payment
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('payments.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Payment #, Reference, Name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Method</label>
                                <select name="method" class="form-select">
                                    <option value="">All Methods</option>
                                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="eft" {{ request('method') == 'eft' ? 'selected' : '' }}>EFT</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                                    <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-search-line me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('payments.index') }}" class="btn btn-light">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">All Payments</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Payment #</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Payer</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Gross Amount</th>
                                    <th>Fees</th>
                                    <th>Net Amount</th>
                                    <th>Allocated</th>
                                    <th>Unallocated</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $payment->payment_number }}</span>
                                    </td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td>
                                        @if($payment->isCustomerPayment())
                                            <span class="badge bg-primary-transparent">Customer</span>
                                        @else
                                            <span class="badge bg-success-transparent">Supplier</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payer_name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ strtoupper($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->reference ?? '-' }}</td>
                                    <td>R {{ number_format($payment->gross_amount, 2) }}</td>
                                    <td>R {{ number_format($payment->fee_amount, 2) }}</td>
                                    <td>R {{ number_format($payment->net_amount, 2) }}</td>
                                    <td>R {{ number_format($payment->allocated_amount, 2) }}</td>
                                    <td>
                                        @if($payment->unallocated_amount > 0)
                                            <span class="text-warning fw-semibold">R {{ number_format($payment->unallocated_amount, 2) }}</span>
                                        @else
                                            <span class="text-success">R 0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payment->status === 'posted')
                                            <span class="badge bg-success">Posted</span>
                                        @elseif($payment->status === 'voided')
                                            <span class="badge bg-danger">Voided</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="viewPayment({{ $payment->id }})" title="View">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            @if($payment->status === 'posted' && $payment->hasUnallocatedAmount())
                                                <button type="button" class="btn btn-sm btn-primary" onclick="allocatePayment({{ $payment->id }})" title="Allocate">
                                                    <i class="ri-links-line"></i>
                                                </button>
                                            @endif
                                            @if($payment->status === 'posted' && $payment->allocated_amount == 0)
                                                <button type="button" class="btn btn-sm btn-danger" onclick="voidPayment({{ $payment->id }})" title="Void">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center py-5">
                                        <i class="ri-file-list-line fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No payments found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($payments->hasPages())
                <div class="card-footer">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal Container -->
<div id="paymentModalContainer"></div>

@endsection

@push('scripts')
<script>
function openPaymentModal(type) {
    fetch(`{{ route('payments.create') }}?type=${type}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('paymentModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('createPaymentModal'));
            modal.show();
        });
}

function viewPayment(id) {
    fetch(`/payments/${id}/view-modal`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('paymentModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('viewPaymentModal'));
            modal.show();
        });
}

function allocatePayment(id) {
    fetch(`/payments/${id}/allocate-modal`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('paymentModalContainer').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('allocatePaymentModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading allocation modal');
        });
}

function voidPayment(id) {
    if (!confirm('Are you sure you want to void this payment? This action cannot be undone.')) {
        return;
    }

    fetch(`/payments/${id}/void`, {
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
        alert('Error voiding payment');
    });
}
</script>
@endpush
