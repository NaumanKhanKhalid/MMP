<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Statement - {{ $supplier->supplier_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        
        .company-logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 9pt;
            color: #666;
        }
        
        .statement-title {
            font-size: 20pt;
            font-weight: bold;
            color: #28a745;
            margin: 20px 0 10px 0;
            text-align: center;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 5px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            width: 150px;
        }
        
        .balance-summary {
            background: #f8f9fa;
            border: 2px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .balance-grid {
            display: table;
            width: 100%;
        }
        
        .balance-row {
            display: table-row;
        }
        
        .balance-cell {
            display: table-cell;
            padding: 8px 15px;
            text-align: center;
            border-right: 1px solid #ddd;
        }
        
        .balance-cell:last-child {
            border-right: none;
        }
        
        .balance-label {
            font-size: 8pt;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        
        .balance-value {
            font-size: 16pt;
            font-weight: bold;
            color: #28a745;
        }
        
        .balance-value.negative {
            color: #dc3545;
        }
        
        .balance-value.positive {
            color: #28a745;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table th {
            background: #28a745;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }
        
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .debit {
            color: #28a745;
        }
        
        .credit {
            color: #dc3545;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-invoice {
            background: #e3f2fd;
            color: #0066cc;
        }
        
        .badge-payment {
            background: #e8f5e9;
            color: #28a745;
        }
        
        .badge-grn {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        
        .total-row {
            font-weight: bold;
            background: #f0f0f0 !important;
            border-top: 2px solid #333 !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="width: 50%; border: none; padding: 0;">
                    @if($companySettings['logo'])
                        <img src="{{ public_path('storage/' . $companySettings['logo']) }}" alt="Logo" class="company-logo">
                    @endif
                    <div class="company-info">
                        <strong style="font-size: 12pt; color: #333;">{{ $companySettings['name'] }}</strong><br>
                        @if($companySettings['address']){{ $companySettings['address'] }}<br>@endif
                        @if($companySettings['city']){{ $companySettings['city'] }}, {{ $companySettings['postal_code'] }}<br>@endif
                        @if($companySettings['phone'])Tel: {{ $companySettings['phone'] }}<br>@endif
                        @if($companySettings['email'])Email: {{ $companySettings['email'] }}<br>@endif
                        @if($companySettings['vat_number'])VAT: {{ $companySettings['vat_number'] }}@endif
                    </div>
                </td>
                <td style="width: 50%; text-align: right; border: none; padding: 0;">
                    <div style="font-size: 8pt; color: #666;">
                        <strong>Statement Date:</strong> {{ $generatedDate->format('d M Y') }}<br>
                        <strong>Period:</strong> {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="statement-title">SUPPLIER STATEMENT</div>

    <!-- Supplier Info -->
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell info-label">Supplier Code:</div>
            <div class="info-cell">{{ $supplier->supplier_code }}</div>
            <div class="info-cell info-label">Supplier Type:</div>
            <div class="info-cell">{{ ucfirst($supplier->supplier_type) }}</div>
        </div>
        <div class="info-row">
            <div class="info-cell info-label">Supplier Name:</div>
            <div class="info-cell">{{ $supplier->name }}</div>
            <div class="info-cell info-label">Terms:</div>
            <div class="info-cell">{{ $supplier->payment_terms }}</div>
        </div>
        @if($supplier->email)
        <div class="info-row">
            <div class="info-cell info-label">Email:</div>
            <div class="info-cell">{{ $supplier->email }}</div>
            <div class="info-cell info-label">Phone:</div>
            <div class="info-cell">{{ $supplier->phone ?? 'N/A' }}</div>
        </div>
        @endif
        @if($supplier->address)
        <div class="info-row">
            <div class="info-cell info-label">Address:</div>
            <div class="info-cell" colspan="3">{{ $supplier->address }}</div>
        </div>
        @endif
    </div>

    <!-- Balance Summary -->
    <div class="balance-summary">
        <div class="balance-grid">
            <div class="balance-row">
                <div class="balance-cell">
                    <span class="balance-label">Opening Balance</span>
                    <div class="balance-value {{ $openingBalance > 0 ? 'positive' : ($openingBalance < 0 ? 'negative' : '') }}">
                        R {{ number_format(abs($openingBalance), 2) }}
                    </div>
                    <small style="color: #666;">{{ $openingBalance > 0 ? 'We Owe' : ($openingBalance < 0 ? 'They Owe' : 'Zero') }}</small>
                </div>
                <div class="balance-cell">
                    <span class="balance-label">Total Credits</span>
                    <div class="balance-value positive">
                        R {{ number_format($ledgerEntries->sum('credit'), 2) }}
                    </div>
                    <small style="color: #666;">Purchases</small>
                </div>
                <div class="balance-cell">
                    <span class="balance-label">Total Debits</span>
                    <div class="balance-value negative">
                        R {{ number_format($ledgerEntries->sum('debit'), 2) }}
                    </div>
                    <small style="color: #666;">Payments Made</small>
                </div>
                <div class="balance-cell">
                    <span class="balance-label">Closing Balance</span>
                    <div class="balance-value {{ $closingBalance > 0 ? 'positive' : ($closingBalance < 0 ? 'negative' : '') }}">
                        R {{ number_format(abs($closingBalance), 2) }}
                    </div>
                    <small style="color: #666;">{{ $closingBalance > 0 ? 'We Owe Them' : ($closingBalance < 0 ? 'They Owe Us' : 'Zero') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    @if($ledgerEntries->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Date</th>
                <th style="width: 18%;">Type</th>
                <th style="width: 20%;">Document #</th>
                <th style="width: 25%;">Description</th>
                <th class="text-right" style="width: 10%;">Debit (R)</th>
                <th class="text-right" style="width: 10%;">Credit (R)</th>
                <th class="text-right" style="width: 15%;">Balance (R)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Opening Balance Row -->
            @if($openingBalance != 0)
            <tr>
                <td>{{ $startDate->format('d M Y') }}</td>
                <td><span class="badge" style="background: #e0e0e0; color: #333;">OPENING</span></td>
                <td>-</td>
                <td>Opening Balance</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format(abs($openingBalance), 2) }}</td>
            </tr>
            @endif

            <!-- Ledger Entries -->
            @foreach($ledgerEntries as $entry)
            <tr>
                <td>{{ $entry->transaction_date->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-{{ $entry->transaction_type === 'supplier_invoice' ? 'invoice' : ($entry->transaction_type === 'payment' ? 'payment' : 'grn') }}">
                        {{ ucfirst(str_replace('_', ' ', $entry->transaction_type)) }}
                    </span>
                </td>
                <td>{{ $entry->document_number }}</td>
                <td>{{ $entry->description ?? '-' }}</td>
                <td class="text-right debit">
                    {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}
                </td>
                <td class="text-right credit">
                    {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}
                </td>
                <td class="text-right" style="font-weight: bold;">
                    {{ number_format(abs($entry->balance), 2) }}
                </td>
            </tr>
            @endforeach

            <!-- Closing Balance Row -->
            <tr class="total-row">
                <td colspan="4" style="text-align: right; padding-right: 15px;">CLOSING BALANCE:</td>
                <td class="text-right">{{ number_format($ledgerEntries->sum('debit'), 2) }}</td>
                <td class="text-right">{{ number_format($ledgerEntries->sum('credit'), 2) }}</td>
                <td class="text-right" style="font-size: 11pt;">{{ number_format(abs($closingBalance), 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($closingBalance > 0)
    <div style="background: #d4edda; border: 1px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <strong style="color: #155724;">Amount We Owe: R {{ number_format($closingBalance, 2) }}</strong><br>
        <span style="font-size: 9pt; color: #155724;">Payment will be processed as per agreed terms.</span>
    </div>
    @endif

    @else
    <div style="text-align: center; padding: 40px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
        <p style="color: #666; font-size: 11pt;">No transactions found for the selected period.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated statement and does not require a signature.</p>
        <p>For any queries, please contact us at {{ $companySettings['email'] ?? $companySettings['phone'] }}</p>
        <p style="margin-top: 10px; color: #999;">Generated: {{ $generatedDate->format('d M Y H:i') }}</p>
    </div>
</body>
</html>

