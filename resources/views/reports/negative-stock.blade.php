@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Negative Stock Report</h4>
            <p class="fs-13 text-muted mb-0">Products below zero requiring attention</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.negative-stock', ['export' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i> Export PDF
            </a>
            <a href="{{ route('reports.negative-stock', ['export' => 'csv']) }}" class="btn btn-success btn-sm">
                <i class="ri-file-excel-line me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Alert Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">
                            <i class="ri-alert-line me-2"></i> {{ $products->count() }} Products with Negative Stock
                        </h5>
                        <p class="mb-0">Total value impact: <strong>R {{ number_format(abs($totalNegativeValue), 2) }}</strong></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-light">
                            <i class="ri-shopping-cart-line me-1"></i> Create Purchase Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Negative Stock Table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Products Below Zero</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Bin Location</th>
                            <th class="text-end">On Hand</th>
                            <th class="text-end">Avg Cost</th>
                            <th class="text-end">Value Impact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->barcode_primary)
                                    <br><small class="text-muted">{{ $product->barcode_primary }}</small>
                                @endif
                            </td>
                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                            <td>{{ $product->brand ? $product->brand->name : '-' }}</td>
                            <td>{{ $product->bin_location ?? '-' }}</td>
                            <td class="text-end">
                                <span class="badge bg-danger">{{ number_format($product->on_hand, 2) }}</span>
                            </td>
                            <td class="text-end">R {{ number_format($product->avg_cost, 2) }}</td>
                            <td class="text-end text-danger fw-semibold">R {{ number_format(abs($product->value_impact), 2) }}</td>
                            <td>
                                <a href="{{ route('purchase-orders.create') }}" class="btn btn-sm btn-primary" title="Create PO">
                                    <i class="ri-shopping-cart-line"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                <p class="text-success mt-2 fw-semibold">No negative stock! All products have stock ≥ 0</p>
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
