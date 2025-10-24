<div class="modal fade" id="createPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line me-2"></i> Record Customer Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createPaymentForm" action="<?php echo e(route('payments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="payment_type" value="customer">
                
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Customer Selection -->
                        <div class="col-md-6">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customerId" class="form-select" required>
                                <option value="">Select Customer</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" data-balance="<?php echo e($customer->balance); ?>">
                                        <?php echo e($customer->name); ?> (<?php echo e($customer->customer_code); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted" id="customerBalance"></small>
                        </div>

                        <!-- Payment Date -->
                        <div class="col-md-6">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="paymentMethod" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="eft">EFT</option>
                            </select>
                        </div>

                        <!-- Reference -->
                        <div class="col-md-6">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference" class="form-control" placeholder="Cheque #, EFT Ref, etc.">
                        </div>

                        <!-- Gross Amount -->
                        <div class="col-md-4">
                            <label class="form-label">Amount Received <span class="text-danger">*</span></label>
                            <input type="number" name="gross_amount" id="grossAmount" class="form-control" step="0.01" min="0.01" required>
                        </div>

                        <!-- Estimated Fees -->
                        <div class="col-md-4">
                            <label class="form-label">Estimated Fees</label>
                            <input type="text" id="estimatedFees" class="form-control" readonly>
                        </div>

                        <!-- Net Amount -->
                        <div class="col-md-4">
                            <label class="form-label">Net Amount</label>
                            <input type="text" id="netAmount" class="form-control" readonly>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Payment notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ri-save-line me-1"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Use setTimeout to ensure DOM is ready when modal content is injected
setTimeout(function() {
    const customerSelect = document.getElementById('customerId');
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const grossAmountInput = document.getElementById('grossAmount');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('createPaymentForm');
    
    if (!customerSelect || !form) {
        console.log('Payment form elements not found, retrying...');
        return;
    }

    // Show customer balance
    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const balance = option.getAttribute('data-balance');
            const balanceDisplay = document.getElementById('customerBalance');
            if (balanceDisplay && balance) {
                balanceDisplay.textContent = `Current Balance: R ${parseFloat(balance).toFixed(2)}`;
            }
        });
    }

    // Calculate fees and net amount
    function calculateFees() {
        const method = paymentMethodSelect ? paymentMethodSelect.value : 'cash';
        const gross = grossAmountInput ? (parseFloat(grossAmountInput.value) || 0) : 0;
        
        let fee = 0;
        if (method === 'card') {
            fee = gross * 0.025; // 2.5%
        } else if (method === 'cash') {
            fee = (gross / 100) * 1.5; // R1.50 per R100
        }
        
        const net = gross - fee;
        
        const feesDisplay = document.getElementById('estimatedFees');
        const netDisplay = document.getElementById('netAmount');
        
        if (feesDisplay) feesDisplay.value = 'R ' + fee.toFixed(2);
        if (netDisplay) netDisplay.value = 'R ' + net.toFixed(2);
    }

    if (paymentMethodSelect) paymentMethodSelect.addEventListener('change', calculateFees);
    if (grossAmountInput) grossAmountInput.addEventListener('input', calculateFees);

    // Submit form via AJAX
    if (form) {
        form.addEventListener('submit', function(e) {
        e.preventDefault();
            e.stopPropagation();
        
            if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            }

        const formData = new FormData(this);
        
            fetch('<?php echo e(route("payments.store")); ?>', {
            method: 'POST',
                body: formData,
            headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                    // Close payment modal
                    const paymentModal = bootstrap.Modal.getInstance(document.getElementById('createPaymentModal'));
                    if (paymentModal) paymentModal.hide();
                    
                    // Show success modal
                    showPaymentSuccessModal(data);
            } else {
                    alert(data.message || 'Error recording payment');
                    if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Record Payment';
                    }
            }
        })
        .catch(error => {
            console.error('Error:', error);
                alert('Error recording payment. Please try again.');
                if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Record Payment';
                }
            });
            
            return false;
        });
    }
}, 300); // Wait 300ms for modal content to be injected

// Success Modal Function
function showPaymentSuccessModal(data) {
    // Create success modal HTML
    const modalHTML = `
        <div class="modal fade" id="paymentSuccessModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-body text-center p-5">
                        <div class="mb-4">
                            <div class="avatar avatar-xl bg-success-transparent rounded-circle mx-auto mb-3">
                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                            </div>
                            <h4 class="text-success mb-2">Payment Recorded Successfully!</h4>
                            <p class="text-muted mb-0">${data.message || 'Payment has been recorded and ledger updated.'}</p>
                        </div>
                        
                        <div class="alert alert-light border mb-4">
                            <div class="row text-start">
                                <div class="col-6">
                                    <small class="text-muted d-block">Payment Number</small>
                                    <strong class="text-primary">PAY${String(data.payment_id).padStart(5, '0')}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge bg-success">Posted</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="location.reload()">
                                <i class="ri-refresh-line me-1"></i> Refresh & View Payment
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Append to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show modal
    const successModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'));
    successModal.show();
    
    // Remove modal from DOM when hidden
    document.getElementById('paymentSuccessModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}
</script>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/payments/partials/create_customer_modal.blade.php ENDPATH**/ ?>