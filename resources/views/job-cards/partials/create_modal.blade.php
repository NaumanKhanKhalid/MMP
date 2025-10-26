<div class="modal fade" id="createJobCardModal" tabindex="-1" data-products='@json($products)'
     data-technicians='@json($technicians)'>
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95%;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createJobCardModalLabel">
                    <i class="ri-file-list-3-line me-2"></i>Create New Job Card
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createJobCardForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- LEFT: Products & Items (POS Style) -->
                        <div class="col-md-7 order-md-1">
                            <!-- Enhanced Parts Section -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-shopping-cart-line me-2"></i>Parts Used
                                <span class="badge bg-primary-transparent text-primary ms-2" id="partsCount">0</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Product Search -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Search & Add Product</label>
                                        <input type="text" class="form-control" id="productSearch"
                                            placeholder="Type product name, SKU, or scan barcode...">
                                    <div id="productSearchResults" class="list-group mt-2"
                                        style="display: none; max-height: 200px; overflow-y: auto;"></div>
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
                        </div>
                    </div>

                    <!-- Enhanced Labour Section -->
                    <div class="card mt-3 border-0 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-hammer-line me-2"></i>Labour
                                <span class="badge bg-success-transparent text-success ms-2" id="labourCount">0</span>
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
                                                    <th width="60%">Description</th>
                                                    <th width="25%">Price (R)</th>
                                                    <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="labourTableBody">
                                        <!-- Dynamic rows will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="mb-3 fw-semibold">
                                <i class="ri-calculator-line me-2"></i>Job Summary
                            </h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Parts Total:</td>
                                        <td class="text-end">R<span id="partsTotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Labour Total:</td>
                                        <td class="text-end">R<span id="labourTotal">0.00</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="text-muted">Subtotal:</td>
                                        <td class="text-end">R<span id="subtotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">VAT ({{ $vatEnabled ? $vatRate : 0 }}%):</td>
                                        <td class="text-end">R<span id="vatAmount">0.00</span></td>
                                    </tr>
                                    <tr class="border-top border-2">
                                        <td class="fw-bold">Grand Total:</td>
                                        <td class="text-end fw-bold">R<span id="grandTotal">0.00</span></td>
                                    </tr>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                        <!-- END of LEFT column (col-md-7) -->

                <!-- RIGHT: Customer & Vehicle (POS Style) -->
                <div class="col-md-5 order-md-2">
                            <!-- Customer & Vehicle Info -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="ri-user-3-line me-2"></i>Customer & Vehicle
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Customer Search -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold mb-1">
                                            <i class="ri-search-line me-1 text-primary"></i>Customer
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control shadow-sm" id="customerSearch" 
                                                   placeholder="Search by name, phone, email...">
                                            <button class="btn btn-success" type="button" onclick="window.openJobCardAddCustomerModal()" 
                                                    title="Add New Customer">
                                                <i class="ri-user-add-line"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Leave empty for Walk-in Customer</small>
                                        <div id="customerSearchResults" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                    </div>

                                    <!-- Selected Customer Info Card -->
                                    <div id="selectedCustomerCard" class="card bg-light mb-3" style="display: none;">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" id="selectedCustomerName">Customer Name</h6>
                                                    <small class="text-muted" id="selectedCustomerDetails">Phone & Email</small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelectedCustomer()">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Walk-in Customer Form -->
                                    <div id="walkInCustomerForm" class="mb-3" style="display: block;">
                                        <div class="alert alert-info py-2 mb-2">
                                            <i class="ri-walk-line me-1"></i><strong>Walk-in Customer</strong>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small mb-1">
                                                <i class="ri-user-line me-1 text-primary"></i>Name <small class="text-muted">(Optional)</small>
                                            </label>
                                            <input type="text" class="form-control form-control-sm" id="walkInName" name="customer_name" placeholder="Enter name...">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small mb-1">
                                                <i class="ri-phone-line me-1 text-success"></i>Phone <small class="text-muted">(Required if no email)</small>
                                            </label>
                                            <input type="text" class="form-control form-control-sm" id="walkInPhone" name="customer_phone" placeholder="Enter phone...">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label small mb-1">
                                                <i class="ri-mail-line me-1 text-info"></i>Email <small class="text-muted">(Required if no phone)</small>
                                            </label>
                                            <input type="email" class="form-control form-control-sm" id="walkInEmail" name="customer_email" placeholder="Enter email...">
                                        </div>
                                        
                                        <div class="alert alert-warning py-2 mb-0">
                                            <small><i class="ri-alert-line me-1"></i>Please provide either email or phone number</small>
                                        </div>
                                    </div>

                                    <!-- Vehicle Info (Optional) -->
                                    <div class="mb-3">
                                        <h6 class="mb-2">
                                            <i class="ri-car-line me-1 text-primary"></i>Vehicle Info <small class="text-muted">(Optional)</small>
                                        </h6>
                                        
                                        <!-- For Selected Customer: Show Dropdown -->
                                        <div id="vehicleSelectSection" style="display: none;">
                                            <div class="d-flex gap-2 mb-2">
                                                <select class="form-select form-select-sm" id="vehicleSelect" onchange="selectJobCardVehicle()">
                                                    <option value="">Select Vehicle...</option>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAddVehicleModal()">
                                                    <i class="ri-add-line"></i>Add
                                                </button>
                                            </div>
                                            
                                            <!-- Selected Vehicle Info Display -->
                                            <div id="vehicleInfo" style="display: none;">
                                                <!-- Vehicle details will be populated here -->
                                            </div>
                                        </div>
                                        
                                        <!-- Vehicle Input Fields (Walk-in or Manual Entry) -->
                                        <div id="vehicleManualEntry">
                                            <div class="mb-2">
                                                <label class="form-label small mb-1">Registration</label>
                                                <input type="text" class="form-control form-control-sm" id="vehicleRegistration" name="vehicle_registration" placeholder="e.g., ABC123GP">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small mb-1">Make</label>
                                                    <input type="text" class="form-control form-control-sm" id="vehicleMake" name="vehicle_make" placeholder="Select Make">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small mb-1">Model</label>
                                                    <input type="text" class="form-control form-control-sm" id="vehicleModel" name="vehicle_model" placeholder="Select Model">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small mb-1">Year</label>
                                                    <input type="text" class="form-control form-control-sm" id="vehicleYear" name="vehicle_year" placeholder="Select Year">
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label small mb-1">Engine</label>
                                                    <input type="text" class="form-control form-control-sm" id="vehicleEngine" name="engine_code" placeholder="e.g., 2.0L Turbo">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label small mb-1">Mileage</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm" id="vehicleMileage" name="vehicle_mileage">
                                                        <span class="input-group-text">km</span>
                                                    </div>
                        </div>
                    </div>

                                            <div class="mb-2">
                                                <label class="form-label small mb-1">VIN <small class="text-muted">(Optional)</small></label>
                                                <input type="text" class="form-control form-control-sm" id="vehicleVIN" name="vehicle_vin" placeholder="Optional">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="ri-tools-line me-2"></i>Job Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Initial Status -->
                                    <div class="mb-3">
                                        <label for="initialStatus" class="form-label small fw-bold">Initial Status *</label>
                                        <select class="form-select form-select-sm shadow-sm" id="initialStatus" name="initial_status">
                                            <option value="pending">Pending (Not started)</option>
                                            <option value="booked">Booked In (Parts reserved)</option>
                                            <option value="in_progress">In Progress (Work started)</option>
                                        </select>
                                        <small class="text-muted">Parts will be reserved if Booked/In Progress.</small>
                    </div>

                                    <!-- Job Details -->
                                    <div class="mb-3">
                                        <label for="jobDescription" class="form-label small fw-bold">Job Description *</label>
                                        <textarea class="form-control form-control-sm shadow-sm" id="jobDescription" name="job_description" rows="3" required
                                            placeholder="Describe the work to be done..."></textarea>
                    </div>

                                    <div class="mb-3">
                                        <label for="customerComplaint" class="form-label small fw-bold">Customer Complaint</label>
                                        <textarea class="form-control form-control-sm shadow-sm" id="customerComplaint" name="customer_complaint" rows="2"
                                            placeholder="What did the customer report?"></textarea>
                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label small fw-bold">Internal Notes</label>
                                        <textarea class="form-control form-control-sm shadow-sm" id="notes" name="notes" rows="2" placeholder="Any additional notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-check-line me-1"></i>Create Job Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-car-line me-2"></i>Add Vehicle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-car-line me-1 text-primary"></i>Make <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm shadow-sm" id="addVehicleMake" onchange="loadVehicleModels()">
                            <option value="">Select Make...</option>
                            @foreach(\App\Models\CarMake::orderBy('name')->get() as $make)
                                <option value="{{ $make->id }}">{{ $make->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-car-line me-1 text-success"></i>Model <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm shadow-sm" id="addVehicleModel">
                            <option value="">Select Model...</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-calendar-line me-1 text-info"></i>Year <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm shadow-sm" id="addVehicleYear">
                            <option value="">Select Year...</option>
                            @for($year = date('Y') + 1; $year >= 1980; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-settings-3-line me-1 text-warning"></i>Engine
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" id="addVehicleEngine" placeholder="e.g., 2.0L Turbo">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-file-text-line me-1 text-secondary"></i>Registration
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" id="addVehicleRegistration" placeholder="Enter registration...">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-barcode-line me-1 text-primary"></i>VIN
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" id="addVehicleVIN" placeholder="Enter VIN...">
                </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1">
                            <i class="ri-speed-line me-1 text-success"></i>Mileage
                        </label>
                        <input type="text" class="form-control form-control-sm shadow-sm" id="addVehicleMileage" placeholder="Enter mileage...">
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
            </button>
                <button type="button" class="btn btn-primary" onclick="addVehicleToJobCard()">
                    <i class="ri-add-line me-1"></i>Add Vehicle
            </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Add Modal Removed - Only search and select existing products -->

<!-- Add New Customer Modal (Quick Add Style - Same as Quotation) -->
<div class="modal fade" id="addJobCardCustomerModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="true"
    style="z-index: 1065;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success" style="border-width: 3px;">
            <form id="addJobCardCustomerForm">
                @csrf
                <div class="modal-header bg-success-transparent">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line text-success me-2"></i>⚡ Quick Add Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" id="jobCardCustomerName" name="name"
                                class="form-control form-control-lg" placeholder="Enter full name" required autofocus>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" id="jobCardCustomerEmail" name="email"
                                class="form-control form-control-lg" placeholder="customer@example.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" id="jobCardCustomerPhone" name="phone" class="form-control"
                                placeholder="123-456-7890">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price Tier</label>
                            <select name="price_tier" class="form-select">
                                <option value="normal">Normal (Retail)</option>
                                <option value="workshop">Workshop</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-user-add-line me-1"></i>Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


