<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices Export - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }
        .report-date {
            font-size: 12px;
            color: #666;
        }
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-partial {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-draft {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MMP Auto-Meister</div>
        <div class="report-title">Invoices Export Report</div>
        <div class="report-date">Generated on: {{ now()->format('d M Y, H:i') }}</div>
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-value">{{ $invoices->count() }}</div>
            <div class="stat-label">Total Invoices</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">R {{ number_format($invoices->sum('grand_total'), 2) }}</div>
            <div class="stat-label">Total Amount</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">R {{ number_format($invoices->where('payment_status', 'paid')->sum('grand_total'), 2) }}</div>
            <div class="stat-label">Paid Amount</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">R {{ number_format($invoices->where('payment_status', 'unpaid')->sum('balance_due'), 2) }}</div>
            <div class="stat-label">Outstanding</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Subtotal</th>
                <th>VAT</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                <td>{{ $invoice->customer_name ?? 'Walk-in Customer' }}</td>
                <td>{{ $invoice->customer_phone ?? '-' }}</td>
                <td class="text-right">R {{ number_format($invoice->subtotal, 2) }}</td>
                <td class="text-right">R {{ number_format($invoice->vat_amount ?? 0, 2) }}</td>
                <td class="text-right">R {{ number_format($invoice->grand_total, 2) }}</td>
                <td class="text-right">R {{ number_format($invoice->amount_paid ?? 0, 2) }}</td>
                <td class="text-right">R {{ number_format($invoice->balance_due ?? 0, 2) }}</td>
                <td class="text-center">
                    <span class="status-badge status-{{ $invoice->payment_status }}">
                        {{ ucfirst($invoice->payment_status) }}
                    </span>
                </td>
                <td class="text-center">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method ?? 'cash')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated by MMP Auto-Meister POS System</p>
        <p>© {{ date('Y') }} MMP Auto-Meister. All rights reserved.</p>
    </div>
</body>
</html>