@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Quote</h1>
    <form action="{{ route('quotes.update', $quote) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Customer ID</label>
            <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id', $quote->customer_id) }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Make</label>
            <input type="text" name="vehicle_make" class="form-control" value="{{ old('vehicle_make', $quote->vehicle_make) }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Model</label>
            <input type="text" name="vehicle_model" class="form-control" value="{{ old('vehicle_model', $quote->vehicle_model) }}">
        </div>
        <div class="mb-3">
            <label>Vehicle VIN</label>
            <input type="text" name="vehicle_vin" class="form-control" value="{{ old('vehicle_vin', $quote->vehicle_vin) }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Reg</label>
            <input type="text" name="vehicle_reg" class="form-control" value="{{ old('vehicle_reg', $quote->vehicle_reg) }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Mileage</label>
            <input type="text" name="vehicle_mileage" class="form-control" value="{{ old('vehicle_mileage', $quote->vehicle_mileage) }}">
        </div>
        <div class="mb-3">
            <label>Valid Until</label>
            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $quote->valid_until) }}">
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes', $quote->notes) }}</textarea>
        </div>
        <!-- Items input (simple for now) -->
        <div class="mb-3">
            <label>Items (JSON array: product_id, description, quantity, unit_price, discount, total)</label>
            <textarea name="items" class="form-control" rows="3">{{ old('items', json_encode($quote->items->map(function($item){return $item->only(['product_id','description','quantity','unit_price','discount','total']);})->toArray())) }}</textarea>
            <small>For demo: Enter as JSON array. UI can be improved later.</small>
        </div>
        <button class="btn btn-primary">Update Quote</button>
    </form>
</div>
@endsection
