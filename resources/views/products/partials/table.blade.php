@forelse ($products as $p)
    <tr class="clickable-row" onclick="openViewModal('{{ $p->id }}')" style="cursor: pointer;">
        <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>

        {{-- Product Name --}}
        <td>
            <div class="d-flex">
                <span class="avatar avatar-md avatar-square bg-primary-transparent p-1">
                    <img src="{{ $p->primary_image_url }}" class="w-100 h-100" alt="{{ $p->name }}">
                </span>
                <div class="ms-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center">
                        <a href="{{ route('products.show', $p->id) }}">{{ $p->name }}</a>
                    </p>
                    <p class="fs-12 text-muted mb-0">SKU: {{ $p->sku }}</p>
                </div>
            </div>
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
            <div class="btn-list">
                <!-- Toggle Status -->
                <form method="POST" action="{{ route('products.toggleStatus', $p->id) }}" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $p->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                        title="{{ $p->status === 'active' ? 'Deactivate' : 'Activate' }}">
                        <i class="ri-toggle-{{ $p->status === 'active' ? 'line' : 'fill' }}"></i>
                    </button>
                </form>
                <!-- View -->
                <button class="btn btn-sm btn-primary-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#viewProductModal-{{ $p->id }}" title="View">
                    <i class="ri-eye-line"></i>
                </button>
                <!-- Edit -->
                <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#editProductModal-{{ $p->id }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                </button>
                <!-- Print Barcode -->
                <button class="btn btn-sm btn-info-light btn-icon" 
                    onclick="printSingleBarcode({{ $p->id}}, '{{ $p->name }}', '{{ $p->sku }}', '{{ $p->barcode_primary }}')" 
                    title="Print Barcode">
                    <i class="ri-printer-line"></i>
                </button>
                <!-- Delete -->
                <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                    data-bs-target="#deleteProduct{{ $p->id }}" title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
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

