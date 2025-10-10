@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Quote #{{ $quote->quote_number }}</h1>
    <div class="mb-3">
        <strong>Customer ID:</strong> {{ $quote->customer_id ?? '-' }}<br>
        <strong>Status:</strong> {{ ucfirst($quote->status) }}<br>
        <strong>Valid Until:</strong> {{ $quote->valid_until }}<br>
        <strong>Vehicle:</strong> {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}<br>
        <strong>VIN:</strong> {{ $quote->vehicle_vin }}<br>
        <strong>Reg:</strong> {{ $quote->vehicle_reg }}<br>
        <strong>Mileage:</strong> {{ $quote->vehicle_mileage }}<br>
        <strong>Notes:</strong> {{ $quote->notes }}
    </div>
    <h4>Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->product_id }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->discount }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('quotes.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
