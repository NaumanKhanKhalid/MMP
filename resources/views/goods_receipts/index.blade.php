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

        // Search input with debounce (only for filter form, not modal)
        $('#filterForm input[name="search"]').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                filterGRNs();
            }, 500);
        });

        // Select filters with immediate response (only for filter form, not modal)
        $('#filterForm select[name="status"], #filterForm select[name="supplier_id"], #filterForm input[name="date_from"], #filterForm input[name="date_to"]').on('change', function() {
            filterGRNs();
        });

        // Filter function
        function filterGRNs() {
            const formData = $('#filterForm').serialize();

            $.ajax({
                url: '{{ route('goods-receipts.index') }}',
                type: 'GET',
                data: formData,
                beforeSend: function() {
                    // Show loading overlay (only in main GRN list, not in modals)
                    $('#mainGrnListContainer').append(
                        '<div class="position-absolute top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );
                },
                success: function(response) {
                    // Parse the response and update the table
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(response, 'text/html');
                    const newTable = doc.querySelector('#mainGrnListTable');
                    const newPagination = doc.querySelector('#paginationContainer');

                    if (newTable) {
                        // Only update the main GRN list table, not modal tables
                        $('#mainGrnListTable').replaceWith(newTable);
                    }

                    if (newPagination) {
                        // Only update pagination in the main GRN list container, not in modals
                        $('#paginationContainer').replaceWith(newPagination);
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
                    toastr.error('Failed to filter GRNs. Please try again.');
                },
                complete: function() {
                    // Remove spinner only from main GRN list, not from modals
                    $('#mainGrnListContainer .spinner-border').parent().remove();
                }
            });
        }

        // Initialize row click handlers (only for main GRN list, not modals)
        function initializeRowClickHandlers() {
            $('#mainGrnListTable .clickable-row').off('click').on('click', function(e) {
                if (!$(e.target).closest('button, a').length) {
                    const grnId = $(this).data('grn-id');
                    if (grnId) {
                        openViewModal(grnId);
                    }
                }
            });
        }

        // Initialize on page load
        initializeRowClickHandlers();
    });

    // Open view modal
    function openViewModal(grnId) {
        const url = '{{ route("goods-receipts.view-modal", ":id") }}'.replace(':id', grnId);
        
        // Create view modal if it doesn't exist
        if (!$('#viewGrnModal').length) {
            $('body').append('<div class="modal fade" id="viewGrnModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl"><div class="modal-content" id="viewGrnModalContent"></div></div></div>');
        }
        
        $.ajax({
            url: url,
            method: 'GET',
            beforeSend: function() {
                $('#viewGrnModalContent').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div><div class="mt-2">Loading GRN details...</div></div>');
                $('#viewGrnModal').modal('show');
            },
            success: function(response) {
                $('#viewGrnModalContent').html(response);
            },
            error: function(xhr) {
                console.error('Error loading GRN:', xhr);
                $('#viewGrnModalContent').html('<div class="text-center p-5 text-danger"><i class="ri-error-warning-line fs-1"></i><div class="mt-2">Failed to load GRN details</div></div>');
                toastr.error('Failed to load GRN details');
            }
        });
    }

    // Post GRN
    $(document).on('click', '.post-grn-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const grnId = $(this).data('grn-id');
        const $btn = $(this);
        
        // if (!confirm('Are you sure you want to post this GRN? This will update stock and cannot be undone.')) {
        //     return;
        // }

        const url = '{{ route("goods-receipts.post", ":id") }}'.replace(':id', grnId);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to post GRN');
                }
            },
            error: function(xhr) {
                console.error('Error posting GRN:', xhr);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('Failed to post GRN');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="ri-check-line"></i>');
            }
        });
    });
</script>
@endpush

@section('content')
    <div class="container-fluid py-4">
    <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h4 class="mb-0 me-3">Goods Receipts (GRN)</h4>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <!-- Create GRN Button -->
            <button class="btn btn-primary-light btn-wave me-2 waves-effect waves-light" data-bs-toggle="modal"
                data-bs-target="#createGoodsReceiptModal" title="Create New GRN">
                <i class="ri-add-line me-1"></i> Create GRN
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('goods-receipts.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by GRN number, PO number..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="supplier_id" class="form-select">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control"
                            placeholder="Date From" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control"
                            placeholder="Date To" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <div class="d-grid gap-1">
                            <button type="button" class="btn btn-outline-info" onclick="window.location.href='{{ route('goods-receipts.index') }}'">
                                Reset
            </button>
        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- GRN Table -->
        <div class="card shadow-sm">
        <div class="card-body">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="card-title">
                    Goods Receipts<span
                        class="badge bg-light text-default rounded ms-1 fs-12 align-middle">{{ $grns->total() }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <!-- Print & Export Dropdown -->
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="printGRNs()">
                                <i class="ri-printer-line me-2 text-secondary"></i>Print
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('goods-receipts.export', ['format' => 'pdf']) }}">
                                <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('goods-receipts.export', ['format' => 'csv']) }}">
                                <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('goods-receipts.export', ['format' => 'excel']) }}">
                                <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-responsive position-relative" id="mainGrnListContainer">
                <table class="table table-striped align-middle table-hover" id="mainGrnListTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>GRN Number</th>
                            <th>Supplier</th>
                            <th>PO Number</th>
                            <th>Received Date</th>
                            <th>Total Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grns as $grn)
                            <tr class="clickable-row" data-grn-id="{{ $grn->id }}">
                                <td>{{ $loop->iteration + ($grns->currentPage() - 1) * $grns->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $grn->grn_number }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $grn->supplier->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $grn->supplier->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if($grn->purchaseOrder)
                                        <a href="javascript:void(0);" class="text-decoration-underline text-primary view-po-btn" 
                                           data-po-id="{{ $grn->purchaseOrder->id }}" 
                                           onclick="event.stopPropagation(); openPOModal({{ $grn->purchaseOrder->id }});">
                                            {{ $grn->purchaseOrder->po_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $grn->received_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-info-transparent rounded-pill">{{ $grn->items->count() }} items</span>
                                </td>
                                <td>
                                    <span class="fw-semibold">R {{ number_format($grn->total_amount ?? 0, 2) }}</span>
                                </td>
                                <td>
                                    @if($grn->status === 'pending')
                                        <span class="badge rounded-pill bg-warning-transparent">Pending</span>
                                    @elseif($grn->status === 'completed')
                                        <span class="badge rounded-pill bg-success-transparent">Completed</span>
                                    @elseif($grn->status === 'cancelled')
                                        <span class="badge rounded-pill bg-danger-transparent">Cancelled</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">{{ ucfirst($grn->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View -->
                                        <button class="btn btn-sm btn-info-light btn-icon view-grn-btn" 
                                                data-grn-id="{{ $grn->id }}" title="View Details"
                                                onclick="openViewModal({{ $grn->id }});">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                        <!-- Print -->
                                        <button type="button" class="btn btn-sm btn-primary-light btn-icon" 
                                                title="Print GRN"
                                                onclick="printGRN({{ $grn->id }});">
                                            <i class="ri-printer-line"></i>
                                        </button>
                                        @if($grn->status === 'pending')
                                            <!-- Post GRN -->
                                            <button class="btn btn-sm btn-success-light btn-icon post-grn-btn" 
                                                    data-grn-id="{{ $grn->id }}" title="Post GRN">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No GRNs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
            <div class="card-footer">
                <div id="paginationContainer">
                    @include('goods_receipts.partials.pagination')
                </div>
            </div>
        </div>

    <!-- View GRN Modal -->
        <div class="modal fade" id="viewGrnModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="viewGrnModalContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>

        @include('goods_receipts._create_modal')
    </div>

    <script>
// Print function
function printGRNs() {
    window.print();
}

// Print GRN (same page using iframe)
function printGRN(grnId) {
    const printUrl = '{{ route("goods-receipts.print", ":id") }}'.replace(':id', grnId);
    
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

// Old print function (removed)
function printGRNOld(grnId) {
    $.ajax({
        url: '{{ route("goods-receipts.view-modal", ":id") }}'.replace(':id', grnId),
        method: 'GET',
        success: function(response) {
            // Extract GRN data from the modal HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(response, 'text/html');
            
            // Get supplier info
            const supplierName = doc.querySelector('.card-body tbody tr:nth-child(1) td:last-child')?.textContent.trim() || 'N/A';
            const supplierEmail = doc.querySelector('.card-body tbody tr:nth-child(2) td:last-child')?.textContent.trim() || '-';
            const supplierPhone = doc.querySelector('.card-body tbody tr:nth-child(3) td:last-child')?.textContent.trim() || '-';
            
            // Get GRN info
            const grnNumber = doc.querySelector('.modal-title')?.textContent.match(/GRN #(\w+)/)?.[1] || 'N/A';
            const receivedDate = doc.querySelector('.card-body tbody tr:nth-child(2) td:last-child')?.textContent.trim() || '-';
            const linkedPO = doc.querySelector('.card-body tbody tr:nth-child(3) td:last-child a')?.textContent.trim() || '-';
            const createdBy = doc.querySelector('.card-body tbody tr:nth-child(4) td:last-child')?.textContent.trim() || 'N/A';
            
            // Get items
            const items = [];
            doc.querySelectorAll('.table tbody tr').forEach((row, index) => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    items.push({
                        number: index + 1,
                        product: cells[1].textContent.trim(),
                        ordered_qty: cells[2].textContent.trim(),
                        received_qty: cells[3].textContent.trim(),
                        unit_cost: cells[4].textContent.trim(),
                        total: cells[5].textContent.trim()
                    });
                }
            });
            
            // Get notes
            const notes = doc.querySelector('.card-body p')?.textContent.trim() || '';
            
            // Open print window
            const printWindow = window.open('', '_blank', 'width=1200,height=800');
            
            const printHTML = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>GRN #${grnNumber} - ${new Date().toLocaleDateString()}</title>
                    <style>
                        body {
                            font-family: 'DejaVu Sans', sans-serif;
                            font-size: 12px;
                            margin: 0;
                            padding: 20px;
                            color: #333;
                        }
                        
                        .header {
                            text-align: center;
                            margin-bottom: 30px;
                            border-bottom: 2px solid #007bff;
                            padding-bottom: 15px;
                        }
                        
                        .header h1 {
                            margin: 0;
                            font-size: 24px;
                            color: #007bff;
                        }
                        
                        .header h2 {
                            margin: 5px 0;
                            font-size: 18px;
                            color: #333;
                        }
                        
                        .header p {
                            margin: 5px 0 0 0;
                            color: #666;
                        }
                        
                        .info-section {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 20px;
                        }
                        
                        .info-box {
                            width: 48%;
                            border: 1px solid #ddd;
                            padding: 15px;
                            border-radius: 5px;
                        }
                        
                        .info-box h3 {
                            margin: 0 0 10px 0;
                            font-size: 14px;
                            color: #007bff;
                            border-bottom: 1px solid #007bff;
                            padding-bottom: 5px;
                        }
                        
                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            padding: 5px 0;
                        }
                        
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                        }
                        
                        th {
                            background-color: #007bff;
                            color: white;
                            padding: 10px;
                            text-align: left;
                            font-weight: bold;
                            border: 1px solid #0056b3;
                        }
                        
                        td {
                            padding: 8px;
                            border: 1px solid #ddd;
                        }
                        
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        
                        .text-end {
                            text-align: right;
                        }
                        
                        .text-center {
                            text-align: center;
                        }
                        
                        .summary {
                            margin-top: 20px;
                            text-align: right;
                        }
                        
                        .summary-row {
                            display: flex;
                            justify-content: flex-end;
                            padding: 5px 0;
                        }
                        
                        .summary-row strong {
                            width: 150px;
                        }
                        
                        .footer {
                            margin-top: 30px;
                            text-align: center;
                            font-size: 10px;
                            color: #666;
                            border-top: 1px solid #ddd;
                            padding-top: 10px;
                        }
                        
                        @page {
                            margin: 1cm;
                            size: A4;
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>MMP Auto-Meister</h1>
                        <h2>Goods Receipt Note</h2>
                        <p>GRN #${grnNumber} | Date: ${receivedDate}</p>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-box">
                            <h3>Supplier Information</h3>
                            <div class="info-row">
                                <span><strong>Name:</strong></span>
                                <span>${supplierName}</span>
                            </div>
                            <div class="info-row">
                                <span><strong>Email:</strong></span>
                                <span>${supplierEmail}</span>
                            </div>
                            <div class="info-row">
                                <span><strong>Phone:</strong></span>
                                <span>${supplierPhone}</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <h3>GRN Information</h3>
                            <div class="info-row">
                                <span><strong>GRN Number:</strong></span>
                                <span>${grnNumber}</span>
                            </div>
                            <div class="info-row">
                                <span><strong>Received Date:</strong></span>
                                <span>${receivedDate}</span>
                            </div>
                            <div class="info-row">
                                <span><strong>Linked PO:</strong></span>
                                <span>${linkedPO}</span>
                            </div>
                            <div class="info-row">
                                <span><strong>Created By:</strong></span>
                                <span>${createdBy}</span>
                            </div>
                        </div>
    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 40%;">Product</th>
                                <th style="width: 15%;" class="text-center">Ordered Qty</th>
                                <th style="width: 15%;" class="text-center">Received Qty</th>
                                <th style="width: 15%;" class="text-end">Unit Cost</th>
                                <th style="width: 15%;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map(item => `
                                <tr>
                                    <td class="text-center">${item.number}</td>
                                    <td>${item.product}</td>
                                    <td class="text-center">${item.ordered_qty}</td>
                                    <td class="text-center">${item.received_qty}</td>
                                    <td class="text-end">${item.unit_cost}</td>
                                    <td class="text-end">${item.total}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    
                    ${notes ? `
                    <div style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                        <h3 style="margin: 0 0 10px 0; font-size: 14px; color: #007bff;">Notes</h3>
                        <p style="margin: 0;">${notes}</p>
                    </div>
                    ` : ''}
                    
                    <div class="footer">
                        <p>This document was generated by MMP Auto-Meister POS System</p>
                        <p>© ${new Date().getFullYear()} MMP Auto-Meister. All rights reserved.</p>
                        <br>
                        <button onclick="window.print()" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px;">
                            Print This Document
                        </button>
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(printHTML);
            printWindow.document.close();

            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.focus();
                }, 1000);
            };
        },
        error: function() {
            toastr.error('Failed to load GRN for printing');
        }
    });
}

// View PO Modal
function openPOModal(poId) {
    const url = '{{ route("purchase-orders.view-modal", ":id") }}'.replace(':id', poId);
    
    $.ajax({
        url: url,
        method: 'GET',
        beforeSend: function() {
            // Create PO modal if it doesn't exist
            if (!$('#poModal').length) {
                $('body').append('<div class="modal fade" id="poModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content" id="poModalContent"></div></div></div>');
            }
            $('#poModalContent').html('<div class="text-center p-5"><div class="spinner-border"></div><div class="mt-2">Loading...</div></div>');
            $('#poModal').modal('show');
        },
        success: function(response) {
            $('#poModalContent').html(response);
        },
        error: function(xhr) {
            console.error('Error loading PO:', xhr);
            $('#poModalContent').html('<div class="text-center p-5 text-danger"><i class="ri-error-warning-line fs-1"></i><div class="mt-2">Failed to load PO details</div></div>');
            toastr.error('Failed to load PO details');
        }
    });
}
    </script>
@endsection
