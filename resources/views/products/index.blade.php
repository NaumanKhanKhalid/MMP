@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Products</h4>
    <div>
      <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#quickAddModal">Quick Add</button>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
        <i class="bi bi-plus-circle me-1"></i> Add Product
      </button>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>#</th><th>SKU</th><th>Barcode</th><th>Name</th><th>Brand</th><th>Category</th>
            <th>Supplier</th><th>On-hand</th><th>Price</th><th>Status</th><th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $p)
            <tr>
              <td>{{ $loop->iteration + ($products->currentPage()-1)*$products->perPage() }}</td>
              <td>{{ $p->sku }}</td>
              <td>{{ $p->barcode }}</td>
              <td>{{ $p->name }}</td>
              <td>{{ $p->brand->name ?? '-' }}</td>
              <td>{{ $p->category->name ?? '-' }}</td>
              <td>{{ $p->primarySupplier->name ?? '-' }}</td>
              <td>
                @php $onHand = $p->on_hand_sum ?? 0; @endphp
                @if($onHand < 0) <span class="text-danger">{{ $onHand }}</span>
                @else {{ $onHand }} @endif
              </td>
              <td>{{ number_format($p->price_normal,2) }}</td>
              <td>
                @if($p->status === 'active') <span class="badge rounded-pill bg-success-transparent">Active</span>
                @else <span class="badge rounded-pill bg-secondary-transparent">Inactive</span> @endif
              </td>
              <td class="text-end">
                <div class="btn-list">
                  <form method="POST" action="{{ route('products.toggleStatus', $p->id) }}" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm {{ $p->status==='active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon" title="Toggle">
                      <i class="ri-toggle-{{ $p->status==='active' ? 'line' : 'fill' }}"></i>
                    </button>
                  </form>

                  <button class="btn btn-sm btn-info-light btn-icon" data-bs-toggle="modal" data-bs-target="#showProduct{{ $p->id }}" title="History"><i class="ri-time-line"></i></button>

                  <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal" data-bs-target="#editProduct{{ $p->id }}"><i class="ri-pencil-line"></i></button>

                  <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal" data-bs-target="#deleteProduct{{ $p->id }}"><i class="ri-delete-bin-line"></i></button>
                </div>
              </td>
            </tr>

            {{-- Show (History) Modal --}}
            <div class="modal fade" id="showProduct{{ $p->id }}" tabindex="-1">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header"><h5 class="modal-title">Product History: {{ $p->name }} ({{ $p->sku }})</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                  <div class="modal-body">
                    <div class="row mb-3">
                      <div class="col-md-4"><strong>Barcode:</strong> {{ $p->barcode }}</div>
                      <div class="col-md-4"><strong>On-hand:</strong> {{ $p->on_hand_sum ?? 0 }}</div>
                      <div class="col-md-4"><strong>Bin:</strong> {{ $p->bin_location ?? '-' }}</div>
                    </div>

                    <h6>Batches (FIFO)</h6>
                    <table class="table table-sm">
                      <thead><tr><th>Batch</th><th>Received Date</th><th>Qty Received</th><th>Qty Left</th><th>Unit Cost</th></tr></thead>
                      <tbody>
                        @foreach($p->stockBatches()->orderBy('received_date','desc')->get() as $b)
                          <tr>
                            <td>{{ $b->batch_code ?? '-'.$b->id }}</td>
                            <td>{{ $b->received_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $b->qty_received }}</td>
                            <td @if($b->qty_left < 0) class="text-danger" @endif>{{ $b->qty_left }}</td>
                            <td>{{ number_format($b->landed_unit_cost,4) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                    <h6 class="mt-3">Stock Ledger (last 20)</h6>
                    <table class="table table-sm">
                      <thead><tr><th>Date</th><th>Doc</th><th>Qty</th><th>Unit Cost</th><th>Total</th><th>User</th><th>Notes</th></tr></thead>
                      <tbody>
                        @foreach($p->stockLedger()->orderByDesc('created_at')->limit(20)->get() as $l)
                          <tr>
                            <td>{{ $l->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $l->document_type }} #{{ $l->document_id }}</td>
                            <td @if($l->qty < 0) class="text-danger" @endif>{{ $l->qty }}</td>
                            <td>{{ number_format($l->unit_cost,4) }}</td>
                            <td>{{ number_format($l->total_cost,4) }}</td>
                            <td>{{ $l->user?->name ?? '-' }}</td>
                            <td>{{ $l->notes }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>
                  <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Close</button></div>
                </div>
              </div>
            </div>

            {{-- Edit Modal --}}
            <!-- similar to controller fields: see previous message for full edit modal markup -->
            @include('products._edit_modal', ['product'=>$p])

            {{-- Delete Modal --}}
            <div class="modal fade" id="deleteProduct{{ $p->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" action="{{ route('products.destroy', $p->id) }}">
                  @csrf @method('DELETE')
                  <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">Delete product <strong>{{ $p->name }}</strong> ?</div>
                    <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger">Delete</button></div>
                  </div>
                </form>
              </div>
            </div>

          @empty
            <tr><td colspan="11" class="text-center text-muted">No products found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer border-top-0">{{ $products->links() }}</div>
  </div>

  {{-- Create Modal + Quick Add Modal (see previous messages for full markup) --}}
  @include('products._create_modal')
  @include('products._quick_add_modal')
</div>
@endsection
