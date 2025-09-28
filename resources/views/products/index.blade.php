@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Products</h4>
            <div>
                <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#quickAddModal">Quick
                    Add</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Product
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Barcode</th>
                            <th scope="col">Name</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Category</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">On-hand</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td>{{ $p->sku }}</td>
                                <td>{{ $p->barcode }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->brand->name ?? '-' }}</td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td>{{ $p->primarySupplier->name ?? '-' }}</td>
                                <td>
                                    @php $onHand = $p->on_hand_sum ?? 0; @endphp
                                    @if ($onHand < 0)
                                        <span class="text-danger">{{ $onHand }}</span>
                                    @else
                                        {{ $onHand }}
                                    @endif
                                </td>
                                <td>{{ number_format($p->price_normal, 2) }}</td>
                                <td>
                                    @if ($p->status === 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <form method="POST" action="{{ route('products.toggleStatus', $p->id) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $p->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="Toggle Status"
                                                aria-label="Toggle status for product {{ $p->name }}">
                                                <i class="ri-toggle-{{ $p->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-info-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#showProduct{{ $p->id }}" title="View History"
                                            aria-label="View history of product {{ $p->name }}">
                                            <i class="ri-time-line"></i>
                                        </button>

                                        <button class="btn btn-sm btn-primary-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editProductModal-{{ $p->id }}" title="Edit Product"
                                            aria-label="Edit product {{ $p->name }}">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#deleteProduct{{ $p->id }}" title="Delete Product"
                                            aria-label="Delete product {{ $p->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Show (History) Modal --}}

                            {{-- Edit Modal --}}
                            @include('products._edit_modal', ['product' => $p])

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteProduct{{ $p->id }}" tabindex="-1"
                                aria-labelledby="deleteProductLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('products.destroy', $p->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteProductLabel{{ $p->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
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

            <div class="card-footer border-top-0">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Create Modal & Quick Add Modal --}}
        @include('products._create_modal')
        @include('products._quick_add_modal')
    </div>
@endsection
