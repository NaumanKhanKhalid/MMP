{{-- Statement Date Range Form Modal --}}
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-file-text-line me-2"></i> Generate Customer Statement
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form method="GET" action="{{ route('statements.customer', $customer) }}" target="_blank">
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ri-information-line me-2"></i>
            <strong>Customer:</strong> {{ $customer->display_name }} ({{ $customer->customer_code }})<br>
            <strong>Current Balance:</strong> R {{ number_format($customer->balance, 2) }}
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ now()->startOfMonth()->format('Y-m-d') }}" required>
                <small class="text-muted">Statement period start</small>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ now()->format('Y-m-d') }}" required>
                <small class="text-muted">Statement period end</small>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="ri-file-pdf-line me-1"></i> Generate PDF
        </button>
    </div>
</form>

