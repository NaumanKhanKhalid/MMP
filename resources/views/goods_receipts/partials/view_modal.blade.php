<!-- resources/views/goods_receipts/partials/view_modal.blade.php -->

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-file-text-line me-2"></i> Goods Receipt Note #{{ $grn->grn_number }}
        @php
            $statusClasses = [
                'pending' => 'bg-warning',
                'completed' => 'bg-success',
                'cancelled' => 'bg-danger'
            ];
            $statusClass = $statusClasses[$grn->status] ?? 'bg-secondary';
        @endphp
        <span class="badge {{ $statusClass }} ms-2">{{ ucfirst($grn->status) }}</span>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
    
    <!-- Supplier & GRN Info Side by Side -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-primary-transparent py-2">
                    <h6 class="mb-0"><i class="ri-building-line me-1"></i>Supplier Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="120">Name:</td>
                            <td>{{ $grn->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Email:</td>
                            <td>{{ $grn->supplier->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Phone:</td>
                            <td>{{ $grn->supplier->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Address:</td>
                            <td>{{ $grn->supplier->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                        </div>
                </div>
        
        <div class="col-md-6">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-file-info-line me-1"></i>GRN Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="fw-semibold" width="140">GRN Number:</td>
                            <td><span class="badge bg-primary">{{ $grn->grn_number }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Received Date:</td>
                            <td>{{ $grn->received_date->format('d M Y') }}</td>
                        </tr>
                        @if($grn->purchaseOrder)
                        <tr>
                            <td class="fw-semibold">Linked PO:</td>
                            <td>
                                <a href="javascript:void(0);" class="text-decoration-underline text-primary" 
                                   onclick="viewPO({{ $grn->purchaseOrder->id }})">
                                    {{ $grn->purchaseOrder->po_number }}
                                </a>
                                <span class="badge bg-info ms-2">{{ ucfirst($grn->purchaseOrder->status) }}</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="fw-semibold">Created By:</td>
                            <td>{{ $grn->user->name ?? 'N/A' }}</td>
                        </tr>
                        @if($grn->invoice_number)
                        <tr>
                            <td class="fw-semibold">Invoice Number:</td>
                            <td>{{ $grn->invoice_number }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- GRN Items Table -->
    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="ri-shopping-cart-line me-1"></i>Received Items</h6>
            <span class="badge bg-primary">{{ $grn->items->count() }} items</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">Product</th>
                            <th width="15%" class="text-center">Ordered Qty</th>
                            <th width="15%" class="text-center">Received Qty</th>
                            <th width="15%" class="text-end">Unit Cost</th>
                            <th width="15%" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grn->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->product->name ?? 'Product not found' }}</div>
                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ $item->ordered_qty }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success-transparent rounded-pill">{{ $item->received_qty }}</span>
                            </td>
                            <td class="text-end">R {{ number_format($item->unit_cost, 2) }}</td>
                            <td class="text-end fw-semibold">R {{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- GRN Summary -->
    <div class="row">
        <div class="col-md-7">
            @if($grn->notes)
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-secondary-transparent py-2">
                    <h6 class="mb-0"><i class="ri-sticky-note-line me-1"></i>Notes</h6>
                </div>
                    <div class="card-body">
                    <p class="mb-0">{{ $grn->notes }}</p>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-5">
            <div class="card border shadow-sm">
                <div class="card-header bg-success-transparent py-2">
                    <h6 class="mb-0"><i class="ri-calculator-line me-1"></i>GRN Summary</h6>
                    </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-end fw-semibold">Total Items:</td>
                            <td class="text-end" width="120">{{ $grn->items->count() }}</td>
                        </tr>
                        <tr>
                            <td class="text-end fw-semibold">Total Quantity:</td>
                            <td class="text-end">{{ $grn->items->sum('received_qty') }}</td>
                        </tr>
                        <tr class="table-success">
                            <td class="text-end fw-bold fs-16">Grand Total:</td>
                            <td class="text-end fw-bold fs-16">R {{ number_format($grn->total_amount ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-primary" onclick="printGRNModal({{ $grn->id }})">
        <i class="ri-printer-line me-1"></i> Print
    </button>
</div>

<script>
function printGRNModal(grnId) {
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
    window.open('{{ route("goods-receipts.print", ":id") }}'.replace(':id', grnId), '_blank');
    return;
    
    const printWindow = window.open('', '_blank', 'width=1200,height=800');
    
    // Get GRN data
    const grnData = @json($grn);
    
    const printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>GRN #${grnData.grn_number} - ${new Date().toLocaleDateString()}</title>
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
                <p>GRN #${grnData.grn_number} | Date: ${new Date(grnData.received_date).toLocaleDateString()}</p>
            </div>
            
            <div class="info-section">
                <div class="info-box">
                    <h3>Supplier Information</h3>
                    <div class="info-row">
                        <span><strong>Name:</strong></span>
                        <span>${grnData.supplier?.name || 'N/A'}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Email:</strong></span>
                        <span>${grnData.supplier?.email || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Phone:</strong></span>
                        <span>${grnData.supplier?.phone || '-'}</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>GRN Information</h3>
                    <div class="info-row">
                        <span><strong>GRN Number:</strong></span>
                        <span>${grnData.grn_number}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Received Date:</strong></span>
                        <span>${new Date(grnData.received_date).toLocaleDateString()}</span>
                    </div>
                    ${grnData.purchase_order ? `
                    <div class="info-row">
                        <span><strong>Linked PO:</strong></span>
                        <span>${grnData.purchase_order.po_number}</span>
                    </div>
                    ` : ''}
                    <div class="info-row">
                        <span><strong>Status:</strong></span>
                        <span>${grnData.status}</span>
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
                    ${grnData.items.map((item, index) => `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${item.product?.name || 'N/A'}<br><small>SKU: ${item.product?.sku || 'N/A'}</small></td>
                            <td class="text-center">${item.ordered_qty}</td>
                            <td class="text-center">${item.received_qty}</td>
                            <td class="text-end">R ${parseFloat(item.unit_cost).toFixed(2)}</td>
                            <td class="text-end">R ${parseFloat(item.line_total).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            
            <div class="summary">
                <div class="summary-row">
                    <strong>Total Items:</strong> ${grnData.items.length}
                </div>
                <div class="summary-row">
                    <strong>Total Quantity:</strong> ${grnData.items.reduce((sum, item) => sum + parseFloat(item.received_qty), 0)}
                </div>
                <div class="summary-row" style="font-size: 16px; font-weight: bold; color: #28a745;">
                    <strong>Grand Total:</strong> R ${parseFloat(grnData.total_amount || 0).toFixed(2)}
                </div>
            </div>
            
            ${grnData.notes ? `
            <div style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                <h3 style="margin: 0 0 10px 0; font-size: 14px; color: #007bff;">Notes</h3>
                <p style="margin: 0;">${grnData.notes}</p>
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
}

function viewPO(poId) {
    $('#viewGrnModal').modal('hide');
    
    setTimeout(function() {
        $.ajax({
            url: '{{ route("purchase-orders.view-modal", ":id") }}'.replace(':id', poId),
            method: 'GET',
            beforeSend: function() {
                if (!$('#poModal').length) {
                    $('body').append('<div class="modal fade" id="poModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content" id="poModalContent"></div></div></div>');
                }
                $('#poModalContent').html('<div class="text-center p-5"><div class="spinner-border"></div><div class="mt-2">Loading...</div></div>');
                $('#poModal').modal('show');
            },
            success: function(response) {
                $('#poModalContent').html(response);
            },
            error: function() {
                $('#poModalContent').html('<div class="text-center p-5 text-danger">Failed to load PO details</div>');
            }
        });
    }, 300);
}
</script>
