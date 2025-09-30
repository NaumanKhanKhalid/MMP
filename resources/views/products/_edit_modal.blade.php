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

                <div class="modal-body">
                    {{-- Tab Navigation --}}
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                href="#edit-basic-{{ $product->id }}">Basic Info</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                href="#edit-pricing-{{ $product->id }}">Pricing</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                href="#edit-inventory-{{ $product->id }}">Inventory</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                href="#edit-fitments-{{ $product->id }}">Fitments</a></li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        <div class="tab-pane fade show active" id="edit-basic-{{ $product->id }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" class="form-control bg-light" value="{{ $product->sku }}"
                                        disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Primary Barcode</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $product->barcode_primary }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alternate Barcode</label>
                                    <input type="text" name="barcode_alternate" class="form-control"
                                        value="{{ $product->barcode_alternate }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $product->name }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Brand</label>
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
                                    <label class="form-label">Category</label>
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
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Suppliers</label>
                                    <select name="supplier_ids[]"
                                        class="form-select select2-edit-suppliers-{{ $product->id }}" multiple>
                                        @foreach ($suppliers as $s)
                                            <option value="{{ $s->id }}"
                                                {{ $product->suppliers->contains($s->id) ? 'selected' : '' }}>
                                                {{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing Tab --}}
                        <div class="tab-pane fade" id="edit-pricing-{{ $product->id }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Normal Price (R)</label>
                                    <input type="number" step="0.01" name="price_normal" class="form-control"
                                        value="{{ $product->price_normal }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Online Price (R)</label>
                                    <input type="number" step="0.01" name="price_online" class="form-control"
                                        value="{{ $product->price_online }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Workshop Price (R)</label>
                                    <input type="number" step="0.01" name="price_workshop" class="form-control"
                                        value="{{ $product->price_workshop }}">
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Pricing Tiers:</strong> Normal = Retail, Online = E-commerce, Workshop =
                                        Trade discount
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Inventory Tab --}}
                        <div class="tab-pane fade" id="edit-inventory-{{ $product->id }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unit</label>
                                    <select name="unit" class="form-select">
                                        <option value="PCS" @selected($product->unit == 'PCS')>PCS</option>
                                        <option value="SET" @selected($product->unit == 'SET')>SET</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bin Location</label>
                                    <input type="text" name="bin_location" class="form-control"
                                        value="{{ $product->bin_location }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reorder Level</label>
                                    <input type="number" name="reorder_level" class="form-control"
                                        value="{{ $product->reorder_level }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" @selected($product->status == 'active')>Active</option>
                                        <option value="inactive" @selected($product->status == 'inactive')>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="allow_negative"
                                            id="edit_allow_negative_{{ $product->id }}"
                                            {{ $product->allow_negative ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="edit_allow_negative_{{ $product->id }}">
                                            <strong>Allow Negative Sale</strong>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="special_order"
                                            id="edit_special_order_{{ $product->id }}"
                                            {{ $product->special_order ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edit_special_order_{{ $product->id }}">
                                            <strong>Special Order Only</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">OE Numbers</label>
                                    <input type="text" name="oe_numbers" class="form-control"
                                        id="edit_oe_numbers_{{ $product->id }}" value="{{ $product->oe_numbers }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Cross References</label>
                                    <input type="text" name="cross_refs" class="form-control"
                                        id="edit_cross_refs_{{ $product->id }}" value="{{ $product->cross_refs }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Product Images (Max 3)</label>
                                    <input type="file" name="images[]" class="form-control" multiple
                                        accept="image/*">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ $product->notes }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Fitments Tab --}}
                        <div class="tab-pane fade" id="edit-fitments-{{ $product->id }}">
                            <div id="edit-fitments-container-{{ $product->id }}">
                                @foreach ($product->fitments as $i => $fit)
                                    <div class="row mb-2 fitment-row border p-2 rounded bg-light">
                                        <div class="col-md-3">
                                            <select name="fitments[{{ $i }}][make_id]"
                                                class="form-select form-select-sm select2-fitment-make">
                                                <option value="">Select Make</option>
                                                @foreach ($makes as $make)
                                                    <option value="{{ $make->id }}" @selected($fit->make_id == $make->id)>
                                                        {{ $make->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="fitments[{{ $i }}][model_id]"
                                                class="form-select form-select-sm select2-fitment-model">
                                                <option value="">Select Model</option>
                                                @foreach ($models as $model)
                                                    <option value="{{ $model->id }}" @selected($fit->model_id == $model->id)>
                                                        {{ $model->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="fitments[{{ $i }}][engine_id]"
                                                class="form-select form-select-sm select2-fitment-engine">
                                                <option value="">Engine (Opt)</option>
                                                @foreach ($engines as $engine)
                                                    <option value="{{ $engine->id }}" @selected($fit->engine_id == $engine->id)>
                                                        {{ $engine->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="fitments[{{ $i }}][year_start]"
                                                class="form-control form-control-sm" value="{{ $fit->year_start }}">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="number" name="fitments[{{ $i }}][year_end]"
                                                class="form-control form-control-sm" value="{{ $fit->year_end }}">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button"
                                                class="btn btn-sm btn-danger w-100 removeFitmentBtn">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary"
                                id="editAddFitmentBtn-{{ $product->id }}">
                                <i class="bi bi-plus"></i> Add Fitment
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Fitment Row Template --}}
<template id="editFitmentRowTemplate-{{ $product->id }}">
    <div class="row mb-2 fitment-row border p-2 rounded bg-light">
        <div class="col-md-3">
            <select name="fitments[__INDEX__][make_id]" class="form-select form-select-sm select2-fitment-make">
                <option value="">Select Make</option>
                @foreach ($makes as $make)
                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="fitments[__INDEX__][model_id]" class="form-select form-select-sm select2-fitment-model">
                <option value="">Select Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="fitments[__INDEX__][engine_id]" class="form-select form-select-sm select2-fitment-engine">
                <option value="">Engine (Opt)</option>
                @foreach ($engines as $engine)
                    <option value="{{ $engine->id }}">{{ $engine->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="fitments[__INDEX__][year_start]" class="form-control form-control-sm"
                placeholder="Start">
        </div>
        <div class="col-md-1">
            <input type="number" name="fitments[__INDEX__][year_end]" class="form-control form-control-sm"
                placeholder="End">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger w-100 removeFitmentBtn">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</template>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editFitmentIndex = {{ $product->fitments->count() }};

            function initEditSelect2(productId) {
                $(`.select2-edit-brand-${productId}, .select2-edit-category-${productId}, .select2-edit-subcategory-${productId}, .select2-edit-suppliers-${productId}`)
                    .select2({
                        dropdownParent: $(`#editProductModal-${productId}`),
                        width: '100%',
                        allowClear: true
                    });

                $(`#edit-fitments-container-${productId} .select2-fitment-make, #edit-fitments-container-${productId} .select2-fitment-model, #edit-fitments-container-${productId} .select2-fitment-engine`)
                    .select2({
                        dropdownParent: $(`#editProductModal-${productId}`),
                        width: '100%'
                    });
            }

            function initEditTagify(productId) {
                const oeInput = document.querySelector(`#edit_oe_numbers_${productId}`);
                const crossInput = document.querySelector(`#edit_cross_refs_${productId}`);
                if (oeInput) new Tagify(oeInput);
                if (crossInput) new Tagify(crossInput);
            }

            // Add Fitment Row
            document.getElementById(`editAddFitmentBtn-{{ $product->id }}`).addEventListener('click', function() {
                const template = document.getElementById(`editFitmentRowTemplate-{{ $product->id }}`)
                    .innerHTML;
                const html = template.replace(/__INDEX__/g, editFitmentIndex);
                document.getElementById(`edit-fitments-container-{{ $product->id }}`).insertAdjacentHTML(
                    'beforeend', html);
                initEditSelect2({{ $product->id }});
                editFitmentIndex++;
            });

            // Remove Fitment Row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeFitmentBtn')) {
                    e.target.closest('.fitment-row').remove();
                }
            });

            // Init when modal opens
            $(`#editProductModal-{{ $product->id }}`).on('shown.bs.modal', function() {
                initEditSelect2({{ $product->id }});
                initEditTagify({{ $product->id }});
            });

            // Bin Location validation
            const binInput = document.querySelector(
                '#editProductModal-{{ $product->id }} input[name="bin_location"]');
            if (binInput) {
                binInput.addEventListener('blur', function() {
                    const value = this.value.trim().toUpperCase();
                    if (value && !/^[A-Z]-\d+$/.test(value)) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.value = value;
                    }
                });
            }
        });
    </script>
@endpush
