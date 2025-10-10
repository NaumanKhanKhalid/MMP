<div class="modal fade" id="editJobCardModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Edit Job Card - {{ $jobCard->job_card_number }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editJobCardForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Editing existing job card. Status: <strong>{{ $jobCard->status_text }}</strong>
                    </div>

                    <div class="row">
                        <!-- Customer & Vehicle -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Customer & Vehicle</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name *</label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ $jobCard->customer_name }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" class="form-control" name="customer_phone" value="{{ $jobCard->customer_phone }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="customer_email" value="{{ $jobCard->customer_email }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Vehicle Make</label>
                                                <input type="text" class="form-control" name="vehicle_make" value="{{ $jobCard->vehicle_make }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Vehicle Model</label>
                                                <input type="text" class="form-control" name="vehicle_model" value="{{ $jobCard->vehicle_model }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Registration</label>
                                                <input type="text" class="form-control" name="vehicle_registration" value="{{ $jobCard->vehicle_registration }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">VIN</label>
                                                <input type="text" class="form-control" name="vehicle_vin" value="{{ $jobCard->vehicle_vin }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mileage</label>
                                                <input type="text" class="form-control" name="vehicle_mileage" value="{{ $jobCard->vehicle_mileage }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Engine Code</label>
                                                <input type="text" class="form-control" name="engine_code" value="{{ $jobCard->engine_code }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Job Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Job Description *</label>
                                        <textarea class="form-control" name="job_description" rows="3" required>{{ $jobCard->job_description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Customer Complaint</label>
                                        <textarea class="form-control" name="customer_complaint" rows="3">{{ $jobCard->customer_complaint }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="2">{{ $jobCard->notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Parts -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Parts Used
                                <span class="badge bg-primary ms-2">{{ $jobCard->items->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($jobCard->items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jobCard->items as $item)
                                        <tr>
                                            <td><strong>{{ $item->product_name }}</strong></td>
                                            <td>{{ $item->product_sku }}</td>
                                            <td>{{ $item->quantity_used }}</td>
                                            <td>R {{ number_format($item->unit_price, 2) }}</td>
                                            <td><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-muted small mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Parts cannot be edited after creation. Create a new job card if needed.
                            </p>
                            @else
                            <p class="text-muted">No parts added yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Existing Labour -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-wrench me-2"></i>Labour
                                <span class="badge bg-success ms-2">{{ $jobCard->labour->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($jobCard->labour->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Hours</th>
                                            <th>Rate</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jobCard->labour as $labour)
                                        <tr>
                                            <td><strong>{{ $labour->labour_description }}</strong></td>
                                            <td><span class="badge bg-{{ $labour->labour_type_badge }}">{{ $labour->labour_type_text }}</span></td>
                                            <td>{{ $labour->hours_worked }}h</td>
                                            <td>R {{ number_format($labour->hourly_rate, 2) }}/hr</td>
                                            <td><strong>R {{ number_format($labour->total_amount, 2) }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-muted small mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Labour cannot be edited after creation. Create a new job card if needed.
                            </p>
                            @else
                            <p class="text-muted">No labour added yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Job Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


