@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">New Items Report</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reports.index') }}">Reports</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">New Items</li>
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
                    <form method="GET" action="{{ route('reports.new-items') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                            </div>
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
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line me-1"></i>Apply Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="{{ route('reports.new-items') }}" class="btn btn-secondary w-100">
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
                            <span class="d-block mb-2">Total New Items</span>
                            <h3 class="fw-semibold mb-0">{{ $newProducts->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent svg-white">
                            <i class="ri-add-circle-line fs-18"></i>
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
                            <span class="d-block mb-2">Active Items</span>
                            <h3 class="fw-semibold mb-0 text-success">{{ $newProducts->where('status', 'active')->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-success-transparent svg-white">
                            <i class="ri-check-circle-line fs-18"></i>
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
                            <span class="d-block mb-2">Inactive Items</span>
                            <h3 class="fw-semibold mb-0 text-warning">{{ $newProducts->where('status', 'inactive')->count() }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent svg-white">
                            <i class="ri-pause-circle-line fs-18"></i>
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
                            <span class="d-block mb-2">Date Range</span>
                            <h3 class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} days</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-info-transparent svg-white">
                            <i class="ri-calendar-line fs-18"></i>
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
                        New Items Report - {{ $startDate }} to {{ $endDate }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newProducts as $product)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                            @if($product->notes)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($product->notes, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-transparent">{{ $product->sku }}</span>
                                    </td>
                                    <td>{{ $product->category ? $product->category->name : 'Uncategorized' }}</td>
                                    <td>{{ $product->brand ? $product->brand->name : 'No Brand' }}</td>
                                    <td>{{ $product->supplier ? $product->supplier->name : 'No Supplier' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'warning' }}-transparent">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $product->creator ? $product->creator->name : 'System' }}</td>
                                    <td>
                                        <div>
                                            {{ $product->created_at->format('Y-m-d') }}
                                            <br>
                                            <small class="text-muted">{{ $product->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-24 mb-2"></i>
                                            <p>No new items found for the selected period.</p>
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

    <!-- Start:: Products by Date -->
    @if($productsByDate->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        New Items by Date
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($productsByDate as $date => $products)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h6>
                                    <small class="text-muted">{{ $products->count() }} items</small>
                                </div>
                                <div class="card-body">
                                    @foreach($products->take(3) as $product)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1">
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $product->sku }}</small>
                                        </div>
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'warning' }}-transparent">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                    @if(!$loop->last)
                                        <hr class="my-2">
                                    @endif
                                    @endforeach
                                    @if($products->count() > 3)
                                        <div class="text-center mt-2">
                                            <small class="text-muted">+{{ $products->count() - 3 }} more items</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- End:: Products by Date -->
</div>
@endsection

