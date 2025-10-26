<div class="modal fade" id="viewJobCardModal" tabindex="-1" aria-labelledby="viewJobCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewJobCardModalLabel">
                    <i class="ri-file-list-line me-2"></i>Job Card: {{ $jobCard->job_card_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left Column - Job Card Info -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Job Card Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Job Card #:</strong><br>
                                        <span class="text-primary">{{ $jobCard->job_card_number }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Status:</strong><br>
                                        <span class="badge bg-{{ $jobCard->status_badge }}">{{ $jobCard->status_text }}</span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Created:</strong><br>
                                        {{ $jobCard->created_at->format('M d, Y H:i A') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Created By:</strong><br>
                                        {{ $jobCard->createdBy->name ?? 'Unknown' }}
                                    </div>
                                </div>
                                
                                @if($jobCard->booked_at)
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Booked At:</strong><br>
                                        {{ $jobCard->booked_at->format('M d, Y H:i A') }}
                                    </div>
                                </div>
                                @endif
                                
                                @if($jobCard->started_at)
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Started At:</strong><br>
                                        {{ $jobCard->started_at->format('M d, Y H:i A') }}
                                    </div>
                                </div>
                                @endif
                                
                                @if($jobCard->completed_at)
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Completed At:</strong><br>
                                        {{ $jobCard->completed_at->format('M d, Y H:i A') }}
                                    </div>
                                </div>
                                @endif
                                
                                @if($jobCard->final_invoice_id)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <strong>Final Invoice:</strong><br>
                                        <a href="{{ route('invoices.index') }}?search={{ $jobCard->finalInvoice->invoice_number ?? '' }}" class="text-primary" target="_blank">
                                            {{ $jobCard->finalInvoice->invoice_number ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Customer Information (Enhanced) -->
                        <div class="card mt-3 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="ri-user-3-line me-2 text-success"></i>Customer Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-user-line text-primary me-1"></i>Name
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->customer_name ?? 'Walk-in Customer' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-phone-line text-success me-1"></i>Phone
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->customer_phone ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-mail-line text-info me-1"></i>Email
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->customer_email ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vehicle Information (Enhanced) -->
                        <div class="card mt-3 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="ri-car-line me-2 text-primary"></i>Vehicle Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-road-map-line text-danger me-1"></i>Registration
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_registration ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-car-line text-primary me-1"></i>Make
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_make ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-car-line text-success me-1"></i>Model
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_model ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-calendar-line text-info me-1"></i>Year
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_year ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-settings-3-line text-warning me-1"></i>Engine
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->engine_code ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-barcode-line text-secondary me-1"></i>VIN
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_vin ?? 'N/A' }}" disabled>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1 fw-semibold">
                                            <i class="ri-speed-line text-warning me-1"></i>Mileage
                                        </label>
                                        <input type="text" class="form-control form-control-sm shadow-sm" 
                                               value="{{ $jobCard->vehicle_mileage ? $jobCard->vehicle_mileage . ' km' : 'N/A' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Job Details -->
                    <div class="col-md-6">
                        <!-- Job Description -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Job Description</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jobCard->job_description }}</p>
                            </div>
                        </div>
                        
                        @if($jobCard->customer_complaint)
                        <!-- Customer Complaint -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Customer Complaint</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jobCard->customer_complaint }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($jobCard->notes)
                        <!-- Notes -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Notes</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $jobCard->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Parts Used (Enhanced) -->
                @if($jobCard->items->count() > 0)
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-shopping-cart-line me-2 text-primary"></i>Parts Used ({{ $jobCard->items->count() }} items)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="35%">Product</th>
                                        <th width="15%">Quantity</th>
                                        <th width="20%">Unit Price</th>
                                        <th width="20%" class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCard->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            <br><small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                            @if($item->notes)
                                                <br><small class="text-info">Note: {{ $item->notes }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity_used }}</td>
                                        <td>R{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end"><strong>R{{ number_format($item->line_total, 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4">Parts Total:</th>
                                        <th class="text-end">R{{ number_format($jobCard->parts_total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Labour (Simplified) -->
                @if($jobCard->labour->count() > 0)
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-semibold">
                            <i class="ri-hammer-line me-2 text-warning"></i>Labour ({{ $jobCard->labour->count() }} entries)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="70%">Description</th>
                                        <th width="25%" class="text-end">Price (R)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCard->labour as $index => $labour)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $labour->labour_description }}</strong>
                                            @if($labour->detailed_description)
                                                <br><small class="text-muted">{{ $labour->detailed_description }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end"><strong>R{{ number_format($labour->total_amount, 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">Labour Total:</th>
                                        <th class="text-end">R{{ number_format($jobCard->labour_total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Totals (Enhanced with VAT) -->
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted">Parts Total:</td>
                                            <td class="text-end">R{{ number_format($jobCard->parts_total, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Labour Total:</td>
                                            <td class="text-end">R{{ number_format($jobCard->labour_total, 2) }}</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-muted">Subtotal:</td>
                                            <td class="text-end">R{{ number_format($jobCard->parts_total + $jobCard->labour_total, 2) }}</td>
                                        </tr>
                                        @if($jobCard->vat_enabled)
                                        <tr>
                                            <td class="text-muted">VAT ({{ $jobCard->vat_rate ?? 15 }}%):</td>
                                            <td class="text-end">R{{ number_format($jobCard->vat_amount ?? 0, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="border-top border-2">
                                            <td class="fw-bold fs-5">Grand Total:</td>
                                            <td class="text-end fw-bold fs-5 text-success">R{{ number_format($jobCard->grand_total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info mb-0">
                                    <small>
                                        <i class="ri-information-line me-1"></i>
                                        <strong>Created:</strong> {{ $jobCard->created_at->format('M d, Y H:i A') }}
                                        @if($jobCard->updated_at != $jobCard->created_at)
                                            <br><strong>Updated:</strong> {{ $jobCard->updated_at->format('M d, Y H:i A') }}
                                        @endif
                                        <br><strong>Created By:</strong> {{ $jobCard->createdBy->name ?? 'System' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" onclick="downloadPDF({{ $jobCard->id }})">
                    <i class="ri-file-pdf-line me-1"></i>Download PDF
                </button>
                @if($jobCard->status !== 'completed' && $jobCard->status !== 'cancelled')
                <button type="button" class="btn btn-success" onclick="editJobCard({{ $jobCard->id }})">
                    <i class="ri-edit-line me-1"></i>Edit
                </button>
                @endif
                @if($jobCard->status === 'completed' && !$jobCard->final_invoice_id)
                <button type="button" class="btn btn-primary" onclick="showConvertToInvoiceModal({{ $jobCard->id }})">
                    <i class="ri-file-add-line me-1"></i>Convert to Invoice
                </button>
                @endif
                @if($jobCard->final_invoice_id)
                <a href="{{ route('invoices.show', $jobCard->final_invoice_id) }}" class="btn btn-success">
                    <i class="ri-file-list-3-line me-1"></i>View Invoice
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Convert to Invoice Payment Modal -->
<div class="modal fade" id="convertToInvoiceModal" tabindex="-1" aria-labelledby="convertToInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="convertToInvoiceModalLabel">
                    <i class="ri-money-dollar-circle-line me-2"></i>Convert to Invoice - Payment Options
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="customerTypeAlert"></div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Payment Method</label>
                    <select class="form-select" id="invoicePaymentMethod" onchange="updateInvoicePaymentFields()">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="eft">EFT</option>
                        <option value="credit" id="invoiceOnAccountOption" style="display: none;">Credit</option>
                    </select>
                </div>

                <div class="mb-3" id="invoiceAmountPaidRow">
                    <label class="form-label fw-bold">Amount Paid <span class="text-danger">*</span></label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">R</span>
                        <input type="number" class="form-control" id="invoiceAmountPaid" step="0.01" min="0" value="0">
                    </div>
                    <div class="text-danger mt-1" id="cashCustomerWarning" style="display: none;">
                        <i class="ri-alert-line me-1"></i>Cash customers must pay in full.
                    </div>
                </div>

                <div class="mb-3" id="invoicePaymentReferenceRow">
                    <label class="form-label">Payment Reference (Optional)</label>
                    <input type="text" class="form-control" id="invoicePaymentReference" placeholder="Transaction reference, receipt number, etc.">
                </div>

                <div class="alert alert-info mb-0" id="invoiceChangeRow">
                    <div class="d-flex justify-content-between">
                        <span>Change:</span>
                        <span class="fw-bold" id="invoiceChangeAmount">R 0.00</span>
                    </div>
                </div>
                
                <div class="mt-3 p-3 border rounded" style="background: #f8f9fa;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Invoice Total:</span>
                        <span class="fw-bold text-primary" id="invoiceTotalDisplay">R 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Balance Due:</span>
                        <span class="fw-bold text-danger" id="balanceDueDisplay">R 0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmConvertToInvoice()">
                    <i class="ri-refresh-line me-1"></i>Convert to Invoice
                </button>
            </div>
        </div>
    </div>
</div>
