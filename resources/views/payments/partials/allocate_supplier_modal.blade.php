<div class="modal fade" id="allocatePaymentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-links-line me-2"></i> Allocate Payment to Supplier Invoices
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Payment Info -->
                <div class="alert alert-success mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Payment Number</small>
                            <strong>{{ $payment->payment_number }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Supplier</small>
                            <strong>{{ $payment->supplier->name }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Net Amount</small>
                            <strong>R {{ number_format($payment->net_amount, 2) }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Unallocated</small>
                            <strong class="text-warning">R {{ number_format($payment->unallocated_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Supplier Invoices -->
                @if($supplierInvoices->count() > 0)
                <form id="allocatePaymentForm">
                    @csrf
                    <h6 class="mb-3">Outstanding Supplier Invoices</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th width="150">Allocate Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplierInvoices as $invoice)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input invoice-checkbox" 
                                               data-invoice-id="{{ $invoice->id }}"
                                               data-balance="{{ $invoice->balance_due }}">
                                    </td>
                                    <td>{{ $invoice->supplier_invoice_number }}</td>
                                    <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td>R {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td>R {{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td><strong>R {{ number_format($invoice->balance_due, 2) }}</strong></td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm allocation-amount" 
                                               data-invoice-id="{{ $invoice->id }}"
                                               step="0.01" 
                                               min="0" 
                                               max="{{ min($invoice->balance_due, $payment->unallocated_amount) }}"
                                               placeholder="0.00"
                                               disabled>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="6" class="text-end fw-bold">Total to Allocate:</td>
                                    <td><strong id="totalAllocation">R 0.00</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end">Remaining Unallocated:</td>
                                    <td><span id="remainingAmount" class="text-warning fw-bold">R {{ number_format($payment->unallocated_amount, 2) }}</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
                @else
                <div class="alert alert-warning">
                    <i class="ri-information-line me-2"></i> No outstanding invoices found for this supplier.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                @if($supplierInvoices->count() > 0)
                <button type="button" class="btn btn-success" id="submitAllocation">
                    <i class="ri-save-line me-1"></i> Allocate Payment
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const unallocatedAmount = {{ $payment->unallocated_amount }};
    let totalAllocated = 0;

    // Enable/disable amount input when checkbox is checked
    document.querySelectorAll('.invoice-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const invoiceId = this.dataset.invoiceId;
            const amountInput = document.querySelector(`.allocation-amount[data-invoice-id="${invoiceId}"]`);
            
            if (this.checked) {
                amountInput.disabled = false;
                // Auto-fill with balance or remaining unallocated amount
                const balance = parseFloat(this.dataset.balance);
                const remaining = unallocatedAmount - totalAllocated;
                amountInput.value = Math.min(balance, remaining).toFixed(2);
            } else {
                amountInput.disabled = true;
                amountInput.value = '';
            }
            calculateTotal();
        });
    });

    // Select all
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.invoice-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    // Recalculate on amount change
    document.querySelectorAll('.allocation-amount').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    function calculateTotal() {
        totalAllocated = 0;
        document.querySelectorAll('.allocation-amount:not([disabled])').forEach(input => {
            totalAllocated += parseFloat(input.value) || 0;
        });

        document.getElementById('totalAllocation').textContent = 'R ' + totalAllocated.toFixed(2);
        document.getElementById('remainingAmount').textContent = 'R ' + (unallocatedAmount - totalAllocated).toFixed(2);
    }

    // Submit allocation
    document.getElementById('submitAllocation')?.addEventListener('click', function() {
        const allocations = [];
        
        document.querySelectorAll('.invoice-checkbox:checked').forEach(checkbox => {
            const invoiceId = checkbox.dataset.invoiceId;
            const amount = parseFloat(document.querySelector(`.allocation-amount[data-invoice-id="${invoiceId}"]`).value) || 0;
            
            if (amount > 0) {
                allocations.push({
                    supplier_invoice_id: invoiceId,
                    amount: amount
                });
            }
        });

        if (allocations.length === 0) {
            alert('Please select at least one invoice and enter allocation amounts');
            return;
        }

        if (totalAllocated > unallocatedAmount) {
            alert('Total allocation exceeds unallocated amount');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

        fetch('{{ route('payments.allocate', $payment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ allocations: allocations })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
                this.disabled = false;
                this.innerHTML = '<i class="ri-save-line me-1"></i> Allocate Payment';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error allocating payment');
            this.disabled = false;
            this.innerHTML = '<i class="ri-save-line me-1"></i> Allocate Payment';
        });
    });
});
</script>
