@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Sales Report</h4>
            <p class="fs-13 text-muted mb-0">{{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.sales', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i> Export PDF
            </a>
            <a href="{{ route('reports.sales', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'csv']) }}" class="btn btn-success btn-sm">
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
            <form method="GET" action="{{ route('reports.sales') }}">
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
                        <label class="form-label">Customer (Optional)</label>
                        <select name="customer_id" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Sales</span>
                    <h3 class="mb-0">R {{ number_format($totalSales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Cost</span>
                    <h3 class="mb-0 text-danger">R {{ number_format($totalCost, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Profit</span>
                    <h3 class="mb-0 text-success">R {{ number_format($totalProfit, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Average Margin</span>
                    <h3 class="mb-0 text-info">{{ number_format($averageMargin, 2) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Sales Transactions ({{ $invoices->count() }} invoices)</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Subtotal</th>
                            <th>VAT</th>
                            <th>Total</th>
                            <th>Cost</th>
                            <th>Profit</th>
                            <th>Margin %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->created_at->format('d M Y') }}</td>
                            <td>{{ $invoice->customer ? $invoice->customer->name : 'Cash Sale' }}</td>
                            <td>R {{ number_format($invoice->subtotal, 2) }}</td>
                            <td>R {{ number_format($invoice->vat_amount, 2) }}</td>
                            <td>R {{ number_format($invoice->grand_total, 2) }}</td>
                            <td>R {{ number_format($invoice->items->sum('line_cost'), 2) }}</td>
                            <td class="text-success fw-semibold">R {{ number_format($invoice->total_profit, 2) }}</td>
                            <td>{{ number_format($invoice->gross_profit_percentage, 2) }}%</td>
                            <td>
                                <span class="badge bg-{{ $invoice->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($invoice->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="ri-inbox-line fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No sales found for this period</p>
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
