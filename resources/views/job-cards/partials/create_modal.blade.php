<div class="modal fade" id="createJobCardModal" tabindex="-1" 
     data-products='@json($products)' 
     data-technicians='@json($technicians)'>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createJobCardModalLabel">
                    <i class="bi bi-file-list-3-line me-2"></i>Create New Job Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createJobCardForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column - Customer & Vehicle Info -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Customer & Vehicle Information</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Customer Selection -->
                                    <div class="mb-3">
                                        <label for="customerSelect" class="form-label">Customer</label>
                                        <select class="form-select" id="customerSelect">
                                            <option value="">Select existing customer...</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" 
                                                    data-name="{{ $customer->name }}"
                                                    data-phone="{{ $customer->phone }}"
                                                    data-email="{{ $customer->email }}"
                                                    data-vehicle-make="{{ $customer->vehicle_make }}"
                                                    data-vehicle-model="{{ $customer->vehicle_model }}"
                                                    data-vehicle-vin="{{ $customer->vehicle_vin }}"
                                                    data-vehicle-reg="{{ $customer->vehicle_reg }}"
                                                    data-vehicle-mileage="{{ $customer->vehicle_mileage }}">
                                                {{ $customer->name }} - {{ $customer->phone }}
                                            </option>
                                            @endforeach
                                            <option value="new">+ New Customer</option>
                                        </select>
                                    </div>
                                    
                                    <!-- New Customer Fields -->
                                    <div id="newCustomerFields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customerName" class="form-label">Customer Name *</label>
                                                    <input type="text" class="form-control" id="customerName" name="customer_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="customerPhone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="customerPhone" name="customer_phone">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="customerEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="customerEmail" name="customer_email">
                                        </div>
                                    </div>
                                    
                                    <!-- Vehicle Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleMake" class="form-label">Vehicle Make</label>
                                                <input type="text" class="form-control" id="vehicleMake" name="vehicle_make">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleModel" class="form-label">Vehicle Model</label>
                                                <input type="text" class="form-control" id="vehicleModel" name="vehicle_model">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleYear" class="form-label">Year</label>
                                                <input type="number" class="form-control" id="vehicleYear" name="vehicle_year" min="1900" max="{{ date('Y') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleRegistration" class="form-label">Registration</label>
                                                <input type="text" class="form-control" id="vehicleRegistration" name="vehicle_registration">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleVIN" class="form-label">VIN</label>
                                                <input type="text" class="form-control" id="vehicleVIN" name="vehicle_vin">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicleMileage" class="form-label">Mileage</label>
                                                <input type="text" class="form-control" id="vehicleMileage" name="vehicle_mileage">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="engineCode" class="form-label">Engine Code</label>
                                        <input type="text" class="form-control" id="engineCode" name="engine_code">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Job Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Job Details</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Initial Status -->
                                    <div class="mb-3">
                                        <label for="initialStatus" class="form-label">Initial Status *</label>
                                        <select class="form-select" id="initialStatus" name="initial_status">
                                            <option value="pending">Pending (Not started)</option>
                                            <option value="booked">Booked In (Parts will be reserved)</option>
                                            <option value="in_progress">In Progress (Work started)</option>
                                        </select>
                                        <small class="text-muted">Select appropriate status. Parts will be reserved if Booked/In Progress.</small>
                                    </div>
                                
                                    <!-- Job Details -->
                                    <div class="mb-3">
                                        <label for="jobDescription" class="form-label">Job Description *</label>
                                        <textarea class="form-control" id="jobDescription" name="job_description" rows="3" required placeholder="Describe the work to be done..."></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="customerComplaint" class="form-label">Customer Complaint</label>
                                        <textarea class="form-control" id="customerComplaint" name="customer_complaint" rows="3" placeholder="What did the customer report?"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Internal Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Any additional notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Parts Section -->
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="ri-shopping-cart-line me-2"></i>Parts Used
                                <span class="badge bg-primary ms-2" id="partsCount">0</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addPartBtn">
                                <i class="bi bi-plus-circle me-1"></i>Add Part
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Product Search -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Quick Search Product</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="productSearch" placeholder="Search by name, SKU, or barcode...">
                                        <button class="btn btn-secondary" type="button" id="scanBarcodeBtn">
                                            <i class="bi bi-upc-scan"></i> Scan
                                        </button>
                                    </div>
                                    <div id="productSearchResults" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quick Add New Product</label>
                                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#quickAddProductJobCardModal">
                                        <i class="bi bi-lightning-fill me-1"></i>Quick Add
                                    </button>
                                </div>
                            </div>

                            <!-- Parts Table -->
                            <div class="table-responsive">
                                <table class="table table-sm" id="partsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">Product</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Unit Price</th>
                                            <th width="15%">Total</th>
                                            <th width="10%">Stock</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="partsTableBody">
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Parts Summary -->
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="alert alert-info mb-0">
                                        <small>
                                            <i class="ri-information-line me-1"></i>
                                            <strong>Tips:</strong> Use search to find products quickly, scan barcodes for instant addition, or quick add new products on the fly.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <strong>Parts Total: R<span id="partsTotal">0.00</span></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Labour Section -->
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="ri-tools-line me-2"></i>Labour
                                <span class="badge bg-success ms-2" id="labourCount">0</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-success" id="addLabourBtn">
                                <i class="bi bi-plus-circle me-1"></i>Add Labour
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Labour Table -->
                            <div class="table-responsive">
                                <table class="table table-sm" id="labourTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="30%">Description</th>
                                            <th width="20%">Type</th>
                                            <th width="15%">Hours</th>
                                            <th width="15%">Rate (R/hr)</th>
                                            <th width="15%">Total</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="labourTableBody">
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Labour Summary -->
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="alert alert-success mb-0">
                                        <small>
                                            <i class="ri-information-line me-1"></i>
                                            <strong>Labour Types:</strong> Diagnostic, Repair, Maintenance, Installation, Other
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <strong>Labour Total: R<span id="labourTotal">0.00</span></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <small>
                                    <i class="ri-information-line me-1"></i>
                                    Job card will be created in "Pending" status. You can change status later.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Job Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Add Product Modal for Job Card -->
<div class="modal fade" id="quickAddProductJobCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>Quick Add Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Creates product with auto SKU/Barcode. You can edit full details later.
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="quickProductName" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Selling Price (R) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="quickProductPrice" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity Needed</label>
                    <input type="number" step="0.001" class="form-control" id="quickProductQty" value="1">
                </div>

                <div class="alert alert-warning mb-0">
                    <strong>Auto-enabled:</strong> Allow Negative Sale, Special Order Only
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
            </button>
                <button type="button" class="btn btn-primary" onclick="submitQuickAddProduct()">
                    <i class="bi bi-check-circle me-1"></i>Create & Add to Job
            </button>
            </div>
        </div>
    </div>
</div>