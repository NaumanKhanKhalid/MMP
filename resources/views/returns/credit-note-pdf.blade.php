<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note {{ $creditNote->credit_note_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .credit-note-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 5px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .credit-note-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .credit-note-info {
            flex: 1;
        }
        .customer-info {
            flex: 1;
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
            margin-bottom: 5px;
        }
        .return-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table .label {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        .totals-table .amount {
            text-align: right;
            color: #666;
        }
        .totals-table .grand-total {
            background-color: #dc3545;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .totals-table .grand-total td {
            border-bottom: none;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(220, 53, 69, 0.1);
            font-weight: bold;
            z-index: -1;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .credit-note-container { box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="watermark">CREDIT NOTE</div>
    
    <div class="credit-note-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">MMP Auto-Meister</div>
                <div class="company-details">
                    Auto Parts & Workshop Services<br>
                    Point of Sale & Inventory System<br>
                    Email: info@mmpautomeister.co.za | Phone: +27 (0)11 123 4567
                </div>
            </div>
            
            <div class="credit-note-title">Credit Note</div>
            
            <div class="details-section">
                <div class="credit-note-info">
                    <div class="info-label">Credit Note Number:</div>
                    <div class="info-value">{{ $creditNote->credit_note_number }}</div>
                    <div class="info-label">Issue Date:</div>
                    <div class="info-value">{{ $creditNote->issued_at->format('d/m/Y') }}</div>
                    <div class="info-label">Original Invoice:</div>
                    <div class="info-value">{{ $creditNote->invoice->invoice_number }}</div>
                    <div class="info-label">Return Number:</div>
                    <div class="info-value">{{ $creditNote->productReturn->return_number }}</div>
                    <div class="info-label">Issued by:</div>
                    <div class="info-value">{{ $creditNote->user->name }}</div>
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Customer:</div>
                    @if($creditNote->customer)
                    <div class="info-value">{{ $creditNote->customer->name }}</div>
                    @if($creditNote->customer->email)
                    <div class="info-value">{{ $creditNote->customer->email }}</div>
                    @endif
                    @if($creditNote->customer->phone)
                    <div class="info-value">{{ $creditNote->customer->phone }}</div>
                    @endif
                    @if($creditNote->customer->address)
                    <div class="info-value">{{ $creditNote->customer->address }}</div>
                    @endif
                    @else
                    <div class="info-value">Walk-in Customer</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Return Details -->
        <div class="return-details">
            <h6 style="margin-top:0; margin-bottom:10px; color:#dc3545;">Return Details</h6>
            <div class="info-label">Return Type:</div>
            <div class="info-value" style="margin-bottom:8px;">{{ ucfirst($creditNote->productReturn->return_type) }}</div>
            <div class="info-label">Return Reason:</div>
            <div class="info-value" style="margin-bottom:8px;">{{ $creditNote->productReturn->reason }}</div>
            <div class="info-label">Refund Method:</div>
            <div class="info-value" style="margin-bottom:8px;">{{ ucfirst(str_replace('_', ' ', $creditNote->productReturn->refund_method)) }}</div>
            <div class="info-label">Stock Handling:</div>
            <div class="info-value">
                @if($creditNote->productReturn->stock_handling_type === 'restock')
                    <span style="color:#28a745;">Restocked to Inventory (FIFO)</span>
                @elseif($creditNote->productReturn->stock_handling_type === 'writeoff')
                    <span style="color:#ffc107;">Written Off (Damaged)</span>
                @else
                    <span style="color:#17a2b8;">Credit Only (No Stock Movement)</span>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th class="text-center">Qty Returned</th>
                    <th class="text-center">Condition</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($creditNote->productReturn->items as $item)
                <tr>
                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->quantity_returned, 0) }}</td>
                    <td class="text-center">
                        <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; 
                            background-color: {{ $item->condition === 'new' ? '#d4edda' : ($item->condition === 'damaged' ? '#f8d7da' : '#fff3cd') }}; 
                            color: {{ $item->condition === 'new' ? '#155724' : ($item->condition === 'damaged' ? '#721c24' : '#856404') }};">
                            {{ ucfirst($item->condition) }}
                        </span>
                    </td>
                    <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">R {{ number_format($creditNote->subtotal, 2) }}</td>
                </tr>
                @if($creditNote->discount_amount > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="amount">-R {{ number_format($creditNote->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($creditNote->tax_amount > 0)
                <tr>
                    <td class="label">VAT:</td>
                    <td class="amount">R {{ number_format($creditNote->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Credit Amount:</td>
                    <td class="amount">R {{ number_format($creditNote->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a system-generated credit note.</strong></p>
            <p>The credit amount will be applied to your customer account.</p>
            <p style="margin-top: 10px;">Thank you for your business!</p>
            <p style="margin-top: 20px; font-size: 10px;">
                Generated on {{ now()->format('d/m/Y H:i:s') }} by MMP Auto-Meister POS System
            </p>
        </div>
    </div>
</body>
</html>

