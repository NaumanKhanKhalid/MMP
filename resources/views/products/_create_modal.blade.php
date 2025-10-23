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

                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                            {{-- Section 1: Basic Information --}}
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Basic Information
                                </h6>
                                    <div class="row">
                                    <div class="col-md-4 mb-3">
                                            <label class="form-label">Product Name <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required
                                            placeholder="Product Name">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_ids" class="form-select select2-create-suppliers">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Supplier Code <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="supplier_code" class="form-control" required
                                            placeholder="Supplier Code">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="brand_id" class="form-select select2-create-brand" required>
                                                    <option value="">Select Brand</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                                                    <i class="ri-add-line"></i>
                                                </button> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="category_id" class="form-select select2-create-category" required>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            {{-- <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                                <i class="ri-add-line"></i>
                                            </button> --}}
                                        </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Subcategory</label>
                                            <div class="input-group">
                                                <select name="subcategory_id" class="form-select select2-create-subcategory">
                                                    <option value="">Select Subcategory</option>
                                                    @foreach ($subCategories as $sub)
                                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#createSubcategoryModal">
                                                    <i class="ri-add-line"></i>
                                                </button> --}}
                                            </div>
                                        </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Alternate Barcode (Optional)</label>
                                        <input type="text" name="barcode_alternate" class="form-control"
                                            placeholder="Manual barcode entry">
                                        </div>
                                    </div>
                                </div>

                            {{-- Section 2: Pricing --}}
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-tag me-2"></i>Pricing
                                </h6>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Normal Price (R)</label>
                                        <input type="number" step="0.01" name="price_normal" class="form-control"
                                            placeholder="0.00">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Online Price (R)</label>
                                        <input type="number" step="0.01" name="price_online" class="form-control"
                                            placeholder="0.00">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Workshop Price (R)</label>
                                        <input type="number" step="0.01" name="price_workshop" class="form-control"
                                            placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                            {{-- Section 3: Inventory Settings --}}
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-boxes me-2"></i>Inventory Settings
                                </h6>
                                    <div class="row">
                                    <div class="col-md-4 mb-3">
                                            <label class="form-label">Unit</label>
                                            <select name="unit" class="form-select">
                                            <option value="PCS" selected>PCS</option>
                                                <option value="SET">SET</option>
                                            </select>
                                        </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Bin Location</label>
                                            <input type="text" name="bin_location" class="form-control"
                                                placeholder="A-16">
                                        </div>
                                    <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="active" selected>Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Reorder Point</label>
                                        <input type="number" name="reorder_point" class="form-control"
                                            placeholder="5" min="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Target Stock Level</label>
                                        <input type="number" name="target_stock_level" class="form-control"
                                            placeholder="10" min="0">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Initial Quantity on Hand</label>
                                            <input type="number" name="initial_qty" class="form-control"
                                                placeholder="0" min="0" id="initial_qty_input">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Initial Cost Price (R)</label>
                                            <input type="number" step="0.01" name="initial_cost"
                                                class="form-control" placeholder="0.00" min="0"
                                                id="initial_cost_input">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                                <input class="form-check-input" type="checkbox" name="allow_negative"
                                                    id="create_allow_negative" checked value="1">
                                                <label class="form-check-label" for="create_allow_negative">
                                                    <strong>Allow Negative Sale</strong>
                                                <br><small class="text-muted">Permit sales even when stock is zero
                                                    or negative</small>
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                                <input class="form-check-input" type="checkbox" name="special_order"
                                                    id="create_special_order" value="1">
                                                <label class="form-check-label" for="create_special_order">
                                                    <strong>Special Order Only</strong>
                                                <br><small class="text-muted">Mark this product as special order
                                                    item</small>
                                                </label>
                                            </div>
                                        </div>
                                    <div class="col-md-4 mb-3">
                                        <!-- Empty column for perfect alignment -->
                                    </div>
                                </div>
                            </div>

                            {{-- Section 4: Vehicle Fitment --}}
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-car-front me-2"></i>Vehicle Fitment
                                </h6>
                                
                                {{-- Model Selection --}}
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vehicle Model</label>
                                        <select name="car_model_id" class="form-select select2-create-model">
                                            <option value="">Select Model</option>
                                            @foreach ($models as $model)
                                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Engine</label>
                                        <select name="engine_id" class="form-select select2-create-engine">
                                            <option value="">Select Engine</option>
                                            @foreach ($engines as $engine)
                                                <option value="{{ $engine->id }}">{{ $engine->code }} - {{ $engine->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info alert-sm">
                                    <small><i class="bi bi-info-circle me-1"></i>Specify which vehicles this part fits
                                        (optional, multiple allowed)</small>
                                </div>
                                <div id="create-fitments-container"></div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-primary" id="createAddFitmentBtn">
                                        <i class="bi bi-plus-circle me-1"></i> Add Fitment
                                    </button>
                                </div>
                            </div>

                            {{-- Section 5: References & Additional Info --}}
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-link-45deg me-2"></i>References & Additional Info
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                            <label class="form-label">OE Numbers</label>
                                            <input type="text" name="oe_numbers" class="form-control"
                                                id="create_oe_numbers" placeholder="Press Enter to add">
                                        </div>
                                    <div class="col-md-6 mb-3">
                                            <label class="form-label">Cross References</label>
                                            <input type="text" name="cross_refs" class="form-control"
                                                id="create_cross_refs" placeholder="Press Enter to add">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                        <label class="form-label">Product Images</label>
                                            <input type="file" name="images[]" class="form-control" multiple
                                                accept="image/*">
                                        <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP (Max 2MB each)</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this product"></textarea>
                                    </div>
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
            <div class="row mb-2 fitment-row border p-2 rounded bg-light align-items-center">
                <div class="col-md-3">
                    <label class="form-label mb-1 small text-muted">Make</label>
                    <select name="fitments[__INDEX__][make_id]"
                        class="form-select form-select-sm select2-fitment-make">
                        <option value="">Select Make</option>
                        @foreach ($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small text-muted">Model</label>
                    <select name="fitments[__INDEX__][model_id]"
                        class="form-select form-select-sm select2-fitment-model">
                        <option value="">Select Model</option>
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small text-muted">Engine</label>
                    <select name="fitments[__INDEX__][engine_id]"
                        class="form-select form-select-sm select2-fitment-engine">
                        <option value="">Optional</option>
                        @foreach ($engines as $engine)
                            <option value="{{ $engine->id }}">{{ $engine->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small text-muted">Year From</label>
                    <select name="fitments[__INDEX__][year_start]"
                        class="form-select form-select-sm select2-fitment-year-start">
                        <option value="">From</option>
                        @php
                            $currentYear = date('Y');
                            for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                echo "<option value=\"{$year}\">{$year}</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small text-muted">Year To</label>
                    <select name="fitments[__INDEX__][year_end]"
                        class="form-select form-select-sm select2-fitment-year-end">
                        <option value="">To</option>
                        @php
                            $currentYear = date('Y');
                            for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                echo "<option value=\"{$year}\">{$year}</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label mb-1 small text-muted">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 removeFitmentBtn"
                        title="Remove this fitment">
                        <i class="bi bi-trash3"></i>
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
                        }).on('change', function() {
                            var supplierId = $(this).val();
                            if (supplierId) {
                                // Get suppliers data
                                var suppliers = @json($suppliers);
                                var selectedSupplier = suppliers.find(s => s.id == supplierId);
                                if (selectedSupplier && selectedSupplier.supplier_code) {
                                    $('input[name="supplier_code"]').val(selectedSupplier.supplier_code);
                                }
                            } else {
                                $('input[name="supplier_code"]').val('');
                            }
                        });

                        $('.select2-primary-supplier').select2({
                            dropdownParent: $('#createProductModal'),
                            placeholder: 'Select Primary Supplier',
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
                        $(`.select2-fitment-make, .select2-fitment-model, .select2-fitment-engine, .select2-fitment-year-start, .select2-fitment-year-end`)
                            .each(
                            function() {
                                if (!$(this).hasClass('select2-hidden-accessible')) {
                                        const $element = $(this);
                                        const isYearField = $element.hasClass('select2-fitment-year-start') || $element.hasClass('select2-fitment-year-end');
                                        
                                        // Year fields - no tagging
                                        if (isYearField) {
                                            $element.select2({
                                                dropdownParent: $('#createProductModal'),
                                                width: '100%',
                                                placeholder: $element.find('option:first').text(),
                                                allowClear: true
                                            });
                                        } 
                                        // Make, Model, Engine - with tagging
                                        else {
                                            $element.select2({
                                        dropdownParent: $('#createProductModal'),
                                        width: '100%',
                                                placeholder: $element.find('option:first').text(),
                                                allowClear: true,
                                                tags: true,
                                                createTag: function(params) {
                                                    const term = $.trim(params.term);
                                                    if (term === '') return null;
                                                    return {
                                                        id: 'new:' + term,
                                                        text: term + ' (Press Enter to add)',
                                                        newTag: true
                                                    };
                                                }
                                            }).on('select2:select', function(e) {
                                                const data = e.params.data;
                                                if (data.newTag) {
                                                    const newName = data.text.replace(' (Press Enter to add)', '');
                                                    const $select = $(this);
                                                    let endpoint = '';
                                                    
                                                    // Determine endpoint based on field type
                                                    if ($select.hasClass('select2-fitment-make')) {
                                                        endpoint = '{{ route("car-makes.quick-add") }}';
                                                    } else if ($select.hasClass('select2-fitment-model')) {
                                                        endpoint = '{{ route("car-models.quick-add") }}';
                                                    } else if ($select.hasClass('select2-fitment-engine')) {
                                                        endpoint = '{{ route("car-engines.quick-add") }}';
                                                    }
                                                    
                                                    // AJAX call to save
                                                    $.ajax({
                                                        url: endpoint,
                                                        method: 'POST',
                                                        data: {
                                                            name: newName,
                                                            _token: '{{ csrf_token() }}'
                                                        },
                                                        success: function(response) {
                                                            if (response.success) {
                                                                // Add new option
                                                                const newOption = new Option(response.data.name, response.data.id, true, true);
                                                                $select.append(newOption);
                                                                $select.val(response.data.id).trigger('change');
                                                                
                                                                // Show success message
                                                                toastr.success(response.data.name + ' added successfully!');
                                                            }
                                                        },
                                                        error: function(xhr) {
                                                            toastr.error('Failed to add. Please try again.');
                                                            $select.val('').trigger('change');
                                                        }
                                                    });
                                                }
                                            });
                                        }
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
                        $('.select2-create-brand, .select2-create-category, .select2-create-subcategory, .select2-create-suppliers, .select2-primary-supplier')
                            .select2('destroy');

                        // Destroy Tagify
                        if (createOeTagify) createOeTagify.destroy();
                        if (createCrossTagify) createCrossTagify.destroy();

                        // Clear fitments
                        document.getElementById('create-fitments-container').innerHTML = '';
                        createFitmentIndex = 0;
                    });

                });
            </script>
        @endpush

        {{-- Include Create Modals --}}
        <div class="modal fade" id="createBrandModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @include('brands.partials.create_brand_modal')
                </div>
            </div>
        </div>

        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @include('categories.partials.create_category_modal')
                </div>
            </div>
        </div>

        <div class="modal fade" id="createSubcategoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @include('categories.partials.create_subcategory_modal')
                </div>
            </div>
        </div>
