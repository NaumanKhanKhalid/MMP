{{-- resources/views/products/_view_modal.blade.php --}}
<div class="modal fade" id="viewProductModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Product Details: {{ $product->name }}
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
                            <label class="form-label text-muted small">SKU</label>
                            <div class="fw-semibold">{{ $product->sku }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Primary Barcode</label>
                            <div class="fw-semibold">{{ $product->barcode_primary }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Alternate Barcode</label>
                            <div class="fw-semibold">{{ $product->barcode_alternate ?: '-' }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Product Name</label>
                            <div class="fw-semibold">{{ $product->name }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Supplier</label>
                            <div class="fw-semibold">{{ $product->supplier->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Supplier Code</label>
                            <div class="fw-semibold">{{ $product->supplier_code ?: '-' }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Brand</label>
                            <div class="fw-semibold">{{ $product->brand->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Category</label>
                            <div class="fw-semibold">{{ $product->category->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Subcategory</label>
                            <div class="fw-semibold">{{ $product->subcategory->name ?? '-' }}</div>
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
                            <label class="form-label text-muted small">Normal Price</label>
                            <div class="fw-semibold">R {{ number_format($product->price_normal, 2) }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Online Price</label>
                            <div class="fw-semibold">R {{ number_format($product->price_online, 2) }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">Workshop Price</label>
                            <div class="fw-semibold">R {{ number_format($product->price_workshop, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Inventory Settings --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-boxes me-2"></i>Inventory Settings
                    </h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Unit</label>
                            <div class="fw-semibold">{{ $product->unit }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Bin Location</label>
                            <div class="fw-semibold">{{ $product->bin_location ?: '-' }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Status</label>
                            <div class="fw-semibold">{{ ucfirst($product->status) }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Reorder Point</label>
                            <div class="fw-semibold">{{ $product->reorder_level }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">On-Hand Stock</label>
                            @php
                                $onHand = $product->on_hand_sum ?? 0;
                                $actualStock = $product->actual_stock_sum ?? 0;
                            @endphp
                            <div class="fw-semibold">
                                @if ($actualStock < 0)
                                    <span class="badge bg-danger text-white">{{ $actualStock }}</span>
                                    <small class="d-block text-danger mt-1" style="font-size: 10px;">
                                        Negative Stock (Batches: {{ $onHand }})
                                    </small>
                                @elseif ($onHand < 0)
                                    <span class="badge bg-danger">{{ $onHand }}</span>
                                @elseif ($onHand == 0)
                                    <span class="badge bg-warning">0</span>
                                @else
                                    <span class="badge bg-success">{{ $onHand }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Allow Negative Sale</label>
                            <div class="fw-semibold">{{ $product->allow_negative ? 'Yes' : 'No' }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small">Special Order</label>
                            <div class="fw-semibold">{{ $product->special_order ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Vehicle Fitment --}}
                @if ($product->fitments && $product->fitments->count() > 0)
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-car-front me-2"></i>Vehicle Fitment
                        </h6>
                        <div class="row">
                            @foreach ($product->fitments as $fit)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">Make:</small><br>
                                                    <strong>{{ $fit->make->name ?? '-' }}</strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Model:</small><br>
                                                    <strong>{{ $fit->model->name ?? '-' }}</strong>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">Engine:</small><br>
                                                    <strong>{{ $fit->engine->code ?? '-' }}</strong>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Year Range:</small><br>
                                                    <strong>
                                                        @if ($fit->year_start || $fit->year_end)
                                                            {{ $fit->year_start ?: '...' }} - {{ $fit->year_end ?: '...' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Section 5: References & Additional Info --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="bi bi-link-45deg me-2"></i>References & Additional Info
                    </h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">OE Numbers</label>
                            <div class="fw-semibold">
                                @if ($product->oeNumbers && $product->oeNumbers->count() > 0)
                                    {{ $product->oeNumbers->pluck('oe_number')->implode(', ') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Cross References</label>
                            <div class="fw-semibold">
                                @if ($product->crossRefs && $product->crossRefs->count() > 0)
                                    {{ $product->crossRefs->pluck('cross_ref')->implode(', ') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        
                        @if ($product->images && $product->images->count() > 0)
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted small">Product Images</label>
                                <div class="d-flex gap-3 flex-wrap">
                                    @foreach ($product->images as $img)
                                        <div class="border rounded p-2">
                                            <img src="{{ $img->url }}" class="rounded" style="width: 150px; height: 150px; object-fit: cover;" alt="Product">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($product->notes)
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted small">Notes</label>
                                <div class="fw-semibold">{{ $product->notes }}</div>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Created By</label>
                            <div class="fw-semibold">{{ $product->creator->name ?? 'System' }}</div>
                            <small class="text-muted">{{ $product->created_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Last Updated</label>
                            <div class="fw-semibold">{{ $product->updated_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editProductModal-{{ $product->id }}">
                    <i class="bi bi-pencil me-1"></i>Edit Product
                </button>
            </div>
        </div>
    </div>
</div>

