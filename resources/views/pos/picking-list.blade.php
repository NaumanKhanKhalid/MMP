<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Picking List - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }
        
        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .picking-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .order-info, .customer-info {
            width: 48%;
        }
        
        .info-section h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #374151;
        }
        
        .info-value {
            color: #1f2937;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: #f0fdf4;
            color: #1f2937;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #bbf7d0;
        }
        
        .items-table td {
            padding: 10px 8px;
            border: 1px solid #bbf7d0;
            vertical-align: top;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .picking-instructions {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0fdf4;
            border-radius: 4px;
            border-left: 4px solid #059669;
        }
        
        .picking-instructions h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .instructions-list {
            margin: 0;
            padding-left: 20px;
        }
        
        .instructions-list li {
            margin-bottom: 5px;
            color: #374151;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin-bottom: 5px;
        }
        
        .signature-label {
            font-size: 10px;
            color: #6b7280;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        
        .priority-badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .urgent {
            background-color: #fecaca;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MMP Auto-Meister</div>
        <div class="company-tagline">Picking List - Warehouse Operations</div>
        <div class="company-tagline">Phone: +92-XXX-XXXXXXX | Email: warehouse@mmpautomeister.com</div>
    </div>

    <div class="picking-title">PICKING LIST</div>

    <div class="order-details">
        <div class="order-info">
            <h3>Order Details</h3>
            <div class="info-row">
                <span class="info-label">Invoice #:</span>
                <span class="info-value">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $invoice->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Time:</span>
                <span class="info-value">{{ $invoice->created_at->format('H:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Priority:</span>
                <span class="info-value">
                    <span class="priority-badge {{ $invoice->created_at->diffInHours(now()) < 2 ? 'urgent' : '' }}">
                        {{ $invoice->created_at->diffInHours(now()) < 2 ? 'URGENT' : 'NORMAL' }}
                    </span>
                </span>
            </div>
        </div>

        <div class="customer-info">
            <h3>Customer Details</h3>
            @if($invoice->customer)
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $invoice->customer->name }}</span>
                </div>
                @if($invoice->customer->phone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $invoice->customer->phone }}</span>
                </div>
                @endif
                @if($invoice->customer->address)
                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value">{{ $invoice->customer->address }}</span>
                </div>
                @endif
            @else
                <div class="info-row">
                    <span class="info-value">Walk-in Customer</span>
                </div>
            @endif
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Product Description</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 10%;">Barcode</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 15%;">Location</th>
                <th style="width: 15%;">Picked</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->product->brand)
                        <br><small>Brand: {{ $item->product->brand->name }}</small>
                    @endif
                </td>
                <td>{{ $item->product_sku }}</td>
                <td class="text-center">{{ $item->product_barcode ?: 'N/A' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-center">
                    @if($item->product->warehouse_location)
                        {{ $item->product->warehouse_location }}
                    @else
                        A-{{ str_pad($item->product->id, 3, '0', STR_PAD_LEFT) }}
                    @endif
                </td>
                <td class="text-center">
                    <div style="border: 1px solid #d1d5db; height: 20px; width: 20px; margin: 0 auto;"></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="picking-instructions">
        <h3>Picking Instructions</h3>
        <ul class="instructions-list">
            <li>Check each item against the SKU and barcode before picking</li>
            <li>Verify quantity matches the order exactly</li>
            <li>Check for any damage or defects before packing</li>
            <li>Place items in designated packing area after picking</li>
            <li>Mark each item as picked in the "Picked" column</li>
            <li>Report any discrepancies immediately to supervisor</li>
            <li>Ensure all items are properly packaged for delivery</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Picker Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Supervisor Signature</div>
        </div>
    </div>

    <div class="footer">
        <div style="margin-top: 20px;">
            <p><strong>Total Items to Pick:</strong> {{ $invoice->items->sum('quantity') }} items</p>
            <p><strong>Generated on:</strong> {{ now()->format('d/m/Y H:i A') }}</p>
            <p><strong>Picking List ID:</strong> PL-{{ $invoice->id }}-{{ now()->format('Ymd') }}</p>
        </div>
    </div>
</body>
</html>
