<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quote->quote_number }}</title>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .quotation-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #28a745;
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
            color: #28a745;
            margin-bottom: 5px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .quotation-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .quotation-info {
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
            background-color: #28a745;
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
            background-color: #e8f5e8;
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
        .status-sent { background-color: #17a2b8; color: white; }
        .status-accepted { background-color: #28a745; color: white; }
        .status-declined { background-color: #dc3545; color: white; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .quotation-container { box-shadow: none; }
            .no-print { display: none; }
}
</style>
</head>
<body>
    <div class="quotation-container">
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
            
            <div class="quotation-details">
                <div class="quotation-info">
                    <div class="info-label">Quotation Number:</div>
                    <div class="info-value">{{ $quote->quote_number }}</div>
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $quote->created_at->format('d/m/Y') }}</div>
                    <div class="info-label">Created by:</div>
                    <div class="info-value">{{ $quote->user->name ?? 'System' }}</div>
                    <div class="info-label">Valid Until:</div>
                    <div class="info-value">{{ $quote->created_at->addDays(30)->format('d/m/Y') }}</div>
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Quote To:</div>
                    <div class="info-value">
                        @if($quote->customer)
                            <strong>{{ $quote->customer->name }}</strong><br>
                            @if($quote->customer->email){{ $quote->customer->email }}<br>@endif
                            @if($quote->customer->phone){{ $quote->customer->phone }}<br>@endif
                            @if($quote->customer->address){{ $quote->customer->address }}@endif
                        @else
                            <strong>{{ $quote->customer_name ?? 'Walk-in Customer' }}</strong><br>
                            @if($quote->customer_email){{ $quote->customer_email }}<br>@endif
                            @if($quote->customer_phone){{ $quote->customer_phone }}@endif
                        @endif
                    </div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $quote->status ?? 'draft' }}">
                            {{ ucfirst($quote->status ?? 'draft') }}
                        </span>
        </div>
        </div>
    </div>
        </div>

        <!-- Vehicle Details -->
        @if($quote->vehicle_make || $quote->vehicle_model || $quote->vehicle_vin || $quote->vehicle_reg)
        <div class="vehicle-details">
            <h4 style="margin-top: 0; color: #28a745;">Vehicle Details</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                @if($quote->vehicle_make)
            <div><strong>Make:</strong> {{ $quote->vehicle_make }}</div>
                @endif
                @if($quote->vehicle_model)
            <div><strong>Model:</strong> {{ $quote->vehicle_model }}</div>
                @endif
                @if($quote->vehicle_vin)
            <div><strong>VIN:</strong> {{ $quote->vehicle_vin }}</div>
                @endif
                @if($quote->vehicle_reg)
                <div><strong>Registration:</strong> {{ $quote->vehicle_reg }}</div>
                @endif
                @if($quote->vehicle_mileage)
                <div><strong>Mileage:</strong> {{ $quote->vehicle_mileage }} km</div>
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
                @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->product->sku ?? $item->product_id }}</td>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->discount_percentage > 0 ? number_format($item->discount_percentage, 1) . '%' : '-' }}</td>
                    <td class="text-right">{{ $item->discount_amount > 0 ? 'R ' . number_format($item->discount_amount, 2) : '-' }}</td>
                    <td class="text-right"><strong>R {{ number_format($item->total, 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">R {{ number_format($quote->items->sum('total'), 2) }}</td>
                </tr>
                @if($quote->total_discount > 0)
                <tr>
                    <td class="label">Total Discount:</td>
                    <td class="amount">-R {{ number_format($quote->total_discount, 2) }}</td>
                </tr>
                @endif
                @if($quote->shipping > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">R {{ number_format($quote->shipping, 2) }}</td>
                </tr>
                @endif
                @if($quote->vat > 0)
                <tr>
                    <td class="label">VAT ({{ $quote->vat_rate ?? 15 }}%):</td>
                    <td class="amount">R {{ number_format($quote->vat, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R {{ number_format($quote->grand_total ?? $quote->items->sum('total'), 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Terms & Conditions -->
        <div style="margin-top: 30px; padding: 20px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
            <h5 style="margin-top: 0; color: #856404;">Terms & Conditions</h5>
            <ul style="margin-bottom: 0; color: #856404;">
                <li>This quotation is valid for 30 days from the date of issue</li>
                <li>Prices are subject to change without notice</li>
                <li>Payment terms: Cash on delivery or as agreed</li>
                <li>All parts carry manufacturer warranty where applicable</li>
                <li>Labor warranty: 90 days or 10,000km whichever comes first</li>
            </ul>
    </div>

        <!-- Notes -->
        @if($quote->notes)
        <div class="notes">
            <h4 style="margin-top: 0;">Notes</h4>
            {{ $quote->notes }}
    </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for considering MMP Auto-Meister for your auto parts needs!</strong></p>
            <p>This quotation was generated on {{ now()->format('d/m/Y H:i:s') }} by MMP Auto-Meister POS System</p>
            @if($quote->reference)
            <p><strong>Reference:</strong> {{ $quote->reference }}</p>
            @endif
    </div>
</div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Quotation
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>
</body>
</html>
