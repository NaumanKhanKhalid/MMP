<div class="modal fade" id="viewPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-file-list-line me-2"></i> Payment Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Payment Information -->
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">Payment Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Payment Number</small>
                                <p class="fw-semibold mb-0">{{ $payment->payment_number }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Payment Date</small>
                                <p class="mb-0">{{ $payment->payment_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Type</small>
                                <p class="mb-0">
                                    @if($payment->isCustomerPayment())
                                        <span class="badge bg-primary">Customer Payment</span>
                                    @else
                                        <span class="badge bg-success">Supplier Payment</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Status</small>
                                <p class="mb-0">
                                    @if($payment->status === 'posted')
                                        <span class="badge bg-success">Posted</span>
                                    @elseif($payment->status === 'voided')
                                        <span class="badge bg-danger">Voided</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">{{ $payment->isCustomerPayment() ? 'Customer' : 'Supplier' }}</small>
                                <p class="mb-0">{{ $payment->payer_name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Payment Method</small>
                                <p class="mb-0"><span class="badge bg-light text-dark">{{ strtoupper($payment->payment_method) }}</span></p>
                            </div>
                            @if($payment->reference)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Reference</small>
                                <p class="mb-0">{{ $payment->reference }}</p>
                            </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Recorded By</small>
                                <p class="mb-0">{{ $payment->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Amounts -->
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">Amount Breakdown</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-semibold">Gross Amount:</td>
                                    <td class="text-end">R {{ number_format($payment->gross_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Fees:</td>
                                    <td class="text-end text-danger">- R {{ number_format($payment->fee_amount, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-semibold">Net Amount:</td>
                                    <td class="text-end fw-bold">R {{ number_format($payment->net_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Allocated:</td>
                                    <td class="text-end">R {{ number_format($payment->allocated_amount, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-semibold">Unallocated:</td>
                                    <td class="text-end {{ $payment->unallocated_amount > 0 ? 'text-warning fw-bold' : 'text-success' }}">
                                        R {{ number_format($payment->unallocated_amount, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Allocations -->
                    @if($payment->allocations->count() > 0)
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">Allocations</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice/Document</th>
                                        <th>Date</th>
                                        <th>Amount Allocated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payment->allocations as $allocation)
                                    <tr>
                                        <td>
                                            @if($allocation->invoice)
                                                {{ $allocation->invoice->invoice_number }}
                                            @elseif($allocation->supplierInvoice)
                                                {{ $allocation->supplierInvoice->supplier_invoice_number }}
                                            @endif
                                        </td>
                                        <td>{{ $allocation->allocation_date->format('d M Y') }}</td>
                                        <td>R {{ number_format($allocation->allocated_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($payment->notes)
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                        <p class="mb-0">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                @if($payment->status === 'posted' && $payment->hasUnallocatedAmount())
                    <button type="button" class="btn btn-primary" onclick="allocatePayment({{ $payment->id }})" data-bs-dismiss="modal">
                        <i class="ri-links-line me-1"></i> Allocate
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
