@forelse ($products as $p)
    <tr class="clickable-row" onclick="openViewModal('{{ $p->id }}')" style="cursor: pointer;">
        <td>
            <div class="d-flex align-items-center">
                <i class="ri-arrow-down-s-line expand-icon" id="expand-icon-{{ $p->id }}" onclick="event.stopPropagation(); toggleBatches('{{ $p->id }}')" style="cursor: pointer;"></i>
            </div>
        </td>

        {{-- Product Name --}}
        <td>
            <div class="d-flex">
                <span class="avatar avatar-md avatar-square bg-primary-transparent p-1" style="cursor: pointer;" onclick="event.stopPropagation(); openImageModal('{{ $p->id }}', '{{ $p->primary_image_url }}', '{{ $p->name }}', {{ $p->images ? $p->images->pluck('url')->toJson() : '[]' }})">
                    <img src="{{ $p->primary_image_url }}" class="w-100 h-100" alt="{{ $p->name }}">
                </span>
                <div class="ms-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center">
                        <span class="text-primary">{{ $p->name }}</span>
                    </p>
                    <p class="fs-12 text-muted mb-0">SKU: {{ $p->sku }}</p>
                </div>
            </div>
        </td>

        {{-- Brand Code --}}
        <td>
            @if ($p->brand && $p->brand->code)
                <span class="badge bg-success-transparent">{{ $p->brand->code }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- Supplier Code --}}
        <td>
            @if($p->supplier_code)
                <span class="badge bg-info-transparent">{{ $p->supplier_code }}</span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- Last Cost --}}
        <td>
            @php
                $lastBatch = $p->stockBatches->sortByDesc('received_date')->first();
                $lastCost = $lastBatch ? $lastBatch->landed_unit_cost : 0;
            @endphp
            <span class="text-dark">R {{ number_format($lastCost, 2) }}</span>
        </td>

        {{-- Total Stock --}}
        <td>
            @php 
                $onHand = $p->on_hand_sum ?? 0;
                $actualStock = $p->actual_stock_sum ?? 0;
                $reserved = $p->reserved ?? 0;
                $available = $onHand - $reserved;
            @endphp
            
            @if ($actualStock < 0)
                <span class="badge bg-danger text-white rounded-pill" title="Negative Stock (from ledger)">
                    {{ $actualStock }}
                </span>
            @elseif ($onHand < 0)
                <span class="badge bg-danger-light rounded-pill" title="Negative Stock (from batches)">
                    {{ $onHand }}
                </span>
            @elseif ($onHand == 0)
                <span class="badge bg-warning-light text-dark rounded-pill">0</span>
            @elseif ($onHand <= $p->reorder_level)
                <span class="badge bg-info-light text-dark rounded-pill">{{ $onHand }}</span>
            @else
                <span class="badge bg-success-transparent rounded-pill">{{ $onHand }}</span>
            @endif
            
            @if ($reserved > 0)
                <br><small class="text-warning" style="font-size: 9px;">Reserved: {{ $reserved }}</small>
            @endif
            
            @if ($available > 0 && $available < $onHand)
                <br><small class="text-success" style="font-size: 9px;">Available: {{ $available }}</small>
            @endif
            
            @if ($actualStock != $onHand && $actualStock < 0)
                <br><small class="text-danger" style="font-size: 9px;">(Ledger: {{ $actualStock }})</small>
            @endif
        </td>

        {{-- Normal Price --}}
        <td>R {{ number_format($p->price_normal, 2) }}</td>

        {{-- Online Price --}}
        <td>R {{ number_format($p->price_online, 2) }}</td>

        {{-- Workshop Price --}}
        <td>R {{ number_format($p->price_workshop, 2) }}</td>

        {{-- FIFO Cost --}}
        <td>
            @php
                $fifoCost = $p->getFifoCost();
            @endphp
            @if ($fifoCost > 0)
                <span class="text-success">R {{ number_format($fifoCost, 2) }}</span>
            @else
                <span class="text-muted">R 0.00</span>
            @endif
        </td>

        {{-- Profit % --}}
        <td>
            @php
                $profitMargin = $p->getProfitMargin();
            @endphp
            @if ($profitMargin > 0)
                <span class="badge bg-success-transparent">{{ number_format($profitMargin, 1) }}%</span>
            @elseif ($profitMargin < 0)
                <span class="badge bg-danger-transparent">{{ number_format($profitMargin, 1) }}%</span>
            @else
                <span class="text-muted">0%</span>
            @endif
        </td>

        {{-- Profit R --}}
        <td>
            @php
                $profitAmount = $p->getProfitAmount();
            @endphp
            @if ($profitAmount > 0)
                <span class="text-success">R {{ number_format($profitAmount, 2) }}</span>
            @elseif ($profitAmount < 0)
                <span class="text-danger">R {{ number_format($profitAmount, 2) }}</span>
            @else
                <span class="text-muted">R 0.00</span>
            @endif
        </td>

        {{-- OE Number --}}
        <td>
            @if ($p->oeNumbers && $p->oeNumbers->count() > 0)
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($p->oeNumbers->take(2) as $oe)
                        <span class="badge bg-primary-transparent rounded-pill" title="{{ $oe->oe_number }}">
                            {{ $oe->oe_number }}
                        </span>
                    @endforeach
                    @if ($p->oeNumbers->count() > 2)
                        <span class="badge bg-secondary-transparent rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $p->oeNumbers->skip(2)->pluck('oe_number')->implode(', ') }}">
                            +{{ $p->oeNumbers->count() - 2 }}
                        </span>
                    @endif
                </div>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- Cross Ref --}}
        <td>
            @if ($p->crossRefs && $p->crossRefs->count() > 0)
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($p->crossRefs->take(2) as $cross)
                        <span class="badge bg-info-transparent rounded-pill" title="{{ $cross->cross_ref }}">
                            {{ $cross->cross_ref }}
                        </span>
                    @endforeach
                    @if ($p->crossRefs->count() > 2)
                        <span class="badge bg-secondary-transparent rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $p->crossRefs->skip(2)->pluck('cross_ref')->implode(', ') }}">
                            +{{ $p->crossRefs->count() - 2 }}
                        </span>
                    @endif
                </div>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>

        {{-- Status --}}
        <td>
            @if ($p->status === 'active')
                <span class="badge rounded-pill bg-success-transparent">Active</span>
            @else
                <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
            @endif
        </td>
        
        <td class="text-end">
            <div class="btn-list d-flex">
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#viewProductModal-{{ $p->id }}" onclick="event.stopPropagation();" title="View Details">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#editProductModal-{{ $p->id }}" onclick="event.stopPropagation();" title="Edit Product">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Print Barcode -->
                <button class="btn btn-sm btn-info-light btn-icon" 
                    onclick="event.stopPropagation(); printSingleBarcode({{ $p->id}}, '{{ $p->name }}', '{{ $p->sku }}', '{{ $p->barcode_primary }}')" 
                    title="Print Barcode">
                    <i class="ri-printer-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#deleteProduct{{ $p->id }}" onclick="event.stopPropagation();" title="Delete Product">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
    
    {{-- Expandable Batches Row --}}
    <tr id="batches-row-{{ $p->id }}" class="batches-row" style="display: none;">
        <td colspan="16">
            <div class="p-3 bg-light">
                <h6 class="mb-3">
                    <i class="ri-boxes-line me-2"></i>FIFO Stock Batches
                </h6>
                @if ($p->stockBatches && $p->stockBatches->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Received Date</th>
                                    <th>Batch ID</th>
                                    <th>Qty Available</th>
                                    <th>Unit Cost</th>
                                    <th>Batch Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($p->stockBatches->sortByDesc('received_date') as $batch)
                                    <tr>
                                        <td>{{ $batch->received_date ? $batch->received_date->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-primary-transparent">{{ $batch->id }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if ($batch->qty_left > 0)
                                                <span class="badge bg-success-transparent">{{ number_format($batch->qty_left, 0) }}</span>
                                            @elseif ($batch->qty_left == 0)
                                                <span class="badge bg-warning-transparent">0</span>
                                            @else
                                                <span class="badge bg-danger-transparent">{{ number_format($batch->qty_left, 0) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">R {{ number_format($batch->landed_unit_cost, 2) }}</td>
                                        <td class="text-end">R {{ number_format($batch->qty_left * $batch->landed_unit_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                        No batches found for this product.
                    </div>
                @endif
            </div>
        </td>
    </tr>
    
    {{-- View Modal --}}
    @include('products._view_modal', ['product' => $p])
    
    {{-- Edit Modal --}}
    @include('products._edit_modal', [
        'product' => $p,
        'brands' => $brands,
        'categories' => $categories,
        'subCategories' => $subCategories,
        'makes' => $makes,
        'models' => $models,
        'engines' => $engines,
        'suppliers' => $suppliers
    ])
    
    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteProduct{{ $p->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('products.destroy', $p->id) }}">
                @csrf @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete product <strong>{{ $p->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@empty
    <tr>
        <td colspan="12" class="text-center text-muted py-4">
            <div class="d-flex flex-column align-items-center">
                <i class="ri-inbox-line fs-48 text-muted mb-2"></i>
                <h6>No products found</h6>
                <p class="text-muted mb-0">Try adjusting your filters or add a new product</p>
            </div>
        </td>
    </tr>
@endforelse

