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
                                        <a href="{{ route('invoices.show', $jobCard->final_invoice_id) }}" class="text-primary">
                                            {{ $jobCard->finalInvoice->invoice_number ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Customer Information -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Customer Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Name:</strong><br>
                                        {{ $jobCard->customer_name }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Phone:</strong><br>
                                        {{ $jobCard->customer_phone ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                @if($jobCard->customer_email)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <strong>Email:</strong><br>
                                        {{ $jobCard->customer_email }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Vehicle Information -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Vehicle Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Make/Model:</strong><br>
                                        {{ $jobCard->vehicle_make }} {{ $jobCard->vehicle_model }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Year:</strong><br>
                                        {{ $jobCard->vehicle_year ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Registration:</strong><br>
                                        {{ $jobCard->vehicle_registration ?? 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>VIN:</strong><br>
                                        {{ $jobCard->vehicle_vin ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Mileage:</strong><br>
                                        {{ $jobCard->vehicle_mileage ?? 'N/A' }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Engine Code:</strong><br>
                                        {{ $jobCard->engine_code ?? 'N/A' }}
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

                <!-- Parts Used -->
                @if($jobCard->items->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Parts Used ({{ $jobCard->items->count() }} items)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCard->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->notes)
                                                <br><small class="text-muted">{{ $item->notes }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->product_sku }}</td>
                                        <td>{{ $item->quantity_used }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td><strong>${{ number_format($item->line_total, 2) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $item->status_badge }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Parts Total:</th>
                                        <th>${{ number_format($jobCard->parts_total, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Labour -->
                @if($jobCard->labour->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Labour ({{ $jobCard->labour->count() }} entries)</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Hours</th>
                                        <th>Rate</th>
                                        <th>Total</th>
                                        <th>Technician</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobCard->labour as $labour)
                                    <tr>
                                        <td>
                                            <strong>{{ $labour->labour_description }}</strong>
                                            @if($labour->detailed_description)
                                                <br><small class="text-muted">{{ $labour->detailed_description }}</small>
                                            @endif
                                            @if($labour->notes)
                                                <br><small class="text-info">{{ $labour->notes }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $labour->labour_type_badge }}">
                                                {{ $labour->labour_type_text }}
                                            </span>
                                        </td>
                                        <td>{{ $labour->hours_worked }}</td>
                                        <td>${{ number_format($labour->hourly_rate, 2) }}</td>
                                        <td><strong>${{ number_format($labour->total_amount, 2) }}</strong></td>
                                        <td>{{ $labour->technician_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $labour->status_badge }}">
                                                {{ $labour->status_text }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Labour Total:</th>
                                        <th>${{ number_format($jobCard->labour_total, 2) }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Totals -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <small>
                                        <i class="ri-information-line me-1"></i>
                                        Job card created on {{ $jobCard->created_at->format('M d, Y H:i A') }}
                                        @if($jobCard->updated_at != $jobCard->created_at)
                                            and last updated on {{ $jobCard->updated_at->format('M d, Y H:i A') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <h6>Parts Total: <span class="text-primary">${{ number_format($jobCard->parts_total, 2) }}</span></h6>
                                <h6>Labour Total: <span class="text-primary">${{ number_format($jobCard->labour_total, 2) }}</span></h6>
                                <h4>Grand Total: <span class="text-success">${{ number_format($jobCard->grand_total, 2) }}</span></h4>
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
            </div>
        </div>
    </div>
</div>
