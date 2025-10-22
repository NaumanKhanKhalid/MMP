@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Dashboard</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
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
    <!-- End::page-header -->

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-white-50" style="font-size: 14px;">Total Revenue</span>
                            <h3 class="fw-bold mb-0" style="font-size: 28px;">R {{ number_format($metrics['revenue'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-white bg-opacity-20">
                            <i class="ri-money-dollar-circle-line fs-24 text-white"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-white border-opacity-20">
                        <span class="text-white-50">
                            <i class="ri-file-list-line me-1"></i>{{ $metrics['invoice_count'] }} invoices
                        </span>
                        <span class="fw-bold {{ $metrics['revenue_change'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="ri-arrow-{{ $metrics['revenue_change'] >= 0 ? 'up' : 'down' }}-s-line me-1"></i>
                            {{ number_format(abs($metrics['revenue_change']), 2) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-white-50" style="font-size: 14px;">Gross Profit</span>
                            <h3 class="fw-bold mb-0" style="font-size: 28px;">R {{ number_format($metrics['gross_profit'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-white bg-opacity-20">
                            <i class="ri-line-chart-line fs-24 text-white"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-white border-opacity-20">
                        <span class="text-white-50">
                            <i class="ri-percent-line me-1"></i>Profit Margin
                        </span>
                        <span class="fw-bold text-white">
                            {{ $metrics['revenue'] > 0 ? number_format(($metrics['gross_profit'] / $metrics['revenue']) * 100, 2) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-white-50" style="font-size: 14px;">Net Profit</span>
                            <h3 class="fw-bold mb-0" style="font-size: 28px;">R {{ number_format($metrics['net_profit'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-white bg-opacity-20">
                            <i class="ri-stack-line fs-24 text-white"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-white border-opacity-20">
                        <span class="text-white-50">
                            <i class="ri-calculator-line me-1"></i>Net Margin
                        </span>
                        <span class="fw-bold text-white">
                            {{ $metrics['revenue'] > 0 ? number_format(($metrics['net_profit'] / $metrics['revenue']) * 100, 2) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-white-50" style="font-size: 14px;">Inventory Value</span>
                            <h3 class="fw-bold mb-0" style="font-size: 28px;">R {{ number_format($metrics['inventory_value'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-white bg-opacity-20">
                            <i class="ri-box-3-line fs-24 text-white"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-white border-opacity-20">
                        <span class="text-white-50">
                            <i class="ri-price-tag-3-line me-1"></i>At Cost
                        </span>
                        <a href="{{ route('products.index') }}" class="text-white fw-bold text-decoration-none">
                            <i class="ri-eye-line me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-1 -->

    <!-- Start:: row-2 -->
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-muted" style="font-size: 14px;">
                                <i class="ri-user-received-line me-1"></i>Debtors Balance
                            </span>
                            <h3 class="fw-bold mb-0" style="font-size: 32px; color: #d63384;">R {{ number_format($metrics['debtors_balance'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-danger bg-opacity-10">
                            <i class="ri-user-received-line fs-24" style="color: #d63384;"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-danger border-opacity-20">
                        <span class="text-muted">Outstanding Amount</span>
                        <a href="{{ route('reports.debtors-ageing') }}" class="fw-bold text-decoration-none" style="color: #d63384;">
                            <i class="ri-file-chart-line me-1"></i>View Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card custom-card border-0 shadow-sm" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <span class="d-block mb-2 text-muted" style="font-size: 14px;">
                                <i class="ri-truck-line me-1"></i>Creditors Balance
                            </span>
                            <h3 class="fw-bold mb-0" style="font-size: 32px; color: #198754;">R {{ number_format($metrics['creditors_balance'], 2) }}</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-success bg-opacity-10">
                            <i class="ri-truck-line fs-24" style="color: #198754;"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top border-success border-opacity-20">
                        <span class="text-muted">Payable Amount</span>
                        <a href="{{ route('reports.creditors-ageing') }}" class="fw-bold text-decoration-none" style="color: #198754;">
                            <i class="ri-file-chart-line me-1"></i>View Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: row-2 -->
    <!-- Sales Chart & Low Stock -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-line-chart-line me-2 text-primary"></i>Sales Statistics (Last 30 Days)
                        </h5>
                        <span class="badge bg-primary-transparent text-primary">30 Days</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="ri-alert-line me-2 text-warning"></i>Low Stock Alerts
                        </h5>
                        @if($lowStockProducts->count() > 0)
                        <span class="badge bg-danger rounded-pill">{{ $lowStockProducts->count() }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-3" style="max-height: 350px; overflow-y: auto;">
                    @if($lowStockProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                            <div class="list-group-item px-3 py-3 mb-2 rounded border-0" style="background: {{ $product->on_hand < 0 ? '#fff5f5' : '#fffbf0' }};">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-{{ $product->on_hand < 0 ? 'danger' : 'warning' }} rounded-pill me-2">
                                                <i class="ri-stack-line me-1"></i>{{ number_format($product->on_hand, 0) }}
                                            </span>
                                        </div>
                                        <strong class="d-block text-dark mb-1">{{ $product->name }}</strong>
                                        <small class="text-muted d-block">
                                            <i class="ri-barcode-line me-1"></i>{{ $product->sku }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="ri-arrow-up-circle-line me-1"></i>Reorder: {{ $product->reorder_level }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('reports.negative-stock') }}" class="btn btn-danger w-100 shadow-sm">
                                <i class="ri-alert-line me-2"></i>View All Low Stock
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="ri-checkbox-circle-line text-success" style="font-size: 48px;"></i>
                            </div>
                            <h6 class="fw-bold text-success">All Stock Levels OK!</h6>
                            <p class="text-muted mb-0 small">No low stock alerts at the moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Actions & Recent Activity -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="ri-flashlight-line me-2 text-warning"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('pos.index') }}" class="btn btn-primary w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-store-2-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">New Sale</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('quotes.index') }}" class="btn btn-info w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-file-list-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">New Quote</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-success w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-shopping-cart-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">New PO</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('stock-counts.create') }}" class="btn btn-warning w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-calculator-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">Stock Count</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-money-dollar-circle-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">Payments</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-dark w-100 shadow-sm" style="height: 85px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="ri-file-chart-line" style="font-size: 32px; margin-bottom: 8px;"></i>
                                <span class="fw-bold">Reports</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="ri-history-line me-2 text-info"></i>Recent Stock Activity
                    </h5>
                </div>
                <div class="card-body p-3" style="max-height: 350px; overflow-y: auto;">
                    @if($recentActivity->count() > 0)
                        <div class="list-unstyled mb-0">
                            @foreach($recentActivity as $activity)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start">
                                    <div class="bg-{{ $activity->qty > 0 ? 'success' : 'danger' }}-transparent rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-{{ $activity->qty > 0 ? 'add' : 'subtract' }}-line text-{{ $activity->qty > 0 ? 'success' : 'danger' }}" style="font-size: 20px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong class="d-block text-dark mb-1">{{ $activity->product->name }}</strong>
                                        <span class="badge bg-{{ $activity->qty > 0 ? 'success' : 'primary' }}-transparent text-{{ $activity->qty > 0 ? 'success' : 'primary' }} me-2">
                                            {{ $activity->document_type }}
                                        </span>
                                        <span class="fw-bold {{ $activity->qty > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $activity->qty > 0 ? '+' : '' }}{{ number_format($activity->qty, 0) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="ri-user-line me-1"></i>{{ $activity->user ? $activity->user->name : 'System' }}
                                            <i class="ri-time-line ms-2 me-1"></i>{{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="ri-inbox-line text-info" style="font-size: 48px;"></i>
                            </div>
                            <h6 class="fw-bold text-info">No Recent Activity</h6>
                            <p class="text-muted mb-0 small">Stock movements will appear here</p>
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
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
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
                labels: {
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    padding: 15,
                    usePointStyle: true
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
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
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    callback: function(value) {
                        return 'R ' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            }
        }
    }
});
</script>
@endpush
