@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Replenishment Report</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reports.index') }}">Reports</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Replenishment</li>
            </ol>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-secondary-light btn-wave waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="ri-download-line me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?export=pdf&{{ request()->getQueryString() }}"><i class="ri-file-pdf-line me-2"></i>Export as PDF</a></li>
                    <li><a class="dropdown-item" href="?export=csv&{{ request()->getQueryString() }}"><i class="ri-file-excel-line me-2"></i>Export as CSV</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start:: Filter Row -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.replenishment') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select" name="category_id">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Supplier</label>
                                <select class="form-select" name="supplier_id">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line me-1"></i>Apply Filter
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('reports.replenishment') }}" class="btn btn-secondary w-100">
                                    <i class="ri-refresh-line me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Filter Row -->

    <!-- Start:: Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Total Products</span>
                            <h3 class="fw-semibold mb-0">{{ $products->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent svg-white">
                            <i class="ri-box-3-line fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Low Stock</span>
                            <h3 class="fw-semibold mb-0 text-warning">{{ $lowStockProducts->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent svg-white">
                            <i class="ri-alert-line fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Out of Stock</span>
                            <h3 class="fw-semibold mb-0 text-danger">{{ $outOfStockProducts->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-danger-transparent svg-white">
                            <i class="ri-close-circle-line fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Need Replenishment</span>
                            <h3 class="fw-semibold mb-0 text-info">{{ $lowStockProducts->count() + $outOfStockProducts->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-info-transparent svg-white">
                            <i class="ri-add-circle-line fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Summary Cards -->

    <!-- Start:: Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Replenishment Report
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Action Needed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="{{ $product->total_stock <= 0 ? 'table-danger' : ($product->needs_replenishment ? 'table-warning' : '') }}">
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                            <br>
                                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $product->category ? $product->category->name : 'Uncategorized' }}</td>
                                    <td>{{ $product->supplier ? $product->supplier->name : 'No Supplier' }}</td>
                                    <td class="fw-semibold {{ $product->total_stock <= 0 ? 'text-danger' : ($product->needs_replenishment ? 'text-warning' : 'text-success') }}">
                                        {{ number_format($product->total_stock, 0) }}
                                    </td>
                                    <td>{{ $product->reorder_level }}</td>
                                    <td>
                                        @if($product->total_stock <= 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->needs_replenishment)
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->total_stock <= 0)
                                            <span class="text-danger fw-semibold">URGENT: Restock Immediately</span>
                                        @elseif($product->needs_replenishment)
                                            <span class="text-warning fw-semibold">Order Soon</span>
                                        @else
                                            <span class="text-success">No Action Needed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-24 mb-2"></i>
                                            <p>No products found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Report Table -->
</div>
@endsection

