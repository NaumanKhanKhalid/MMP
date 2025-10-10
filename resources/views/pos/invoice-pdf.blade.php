<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-info, .customer-info {
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
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #d1d5db;
        }
        
        .items-table td {
            padding: 10px 8px;
            border: 1px solid #d1d5db;
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
        
        .totals-section {
            margin-left: auto;
            width: 300px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .total-label {
            font-weight: bold;
            color: #374151;
        }
        
        .total-value {
            color: #1f2937;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .payment-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        
        .payment-info h3 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        
        .terms {
            margin-top: 20px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MMP Auto-Meister</div>
        <div class="company-tagline">Your Trusted Auto Parts Partner</div>
        <div class="company-tagline">Phone: +92-XXX-XXXXXXX | Email: info@mmpautomeister.com</div>
    </div>

    <div class="invoice-title">INVOICE</div>

    <div class="invoice-details">
        <div class="invoice-info">
            <h3>Invoice Details</h3>
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
                <span class="info-label">Cashier:</span>
                <span class="info-value">{{ $invoice->user->name ?? 'N/A' }}</span>
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
                @if($invoice->customer->email)
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $invoice->customer->email }}</span>
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
                <th style="width: 35%;">Description</th>
                <th style="width: 15%;">SKU</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 15%;">Unit Price</th>
                <th style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->product_barcode)
                        <br><small>Barcode: {{ $item->product_barcode }}</small>
                    @endif
                </td>
                <td>{{ $item->product_sku }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-value">${{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        
        @if($invoice->discount_amount > 0)
        <div class="total-row">
            <span class="total-label">Discount:</span>
            <span class="total-value">-${{ number_format($invoice->discount_amount, 2) }}</span>
        </div>
        @endif
        
        @if($invoice->tax_amount > 0)
        <div class="total-row">
            <span class="total-label">Tax ({{ $invoice->tax_rate }}%):</span>
            <span class="total-value">${{ number_format($invoice->tax_amount, 2) }}</span>
        </div>
        @endif
        
        <div class="total-row grand-total">
            <span class="total-label">Total:</span>
            <span class="total-value">${{ number_format($invoice->grand_total, 2) }}</span>
        </div>
    </div>

    <div class="payment-info">
        <h3>Payment Information</h3>
        <div class="info-row">
            <span class="info-label">Payment Method:</span>
            <span class="info-value">{{ ucfirst($invoice->payment_method) }}</span>
        </div>
        @if($invoice->payment_method === 'cash' && $invoice->amount_received)
        <div class="info-row">
            <span class="info-label">Amount Received:</span>
            <span class="info-value">${{ number_format($invoice->amount_received, 2) }}</span>
        </div>
        @if($invoice->amount_received > $invoice->grand_total)
        <div class="info-row">
            <span class="info-label">Change:</span>
            <span class="info-value">${{ number_format($invoice->amount_received - $invoice->grand_total, 2) }}</span>
        </div>
        @endif
        @endif
    </div>

    <div class="footer">
        <div class="terms">
            <p><strong>Terms & Conditions:</strong></p>
            <p>• All sales are final unless otherwise specified</p>
            <p>• Returns accepted within 30 days with original receipt</p>
            <p>• Warranty terms as per manufacturer specifications</p>
            <p>• Thank you for choosing MMP Auto-Meister!</p>
        </div>
        
        <div style="margin-top: 20px;">
            <p>Generated on {{ now()->format('d/m/Y H:i A') }}</p>
        </div>
    </div>
</body>
</html>
