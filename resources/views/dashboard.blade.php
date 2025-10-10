@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
        <div>
            <p class="fw-semibold fs-20 mb-0">Welcome Back, {{ auth()->user()->name }}</p>
            <p class="fs-13 text-muted mb-0">{{ $filterType === 'today' ? 'Today' : ($filterType === 'week' ? 'This Week' : ($filterType === 'month' ? 'This Month' : 'This Year')) }}'s Overview</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Date Filter Buttons -->
            <div class="btn-group" role="group">
                <a href="{{ route('dashboard', ['filter' => 'today']) }}" 
                   class="btn btn-{{ $filterType === 'today' ? 'primary' : 'light' }} btn-sm">Today</a>
                <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
                   class="btn btn-{{ $filterType === 'week' ? 'primary' : 'light' }} btn-sm">Week</a>
                <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
                   class="btn btn-{{ $filterType === 'month' ? 'primary' : 'light' }} btn-sm">Month</a>
                <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
                   class="btn btn-{{ $filterType === 'year' ? 'primary' : 'light' }} btn-sm">Year</a>
            </div>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#customDateModal">
                <i class="ri-calendar-line me-1"></i> Custom Range
            </button>
        </div>
    </div>
    <!-- Metric Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card custom-card main-card-item">
                <div class="card-body">
                    <span class="d-block mb-3 fw-medium">Total Revenue</span>
                    <h3 class="fw-semibold lh-1 mb-0">R {{ number_format($metrics['revenue'], 2) }}</h3>
                    <a href="{{ route('reports.sales') }}" class="text-muted text-decoration-underline fw-medium fs-13">{{ $metrics['invoice_count'] }} invoices</a>
                    <span class="fw-semibold d-block mt-2 {{ $metrics['revenue_change'] >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="ri-arrow-{{ $metrics['revenue_change'] >= 0 ? 'up' : 'down' }}-line"></i>
                        {{ number_format(abs($metrics['revenue_change']), 2) }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card main-card-item">
                <div class="card-body">
                    <span class="d-block mb-3 fw-medium">Gross Profit</span>
                    <h3 class="fw-semibold lh-1 mb-0 text-success">R {{ number_format($metrics['gross_profit'], 2) }}</h3>
                    <a href="{{ route('reports.sales') }}" class="text-muted text-decoration-underline fw-medium fs-13">View sales report</a>
                    <span class="text-muted fw-medium d-block mt-2">
                        Margin: {{ $metrics['revenue'] > 0 ? number_format(($metrics['gross_profit'] / $metrics['revenue']) * 100, 2) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card main-card-item">
                <div class="card-body">
                    <span class="d-block mb-3 fw-medium">Net Profit</span>
                    <h3 class="fw-semibold lh-1 mb-0 {{ $metrics['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                        R {{ number_format($metrics['net_profit'], 2) }}
                    </h3>
                    <span class="text-muted text-decoration-underline fw-medium fs-13">After payment fees</span>
                    <span class="text-muted fw-medium d-block mt-2">
                        Net Margin: {{ $metrics['revenue'] > 0 ? number_format(($metrics['net_profit'] / $metrics['revenue']) * 100, 2) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card main-card-item">
                <div class="card-body">
                    <span class="d-block mb-3 fw-medium">Inventory Value</span>
                    <h3 class="fw-semibold lh-1 mb-0 text-info">R {{ number_format($metrics['inventory_value'], 2) }}</h3>
                    <a href="{{ route('reports.inventory-valuation') }}" class="text-muted text-decoration-underline fw-medium fs-13">View valuation</a>
                    <span class="text-muted fw-medium d-block mt-2">At average cost</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric Cards Row 2 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block mb-2 fw-medium">Debtors Balance</span>
                            <h3 class="fw-semibold lh-1 mb-0 {{ $metrics['debtors_balance'] > 0 ? 'text-warning' : 'text-success' }}">
                                R {{ number_format($metrics['debtors_balance'], 2) }}
                            </h3>
                            <a href="{{ route('reports.debtors-ageing') }}" class="text-muted text-decoration-underline fw-medium fs-13">View ageing report</a>
                        </div>
                        <div class="avatar avatar-lg bg-warning-transparent">
                            <i class="ri-user-received-line fs-24 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="d-block mb-2 fw-medium">Creditors Balance</span>
                            <h3 class="fw-semibold lh-1 mb-0 {{ $metrics['creditors_balance'] > 0 ? 'text-success' : 'text-info' }}">
                                R {{ number_format($metrics['creditors_balance'], 2) }}
                            </h3>
                            <a href="{{ route('reports.creditors-ageing') }}" class="text-muted text-decoration-underline fw-medium fs-13">View ageing report</a>
                        </div>
                        <div class="avatar avatar-lg bg-success-transparent">
                            <i class="ri-truck-line fs-24 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sales Chart & Low Stock -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Sales Statistics (Last 30 Days)</div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Low Stock Alerts</div>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @if($lowStockProducts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong class="d-block">{{ $product->name }}</strong>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $product->on_hand < 0 ? 'danger' : 'warning' }}">
                                            {{ number_format($product->on_hand, 0) }}
                                        </span>
                                        <br><small class="text-muted">Reorder: {{ $product->reorder_level }}</small>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('reports.negative-stock') }}" class="btn btn-sm btn-danger w-100">
                                <i class="ri-alert-line me-1"></i> View All Low Stock
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                            <p class="text-muted mb-0 mt-2">All stock levels OK!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Actions & Recent Activity -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('pos.index') }}" class="btn btn-primary w-100">
                                <i class="ri-store-2-line me-2"></i> New Sale
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('quotes.index') }}" class="btn btn-info w-100">
                                <i class="ri-file-list-line me-2"></i> New Quote
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-success w-100">
                                <i class="ri-shopping-cart-line me-2"></i> New PO
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('stock-counts.create') }}" class="btn btn-warning w-100">
                                <i class="ri-calculator-line me-2"></i> Stock Count
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary w-100">
                                <i class="ri-money-dollar-circle-line me-2"></i> Payments
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-dark w-100">
                                <i class="ri-file-chart-line me-2"></i> Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Recent Stock Activity</div>
                </div>
                <div class="card-body" style="max-height: 280px; overflow-y: auto;">
                    @if($recentActivity->count() > 0)
                        <ul class="list-unstyled recent-activity-list mb-0">
                            @foreach($recentActivity as $activity)
                            <li class="mb-3 pb-2 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1">
                                        <strong>{{ $activity->product->name }}</strong>
                                        <br>
                                        <span class="badge bg-{{ $activity->qty > 0 ? 'success' : 'primary' }}-transparent">
                                            {{ $activity->document_type }}
                                        </span>
                                        <span class="{{ $activity->qty > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $activity->qty > 0 ? '+' : '' }}{{ number_format($activity->qty, 0) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $activity->user ? $activity->user->name : 'System' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-inbox-line fs-1 text-muted"></i>
                            <p class="text-muted mb-0 mt-2">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Date Range Modal -->
<div class="modal fade" id="customDateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Custom Date Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('dashboard') }}">
                <input type="hidden" name="filter" value="custom">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Daily Sales (R)',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Sales: R ' + context.parsed.y.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R ' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }
                }
            }
        }
    }
});
</script>
@endpush
