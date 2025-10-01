@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Purchase Orders</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPoModal">
            <i class="bi bi-plus-circle me-1"></i> Create PO
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->po_number }}</td>
                            <td>{{ $order->supplier->name ?? '-' }}</td>
                            <td>{{ $order->order_date }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                                @if (!isset($order->locked) || !$order->locked)
                                <form method="POST" action="{{ route('purchase-orders.change-status', $order->id) }}" class="d-flex align-items-center gap-2 mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" required>
                                        <option value="draft" @if($order->status=='draft') selected @endif>Draft</option>
                                        <option value="sent" @if($order->status=='sent') selected @endif>Sent</option>
                                        <option value="partially_received" @if($order->status=='partially_received') selected @endif>Partially Received</option>
                                        <option value="completed" @if($order->status=='completed') selected @endif>Completed</option>
                                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                                @else
                                <div class="text-muted small">Locked</div>
                                @endif
                            </td>
                            <td>R {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <button class="btn btn-info btn-sm view-po-btn" data-po-id="{{ $order->id }}">View</button>
                                <button class="btn btn-warning btn-sm edit-po-btn" data-po-id="{{ $order->id }}">Edit</button>
                                <form action="{{ route('purchase-orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this PO?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Create PO Modal -->
    <div class="modal fade" id="createPoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="createPoModalContent">
                @include('purchase_orders.create_modal', ['suppliers' => $suppliers, 'products' => $products])
            </div>
        </div>
    </div>
    <!-- View PO Modal (content loaded dynamically) -->
    <div class="modal fade" id="viewPoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="viewPoModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
    <!-- Edit PO Modal (content loaded dynamically) -->
    <div class="modal fade" id="editPoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="editPoModalContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
<script>
    const routes = {
        viewPoModal: @json(route('purchase-orders.view-modal', ['id' => ':id'])),
        editPoModal: @json(route('purchase-orders.edit-modal', ['id' => ':id']))
    };
</script>


@push('scripts')
<script>
$(function() {
    // View PO
    $('.view-po-btn').on('click', function() {
        var id = $(this).data('po-id');
        var url = routes.viewPoModal.replace(':id', id);

        $('#viewPoModalContent').html('<div class="text-center p-5"><div class="spinner-border"></div></div>');
        $('#viewPoModal').modal('show');

        $.get(url, function(data) {
            $('#viewPoModalContent').html(data);
        });
    });

    // Edit PO
    $('.edit-po-btn').on('click', function() {
        var id = $(this).data('po-id');
        var url = routes.editPoModal.replace(':id', id);

        $('#editPoModalContent').html('<div class="text-center p-5"><div class="spinner-border"></div></div>');
        $('#editPoModal').modal('show');

        $.get(url, function(data) {
            $('#editPoModalContent').html(data);
        });
    });

    // Reset modals on close
    $('#viewPoModal, #editPoModal').on('hidden.bs.modal', function() {
        $(this).find('.modal-content').html('');
    });
});
</script>
@endpush

@endsection
