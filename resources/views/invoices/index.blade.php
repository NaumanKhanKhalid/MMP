@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-receipt me-2"></i>Invoices
                    </h2>
                    <p class="text-muted mb-0">Manage all your sales invoices</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                        <i class="bi bi-plus-circle me-1"></i>New Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" placeholder="Search by invoice number...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Customer</label>
                            <input type="text" class="form-control" placeholder="Search by customer...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-control">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="posted">Posted</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td>
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                        @if($invoice->quote_id)
                                            <small class="text-muted d-block">From Quote</small>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($invoice->customer)
                                            <strong>{{ $invoice->customer->name }}</strong>
                                            @if($invoice->customer->phone)
                                                <small class="text-muted d-block">{{ $invoice->customer->phone }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ $invoice->customer_name ?? 'Cash Sale' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($invoice->payment_status)
                                            @case('draft')
                                                <span class="badge bg-warning">Draft</span>
                                                @break
                                            @case('posted')
                                                <span class="badge bg-info">Posted</span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success">Paid</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
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
                                    <td class="text-end">
                                        <strong>R {{ number_format($invoice->grand_total, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        R {{ number_format($invoice->amount_paid, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                                            R {{ number_format($invoice->balance_due, 2) }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="viewInvoice({{ $invoice->id }})" title="View">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($invoice->isDraft())
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="editInvoice({{ $invoice->id }})" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="postInvoice({{ $invoice->id }})" title="Post">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="printInvoice({{ $invoice->id }})" title="Print">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                            @if($invoice->isDraft())
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteInvoice({{ $invoice->id }})" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-receipt display-4 d-block mb-2"></i>
                                            <h5>No invoices found</h5>
                                            <p>Start by creating your first invoice</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($invoices->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="createInvoiceModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- View Invoice Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="viewInvoiceModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Edit Invoice Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="editInvoiceModalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Load create modal
// Create invoice
$('#createInvoiceModal').on('show.bs.modal', function () {
    $.get('{{ route("invoices.create") }}', function(data) {
        $('#createInvoiceModalContent').html(data);
    });
});

// View invoice
function viewInvoice(id) {
    $.get('{{ route("invoices.view-modal", ":id") }}'.replace(':id', id), function(data) {
        $('#viewInvoiceModalContent').html(data);
        $('#viewInvoiceModal').modal('show');
    });
}

// Edit invoice
function editInvoice(id) {
    $.get('{{ route("invoices.edit-modal", ":id") }}'.replace(':id', id), function(data) {
        $('#editInvoiceModalContent').html(data);
        $('#editInvoiceModal').modal('show');
    });
}

// Post invoice
function postInvoice(id) {
    if (confirm('Are you sure you want to post this invoice? This will update stock levels.')) {
        $.post('{{ route("invoices.post", ":id") }}'.replace(':id', id), {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                showAlert('success', response.message);
                location.reload();
            } else {
                showAlert('error', response.message);
            }
        });
    }
}

// Print invoice
function printInvoice(id) {
    window.open('{{ route("invoices.print", ":id") }}'.replace(':id', id), '_blank');
}

// Delete invoice
function deleteInvoice(id) {
    if (confirm('Are you sure you want to delete this invoice?')) {
        $.ajax({
            url: '{{ route("invoices.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            }
        });
    }
}

// Show alert function
function showAlert(type, message) {
    // You can implement your alert system here
    alert(message);
}
</script>
@endpush
