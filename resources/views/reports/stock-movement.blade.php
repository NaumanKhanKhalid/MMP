@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Stock Movement Report</h4>
            <p class="fs-13 text-muted mb-0">{{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.stock-movement', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i> Export PDF
            </a>
            <a href="{{ route('reports.stock-movement', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'csv']) }}" class="btn btn-success btn-sm">
                <i class="ri-file-excel-line me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card custom-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.stock-movement') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Product (Optional)</label>
                        <select name="product_id" class="form-select">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total In</span>
                    <h3 class="mb-0 text-success">{{ number_format($totalIn, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Out</span>
                    <h3 class="mb-0 text-danger">{{ number_format($totalOut, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Net Movement</span>
                    <h3 class="mb-0 {{ $netMovement >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $netMovement >= 0 ? '+' : '' }}{{ number_format($netMovement, 2) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Movement Table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Stock Movements ({{ $movements->count() }} transactions)</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Product</th>
                            <th>Transaction Type</th>
                            <th>Document #</th>
                            <th class="text-end">Qty In</th>
                            <th class="text-end">Qty Out</th>
                            <th class="text-end">Cost</th>
                            <th>User</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <strong>{{ $movement->product->name }}</strong>
                                <br><small class="text-muted">{{ $movement->product->sku }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $movement->qty > 0 ? 'success' : 'primary' }}-transparent">
                                    {{ $movement->document_type }}
                                </span>
                            </td>
                            <td>{{ $movement->document_id }}</td>
                            <td class="text-end text-success fw-semibold">
                                {{ $movement->qty > 0 ? number_format($movement->qty, 2) : '-' }}
                            </td>
                            <td class="text-end text-danger fw-semibold">
                                {{ $movement->qty < 0 ? number_format(abs($movement->qty), 2) : '-' }}
                            </td>
                            <td class="text-end">R {{ number_format($movement->unit_cost, 2) }}</td>
                            <td>{{ $movement->user ? $movement->user->name : '-' }}</td>
                            <td><small>{{ $movement->notes }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No stock movements found for this period</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
