@php
    use Milon\Barcode\DNS1D;
    $barcodeGen = new DNS1D();
@endphp
{{-- resources/views/products/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Products</h4>
            <div>
                <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                    <i class="bi bi-lightning-fill me-1"></i> Quick Add
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Product
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, SKU, barcode..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="brand_id" class="form-select">
                                <option value="">All Brands</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-2">
                            <select name="stock_status" class="form-select">
                                <option value="">All Stock</option>
                                <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock
                                </option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock
                                </option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of
                                    Stock</option>
                                <option value="negative" {{ request('stock_status') == 'negative' ? 'selected' : '' }}>
                                    Negative</option>
                            </select>
                        </div> --}}
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div class="col-md-12 col-lg-auto">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>On-hand</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td><span class="badge bg-secondary-transparent rounded-pill">{{ $p->sku }}</span>
                                </td>
                                <td>
                                    @if ($p->barcode_primary)
                                        @php
                                            $barcodePng = $barcodeGen->getBarcodePNG(
                                                $p->barcode_primary,
                                                'C128',
                                                1.2,
                                                40,
                                            );
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="data:image/png;base64,{{ $barcodePng }}" alt="Barcode"
                                                id="barcode-img-{{ $p->id }}" width="120" height="40"
                                                style="object-fit:contain;max-width:120px;max-height:40px;">
                                            <a href="data:image/png;base64,{{ $barcodePng }}"
                                                download="barcode-{{ $p->barcode_primary }}.png"
                                                class="btn btn-sm btn-light btn-icon" title="Download Barcode">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                        <div style="font-size: 9px;">{{ $p->barcode_primary }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->brand->name ?? '-' }}</td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td>
                                    @if ($p->suppliers->count())
                                        @foreach ($p->suppliers->take(5) as $supplier)
                                            {{-- sirf 5 show karo --}}
                                            <span
                                                class="badge bg-primary-transparent rounded-pill mt-1">{{ $supplier->name }}</span>
                                        @endforeach

                                        @if ($p->suppliers->count() > 5)
                                            <span class="badge bg-secondary-transparent rounded-pill">
                                                +{{ $p->suppliers->count() - 5 }} more
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @php $onHand = $p->on_hand_sum ?? 0; @endphp
                                    @if ($onHand < 0)
                                        <span class="badge bg-danger-light rounded-pill">NEGATIVE
                                            ({{ $onHand }})
                                        </span>
                                    @elseif ($onHand == 0)
                                        <span class="badge bg-warning-light text-dark rounded-pill">OUT OF STOCK</span>
                                    @elseif ($onHand <= $p->reorder_level)
                                        <span class="badge bg-info-light text-dark rounded-pill">LOW
                                            ({{ $onHand }})</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-transparent rounded-pill">{{ $onHand }}</span>
                                    @endif
                                </td>
                                <td>R {{ number_format($p->price_normal, 2) }}</td>
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
                                        <form method="POST" action="{{ route('products.toggleStatus', $p->id) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $p->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="Toggle Status">
                                                <i class="ri-toggle-{{ $p->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>
                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editProductModal-{{ $p->id }}" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>
                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteProduct{{ $p->id }}" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            {{-- Edit Modal --}}
                            @include('products._edit_modal', ['product' => $p])
                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteProduct{{ $p->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('products.destroy', $p->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete product
                                                <strong>{{ $p->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>{{-- Modals --}}
        @include('products._create_modal')
        @include('products._quick_add_modal')
    </div>
@endsection
