@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Discount Matrix Report</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reports.index') }}">Reports</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Discount Matrix</li>
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
                    <form method="GET" action="{{ route('reports.discount-matrix') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line me-1"></i>Apply Filter
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('reports.discount-matrix') }}" class="btn btn-secondary w-100">
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
                            <span class="d-block mb-2">Total Sales</span>
                            <h3 class="fw-semibold mb-0">R {{ number_format($totalSales, 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent svg-white">
                            <i class="ri-money-dollar-circle-line fs-18"></i>
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
                            <span class="d-block mb-2">Total Profit</span>
                            <h3 class="fw-semibold mb-0">R {{ number_format($totalProfit, 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-success-transparent svg-white">
                            <i class="ri-line-chart-line fs-18"></i>
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
                            <span class="d-block mb-2">Total Items</span>
                            <h3 class="fw-semibold mb-0">{{ number_format($totalItems, 0) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-info-transparent svg-white">
                            <i class="ri-shopping-cart-line fs-18"></i>
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
                            <span class="d-block mb-2">Overall Margin</span>
                            <h3 class="fw-semibold mb-0">{{ $totalSales > 0 ? number_format(($totalProfit / $totalSales) * 100, 1) : '0.0' }}%</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent svg-white">
                            <i class="ri-percent-line fs-18"></i>
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
                        Discount Matrix Report - {{ $startDate }} to {{ $endDate }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Discount Range</th>
                                    <th>Items</th>
                                    <th>Quantity</th>
                                    <th>Total Sales</th>
                                    <th>Total Cost</th>
                                    <th>Total Profit</th>
                                    <th>Avg Discount %</th>
                                    <th>Margin %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discountMatrix as $matrix)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $matrix->discount_range == '0%' ? 'success' : ($matrix->discount_range == '1-5%' ? 'info' : ($matrix->discount_range == '6-10%' ? 'warning' : 'danger')) }}-transparent">
                                            {{ $matrix->discount_range }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($matrix->item_count, 0) }}</td>
                                    <td>{{ number_format($matrix->total_qty, 0) }}</td>
                                    <td class="text-success fw-semibold">R {{ number_format($matrix->total_sales, 2) }}</td>
                                    <td class="text-danger">R {{ number_format($matrix->total_cost, 2) }}</td>
                                    <td class="text-primary fw-semibold">R {{ number_format($matrix->total_profit, 2) }}</td>
                                    <td>{{ number_format($matrix->avg_discount, 1) }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $matrix->total_sales > 0 && (($matrix->total_profit / $matrix->total_sales) * 100) >= 20 ? 'success' : (($matrix->total_profit / $matrix->total_sales) * 100) >= 10 ? 'warning' : 'danger' }}-transparent">
                                            {{ $matrix->total_sales > 0 ? number_format(($matrix->total_profit / $matrix->total_sales) * 100, 1) : '0.0' }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-24 mb-2"></i>
                                            <p>No discount data found for the selected period.</p>
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



