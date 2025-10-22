@extends('layouts.app')

@push('styles')
    <style>
        .clickable-row {
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f8f9fa !important;
        }
    </style>
    <script>
        // AJAX Filter Functionality
        $(document).ready(function() {
            let searchTimeout;

            // Search input with debounce
            $('input[name="search"]').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    filterPOs();
                }, 500);
            });

            // Select filters with immediate response
            $('select[name="status"], select[name="supplier_id"], input[name="date_from"], input[name="date_to"]')
                .on('change', function() {
                    filterPOs();
                });

            // Filter function
            function filterPOs() {
                const formData = $('#filterForm').serialize();

                $.ajax({
                    url: '{{ route('purchase-orders.index') }}',
                    type: 'GET',
                    data: formData,
                    beforeSend: function() {
                        // Show loading overlay
                        $('.table-responsive').append(
                            '<div class="position-absolute top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                    success: function(response) {
                        // Parse the response and update the table
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(response, 'text/html');
                        const newTable = doc.querySelector('.table');
                        const newPagination = doc.querySelector('.pagination');

                        if (newTable) {
                            $('.table').replaceWith(newTable);
                        }

                        if (newPagination) {
                            $('.pagination').replaceWith(newPagination);
                        }

                        // Update URL without page reload
                        const url = new URL(window.location);
                        url.search = new URLSearchParams(formData).toString();
                        window.history.pushState({}, '', url);

                        // Re-initialize click handlers for new content
                        initializeRowClickHandlers();
                    },
                    error: function(xhr) {
                        console.error('Filter error:', xhr);
                        toastr.error('Failed to filter purchase orders. Please try again.');
                    },
                    complete: function() {
                        $('.spinner-border').remove();
                    }
                });
            }

            // Initialize row click handlers
            function initializeRowClickHandlers() {
                $('.clickable-row').off('click').on('click', function(e) {
                    if (!$(e.target).closest('button, a').length) {
                        const poId = $(this).data('po-id');
                        if (poId) {
                            openViewModal(poId);
                        }
                    }
                });
            }

            // Initialize on page load
            initializeRowClickHandlers();
        });

        // Open view modal
        function openViewModal(poId) {
            $.ajax({
                url: '{{ route('purchase-orders.view-modal', ':id') }}'.replace(':id', poId),
                method: 'GET',
                success: function(response) {
                    $('#poModalContent').html(response);
                    $('#poModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Failed to load purchase order details');
                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 me-3">Purchase Orders</h4>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary-light btn-wave me-2 waves-effect waves-light"
                    id="createPurchaseOrderBtn">
                    <i class="ri-add-line me-1"></i> Create PO
                </button>

            </div>
        </div>

        <!-- Filters Section -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('purchase-orders.index') }}" id="filterForm">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Search by PO #, Supplier, Notes...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="partially_received"
                                    {{ request('status') == 'partially_received' ? 'selected' : '' }}>Partially Received
                                </option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="supplier_id">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}"
                                placeholder="Date From">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}"
                                placeholder="Date To">
                        </div>
                        <div class="col-md-1">
                            <div class="d-grid gap-1">
                                <button type="button" class="btn btn-outline-info"
                                    onclick="window.location.href='{{ route('purchase-orders.index') }}'">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Purchase Orders Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="card-title">
                        Purchase Orders<span
                            class="badge bg-light text-default rounded ms-1 fs-12 align-middle">{{ $purchaseOrders->total() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Print & Export Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="printSelectedPOs()">
                                        <i class="ri-printer-line me-2 text-secondary"></i>Print
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('purchase-orders.export', ['format' => 'pdf']) }}">
                                        <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('purchase-orders.export', ['format' => 'csv']) }}">
                                        <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('purchase-orders.export', ['format' => 'excel']) }}">
                                        <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="table-responsive position-relative">
                    <table class="table table-striped align-middle table-hover" id="purchaseOrdersTable">
                        <thead class="table-light">
                            <tr>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Order Date</th>
                                <th>Expected Delivery</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Grand Total</th>
                                <th>Created By</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="grn_list_tbody            ">
                            @forelse($purchaseOrders as $purchaseOrder)
                                <tr class="clickable-row" data-po-id="{{ $purchaseOrder->id }}">
                                    <td>
                                        <div class="fw-semibold text-primary">{{ $purchaseOrder->po_number }}</div>
                                        @if ($purchaseOrder->notes)
                                            <small class="text-muted">{{ Str::limit($purchaseOrder->notes, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</div>
                                        @if ($purchaseOrder->supplier)
                                            <small class="text-muted">{{ $purchaseOrder->supplier->email ?? '' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $purchaseOrder->order_date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $purchaseOrder->order_date->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if ($purchaseOrder->expected_delivery_date)
                                            <div>{{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}</div>
                                            @if ($purchaseOrder->expected_delivery_date < now())
                                                <small class="text-danger">
                                                    <i class="ri-time-line me-1"></i>Overdue
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'draft' => 'bg-secondary-transparent',
                                                'approved' => 'bg-success-transparent',
                                                'sent' => 'bg-info-transparent',
                                                'partially_received' => 'bg-warning-transparent',
                                                'closed' => 'bg-primary-transparent',
                                                'completed' => 'bg-success-transparent',
                                                'cancelled' => 'bg-danger-transparent',
                                            ];
                                            $statusClass =
                                                $statusClasses[$purchaseOrder->status] ?? 'bg-secondary-transparent';
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $purchaseOrder->items->count() }} items</div>
                                        <small class="text-muted">
                                            Qty: {{ $purchaseOrder->items->sum('quantity') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-success">
                                            R {{ number_format($purchaseOrder->grand_total, 2) }}
                                        </div>
                                        @if ($purchaseOrder->vat_enabled && $purchaseOrder->vat > 0)
                                            <small class="text-muted">VAT: R
                                                {{ number_format($purchaseOrder->vat, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $purchaseOrder->user->name ?? 'N/A' }}</div>
                                        <small
                                            class="text-muted">{{ $purchaseOrder->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-sm btn-info-light btn-icon view-po-btn"
                                                data-po-id="{{ $purchaseOrder->id }}" title="View">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            @if (in_array($purchaseOrder->status, ['draft', 'approved']))
                                                <button type="button"
                                                    class="btn btn-sm btn-success-light btn-icon edit-po-btn"
                                                    data-po-id="{{ $purchaseOrder->id }}" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                            @endif
                                            @if ($purchaseOrder->status === 'draft')
                                                <button type="button"
                                                    class="btn btn-sm btn-success-light btn-icon approve-po-btn"
                                                    data-po-id="{{ $purchaseOrder->id }}" title="Approve">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                            @endif
                                            @if (in_array($purchaseOrder->status, ['approved', 'sent', 'partially_received']))
                                                <button type="button"
                                                    class="btn btn-sm btn-warning-light btn-icon create-grn-btn"
                                                    data-po-id="{{ $purchaseOrder->id }}" title="Create GRN">
                                                    <i class="ri-truck-line"></i>
                                                </button>
                                            @endif
                                            <button type="button"
                                                class="btn btn-sm btn-primary-light btn-icon print-po-btn"
                                                data-po-id="{{ $purchaseOrder->id }}" title="Print">
                                                <i class="ri-printer-line"></i>
                                            </button>
                                            @if (!in_array($purchaseOrder->status, ['completed', 'cancelled', 'closed']))
                                                <button type="button"
                                                    class="btn btn-sm btn-danger-light btn-icon delete-po-btn"
                                                    data-po-id="{{ $purchaseOrder->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="ri-file-list-line fs-1 d-block mb-2"></i>
                                        <p>No purchase orders found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($purchaseOrders->hasPages())
                <div class="card-footer">
                    {{ $purchaseOrders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="poModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="poModalContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this purchase order?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            // Global variables
            let deletePoId = null;

            // Create Purchase Order
            $('#createPurchaseOrderBtn, #createPurchaseOrderBtnEmpty').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Ensure table stays visible

                loadCreateModal();
            });

            // View Purchase Order (row click handles this now)
            // Keep view button for explicit clicks
            $(document).on('click', '.view-po-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const poId = $(this).data('po-id');
                openViewModal(poId);
            });

            // Edit Purchase Order
            $(document).on('click', '.edit-po-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const poId = $(this).data('po-id');
                loadEditModal(poId);
            });

            // Print Purchase Order
            $(document).on('click', '.print-po-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const poId = $(this).data('po-id');
                printPurchaseOrder(poId);
            });

            // Delete Purchase Order
            $(document).on('click', '.delete-po-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                deletePoId = $(this).data('po-id');
                $('#deleteConfirmModal').modal('show');
            });

            // Confirm Delete
            $('#confirmDeleteBtn').on('click', function() {
                if (deletePoId) {
                    deletePurchaseOrder(deletePoId);
                }
            });

            // Approve Purchase Order
            $(document).on('click', '.approve-po-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const poId = $(this).data('po-id');
                const btn = $(this);

                if (!confirm('Are you sure you want to approve this purchase order?')) {
                    return;
                }

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: '{{ route('purchase-orders.approve', ':id') }}'.replace(':id', poId),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                            btn.prop('disabled', false).html('<i class="ri-check-line"></i>');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to approve purchase order');
                        btn.prop('disabled', false).html('<i class="ri-check-line"></i>');
                    }
                });
            });

            // Create GRN from PO
            $(document).on('click', '.create-grn-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const poId = $(this).data('po-id');
                openCreateGRNModal(poId);
            });

            function openCreateGRNModal(poId) {
                // Load PO items via AJAX
                $.ajax({
                    url: '{{ url('goods-receipts/po') }}/' + poId + '/items',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Populate modal with PO data
                            $('#grnModalLabel').text('Receive Stock for ' + response.po.po_number);
                            $('#grnPoId').val(response.po.id);
                            $('#grnSupplierId').val(response.po.supplier.id);

                            // Build items table
                            let itemsHtml = '';
                            response.items.forEach(function(item, index) {
                                const receivedQty = item.received_qty || 0;
                                const outstanding = item.quantity - receivedQty;
                                itemsHtml += `
                            <tr data-po-item-id="${item.id}">
                                <td>${item.product_name}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td class="text-center">${receivedQty}</td>
                                <td class="text-center fw-bold text-primary">${outstanding}</td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm text-center grn-receiving-qty" 
                                           value="${outstanding}" min="0" max="${outstanding}" step="1" 
                                           data-po-item-id="${item.id}" data-product-id="${item.product_id}">
                                </td>
                                <td class="text-center">
                                    <input type="number" class="form-control form-control-sm text-center grn-unit-cost" 
                                           value="${item.unit_price}" min="0" step="0.01" 
                                           data-po-item-id="${item.id}">
                                </td>
                            </tr>
                        `;
                            });

                            // Calculate initial total
                            calculateGRNTotals();

                            $('#grnItemsBody').html(itemsHtml);

                            // Show modal
                            $('#grnModal').modal('show');
                        } else {
                            toastr.error(response.message || 'Failed to load PO items');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error loading PO items');
                    }
                });
            }

            // Calculate GRN totals
            function calculateGRNTotals() {
                let total = 0;
                $('#grnItemsBody tr').each(function() {
                    const qty = parseFloat($(this).find('.grn-receiving-qty').val()) || 0;
                    const cost = parseFloat($(this).find('.grn-unit-cost').val()) || 0;
                    total += qty * cost;
                });
                $('#grnTotalDisplay').text('R ' + total.toFixed(2));
                $('#grnTotalAmount').val(total.toFixed(2));
            }

            // Calculate totals when qty or cost changes
            $(document).on('input', '.grn-receiving-qty, .grn-unit-cost', function() {
                calculateGRNTotals();
            });

            // Submit GRN
            $('#grnForm').on('submit', function(e) {
                e.preventDefault();

                // Collect items
                const items = [];
                $('#grnItemsBody tr').each(function() {
                    const poItemId = $(this).data('po-item-id');
                    const productId = $(this).find('.grn-receiving-qty').data('product-id');
                    const receivingQty = parseFloat($(this).find('.grn-receiving-qty').val()) || 0;
                    const unitCost = parseFloat($(this).find('.grn-unit-cost').val()) || 0;

                    if (receivingQty > 0) {
                        items.push({
                            purchase_order_item_id: poItemId,
                            product_id: productId,
                            ordered_qty: $(this).find('td:eq(1)').text(),
                            received_qty: receivingQty,
                            unit_cost: unitCost,
                            line_total: receivingQty * unitCost
                        });
                    }
                });

                if (items.length === 0) {
                    toastr.error('Please enter at least one item to receive');
                    return;
                }

                // Prepare form data
                const formData = {
                    supplier_id: $('#grnSupplierId').val(),
                    purchase_order_id: $('#grnPoId').val(),
                    received_date: $('#grnReceivedDate').val(),
                    invoice_number: $('#grnInvoiceNumber').val(),
                    notes: $('#grnNotes').val(),
                    total_amount: $('#grnTotalAmount').val(),
                    items: items
                };

                // Disable submit button
                const submitBtn = $('#grnSubmitBtn');
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Receiving...');

                // Submit via AJAX
                $.ajax({
                    url: '{{ route('goods-receipts.store') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message || 'Stock received successfully!');
                            $('#grnModal').modal('hide');
                            window.location.reload();
                        } else {
                            toastr.error(response.message || 'Failed to receive stock');
                            submitBtn.prop('disabled', false).html(
                                '<i class="ri-truck-line me-1"></i> Receive Stock');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        let errorMessage = 'Failed to receive stock';

                        if (response && response.message) {
                            errorMessage = response.message;
                        } else if (response && response.errors) {
                            errorMessage = Object.values(response.errors).flat().join('<br>');
                        }

                        toastr.error(errorMessage);
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-truck-line me-1"></i> Receive Stock');
                    }
                });
            });


            // Modal close handler
            $('#poModal').on('hidden.bs.modal', function() {
                $('#poModalContent').html('');
                // Ensure table stays visible
                // $('#purchaseOrdersTable').css('display', 'block').show();
            });

            // Functions
            function loadCreateModal() {
                $('#poModalContent').html(
                    '<div class="text-center p-5"><div class="spinner-border"></div><div class="mt-2">Loading...</div></div>'
                    );
                $('#poModal').modal('show');

                $.get('{{ route('purchase-orders.create-modal') }}')
                    .done(function(data) {
                        $('#poModalContent').html(data);
                    })
                    .fail(function() {
                        $('#poModalContent').html(
                            '<div class="text-center p-5 text-danger">Failed to load create modal</div>');
                    });
            }


            function loadEditModal(poId) {
                $('#poModalContent').html(
                    '<div class="text-center p-5"><div class="spinner-border"></div><div class="mt-2">Loading...</div></div>'
                    );
                $('#poModal').modal('show');

                $.get('{{ route('purchase-orders.edit-modal', ':id') }}'.replace(':id', poId))
                    .done(function(data) {
                        $('#poModalContent').html(data);
                    })
                    .fail(function() {
                        $('#poModalContent').html(
                            '<div class="text-center p-5 text-danger">Failed to load edit modal</div>');
                    });
            }

            function printPurchaseOrder(poId) {
                const printUrl = '{{ route('purchase-orders.print', ':id') }}'.replace(':id', poId);

                // Create hidden iframe for printing
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = printUrl;
                document.body.appendChild(iframe);

                iframe.onload = function() {
                    setTimeout(function() {
                        iframe.contentWindow.print();
                        setTimeout(function() {
                            document.body.removeChild(iframe);
                        }, 1000);
                    }, 500);
                };
            }

            function deletePurchaseOrder(poId) {
                $.ajax({
                    url: '{{ route('purchase-orders.destroy', ':id') }}'.replace(':id', poId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Failed to delete purchase order');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        toastr.error(response?.message || 'Failed to delete purchase order');
                    }
                });

                $('#deleteConfirmModal').modal('hide');
            }
        });

        // Export functions - Now handled by direct route links in dropdown

        function printSelectedPOs() {
            // Print all POs (with current filters applied)
            const params = new URLSearchParams(window.location.search);
            params.append('print', 'all');

            const printUrl = '{{ route('purchase-orders.index') }}?' + params.toString();
            window.open(printUrl, '_blank');
        }

        // Make printPurchaseOrder globally accessible
        window.printPurchaseOrder = function(poId) {
            const printUrl = '{{ route('purchase-orders.print', ':id') }}'.replace(':id', poId);

            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = printUrl;
            document.body.appendChild(iframe);

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.print();
                    setTimeout(function() {
                        document.body.removeChild(iframe);
                    }, 1000);
                }, 500);
            };
        };
    </script>
@endpush

<!-- GRN Modal -->
<div class="modal fade" id="grnModal" tabindex="-1" aria-labelledby="grnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="grnModalLabel">
                    <i class="ri-truck-line me-2"></i> Receive Stock for PO
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="grnForm">
                @csrf
                <div class="modal-body">
                    <!-- Basic Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-information-line me-1"></i> Basic Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Received Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="grnReceivedDate"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Supplier Invoice Number (Optional)</label>
                                    <input type="text" class="form-control" id="grnInvoiceNumber"
                                        placeholder="Enter if you want to create a PI automatically">
                                </div>
                                <input type="hidden" id="grnSupplierId">
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" id="grnNotes" rows="2" placeholder="Add any notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-shopping-cart-line me-1"></i> Items to Receive
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Ordered</th>
                                            <th class="text-center">Received</th>
                                            <th class="text-center">Outstanding</th>
                                            <th class="text-center">Receiving Now</th>
                                            <th class="text-center">Unit Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grnItemsBody">
                                        <!-- Items will be populated here -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="5" class="text-end fw-bold">Total:</td>
                                            <td class="text-center fw-bold fs-18" id="grnTotalDisplay">R 0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" id="grnPoId">
                    <input type="hidden" id="grnTotalAmount" value="0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="grnSubmitBtn">
                        <i class="ri-truck-line me-1"></i> Receive Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
