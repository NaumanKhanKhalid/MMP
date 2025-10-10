<div class="modal fade" id="createReturnModal" tabindex="-1" aria-labelledby="createReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createReturnModalLabel">
                    <i class="ri-add-line me-2"></i>Create New Return
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createReturnForm">
                    <div class="row">
                        <!-- Left Column - Invoice Selection -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Select Invoice</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="invoiceSelect" class="form-label">Invoice *</label>
                                        <select class="form-select" id="invoiceSelect" name="invoice_id" required>
                                            <option value="">Select an invoice...</option>
                                            @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}" 
                                                    data-customer="{{ $invoice->customer ? $invoice->customer->name : 'Walk-in Customer' }}"
                                                    data-total="{{ $invoice->grand_total }}">
                                                {{ $invoice->invoice_number }} - {{ $invoice->customer ? $invoice->customer->name : 'Walk-in Customer' }} - R {{ number_format($invoice->grand_total, 2) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div id="invoiceDetails" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6>Invoice Details</h6>
                                            <div id="invoiceInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Return Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Return Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="returnType" class="form-label">Return Type *</label>
                                                <select class="form-select" id="returnType" name="return_type" required>
                                                    <option value="">Select type...</option>
                                                    <option value="partial">Partial Return</option>
                                                    <option value="full">Full Return</option>
                                                    <option value="exchange">Exchange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="refundMethod" class="form-label">Refund Method *</label>
                                                <select class="form-select" id="refundMethod" name="refund_method" required>
                                                    <option value="">Select method...</option>
                                                    <option value="store_credit">Store Credit</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="exchange">Exchange</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="returnReason" class="form-label">Return Reason *</label>
                                        <textarea class="form-control" id="returnReason" name="reason" rows="3" required placeholder="Enter reason for return (e.g., defective, wrong item, customer changed mind)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="card mt-4" id="itemsCard" style="display: none;">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-0">Select Items to Return</h6>
                                    <small class="text-muted">
                                        Select items and quantities to return. Items will be restocked if enabled.
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h6>Total Return Amount: <span id="totalReturnAmount" class="text-primary">R 0.00</span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th class="text-center">Original Qty</th>
                                            <th class="text-center">Return Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th>Condition</th>
                                        </tr>
                                    </thead>
                    <tbody id="itemsTableBody">
                        <!-- Items will be populated here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Stock Handling Options -->
            <div class="alert alert-info mt-3">
                <h6><i class="ri-information-line me-2"></i>Stock Handling Options</h6>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stockHandling" id="stockRestock" value="restock" checked>
                    <label class="form-check-label" for="stockRestock">
                        <strong>Restock</strong> - Return items to inventory (restores to original batches - FIFO)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stockHandling" id="stockWriteoff" value="writeoff">
                    <label class="form-check-label" for="stockWriteoff">
                        <strong>Write-off</strong> - Items damaged/defective, no restock
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stockHandling" id="stockCreditOnly" value="credit_only">
                    <label class="form-check-label" for="stockCreditOnly">
                        <strong>Credit Only</strong> - Issue credit note without stock adjustment
                    </label>
                </div>
            </div>
        </div>
    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="submitReturnBtn" disabled>
                    <i class="bi bi-check-circle me-1"></i>Create Return
                </button>
            </div>
        </div>
    </div>
</div>
