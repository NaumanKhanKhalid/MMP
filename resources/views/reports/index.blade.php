@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Start::page-header -->
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Reports</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary-light btn-wave waves-effect waves-light" onclick="refreshReports()">
                <i class="ri-refresh-line me-1"></i>Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-secondary-light btn-wave waves-effect waves-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="ri-download-line me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-file-pdf-line me-2"></i>Export as PDF</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-file-excel-line me-2"></i>Export as Excel</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-file-word-line me-2"></i>Export as Word</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start:: Quick Stats Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Total Reports</span>
                            <h3 class="fw-semibold mb-0">20</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-primary-transparent svg-white">
                            <i class="ri-file-list-line fs-18"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">Available</span>
                        <span class="text-success fw-semibold">All Active</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Generated Today</span>
                            <h3 class="fw-semibold mb-0">12</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-success-transparent svg-white">
                            <i class="ri-file-chart-line fs-18"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">This Week</span>
                        <span class="text-success fw-semibold">+8.5%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <span class="d-block mb-2">Most Viewed</span>
                            <h3 class="fw-semibold mb-0">Sales</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-info-transparent svg-white">
                            <i class="ri-eye-line fs-18"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">Last 30 days</span>
                        <span class="text-success fw-semibold">1.2K views</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
        <div>
                            <span class="d-block mb-2">Last Updated</span>
                            <h3 class="fw-semibold mb-0" id="lastUpdated">Just Now</h3>
                        </div>
                        <span class="avatar avatar-md avatar-rounded bg-warning-transparent svg-white">
                            <i class="ri-time-line fs-18"></i>
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted">Auto-refresh</span>
                        <span class="text-success fw-semibold">Enabled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Quick Stats Row -->

    <!-- Start:: Date Filter Row -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" class="form-control" id="startDate" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" class="form-control" id="endDate" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Quick Filter</label>
                            <select class="form-select" id="quickFilter">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="applyDateFilter()">
                                <i class="ri-filter-line me-1"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Date Filter Row -->

    <!-- Start:: Report Categories Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs nav-style-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#sales-tab" role="tab">
                                <i class="ri-line-chart-line me-1"></i>Sales & Revenue
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#inventory-tab" role="tab">
                                <i class="ri-box-3-line me-1"></i>Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#financial-tab" role="tab">
                                <i class="ri-money-dollar-circle-line me-1"></i>Financial
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#customer-tab" role="tab">
                                <i class="ri-user-line me-1"></i>Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#supplier-tab" role="tab">
                                <i class="ri-truck-line me-1"></i>Supplier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#operational-tab" role="tab">
                                <i class="ri-settings-3-line me-1"></i>Operational
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Sales & Revenue Tab -->
                        <div class="tab-pane show active" id="sales-tab" role="tabpanel">
    <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                    <i class="ri-line-chart-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Sales Report</h5>
                                                    <p class="text-muted mb-3 fs-13">Daily sales summary with profit margins</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">Sales</span>
                                                <a href="{{ route('reports.sales') }}" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-bar-chart-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Sales by Category</h5>
                                                    <p class="text-muted mb-3 fs-13">Category performance analysis</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Category</span>
                                                <a href="{{ route('reports.sales-by-category') }}" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-info-transparent">
                                                            <i class="ri-time-line fs-24 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Timed Sales</h5>
                                                    <p class="text-muted mb-3 fs-13">Sales by time intervals</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-info-transparent text-info">Time</span>
                                                <a href="{{ route('reports.timed-sales') }}" class="btn btn-sm btn-info-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-warning-transparent">
                                                            <i class="ri-price-tag-3-line fs-24 text-warning"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Discount Matrix</h5>
                                                    <p class="text-muted mb-3 fs-13">Sales & profit by discount matrix</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-warning-transparent text-warning">Discount</span>
                                                <a href="{{ route('reports.discount-matrix') }}" class="btn btn-sm btn-warning-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <!-- Inventory Tab -->
                        <div class="tab-pane" id="inventory-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                                            <i class="ri-box-3-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Inventory Valuation</h5>
                                                    <p class="text-muted mb-3 fs-13">Stock value and costs</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">Stock</span>
                                                <a href="{{ route('reports.inventory-valuation') }}" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-arrow-up-down-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Stock Movement</h5>
                                                    <p class="text-muted mb-3 fs-13">18-month movement history</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Movement</span>
                                                <a href="{{ route('reports.stock-movement') }}" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-info-transparent">
                                                            <i class="ri-arrow-up-circle-line fs-24 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Replenishment</h5>
                                                    <p class="text-muted mb-3 fs-13">Stock movement analysis</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-info-transparent text-info">Replenish</span>
                                                <a href="{{ route('reports.replenishment') }}" class="btn btn-sm btn-info-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-danger-transparent">
                                                            <i class="ri-alert-line fs-24 text-danger"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Negative Stock</h5>
                                                    <p class="text-muted mb-3 fs-13">Products with negative stock</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-danger-transparent text-danger">Alert</span>
                                                <a href="{{ route('reports.negative-stock') }}" class="btn btn-sm btn-danger-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-warning-transparent">
                                                            <i class="ri-add-circle-line fs-24 text-warning"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">New Items</h5>
                                                    <p class="text-muted mb-3 fs-13">Recently added products</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-warning-transparent text-warning">New</span>
                                                <a href="{{ route('reports.new-items') }}" class="btn btn-sm btn-warning-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <!-- Financial Tab -->
                        <div class="tab-pane" id="financial-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                                            <i class="ri-bar-chart-box-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Profit & Loss</h5>
                                                    <p class="text-muted mb-3 fs-13">Financial performance</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">P&L</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-file-text-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Tax Report</h5>
                                                    <p class="text-muted mb-3 fs-13">Tax reports and summaries</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Tax</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-info-transparent">
                                                            <i class="ri-money-dollar-circle-line fs-24 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Expenses</h5>
                                                    <p class="text-muted mb-3 fs-13">Detailed expense breakdown</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-info-transparent text-info">Expense</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <!-- Customer Tab -->
                        <div class="tab-pane" id="customer-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                                            <i class="ri-user-received-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Customer Balances</h5>
                                                    <p class="text-muted mb-3 fs-13">Outstanding customer balances</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">Balance</span>
                                                <a href="{{ route('reports.debtors-ageing') }}" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-file-list-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Customer Sales</h5>
                                                    <p class="text-muted mb-3 fs-13">Customer Sales Report</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Sales</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-info-transparent">
                                                            <i class="ri-star-line fs-24 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Loyalty Report</h5>
                                                    <p class="text-muted mb-3 fs-13">Loyalty balances, earned & redeemed</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-info-transparent text-info">Loyalty</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <!-- Supplier Tab -->
                        <div class="tab-pane" id="supplier-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                                            <i class="ri-truck-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Supplier Buying</h5>
                                                    <p class="text-muted mb-3 fs-13">Buying From Supplier Report</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">Buying</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-user-received-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Creditors Ageing</h5>
                                                    <p class="text-muted mb-3 fs-13">Supplier outstanding balances</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Ageing</span>
                                                <a href="{{ route('reports.creditors-ageing') }}" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <!-- Operational Tab -->
                        <div class="tab-pane" id="operational-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-primary-transparent">
                                                            <i class="ri-file-list-line fs-24 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Items Sales</h5>
                                                    <p class="text-muted mb-3 fs-13">Combined items sales report</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-primary-transparent text-primary">Items</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-success-transparent">
                                                            <i class="ri-file-text-line fs-24 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Transactions</h5>
                                                    <p class="text-muted mb-3 fs-13">All transactions list</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-success-transparent text-success">Txns</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-success-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-info-transparent">
                                                            <i class="ri-group-line fs-24 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Employee Performance</h5>
                                                    <p class="text-muted mb-3 fs-13">Staff sales and metrics</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-info-transparent text-info">Staff</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-info-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card custom-card report-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-warning-transparent">
                                                            <i class="ri-file-chart-line fs-24 text-warning"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Day End Detailed</h5>
                                                    <p class="text-muted mb-3 fs-13">Employee daily transactions</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-warning-transparent text-warning">Day End</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-warning-light">
                                                    <i class="ri-arrow-right-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
            <div class="card custom-card report-card h-100">
                <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                                        <span class="avatar avatar-lg bg-danger-transparent">
                                                            <i class="ri-time-line fs-24 text-danger"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-semibold mb-2">Lost Sales</h5>
                                                    <p class="text-muted mb-3 fs-13">Missed sales opportunities</p>
                                </div>
                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge bg-danger-transparent text-danger">Lost</span>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger-light">
                                                    <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End:: Report Categories Tabs -->
</div>

<style>
.report-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: #0d6efd;
}

.avatar {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.nav-style-1 .nav-link {
    color: #6c757d;
    font-weight: 500;
}

.nav-style-1 .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
}

.nav-style-1 .nav-link:hover {
    color: #0d6efd;
}
</style>

<script>
function refreshReports() {
    document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
    toastr.success('Reports refreshed successfully!');
}

function applyDateFilter() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    toastr.success(`Filter applied: ${startDate} to ${endDate}`);
}

document.getElementById('quickFilter').addEventListener('change', function() {
    const value = this.value;
    const today = new Date();
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    switch(value) {
        case 'today':
            startDate.value = endDate.value = today.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            startDate.value = weekStart.toISOString().split('T')[0];
            endDate.value = new Date().toISOString().split('T')[0];
            break;
        case 'month':
            startDate.value = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            endDate.value = new Date().toISOString().split('T')[0];
            break;
        case 'quarter':
            const quarter = Math.floor(today.getMonth() / 3);
            startDate.value = new Date(today.getFullYear(), quarter * 3, 1).toISOString().split('T')[0];
            endDate.value = new Date().toISOString().split('T')[0];
            break;
        case 'year':
            startDate.value = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
            endDate.value = new Date().toISOString().split('T')[0];
            break;
    }
});
</script>
@endsection
