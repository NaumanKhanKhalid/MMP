<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #007bff; color: white; font-weight: bold;">
            <th>Quote Number</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Items Count</th>
            <th>Grand Total</th>
            <th>Status</th>
            <th>Valid Until</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quotes as $quote)
        <tr>
            <td>{{ $quote->quote_number }}</td>
            <td>{{ $quote->customer->name ?? 'Cash Sale' }}</td>
            <td>
                @if($quote->vehicle_make)
                    {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}
                    @if($quote->vehicle_reg)
                        ({{ $quote->vehicle_reg }})
                    @endif
                @else
                    -
                @endif
            </td>
            <td style="text-align: center;">{{ $quote->items->count() }}</td>
            <td style="text-align: right;">R {{ number_format($quote->grand_total ?? 0, 2) }}</td>
            <td>{{ ucfirst($quote->status) }}</td>
            <td>{{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : '-' }}</td>
            <td>{{ $quote->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

