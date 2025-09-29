{{-- Include CSS dependencies in your <head> or layout --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />

<style>
    .select2-container--open {
        z-index: 1060 !important;
    }
</style>

{{-- Edit Modal --}}
<div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product — <strong>{{ $product->name }}</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Product Name --}}
                        <div class="col-md-6 mb-3">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $product->name) }}" required>
                        </div>

                        {{-- Brand --}}
                        <div class="col-md-3 mb-3">
                            <label>Brand</label>
                            <select name="brand_id" class="form-select select2 single-select" required>
                                <option value="">-- Select --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Category --}}
                        <div class="col-md-3 mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-select select2 single-select" required>
                                <option value="">-- Select --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Subcategory --}}
                        <div class="col-md-3 mb-3">
                            <label>Subcategory</label>
                            <select name="subcategory_id" class="form-select select2 single-select" required>
                                <option value="">-- Select --</option>
                                @foreach ($subCategories as $subCat)
                                    <option value="{{ $subCat->id }}"
                                        {{ (old('subcategory_id', $product->subcategory_id) == $subCat->id) ? 'selected' : '' }}>
                                        {{ $subCat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Suppliers (multiple) --}}
                        <div class="col-md-6 mb-3">
                            <label>Suppliers <span class="text-muted">(choose multiple)</span></label>
                            <select name="supplier_ids[]" class="form-select select2 multi-select" multiple>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        @if (in_array($s->id, old('supplier_ids', $product->suppliers->pluck('id')->toArray()))) selected @endif>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pick one or more suppliers for this product</small>
                        </div>

                        {{-- Other fields... SKU, barcode, unit, etc. --}}
                        <div class="col-md-3 mb-3">
                            <label>SKU</label>
                            <input type="text" name="sku" class="form-control"
                                value="{{ old('sku', $product->sku) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" class="form-control"
                                value="{{ old('barcode', $product->barcode) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Unit</label>
                            <select name="unit" class="form-select">
                                <option value="PCS" {{ (old('unit', $product->unit) == 'PCS') ? 'selected' : '' }}>PCS</option>
                                <option value="SET" {{ (old('unit', $product->unit) == 'SET') ? 'selected' : '' }}>SET</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Bin Location</label>
                            <input type="text" name="bin_location" class="form-control"
                                value="{{ old('bin_location', $product->bin_location) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Normal Price</label>
                            <input type="number" step="0.01" name="price_normal" class="form-control"
                                value="{{ old('price_normal', $product->price_normal) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Online Price</label>
                            <input type="number" step="0.01" name="price_online" class="form-control"
                                value="{{ old('price_online', $product->price_online) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Workshop Price</label>
                            <input type="number" step="0.01" name="price_workshop" class="form-control"
                                value="{{ old('price_workshop', $product->price_workshop) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder_level" class="form-control"
                                value="{{ old('reorder_level', $product->reorder_level) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Allow Negative Sale</label>
                            <select name="allow_negative" class="form-select">
                                <option value="1" {{ old('allow_negative', $product->allow_negative) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('allow_negative', $product->allow_negative) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Special Order Only</label>
                            <select name="special_order" class="form-select">
                                <option value="1" {{ old('special_order', $product->special_order) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('special_order', $product->special_order) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="mt-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Vehicle Fitments --}}
                    <hr>
                    <h6>Vehicle Fitments</h6>
                    <div id="edit-fitments-wrapper-{{ $product->id }}">
                        @foreach ($product->fitments as $index => $fitment)
                            <div class="row g-2 fitment-item mb-2 border p-2 rounded">
                                <div class="col-md-3">
                                    <select name="fitments[{{ $index }}][make_id]" class="form-select select2 single-select" required>
                                        <option value="">-- Make --</option>
                                        @foreach ($makes as $make)
                                            <option value="{{ $make->id }}" {{ $fitment->make_id == $make->id ? 'selected' : '' }}>{{ $make->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="fitments[{{ $index }}][model_id]" class="form-select select2 single-select" required>
                                        <option value="">-- Model --</option>
                                        @foreach ($models as $model)
                                            <option value="{{ $model->id }}" {{ $fitment->model_id == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="fitments[{{ $index }}][engine_id]" class="form-select select2 single-select">
                                        <option value="">-- Engine --</option>
                                        @foreach ($engines as $engine)
                                            <option value="{{ $engine->id }}" {{ $fitment->engine_id == $engine->id ? 'selected' : '' }}>{{ $engine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fitments[{{ $index }}][year_start]" class="form-control" value="{{ $fitment->year_start }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fitments[{{ $index }}][year_end]" class="form-control" value="{{ $fitment->year_end }}">
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-sm btn-danger removeFitmentBtn">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="editAddFitmentBtn-{{ $product->id }}">+ Add Fitment</button>

                    {{-- OE Numbers (input tag) --}}
                    <div class="mt-4">
                        <label>OE Numbers</label>
                        <input id="edit-oe-numbers-{{ $product->id }}" name="oe_numbers"
                            class="form-control"
                            placeholder="Comma separated"
                            value="{{ old('oe_numbers', $product->oeNumbers->pluck('oe_number')->implode(',')) }}" />
                    </div>

                    {{-- Cross References (input tag) --}}
                    <div class="mt-3">
                        <label>Cross References</label>
                        <input id="edit-cross-refs-{{ $product->id }}" name="cross_refs"
                            class="form-control"
                            placeholder="Comma separated"
                            value="{{ old('cross_refs', $product->crossRefs->pluck('cross_ref')->implode(',')) }}" />
                    </div>

                    {{-- Images --}}
                    <div class="mt-3">
                        <label>Product Images (up to 3)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    {{-- Existing Images --}}
                    @if ($product->images && $product->images->count())
                        <div class="mt-3">
                            <label>Existing Images</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach ($product->images as $img)
                                    <div style="width: 80px; height: 80px; position: relative;">
                                        <img src="{{ url('public/storage/' . $img->path) }}" class="img-thumbnail"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Notes --}}
                    <div class="mt-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $product->notes) }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template for adding new fitment rows --}}
<template id="editFitmentRowTemplate-{{ $product->id }}">
    <div class="row g-2 fitment-item mb-2 border p-2 rounded">
        <div class="col-md-3">
            <select name="fitments[__INDEX__][make_id]" class="form-select select2 single-select" required>
                <option value="">-- Make --</option>
                @foreach ($makes as $make)
                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="fitments[__INDEX__][model_id]" class="form-select select2 single-select" required>
                <option value="">-- Model --</option>
                @foreach ($models as $model)
                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="fitments[__INDEX__][engine_id]" class="form-select select2 single-select">
                <option value="">-- Engine --</option>
                @foreach ($engines as $engine)
                    <option value="{{ $engine->id }}">{{ $engine->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="fitments[__INDEX__][year_start]" class="form-control" placeholder="Year Start" />
        </div>
        <div class="col-md-2">
            <input type="number" name="fitments[__INDEX__][year_end]" class="form-control" placeholder="Year End" />
        </div>
        <div class="col-md-12 text-end">
            <button type="button" class="btn btn-sm btn-danger removeFitmentBtn">Remove</button>
        </div>
    </div>
</template>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productId = "{{ $product->id }}";
        const modalSelector = '#editProductModal-' + productId;

        function initSelect2InModal() {
            $(modalSelector).find('select.select2').each(function() {
                // Destroy existing instance if exists
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    width: '100%',
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    dropdownParent: $(modalSelector)
                });
            });
        }

        // Tagify for OE Numbers & Cross Refs
        const oeInput = document.getElementById('edit-oe-numbers-' + productId);
        if (oeInput) {
            new Tagify(oeInput, {
                delimiters: ",",
                dropdown: { enabled: 0 }
            });
        }
        const crossInput = document.getElementById('edit-cross-refs-' + productId);
        if (crossInput) {
            new Tagify(crossInput, {
                delimiters: ",",
                dropdown: { enabled: 0 }
            });
        }

        // Initialize select2 on load
        initSelect2InModal();

        // Re-init when modal opens
        $(modalSelector).on('shown.bs.modal', function() {
            initSelect2InModal();
        });

        // Fitment add/remove logic
        const wrapper = document.getElementById('edit-fitments-wrapper-' + productId);
        const addBtn = document.getElementById('editAddFitmentBtn-' + productId);
        let fitmentIndex = {{ $product->fitments->count() }};

        addBtn.addEventListener('click', function() {
            let html = document.getElementById('editFitmentRowTemplate-' + productId).innerHTML;
            html = html.replace(/__INDEX__/g, fitmentIndex);
            let tmpDiv = document.createElement('div');
            tmpDiv.innerHTML = html;
            wrapper.appendChild(tmpDiv.firstElementChild);

            initSelect2InModal();  // init newly added selects
            fitmentIndex++;
        });

        wrapper.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeFitmentBtn')) {
                e.target.closest('.fitment-item').remove();
            }
        });
    });
</script>
