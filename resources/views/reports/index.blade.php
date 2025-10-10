@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Reports Dashboard</h4>
            <p class="fs-13 text-muted mb-0">Business intelligence and analytics</p>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="row">
        <!-- Sales Report -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-primary-transparent">
                                    <i class="ri-line-chart-line fs-24 text-primary"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Sales Report</h5>
                            <p class="text-muted mb-3">Detailed sales with profit margins and customer breakdown</p>
                            <a href="{{ route('reports.sales') }}" class="btn btn-primary btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debtors Ageing -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-warning-transparent">
                                    <i class="ri-user-received-line fs-24 text-warning"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Debtors Ageing</h5>
                            <p class="text-muted mb-3">Customer outstanding balances aged by 30/60/90 days</p>
                            <a href="{{ route('reports.debtors-ageing') }}" class="btn btn-warning btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Creditors Ageing -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-success-transparent">
                                    <i class="ri-truck-line fs-24 text-success"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Creditors Ageing</h5>
                            <p class="text-muted mb-3">Supplier outstanding balances aged by 30/60/90 days</p>
                            <a href="{{ route('reports.creditors-ageing') }}" class="btn btn-success btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Negative Stock -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-danger-transparent">
                                    <i class="ri-alert-line fs-24 text-danger"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Negative Stock</h5>
                            <p class="text-muted mb-3">Products with stock below zero requiring attention</p>
                            <a href="{{ route('reports.negative-stock') }}" class="btn btn-danger btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Valuation -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-info-transparent">
                                    <i class="ri-price-tag-3-line fs-24 text-info"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Inventory Valuation</h5>
                            <p class="text-muted mb-3">Stock value breakdown by category and product</p>
                            <a href="{{ route('reports.inventory-valuation') }}" class="btn btn-info btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <div class="avatar avatar-lg bg-secondary-transparent">
                                    <i class="ri-arrow-left-right-line fs-24 text-secondary"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2">Stock Movement</h5>
                            <p class="text-muted mb-3">Products in and out with full transaction history</p>
                            <a href="{{ route('reports.stock-movement') }}" class="btn btn-secondary btn-sm">
                                <i class="ri-file-list-line me-1"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.report-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.avatar {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}
</style>
@endsection
