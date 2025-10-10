@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Goods Receipts (GRN)</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGoodsReceiptModal">
                <i class="bi bi-plus-circle me-1"></i> Create GRN
            </button>
        </div>
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>GRN Number</th>
                            <th>Supplier</th>
                            <th>PO Number</th>
                            <th>Received Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grns as $grn)
                            <tr>
                                <td>{{ $grn->grn_number }}</td>
                                <td>{{ $grn->supplier->name }}</td>
                                <td>
                                    @if($grn->purchaseOrder)
                                        <a href="#" class="text-decoration-underline text-primary view-po-btn" data-po-id="{{ $grn->purchaseOrder->id }}">{{ $grn->purchaseOrder->po_number }}</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($grn->received_date)->format('Y-m-d') }}</td>
                                <td>{{ ucfirst($grn->status) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary-light btn-icon view-grn-btn" data-grn-id="{{ $grn->id }}" title="View">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    {{-- <button class="btn btn-warning btn-sm edit-grn-btn" data-grn-id="{{ $grn->id }}">Edit</button>
                                    <form action="{{ route('goods-receipts.destroy', $grn->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this GRN?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Delete</button></form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $grns->links() }}
            </div>
        </div>

        <!-- Single View Modal (content loaded dynamically) -->
        <div class="modal fade" id="viewGrnModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="viewGrnModalContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
        <!-- Single Edit Modal (content loaded dynamically) -->
        <div class="modal fade" id="editGrnModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="editGrnModalContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>

        @include('goods_receipts._create_modal')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Blade se base URL inject karo
            const viewUrl = @json(route('goods-receipts.view-modal', ':id'));
            const editUrl = @json(route('goods-receipts.edit-modal', ':id'));

            // View
            document.querySelectorAll('.view-grn-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const grnId = this.getAttribute('data-grn-id');
                    const url = viewUrl.replace(':id', grnId);

                    fetch(url)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('viewGrnModalContent').innerHTML = html;
                            new bootstrap.Modal(document.getElementById('viewGrnModal')).show();
                        });
                });
            });

            // Edit
            document.querySelectorAll('.edit-grn-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const grnId = this.getAttribute('data-grn-id');
                    const url = editUrl.replace(':id', grnId);

                    fetch(url)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('editGrnModalContent').innerHTML = html;
                            new bootstrap.Modal(document.getElementById('editGrnModal')).show();
                        });
                });
            });
        });
    </script>
    {{ $grns->links() }}

    {{-- Create Modal --}}
    @include('goods_receipts._create_modal')

    </div>

    {{-- JS for dynamic product rows in Create modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productIndex = 1;

            document.getElementById('addProductRow').addEventListener('click', function() {
                const container = document.getElementById('productRows');
                const newRow = document.querySelector('.product-row').cloneNode(true);

                newRow.querySelectorAll('select, input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\d+/, productIndex);
                        input.setAttribute('name', newName);
                        if (input.tagName === 'SELECT') {
                            input.selectedIndex = 0;
                        } else {
                            input.value = '';
                        }
                    }
                });

                container.appendChild(newRow);

                newRow.querySelector('.remove-product').addEventListener('click', function() {
                    newRow.remove();
                });

                productIndex++;
            });

            document.querySelectorAll('.remove-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    btn.closest('.product-row').remove();
                });
            });
        });
    </script>
@endsection
