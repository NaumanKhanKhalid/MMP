
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Basic Info --}}
                        <div class="col-md-6 mb-3">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Brand</label>
                            <select name="brand_id" class="form-select select2" required>
                                <option value="">-- Select --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-select select2" required>
                                <option value="">-- Select --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Subcategory</label>
                            <select name="subcategory_id" class="form-select select2" required>
                                <option value="">-- Select --</option>
                                @foreach ($subCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Suppliers Multiple --}}
                        <div class="col-md-6 mb-3">
                            <label>Suppliers <span class="text-muted">(choose multiple)</span></label>
                            <select name="supplier_ids[]" class="form-select select2" multiple="multiple">
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pick one or more suppliers for this product</small>
                        </div>

                        {{-- SKU & Barcode --}}
                        <div class="col-md-3 mb-3">
                            <label>SKU</label>
                            <input type="text" name="sku" class="form-control" placeholder="Auto or Manual">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Barcode</label>
                            <input type="text" name="barcode" class="form-control"
                                placeholder="Auto-generated if empty">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Unit</label>
                            <select name="unit" class="form-select">
                                <option value="PCS">PCS</option>
                                <option value="SET">SET</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Bin Location</label>
                            <input type="text" name="bin_location" class="form-control" placeholder="e.g. A-16">
                        </div>

                        {{-- Pricing --}}
                        <div class="col-md-4 mb-3">
                            <label>Normal Price</label>
                            <input type="number" step="0.01" name="price_normal" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Online Price</label>
                            <input type="number" step="0.01" name="price_online" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Workshop Price</label>
                            <input type="number" step="0.01" name="price_workshop" class="form-control">
                        </div>

                        {{-- Stock Controls --}}
                        <div class="col-md-3 mb-3">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder_level" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Allow Negative Sale</label>
                            <select name="allow_negative" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Special Order Only</label>
                            <select name="special_order" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    {{-- Vehicle Fitments --}}
                    <hr>
                    <h6>Vehicle Fitments</h6>
                    <div id="fitments-wrapper"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addFitmentBtn">+ Add
                        Fitment</button>

                    {{-- OE Numbers --}}
                    <div class="mt-4">
                        <label>OE Numbers</label>
                        <textarea name="oe_numbers" class="form-control" placeholder="Comma separated"></textarea>
                    </div>

                    {{-- Cross References --}}
                    <div class="mt-3">
                        <label>Cross References</label>
                        <textarea name="cross_refs" class="form-control" placeholder="Comma separated"></textarea>
                    </div>

                    {{-- Images --}}
                    <div class="mt-3">
                        <label>Product Images (up to 3)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    {{-- Notes --}}
                    <div class="mt-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Fitment Row Template --}}
<template id="fitmentRowTemplate">
    <div class="row g-2 fitment-item mb-2 border p-2 rounded">
        <div class="col-md-3">
            <select name="fitments[__INDEX__][make_id]" class="form-select select2" required>
                <option value="">-- Make --</option>
                @foreach ($makes as $make)
                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="fitments[__INDEX__][model_id]" class="form-select select2" required>
                <option value="">-- Model --</option>
                @foreach ($models as $model)
                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="fitments[__INDEX__][engine_id]" class="form-select select2">
                <option value="">-- Engine --</option>
                @foreach ($engines as $engine)
                    <option value="{{ $engine->id }}">{{ $engine->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="fitments[__INDEX__][year_start]" class="form-control" placeholder="Start">
        </div>
        <div class="col-md-2">
            <input type="number" name="fitments[__INDEX__][year_end]" class="form-control" placeholder="End">
        </div>
        <div class="col-md-12 text-end">
            <button type="button" class="btn btn-sm btn-danger removeFitmentBtn">Remove</button>
        </div>
    </div>
</template>

@push('scripts')
    <script>
        document.getElementById('addFitmentBtn').addEventListener('click', function() {
            let tmpl = document.getElementById('fitmentRowTemplate').innerHTML;
            let index = document.querySelectorAll('#fitments-wrapper .fitment-item').length;
            tmpl = tmpl.replace(/__INDEX__/g, index);
            document.getElementById('fitments-wrapper').insertAdjacentHTML('beforeend', tmpl);
        });

        // Remove fitment
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeFitmentBtn')) {
                e.target.closest('.fitment-item').remove();
            }
        });


        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Select option",
                allowClear: true
            });
        });
    </script>
@endpush
