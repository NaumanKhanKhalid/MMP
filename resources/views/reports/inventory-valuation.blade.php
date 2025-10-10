@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Inventory Valuation Report</h4>
            <p class="fs-13 text-muted mb-0">Stock value at average cost</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.inventory-valuation', ['export' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i> Export PDF
            </a>
            <a href="{{ route('reports.inventory-valuation', ['export' => 'csv']) }}" class="btn btn-success btn-sm">
                <i class="ri-file-excel-line me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card custom-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.inventory-valuation') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
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
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Inventory Value</span>
                    <h2 class="mb-0 text-success">R {{ number_format($totalValue, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Units</span>
                    <h2 class="mb-0 text-info">{{ number_format($totalQty, 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Summary -->
    @if($categorySummary->count() > 0)
    <div class="card custom-card mb-4">
        <div class="card-header">
            <div class="card-title">Value by Category</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Products</th>
                            <th class="text-end">Total Qty</th>
                            <th class="text-end">Total Value</th>
                            <th class="text-end">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorySummary as $summary)
                        <tr>
                            <td><strong>{{ $summary['category'] }}</strong></td>
                            <td class="text-end">{{ $summary['product_count'] }}</td>
                            <td class="text-end">{{ number_format($summary['total_qty'], 0) }}</td>
                            <td class="text-end">R {{ number_format($summary['total_value'], 2) }}</td>
                            <td class="text-end">{{ $totalValue > 0 ? number_format(($summary['total_value'] / $totalValue) * 100, 2) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Detailed Product List -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Product Details</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-end">On Hand</th>
                            <th class="text-end">Avg Cost</th>
                            <th class="text-end">Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                            <td class="text-end">{{ number_format($product->on_hand, 2) }}</td>
                            <td class="text-end">R {{ number_format($product->avg_cost, 2) }}</td>
                            <td class="text-end fw-semibold">R {{ number_format($product->total_value, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No products with stock found</p>
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
