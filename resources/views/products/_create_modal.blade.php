        <div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-plus-circle me-2"></i>Add New Product
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Tab Navigation --}}
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#create-basic">Basic Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#create-pricing">Pricing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#create-inventory">Inventory</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#create-fitments">Fitments</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- Basic Info Tab --}}
                                <div class="tab-pane fade show active" id="create-basic">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">SKU (Auto-generated)</label>
                                            <input type="text" class="form-control bg-light"
                                                placeholder="Auto-generated" disabled>
                                            <small class="text-muted">Will be auto-generated as 0001, 0002, etc.</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Primary Barcode (Auto)</label>
                                            <input type="text" class="form-control bg-light"
                                                placeholder="Auto-generated (MMP-0001)" disabled>
                                            <small class="text-muted">Auto-generated as MMP-XXXX</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alternate Barcode (Optional)</label>
                                            <input type="text" name="barcode_alternate" class="form-control"
                                                placeholder="Manual barcode entry">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Product Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <select name="brand_id" class="form-select select2-create-brand" required>
                                                <option value="">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Category <span
                                                    class="text-danger">*</span></label>
                                            <select name="category_id" class="form-select select2-create-category"
                                                required>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Subcategory</label>
                                            <select name="subcategory_id"
                                                class="form-select select2-create-subcategory">
                                                <option value="">Select Subcategory</option>
                                                @foreach ($subCategories as $sub)
                                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Suppliers (Multiple)</label>
                                            <select name="supplier_ids[]" class="form-select select2-create-suppliers"
                                                multiple>
                                                @foreach ($suppliers as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Select one or more suppliers</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pricing Tab --}}
                                <div class="tab-pane fade" id="create-pricing">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Normal Price (R)</label>
                                            <input type="number" step="0.01" name="price_normal"
                                                class="form-control" placeholder="0.00">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Online Price (R)</label>
                                            <input type="number" step="0.01" name="price_online"
                                                class="form-control" placeholder="0.00">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Workshop Price (R)</label>
                                            <input type="number" step="0.01" name="price_workshop"
                                                class="form-control" placeholder="0.00">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>Pricing Tiers:</strong> Normal = Retail, Online = E-commerce,
                                                Workshop = Trade discount
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Inventory Tab --}}
                                <div class="tab-pane fade" id="create-inventory">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Unit</label>
                                            <select name="unit" class="form-select">
                                                <option value="PCS" selected>PCS (Pieces)</option>
                                                <option value="SET">SET</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Bin Location (e.g., A-16)</label>
                                            <input type="text" name="bin_location" class="form-control"
                                                placeholder="A-16">
                                            <small class="text-muted">Format: Letter-Number (e.g., A-16, B-05)</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Reorder Level</label>
                                            <input type="number" name="reorder_level" class="form-control"
                                                placeholder="0" min="0">
                                            <small class="text-muted">Alert when stock falls below this level</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="active" selected>Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="allow_negative"
                                                    id="create_allow_negative" checked>
                                                <label class="form-check-label" for="create_allow_negative">
                                                    <strong>Allow Negative Sale</strong>
                                                    <small class="d-block text-muted">Permit sales even when stock is
                                                        zero or negative</small>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="special_order"
                                                    id="create_special_order">
                                                <label class="form-check-label" for="create_special_order">
                                                    <strong>Special Order Only</strong>
                                                    <small class="d-block text-muted">Mark this product as special
                                                        order item</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">OE Numbers</label>
                                            <input type="text" name="oe_numbers" class="form-control"
                                                id="create_oe_numbers" placeholder="Press Enter to add">
                                            <small class="text-muted">Original Equipment numbers - type and press
                                                Enter</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Cross References</label>
                                            <input type="text" name="cross_refs" class="form-control"
                                                id="create_cross_refs" placeholder="Press Enter to add">
                                            <small class="text-muted">Alternative part numbers - type and press
                                                Enter</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Product Images (Max 3)</label>
                                            <input type="file" name="images[]" class="form-control" multiple
                                                accept="image/*">
                                            <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP (Max 2MB
                                                each)</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Fitments Tab --}}
                                <div class="tab-pane fade" id="create-fitments">
                                    <div class="alert alert-info">
                                        <i class="bi bi-car-front me-2"></i>
                                        <strong>Vehicle Fitment:</strong> Specify which vehicles this part fits
                                    </div>

                                    <div id="create-fitments-container"></div>

                                    <button type="button" class="btn btn-sm btn-secondary" id="createAddFitmentBtn">
                                        <i class="bi bi-plus"></i> Add Fitment
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Create Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Fitment Row Template --}}
        <template id="createFitmentRowTemplate">
            <div class="row mb-2 fitment-row border p-2 rounded bg-light">
                <div class="col-md-3">
                    <select name="fitments[__INDEX__][make_id]"
                        class="form-select form-select-sm select2-fitment-make">
                        <option value="">Select Make</option>
                        @foreach ($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="fitments[__INDEX__][model_id]"
                        class="form-select form-select-sm select2-fitment-model">
                        <option value="">Select Model</option>
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="fitments[__INDEX__][engine_id]"
                        class="form-select form-select-sm select2-fitment-engine">
                        <option value="">Engine (Opt)</option>
                        @foreach ($engines as $engine)
                            <option value="{{ $engine->id }}">{{ $engine->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="fitments[__INDEX__][year_start]" class="form-control form-control-sm"
                        placeholder="Start" min="1900" max="2100">
                </div>
                <div class="col-md-1">
                    <input type="number" name="fitments[__INDEX__][year_end]" class="form-control form-control-sm"
                        placeholder="End" min="1900" max="2100">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 removeFitmentBtn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </template>

        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" />
        @endpush

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let createFitmentIndex = 0;
                    let createOeTagify, createCrossTagify;

                    // Initialize Select2 for create modal
                    function initCreateSelect2() {
                        $('.select2-create-brand').select2({
                            dropdownParent: $('#createProductModal'),
                            placeholder: 'Select Brand',
                            allowClear: true,
                            width: '100%'
                        });

                        $('.select2-create-category').select2({
                            dropdownParent: $('#createProductModal'),
                            placeholder: 'Select Category',
                            allowClear: true,
                            width: '100%'
                        });

                        $('.select2-create-subcategory').select2({
                            dropdownParent: $('#createProductModal'),
                            placeholder: 'Select Subcategory',
                            allowClear: true,
                            width: '100%'
                        });

                        $('.select2-create-suppliers').select2({
                            dropdownParent: $('#createProductModal'),
                            placeholder: 'Select Suppliers',
                            allowClear: true,
                            width: '100%'
                        });
                    }

                    // Initialize Tagify for OE Numbers and Cross Refs
                    function initCreateTagify() {
                        const oeInput = document.querySelector('#create_oe_numbers');
                        const crossInput = document.querySelector('#create_cross_refs');

                        if (oeInput) {
                            createOeTagify = new Tagify(oeInput, {
                                delimiters: ",| ",
                                placeholder: "Type and press Enter",
                                dropdown: {
                                    enabled: 0
                                }
                            });
                        }

                        if (crossInput) {
                            createCrossTagify = new Tagify(crossInput, {
                                delimiters: ",| ",
                                placeholder: "Type and press Enter",
                                dropdown: {
                                    enabled: 0
                                }
                            });
                        }
                    }

                    // Add Fitment Row
                    document.getElementById('createAddFitmentBtn').addEventListener('click', function() {
                        const template = document.getElementById('createFitmentRowTemplate').innerHTML;
                        const html = template.replace(/__INDEX__/g, createFitmentIndex);
                        document.getElementById('create-fitments-container').insertAdjacentHTML('beforeend', html);

                        // Initialize Select2 for newly added fitment selects
                        $(`.select2-fitment-make, .select2-fitment-model, .select2-fitment-engine`).each(
                    function() {
                            if (!$(this).hasClass('select2-hidden-accessible')) {
                                $(this).select2({
                                    dropdownParent: $('#createProductModal'),
                                    width: '100%',
                                    placeholder: $(this).find('option:first').text()
                                });
                            }
                        });

                        createFitmentIndex++;
                    });

                    // Remove Fitment Row
                    document.addEventListener('click', function(e) {
                        if (e.target.closest('.removeFitmentBtn')) {
                            e.target.closest('.fitment-row').remove();
                        }
                    });

                    // Modal shown event
                    $('#createProductModal').on('shown.bs.modal', function() {
                        initCreateSelect2();
                        initCreateTagify();
                    });

                    // Reset form when modal is closed
                    $('#createProductModal').on('hidden.bs.modal', function() {
                        $(this).find('form')[0].reset();

                        // Destroy Select2
                        $('.select2-create-brand, .select2-create-category, .select2-create-subcategory, .select2-create-suppliers')
                            .select2('destroy');

                        // Destroy Tagify
                        if (createOeTagify) createOeTagify.destroy();
                        if (createCrossTagify) createCrossTagify.destroy();

                        // Clear fitments
                        document.getElementById('create-fitments-container').innerHTML = '';
                        createFitmentIndex = 0;

                        // Reset to first tab
                        $('.nav-tabs a:first').tab('show');
                    });

                    // Bin location validation
                    const binInput = document.querySelector('input[name="bin_location"]');
                    if (binInput) {
                        binInput.addEventListener('blur', function() {
                            const value = this.value.trim().toUpperCase();
                            if (value && !/^[A-Z]-\d+$/.test(value)) {
                                this.classList.add('is-invalid');
                                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains(
                                        'invalid-feedback')) {
                                    const feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    feedback.textContent = 'Format should be: Letter-Number (e.g., A-16)';
                                    this.parentNode.appendChild(feedback);
                                }
                            } else {
                                this.classList.remove('is-invalid');
                                this.value = value;
                            }
                        });
                    }
                });
            </script>
        @endpush
