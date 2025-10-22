{{-- resources/views/products/_edit_modal.blade.php --}}
<div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Product: {{ $product->name }}
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
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}"
                                    required>
                                </div>


                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_ids"
                                    class="form-select select2-edit-suppliers-{{ $product->id }}">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}"
                                            {{ $product->supplier_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier Code <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_code" class="form-control"
                                    value="{{ $product->supplier_code }}" required placeholder="Supplier Code">
                                </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-select select2-edit-brand-{{ $product->id }}"
                                        required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}" @selected($product->brand_id == $b->id)>
                                                {{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                        class="form-select select2-edit-category-{{ $product->id }}" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}" @selected($product->category_id == $c->id)>
                                                {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Subcategory</label>
                                    <select name="subcategory_id"
                                        class="form-select select2-edit-subcategory-{{ $product->id }}">
                                        <option value="">Select Subcategory</option>
                                        @foreach ($subCategories as $sc)
                                            <option value="{{ $sc->id }}" @selected($product->subcategory_id == $sc->id)>
                                                {{ $sc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Alternate Barcode (Optional)</label>
                                <input type="text" name="barcode_alternate" class="form-control"
                                    value="{{ $product->barcode_alternate }}" placeholder="Manual barcode entry">
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
                                    value="{{ $product->price_normal }}" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Online Price (R)</label>
                                    <input type="number" step="0.01" name="price_online" class="form-control"
                                    value="{{ $product->price_online }}" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Workshop Price (R)</label>
                                    <input type="number" step="0.01" name="price_workshop" class="form-control"
                                    value="{{ $product->price_workshop }}" placeholder="0.00">
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
                                        <option value="PCS" @selected($product->unit == 'PCS')>PCS</option>
                                        <option value="SET" @selected($product->unit == 'SET')>SET</option>
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                    <label class="form-label">Bin Location</label>
                                    <input type="text" name="bin_location" class="form-control"
                                    value="{{ $product->bin_location }}" placeholder="A-16">
                                </div>
                            <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                        <option value="active" @selected($product->status == 'active')>Active</option>
                                        <option value="inactive" @selected($product->status == 'inactive')>Inactive</option>
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reorder Point</label>
                                <input type="number" name="reorder_point" class="form-control"
                                    value="{{ $product->reorder_level }}" placeholder="5" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                        <input class="form-check-input" type="checkbox" name="allow_negative"
                                            id="edit_allow_negative_{{ $product->id }}" value="1"
                                            {{ $product->allow_negative ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_allow_negative_{{ $product->id }}">
                                            <strong>Allow Negative Sale</strong>
                                        <br><small class="text-muted">Permit sales even when stock is zero or
                                            negative</small>
                                        </label>
                                    </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                        <input class="form-check-input" type="checkbox" name="special_order"
                                            id="edit_special_order_{{ $product->id }}" value="1"    
                                            {{ $product->special_order ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edit_special_order_{{ $product->id }}">
                                            <strong>Special Order Only</strong>
                                        <br><small class="text-muted">Mark this product as special order item</small>
                                        </label>
                                </div>
                                </div>
                            </div>
                        </div>

                    {{-- Section 4: Vehicle Fitment --}}
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-car-front me-2"></i>Vehicle Fitment
                        </h6>
                        <div class="alert alert-info alert-sm">
                            <small><i class="bi bi-info-circle me-1"></i>Specify which vehicles this part fits
                                (optional, multiple allowed)</small>
                        </div>
                            <div id="edit-fitments-container-{{ $product->id }}">
                                @foreach ($product->fitments as $i => $fit)
                                <div class="row mb-2 fitment-row border p-2 rounded bg-light align-items-center">
                                        <div class="col-md-3">
                                        <label class="form-label mb-1 small text-muted">Make</label>
                                            <select name="fitments[{{ $i }}][make_id]"
                                            class="form-select form-select-sm select2-fitment-make-{{ $product->id }}">
                                                <option value="">Select Make</option>
                                                @foreach ($makes as $make)
                                                    <option value="{{ $make->id }}" @selected($fit->make_id == $make->id)>
                                                        {{ $make->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Model</label>
                                            <select name="fitments[{{ $i }}][model_id]"
                                            class="form-select form-select-sm select2-fitment-model-{{ $product->id }}">
                                                <option value="">Select Model</option>
                                                @foreach ($models as $model)
                                                    <option value="{{ $model->id }}" @selected($fit->model_id == $model->id)>
                                                        {{ $model->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Engine</label>
                                            <select name="fitments[{{ $i }}][engine_id]"
                                            class="form-select form-select-sm select2-fitment-engine-{{ $product->id }}">
                                            <option value="">Optional</option>
                                                @foreach ($engines as $engine)
                                                    <option value="{{ $engine->id }}" @selected($fit->engine_id == $engine->id)>
                                                        {{ $engine->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Year From</label>
                                        <select name="fitments[{{ $i }}][year_start]"
                                            class="form-select form-select-sm select2-fitment-year-start-{{ $product->id }}">
                                            <option value="">From</option>
                                            @php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                                    $selected = $fit->year_start == $year ? 'selected' : '';
                                                    echo "<option value=\"{$year}\" {$selected}>{$year}</option>";
                                                }
                                            @endphp
                                        </select>
                                        </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Year To</label>
                                        <select name="fitments[{{ $i }}][year_end]"
                                            class="form-select form-select-sm select2-fitment-year-end-{{ $product->id }}">
                                            <option value="">To</option>
                                            @php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                                    $selected = $fit->year_end == $year ? 'selected' : '';
                                                    echo "<option value=\"{$year}\" {$selected}>{$year}</option>";
                                                }
                                            @endphp
                                        </select>
                                        </div>
                                        <div class="col-md-1">
                                        <label class="form-label mb-1 small text-muted">&nbsp;</label>
                                            <button type="button"
                                            class="btn btn-sm btn-outline-danger w-100 removeFitmentBtn"
                                            title="Remove this fitment">
                                            <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary"
                                id="editAddFitmentBtn-{{ $product->id }}">
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
                                    id="edit_oe_numbers_{{ $product->id }}" value='@json($product->oeNumbers->pluck('oe_number'))'
                                    placeholder="Press Enter to add">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cross References</label>
                                <input type="text" name="cross_refs" class="form-control"
                                    id="edit_cross_refs_{{ $product->id }}" value='@json($product->crossRefs->pluck('cross_ref'))'
                                    placeholder="Press Enter to add">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Product Images</label>
                                @if ($product->images && $product->images->count() > 0)
                                    <div class="d-flex gap-2 mb-2 flex-wrap">
                                        @foreach ($product->images as $img)
                                            <div class="position-relative">
                                                <img src="{{ $img->url }}" class="rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover;"
                                                    alt="Product">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                    onclick="deleteImage({{ $img->id }})"
                                                    style="padding: 2px 6px;">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" name="images[]" class="form-control" multiple
                                    accept="image/*">
                                <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP (Max 2MB each)</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this product">{{ $product->notes }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Fitment Row Template --}}
<template id="editFitmentRowTemplate-{{ $product->id }}">
    <div class="row mb-2 fitment-row border p-2 rounded bg-light align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-1 small text-muted">Make</label>
            <select name="fitments[__INDEX__][make_id]"
                class="form-select form-select-sm select2-fitment-make-{{ $product->id }}">
                <option value="">Select Make</option>
                @foreach ($makes as $make)
                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Model</label>
            <select name="fitments[__INDEX__][model_id]"
                class="form-select form-select-sm select2-fitment-model-{{ $product->id }}">
                <option value="">Select Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Engine</label>
            <select name="fitments[__INDEX__][engine_id]"
                class="form-select form-select-sm select2-fitment-engine-{{ $product->id }}">
                <option value="">Optional</option>
                @foreach ($engines as $engine)
                    <option value="{{ $engine->id }}">{{ $engine->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Year From</label>
            <select name="fitments[__INDEX__][year_start]"
                class="form-select form-select-sm select2-fitment-year-start-{{ $product->id }}">
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
                class="form-select form-select-sm select2-fitment-year-end-{{ $product->id }}">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editFitmentIndex{{ $product->id }} = {{ $product->fitments->count() }};
            let editOeTagify{{ $product->id }}, editCrossTagify{{ $product->id }};

            // Initialize Select2 for edit modal
            function initEditSelect2{{ $product->id }}() {
                $('.select2-edit-brand-{{ $product->id }}, .select2-edit-category-{{ $product->id }}, .select2-edit-subcategory-{{ $product->id }}, .select2-edit-suppliers-{{ $product->id }}')
                    .select2({
                        dropdownParent: $('#editProductModal-{{ $product->id }}'),
                        placeholder: 'Select option',
                        allowClear: true,
                        width: '100%'
                    });

                // Auto-fill supplier code when supplier is selected
                $('.select2-edit-suppliers-{{ $product->id }}').on('change', function() {
                    var supplierId = $(this).val();
                    if (supplierId) {
                        var suppliers = @json($suppliers);
                        var selectedSupplier = suppliers.find(s => s.id == supplierId);
                        if (selectedSupplier && selectedSupplier.supplier_code) {
                            $('#editProductModal-{{ $product->id }} input[name="supplier_code"]').val(selectedSupplier.supplier_code);
                        }
                    } else {
                        $('#editProductModal-{{ $product->id }} input[name="supplier_code"]').val('');
                    }
                });

                // Initialize existing fitment selects
                $('.select2-fitment-make-{{ $product->id }}, .select2-fitment-model-{{ $product->id }}, .select2-fitment-engine-{{ $product->id }}, .select2-fitment-year-start-{{ $product->id }}, .select2-fitment-year-end-{{ $product->id }}')
                    .each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            const $element = $(this);
                            const isYearField = $element.hasClass(
                                'select2-fitment-year-start-{{ $product->id }}') || $element.hasClass(
                                'select2-fitment-year-end-{{ $product->id }}');

                            if (isYearField) {
                                $element.select2({
                                    dropdownParent: $('#editProductModal-{{ $product->id }}'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true
                                });
                            } else {
                                $element.select2({
                                    dropdownParent: $('#editProductModal-{{ $product->id }}'),
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

                                        if ($select.hasClass(
                                                'select2-fitment-make-{{ $product->id }}')) {
                                            endpoint = '{{ route('car-makes.quick-add') }}';
                                        } else if ($select.hasClass(
                                                'select2-fitment-model-{{ $product->id }}')) {
                                            endpoint = '{{ route('car-models.quick-add') }}';
                                        } else if ($select.hasClass(
                                                'select2-fitment-engine-{{ $product->id }}')) {
                                            endpoint = '{{ route('car-engines.quick-add') }}';
                                        }

                                        $.ajax({
                                            url: endpoint,
                                            method: 'POST',
                                            data: {
                                                name: newName,
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    const newOption = new Option(response
                                                        .data.name, response.data.id,
                                                        true, true);
                                                    $select.append(newOption);
                                                    $select.val(response.data.id).trigger(
                                                        'change');
                                                    toastr.success(response.data.name +
                                                        ' added successfully!');
                                                }
                                            },
                                            error: function(xhr) {
                                                toastr.error(
                                                    'Failed to add. Please try again.');
                                                $select.val('').trigger('change');
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
            }

            // Initialize Tagify for OE Numbers and Cross Refs
            function initEditTagify{{ $product->id }}() {
                const oeInput = document.querySelector('#edit_oe_numbers_{{ $product->id }}');
                const crossInput = document.querySelector('#edit_cross_refs_{{ $product->id }}');

                if (oeInput && !editOeTagify{{ $product->id }}) {
                    editOeTagify{{ $product->id }} = new Tagify(oeInput, {
                        delimiters: ",| ",
                        placeholder: "Type and press Enter",
                        dropdown: {
                            enabled: 0
                        }
                    });
                }

                if (crossInput && !editCrossTagify{{ $product->id }}) {
                    editCrossTagify{{ $product->id }} = new Tagify(crossInput, {
                        delimiters: ",| ",
                        placeholder: "Type and press Enter",
                        dropdown: {
                            enabled: 0
                        }
                    });
                }
            }

            // Add Fitment Row
            document.getElementById('editAddFitmentBtn-{{ $product->id }}').addEventListener('click', function() {
                const template = document.getElementById('editFitmentRowTemplate-{{ $product->id }}')
                    .innerHTML;
                const html = template.replace(/__INDEX__/g, editFitmentIndex{{ $product->id }});
                document.getElementById('edit-fitments-container-{{ $product->id }}').insertAdjacentHTML(
                    'beforeend', html);

                // Initialize Select2 for newly added fitment selects
                $(`.select2-fitment-make-{{ $product->id }}, .select2-fitment-model-{{ $product->id }}, .select2-fitment-engine-{{ $product->id }}, .select2-fitment-year-start-{{ $product->id }}, .select2-fitment-year-end-{{ $product->id }}`)
                    .each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            const $element = $(this);
                            const isYearField = $element.hasClass(
                                    'select2-fitment-year-start-{{ $product->id }}') || $element
                                .hasClass('select2-fitment-year-end-{{ $product->id }}');

                            if (isYearField) {
                                $element.select2({
                                    dropdownParent: $(
                                        '#editProductModal-{{ $product->id }}'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true
                                });
                            } else {
                                $element.select2({
                                    dropdownParent: $(
                                        '#editProductModal-{{ $product->id }}'),
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
                                        const newName = data.text.replace(
                                            ' (Press Enter to add)', '');
                                        const $select = $(this);
                                        let endpoint = '';

                                        if ($select.hasClass(
                                                'select2-fitment-make-{{ $product->id }}')) {
                                            endpoint = '{{ route('car-makes.quick-add') }}';
                                        } else if ($select.hasClass(
                                                'select2-fitment-model-{{ $product->id }}'
                                                )) {
                                            endpoint = '{{ route('car-models.quick-add') }}';
                                        } else if ($select.hasClass(
                                                'select2-fitment-engine-{{ $product->id }}'
                                                )) {
                                            endpoint = '{{ route('car-engines.quick-add') }}';
                                        }

                                        $.ajax({
                                            url: endpoint,
                                            method: 'POST',
                                            data: {
                                                name: newName,
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    const newOption = new Option(
                                                        response.data.name,
                                                        response.data.id, true,
                                                        true);
                                                    $select.append(newOption);
                                                    $select.val(response.data.id)
                                                        .trigger('change');
                                                    toastr.success(response.data
                                                        .name +
                                                        ' added successfully!');
                                                }
                                            },
                                            error: function(xhr) {
                                                toastr.error(
                                                    'Failed to add. Please try again.'
                                                    );
                                                $select.val('').trigger('change');
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });

                editFitmentIndex{{ $product->id }}++;
            });

            // Remove Fitment Row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeFitmentBtn')) {
                    e.target.closest('.fitment-row').remove();
                }
            });

            // Modal shown event
            $('#editProductModal-{{ $product->id }}').on('shown.bs.modal', function() {
                initEditSelect2{{ $product->id }}();
                initEditTagify{{ $product->id }}();
            });

            // Bin location validation
            const binInput = document.querySelector(
                '#editProductModal-{{ $product->id }} input[name="bin_location"]');
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
