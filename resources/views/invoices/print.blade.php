<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #007bff;
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
            color: #007bff;
            margin-bottom: 5px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info {
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
            margin-top: 30px;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            border: none;
        }
        .totals-table .label {
            font-weight: bold;
            text-align: right;
        }
        .totals-table .amount {
            text-align: right;
            color: #333;
        }
        .grand-total {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .vehicle-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background-color: #ffc107; color: #000; }
        .status-posted { background-color: #17a2b8; color: white; }
        .status-paid { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice-container { box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
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
            
            <div class="invoice-details">
                <div class="invoice-info">
                    <div class="info-label">Invoice Number:</div>
                    <div class="info-value">{{ $invoice->invoice_number }}</div>
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $invoice->created_at->format('d/m/Y') }}</div>
                    <div class="info-label">Created by:</div>
                    <div class="info-value">{{ $invoice->user->name }}</div>
                    @if($invoice->quote_id)
                    <div class="info-label">From Quote:</div>
                    <div class="info-value">{{ $invoice->quote->quote_number }}</div>
                    @endif
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Bill To:</div>
                    <div class="info-value">
                        @if($invoice->customer)
                            <strong>{{ $invoice->customer->name }}</strong><br>
                            @if($invoice->customer->email){{ $invoice->customer->email }}<br>@endif
                            @if($invoice->customer->phone){{ $invoice->customer->phone }}<br>@endif
                            @if($invoice->customer->address){{ $invoice->customer->address }}@endif
                        @else
                            <strong>{{ $invoice->customer_name ?? 'Cash Sale' }}</strong><br>
                            @if($invoice->customer_email){{ $invoice->customer_email }}<br>@endif
                            @if($invoice->customer_phone){{ $invoice->customer_phone }}@endif
                        @endif
                    </div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $invoice->payment_status }}">
                            {{ ucfirst($invoice->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Details -->
        @if($invoice->vehicle_make || $invoice->vehicle_model || $invoice->vehicle_vin || $invoice->vehicle_reg)
        <div class="vehicle-details">
            <h4 style="margin-top: 0; color: #1976d2;">Vehicle Details</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                @if($invoice->vehicle_make)
                <div><strong>Make:</strong> {{ $invoice->vehicle_make }}</div>
                @endif
                @if($invoice->vehicle_model)
                <div><strong>Model:</strong> {{ $invoice->vehicle_model }}</div>
                @endif
                @if($invoice->vehicle_vin)
                <div><strong>VIN:</strong> {{ $invoice->vehicle_vin }}</div>
                @endif
                @if($invoice->vehicle_reg)
                <div><strong>Registration:</strong> {{ $invoice->vehicle_reg }}</div>
                @endif
                @if($invoice->vehicle_mileage)
                <div><strong>Mileage:</strong> {{ $invoice->vehicle_mileage }} km</div>
                @endif
            </div>
        </div>
        @endif

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-center">Disc %</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_sku }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->discount_percentage > 0 ? number_format($item->discount_percentage, 1) . '%' : '-' }}</td>
                    <td class="text-right">{{ $item->discount_amount > 0 ? 'R ' . number_format($item->discount_amount, 2) : '-' }}</td>
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
                    <td class="amount">R {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->discount_amount > 0)
                <tr>
                    <td class="label">Total Discount:</td>
                    <td class="amount">-R {{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->shipping > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">R {{ number_format($invoice->shipping, 2) }}</td>
                </tr>
                @endif
                @if($invoice->vat_amount > 0)
                <tr>
                    <td class="label">VAT ({{ $invoice->vat_rate }}%):</td>
                    <td class="amount">R {{ number_format($invoice->vat_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R {{ number_format($invoice->grand_total, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Amount Paid:</td>
                    <td class="amount">R {{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Balance Due:</td>
                    <td class="amount {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                        R {{ number_format($invoice->balance_due, 2) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Payment Method -->
        <div style="margin-top: 20px; padding: 15px; background-color: #e8f5e8; border-radius: 5px;">
            <strong>Payment Method:</strong> 
            @switch($invoice->payment_method)
                @case('cash')
                    💵 Cash
                    @break
                @case('card')
                    💳 Card
                    @break
                @case('eft')
                    🏦 EFT
                    @break
                @case('on_account')
                    👤 On Account
                    @break
            @endswitch
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <h4 style="margin-top: 0;">Notes</h4>
            {{ $invoice->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated on {{ now()->format('d/m/Y H:i:s') }} by MMP Auto-Meister POS System</p>
            @if($invoice->reference)
            <p><strong>Reference:</strong> {{ $invoice->reference }}</p>
            @endif
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Invoice
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>
</body>
</html>
