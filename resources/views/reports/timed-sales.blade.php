@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Timed Sales Report</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('reports.index') }}">Reports</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Timed Sales</li>
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
                    <form method="GET" action="{{ route('reports.timed-sales') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Interval</label>
                                <select class="form-select" name="interval">
                                    <option value="hour" {{ $interval == 'hour' ? 'selected' : '' }}>Hourly</option>
                                    <option value="day" {{ $interval == 'day' ? 'selected' : '' }}>Daily</option>
                                    <option value="week" {{ $interval == 'week' ? 'selected' : '' }}>Weekly</option>
                                    <option value="month" {{ $interval == 'month' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line me-1"></i>Apply Filter
                                </button>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a href="{{ route('reports.timed-sales') }}" class="btn btn-secondary w-100">
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
                            <span class="d-block mb-2">Total Invoices</span>
                            <h3 class="fw-semibold mb-0">{{ number_format($totalInvoices, 0) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-info-transparent svg-white">
                            <i class="ri-file-list-line fs-18"></i>
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
                            <span class="d-block mb-2">Avg per Invoice</span>
                            <h3 class="fw-semibold mb-0">R {{ $totalInvoices > 0 ? number_format($totalSales / $totalInvoices, 2) : '0.00' }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent svg-white">
                            <i class="ri-calculator-line fs-18"></i>
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
                        Timed Sales Report - {{ ucfirst($interval) }} View ({{ $startDate }} to {{ $endDate }})
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    @if($interval == 'hour')
                                        <th>Date</th>
                                        <th>Hour</th>
                                    @elseif($interval == 'day')
                                        <th>Date</th>
                                    @elseif($interval == 'week')
                                        <th>Year</th>
                                        <th>Week</th>
                                    @elseif($interval == 'month')
                                        <th>Year</th>
                                        <th>Month</th>
                                    @endif
                                    <th>Invoices</th>
                                    <th>Total Sales</th>
                                    <th>Total Profit</th>
                                    <th>Avg per Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesData as $data)
                                <tr>
                                    @if($interval == 'hour')
                                        <td>{{ $data->date }}</td>
                                        <td>{{ $data->time_period }}:00</td>
                                    @elseif($interval == 'day')
                                        <td>{{ $data->date }}</td>
                                    @elseif($interval == 'week')
                                        <td>{{ $data->year }}</td>
                                        <td>Week {{ $data->week }}</td>
                                    @elseif($interval == 'month')
                                        <td>{{ $data->year }}</td>
                                        <td>{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</td>
                                    @endif
                                    <td>{{ $data->invoice_count }}</td>
                                    <td class="text-success fw-semibold">R {{ number_format($data->total_sales, 2) }}</td>
                                    <td class="text-primary fw-semibold">R {{ number_format($data->total_profit, 2) }}</td>
                                    <td>R {{ $data->invoice_count > 0 ? number_format($data->total_sales / $data->invoice_count, 2) : '0.00' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $interval == 'hour' ? 6 : ($interval == 'day' ? 5 : 6) }}" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line fs-24 mb-2"></i>
                                            <p>No sales data found for the selected period.</p>
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

