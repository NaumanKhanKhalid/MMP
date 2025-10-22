<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quotations Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        h1 { text-align: center; color: #007bff; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>MMP Auto-Meister - Quotations Report</h1>
    <p style="text-align: center; color: #666;">Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Quote Number</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Items</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Valid Until</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotes as $index => $quote)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $quote->quote_number }}</td>
                <td>{{ $quote->customer->name ?? 'Cash Sale' }}</td>
                <td>
                    @if($quote->vehicle_make)
                        {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $quote->items->count() }}</td>
                <td class="text-right">R {{ number_format($quote->grand_total ?? 0, 2) }}</td>
                <td>{{ ucfirst($quote->status) }}</td>
                <td>{{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

