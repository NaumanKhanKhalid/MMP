@extends('layouts.app')

@section('content')
<div class="container">
    <h1>New Quote</h1>
    <form action="{{ route('quotes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Customer ID</label>
            <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id') }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Make</label>
            <input type="text" name="vehicle_make" class="form-control" value="{{ old('vehicle_make') }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Model</label>
            <input type="text" name="vehicle_model" class="form-control" value="{{ old('vehicle_model') }}">
        </div>
        <div class="mb-3">
            <label>Vehicle VIN</label>
            <input type="text" name="vehicle_vin" class="form-control" value="{{ old('vehicle_vin') }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Reg</label>
            <input type="text" name="vehicle_reg" class="form-control" value="{{ old('vehicle_reg') }}">
        </div>
        <div class="mb-3">
            <label>Vehicle Mileage</label>
            <input type="text" name="vehicle_mileage" class="form-control" value="{{ old('vehicle_mileage') }}">
        </div>
        <div class="mb-3">
            <label>Valid Until</label>
            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>
        <!-- Items input (simple for now) -->
        <div class="mb-3">
            <label>Items (JSON array: product_id, description, quantity, unit_price, discount, total)</label>
            <textarea name="items" class="form-control" rows="3">{{ old('items') }}</textarea>
            <small>For demo: Enter as JSON array. UI can be improved later.</small>
        </div>
        <button class="btn btn-primary">Save Quote</button>
    </form>
</div>
@endsection
