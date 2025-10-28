<!-- Process Customer Return Modal -->
<div class="modal fade" id="processReturnModal" tabindex="-1" aria-labelledby="processReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processReturnModalLabel">Process Customer Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Progress Steps -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Step 1: Find Sale -->
                            <div class="step-item text-center flex-fill">
                                <div class="step-number active" id="step-1-indicator">1</div>
                                <div class="step-label active" id="step-1-label">Find Sale</div>
                            </div>
                            <div class="step-line"></div>
                            
                            <!-- Step 2: Select Items -->
                            <div class="step-item text-center flex-fill">
                                <div class="step-number" id="step-2-indicator">2</div>
                                <div class="step-label" id="step-2-label">Select Items</div>
                            </div>
                            <div class="step-line"></div>
                            
                            <!-- Step 3: Resolution -->
                            <div class="step-item text-center flex-fill">
                                <div class="step-number" id="step-3-indicator">3</div>
                                <div class="step-label" id="step-3-label">Resolution</div>
                            </div>
                            <div class="step-line"></div>
                            
                            <!-- Step 4: Summary -->
                            <div class="step-item text-center flex-fill">
                                <div class="step-number" id="step-4-indicator">4</div>
                                <div class="step-label" id="step-4-label">Summary</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Step 1: Find Sale -->
                <div class="step-content" id="step-1-content">
                    <div class="mb-3">
                        <p class="text-muted">Enter the Sale ID from the customer's receipt to begin the return process.</p>
                    </div>
                    <div class="mb-3">
                        <label for="returnInvoiceNumber" class="form-label">Sale ID</label>
                        <input type="text" class="form-control" id="returnInvoiceNumber" placeholder="e.g., MMP10005">
                    </div>
                </div>

                <!-- Step 2: Select Items -->
                <div class="step-content d-none" id="step-2-content">
                    <div class="mb-3">
                        <h6>Original Sale: <strong id="returnInvoiceNumberDisplay"></strong></h6>
                        <p class="text-muted mb-2">
                            Date: <strong id="returnInvoiceDate"></strong> | 
                            Customer: <strong id="returnCustomerName"></strong>
                        </p>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="returnItemsTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty Sold</th>
                                    <th>Qty to Return</th>
                                    <th>Restock?</th>
                                </tr>
                            </thead>
                            <tbody id="returnItemsTableBody">
                                <!-- Items will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Step 3: Resolution -->
                <div class="step-content d-none" id="step-3-content">
                    <div class="mb-3">
                        <label for="returnReason" class="form-label">Reason for Return <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="returnReason" rows="3" placeholder="e.g., Wrong part supplied, damaged in box..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Resolution Method</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card resolution-option" data-method="credit_note">
                                    <div class="card-body text-center">
                                        <h6>Issue Credit Note</h6>
                                        <p class="text-muted small mb-0">Provide a credit note for the return value.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card resolution-option" data-method="bank_refund">
                                    <div class="card-body text-center">
                                        <h6>Refund to Bank</h6>
                                        <p class="text-muted small mb-0">Process a direct bank refund.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="applyToAccount" checked>
                            <label class="form-check-label" for="applyToAccount">
                                Apply credit to customer's account balance.
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="handlingFee" class="form-label">Handling Fee (optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" class="form-control" id="handlingFee" value="0" min="0" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Summary -->
                <div class="step-content d-none" id="step-4-content">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Confirmation Summary</h6>
                        <hr>
                        <p class="mb-2">
                            <strong>Original Sale:</strong> <span id="summaryInvoiceNumber"></span> 
                            (<span id="summaryCustomerName"></span>)
                        </p>
                        <p class="mb-2">
                            <strong>Gross Refund Amount:</strong> 
                            <span class="text-primary fs-4" id="summaryRefundAmount">R0.00</span>
                        </p>
                        <p class="mb-2">
                            <strong>Reason:</strong> <span id="summaryReason"></span>
                        </p>
                        <p class="mb-2">
                            <strong>Resolution:</strong> <span id="summaryResolution"></span>
                        </p>
                        <p class="mb-0">
                            <strong>Items to Return:</strong> <span id="summaryItemsCount"></span>
                        </p>
                    </div>

                    <div id="summaryItemsList">
                        <!-- Items summary will be shown here -->
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary d-none" id="backButton">Back</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="nextButton">Find Sale</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.step-item {
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto 10px;
    transition: all 0.3s;
}

.step-number.active {
    background-color: #0d6efd;
    color: white;
}

.step-number.completed {
    background-color: #198754;
    color: white;
}

.step-number.completed::before {
    content: '✓';
    font-size: 20px;
}

.step-label {
    font-size: 14px;
    color: #6c757d;
    transition: all 0.3s;
}

.step-label.active {
    color: #0d6efd;
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background-color: #e9ecef;
    margin: 0 10px;
    margin-top: -30px;
}

.resolution-option {
    cursor: pointer;
    border: 2px solid #e9ecef;
    transition: all 0.3s;
}

.resolution-option:hover {
    border-color: #0d6efd;
}

.resolution-option.selected {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

#returnItemsTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let currentStep = 1;
    let invoiceData = null;
    let selectedItems = [];
    let selectedResolutionMethod = 'credit_note';
    // Next Button Click
    $('#nextButton').on('click', function() {
        if (currentStep === 1) {
            handleFindSale();
        } else if (currentStep === 2) {
            handleSelectItems();
        } else if (currentStep === 3) {
            handleResolution();
        } else if (currentStep === 4) {
            handleConfirmReturn();
        }
    });

    // Back Button Click
    $('#backButton').on('click', function() {
        goToPreviousStep();
    });

    // Resolution Method Selection
    $('.resolution-option').on('click', function() {
        $('.resolution-option').removeClass('selected');
        $(this).addClass('selected');
        selectedResolutionMethod = $(this).data('method');
    });

    // Initialize with credit note selected
    $('.resolution-option[data-method="credit_note"]').addClass('selected');

    function handleFindSale() {
        const invoiceNumber = $('#returnInvoiceNumber').val().trim();
    
    if (!invoiceNumber) {
        toastr.error('Please enter a Sale ID');
        return;
    }

    // Show loading
    $('#nextButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Loading...');

    // Fetch invoice details
    fetch('<?php echo e(route("credit-notes.get-invoice-details")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ invoice_number: invoiceNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            invoiceData = data.invoice;
            populateSelectItemsStep();
            goToNextStep();
        } else {
            toastr.error(data.message || 'Invoice not found');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Failed to load invoice details');
    })
    .finally(() => {
        $('#nextButton').prop('disabled', false).html('Next: Select Items');
    });
    }

    function populateSelectItemsStep() {
        // Update invoice info
    $('#returnInvoiceNumberDisplay').text(invoiceData.invoice_number);
    $('#returnInvoiceDate').text(invoiceData.date);
    $('#returnCustomerName').text(invoiceData.customer.name);

    // Clear and populate items table
    const tbody = $('#returnItemsTableBody');
    tbody.empty();

    invoiceData.items.forEach(item => {
        const row = `
            <tr data-invoice-item-id="${item.id}" data-product-id="${item.product_id}">
                <td>${item.product_name}${item.product_sku ? '<br><small class="text-muted">' + item.product_sku + '</small>' : ''}</td>
                <td>R${parseFloat(item.unit_price).toFixed(2)}</td>
                <td>${item.qty_sold}</td>
                <td>
                    <input type="number" class="form-control qty-return" 
                           data-invoice-item-id="${item.id}"
                           data-product-id="${item.product_id}" 
                           data-max-qty="${item.qty_sold}"
                           value="0" 
                           min="0" 
                           max="${item.qty_sold}">
                </td>
                <td class="text-center">
                    <div class="form-check">
                        <input class="form-check-input restock-checkbox" 
                               type="checkbox" 
                               data-invoice-item-id="${item.id}"
                               data-product-id="${item.product_id}"
                               checked>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    // Add change handlers
    $('.qty-return').on('input', function() {
        const maxQty = $(this).data('max-qty');
        const currentQty = parseInt($(this).val()) || 0;
        
        if (currentQty > maxQty) {
            $(this).val(maxQty);
            toastr.warning(`Return quantity cannot exceed sold quantity (${maxQty})`);
        }
    });
}

    function handleSelectItems() {
        selectedItems = [];

        $('#returnItemsTableBody tr').each(function() {
            const productId = $(this).data('product-id'); // Actual product_id
            const invoiceItemId = $(this).data('invoice-item-id'); // Invoice item ID
            const qtyReturned = parseInt($(this).find('.qty-return').val()) || 0;
            const restock = $(this).find('.restock-checkbox').is(':checked');

            if (qtyReturned > 0) {
                selectedItems.push({
                    product_id: productId, // Send actual product_id
                    invoice_item_id: invoiceItemId, // Also send invoice_item_id for reference
                    qty_returned: qtyReturned,
                    stock_handling: restock ? 'restock' : 'write_off'
                });
            }
        });

        if (selectedItems.length === 0) {
            toastr.error('Please select at least one item to return');
            return;
        }

        goToNextStep();
    }

function handleResolution() {
    const reason = $('#returnReason').val().trim();
    
    if (!reason) {
        toastr.error('Please provide a reason for return');
        return;
    }

    populateSummaryStep();
    goToNextStep();
}

    function populateSummaryStep() {
        // Calculate totals
        let subtotal = 0;
        let vatAmount = 0;

        selectedItems.forEach(item => {
            // Find invoice item by invoice_item_id (not product_id)
            const invoiceItem = invoiceData.items.find(i => i.id == item.invoice_item_id);
            if (invoiceItem) {
                const lineTotal = invoiceItem.unit_price * item.qty_returned;
                subtotal += lineTotal;
                vatAmount += invoiceData.vat_enabled ? (lineTotal * invoiceData.vat_rate / 100) : 0;
            }
        });

    const handlingFee = parseFloat($('#handlingFee').val()) || 0;
    const grandTotal = subtotal + vatAmount - handlingFee;

    // Update summary
    $('#summaryInvoiceNumber').text(invoiceData.invoice_number);
    $('#summaryCustomerName').text(invoiceData.customer.name);
    $('#summaryRefundAmount').text('R' + grandTotal.toFixed(2));
    $('#summaryReason').text($('#returnReason').val());
    
    const resolutionText = selectedResolutionMethod === 'credit_note' 
        ? 'Issue Credit Note' 
        : 'Refund to Bank';
    $('#summaryResolution').text(resolutionText);
    
    if (invoiceData.customer.type === 'account' && $('#applyToAccount').is(':checked')) {
        $('#summaryResolution').append('<br><small class="text-muted">Credit will be applied to ' + invoiceData.customer.name + '\'s account.</small>');
    }

    $('#summaryItemsCount').text(selectedItems.length);

        // Populate items list
        let itemsHtml = '<ul class="list-group">';
        selectedItems.forEach(item => {
            // Find invoice item by invoice_item_id (not product_id)
            const invoiceItem = invoiceData.items.find(i => i.id == item.invoice_item_id);
            if (invoiceItem) {
                const stockHandlingText = item.stock_handling === 'restock' 
                    ? 'Will be restocked' 
                    : 'Will be written off';
                itemsHtml += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>${item.qty_returned} x ${invoiceItem.product_name} <small class="text-muted">(${stockHandlingText})</small></span>
                        <span class="badge bg-primary">R${(invoiceItem.unit_price * item.qty_returned).toFixed(2)}</span>
                    </li>
                `;
            }
        });
        itemsHtml += '</ul>';
        $('#summaryItemsList').html(itemsHtml);
    }

function handleConfirmReturn() {
    // Prepare data
    const data = {
        invoice_id: invoiceData.id,
        items: selectedItems,
        reason_for_return: $('#returnReason').val(),
        refund_method: selectedResolutionMethod,
        handling_fee: parseFloat($('#handlingFee').val()) || 0,
        apply_to_account: $('#applyToAccount').is(':checked')
    };

    // Show loading
    $('#nextButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    // Create credit note
    fetch('<?php echo e(route("credit-notes.store")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Credit note created successfully!');
            $('#processReturnModal').modal('hide');
            
            // Reload invoices table if exists
            if (typeof reloadInvoicesTable === 'function') {
                reloadInvoicesTable();
            }
            
            // Reset modal
            resetReturnModal();
        } else {
            toastr.error(data.message || 'Failed to create credit note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while processing the return');
    })
    .finally(() => {
        $('#nextButton').prop('disabled', false).html('Confirm & Process Return');
    });
}

function goToNextStep() {
    // Hide current step
    $(`#step-${currentStep}-content`).addClass('d-none');
    
    // Mark current step as completed
    $(`#step-${currentStep}-indicator`).removeClass('active').addClass('completed');
    $(`#step-${currentStep}-label`).removeClass('active');
    
    // Move to next step
    currentStep++;
    
    // Show next step
    $(`#step-${currentStep}-content`).removeClass('d-none');
    $(`#step-${currentStep}-indicator`).addClass('active');
    $(`#step-${currentStep}-label`).addClass('active');
    
    // Update buttons
    updateButtons();
}

function goToPreviousStep() {
    // Hide current step
    $(`#step-${currentStep}-content`).addClass('d-none');
    $(`#step-${currentStep}-indicator`).removeClass('active');
    $(`#step-${currentStep}-label`).removeClass('active');
    
    // Move to previous step
    currentStep--;
    
    // Show previous step
    $(`#step-${currentStep}-content`).removeClass('d-none');
    $(`#step-${currentStep}-indicator`).removeClass('completed').addClass('active');
    $(`#step-${currentStep}-label`).addClass('active');
    
    // Update buttons
    updateButtons();
}

function updateButtons() {
    const backButton = $('#backButton');
    const nextButton = $('#nextButton');
    
    // Show/hide back button
    if (currentStep > 1) {
        backButton.removeClass('d-none');
    } else {
        backButton.addClass('d-none');
    }
    
    // Update next button text
    if (currentStep === 1) {
        nextButton.text('Find Sale');
    } else if (currentStep === 2) {
        nextButton.text('Next: Resolution');
    } else if (currentStep === 3) {
        nextButton.text('Next: Summary');
    } else if (currentStep === 4) {
        nextButton.text('Confirm & Process Return');
    }
}

function resetReturnModal() {
    currentStep = 1;
    invoiceData = null;
    selectedItems = [];
    selectedResolutionMethod = 'credit_note';
    
    // Reset form
    $('#returnInvoiceNumber').val('');
    $('#returnReason').val('');
    $('#handlingFee').val('0');
    $('#applyToAccount').prop('checked', true);
    
    // Reset steps
    for (let i = 1; i <= 4; i++) {
        $(`#step-${i}-content`).addClass('d-none');
        $(`#step-${i}-indicator`).removeClass('active completed');
        $(`#step-${i}-label`).removeClass('active');
    }
    
    // Show step 1
    $('#step-1-content').removeClass('d-none');
    $('#step-1-indicator').addClass('active');
    $('#step-1-label').addClass('active');
    
    updateButtons();
}

    // Reset modal when closed
    $('#processReturnModal').on('hidden.bs.modal', function() {
        resetReturnModal();
    });
}); // End of $(document).ready()
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/credit-notes/partials/return_modal.blade.php ENDPATH**/ ?>