<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="editProductModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="modal-content needs-validation" novalidate>
            @csrf
            @method('PATCH')

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProductModalLabel-{{ $product->id }}">
                    ✏️ Edit Product - <strong>{{ $product->name }}</strong>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <!-- Left Column -->
                    <div class="col-md-6">

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name-{{ $product->id }}" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name-{{ $product->id }}" name="name"
                                value="{{ old('name', $product->name) }}" required>
                        </div>

                        <!-- Brand -->
                        <div class="mb-3">
                            <label for="brand_id-{{ $product->id }}" class="form-label">Brand</label>
                            <select class="form-select" id="brand_id-{{ $product->id }}" name="brand_id" required>
                                <option value="">-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        @selected(old('brand_id', $product->brand_id) == $brand->id)>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Suppliers -->
                        <div class="mb-3">
                            <label for="supplier_ids-{{ $product->id }}" class="form-label">Suppliers</label>
                            <select class="form-select" id="supplier_ids-{{ $product->id }}" name="supplier_ids[]"
                                multiple>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        @if (in_array($supplier->id, old('supplier_ids', $product->suppliers->pluck('id')->toArray()))) selected @endif>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold CTRL to select multiple suppliers.</small>
                        </div>

                        <!-- Category & Subcategory -->
                        <div class="row">
                            <div class="col">
                                <label for="category_id-{{ $product->id }}" class="form-label">Category</label>
                                <select class="form-select" id="category_id-{{ $product->id }}" name="category_id"
                                    required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @selected(old('category_id', $product->category_id) == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="subcategory_id-{{ $product->id }}" class="form-label">Subcategory</label>
                                <select class="form-select" id="subcategory_id-{{ $product->id }}"
                                    name="subcategory_id" required>
                                    <option value="">-- Select Subcategory --</option>
                                    @foreach ($subCategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            @selected(old('subcategory_id', $product->subcategory_id) == $subcategory->id)>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- SKU & Barcode -->
                        <div class="row mt-3">
                            <div class="col">
                                <label for="sku-{{ $product->id }}" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku-{{ $product->id }}" name="sku"
                                    value="{{ old('sku', $product->sku) }}">
                            </div>
                            <div class="col">
                                <label for="barcode-{{ $product->id }}" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode-{{ $product->id }}"
                                    name="barcode" value="{{ old('barcode', $product->barcode) }}">
                            </div>
                        </div>

                        <!-- Unit & Bin Location -->
                        <div class="row mt-3">
                            <div class="col">
                                <label for="unit-{{ $product->id }}" class="form-label">Unit</label>
                                <input type="text" class="form-control" id="unit-{{ $product->id }}" name="unit"
                                    value="{{ old('unit', $product->unit ?? 'PCS') }}">
                            </div>
                            <div class="col">
                                <label for="bin_location-{{ $product->id }}" class="form-label">Bin Location</label>
                                <input type="text" class="form-control" id="bin_location-{{ $product->id }}"
                                    name="bin_location" value="{{ old('bin_location', $product->bin_location) }}">
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <!-- Prices -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Normal Price</label>
                                <input type="number" step="0.01" class="form-control"
                                    name="price_normal"
                                    value="{{ old('price_normal', $product->price_normal) }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Online Price</label>
                                <input type="number" step="0.01" class="form-control"
                                    name="price_online"
                                    value="{{ old('price_online', $product->price_online) }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Workshop Price</label>
                                <input type="number" step="0.01" class="form-control"
                                    name="price_workshop"
                                    value="{{ old('price_workshop', $product->price_workshop) }}">
                            </div>
                        </div>

                        <!-- Reorder Level -->
                        <div class="mb-3">
                            <label for="reorder_level-{{ $product->id }}" class="form-label">Reorder Level</label>
                            <input type="number" class="form-control" id="reorder_level-{{ $product->id }}"
                                name="reorder_level" value="{{ old('reorder_level', $product->reorder_level) }}">
                        </div>

                        <!-- Flags -->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox"
                                id="allow_negative-{{ $product->id }}" name="allow_negative" value="1"
                                {{ old('allow_negative', $product->allow_negative) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_negative-{{ $product->id }}">
                                Allow Negative Stock
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox"
                                id="special_order-{{ $product->id }}" name="special_order" value="1"
                                {{ old('special_order', $product->special_order) ? 'checked' : '' }}>
                            <label class="form-check-label" for="special_order-{{ $product->id }}">
                                Special Order
                            </label>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status-{{ $product->id }}" class="form-label">Status</label>
                            <select class="form-select" id="status-{{ $product->id }}" name="status" required>
                                <option value="active" @selected(old('status', $product->status) == 'active')>
                                    Active</option>
                                <option value="inactive" @selected(old('status', $product->status) == 'inactive')>
                                    Inactive</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>

                    </div>
                </div>

                <hr>

                <!-- OE Numbers -->
                <div class="mb-3">
                    <label class="form-label">OE Numbers (comma separated)</label>
                    <input type="text" class="form-control" name="oe_numbers"
                        value="{{ old('oe_numbers', $product->oeNumbers->pluck('oe_number')->implode(',')) }}">
                </div>

                <!-- Cross References -->
                <div class="mb-3">
                    <label class="form-label">Cross References (comma separated)</label>
                    <input type="text" class="form-control" name="cross_refs"
                        value="{{ old('cross_refs', $product->crossRefs->pluck('cross_ref')->implode(',')) }}">
                </div>

                <!-- Images -->
                <div class="mb-3">
                    <label class="form-label">Upload Images (max 3)</label>
                    <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                </div>

                <!-- Existing images preview -->
                @if ($product->images && $product->images->count())
                    <div class="mb-3">
                        <label class="form-label">Existing Images</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($product->images as $image)
                                <div style="width: 80px; height: 80px; position: relative;">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Image"
                                        class="img-thumbnail"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Fitments -->
                <div class="mb-3">
                    <label class="form-label">Fitments</label>
                    @forelse ($product->fitments as $fitment)
                        <div class="border rounded p-2 mb-2 bg-light">
                            <strong>{{ $fitment->make->name ?? 'N/A' }}</strong> →
                            {{ $fitment->model->name ?? 'N/A' }} /
                            {{ $fitment->engine->name ?? 'N/A' }}
                            ({{ $fitment->year_start }} - {{ $fitment->year_end }})
                        </div>
                    @empty
                        <p class="text-muted">No fitments linked yet.</p>
                    @endforelse
                    <small class="text-muted">Fitments can be managed in a separate module.</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    💾 Save Changes
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    ✖ Close
                </button>
            </div>

        </form>
    </div>
</div>
