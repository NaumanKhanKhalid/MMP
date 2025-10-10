@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0">Creditors Ageing Report</h4>
            <p class="fs-13 text-muted mb-0">As at {{ date('d M Y', strtotime($asAt)) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.creditors-ageing', ['as_at' => $asAt, 'export' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="ri-file-pdf-line me-1"></i> Export PDF
            </a>
            <a href="{{ route('reports.creditors-ageing', ['as_at' => $asAt, 'export' => 'csv']) }}" class="btn btn-success btn-sm">
                <i class="ri-file-excel-line me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card custom-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.creditors-ageing') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">As At Date</label>
                        <input type="date" name="as_at" class="form-control" value="{{ $asAt }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card custom-card bg-success-transparent">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <h6 class="mb-1">Current</h6>
                            <h4 class="mb-0">R {{ number_format($totals['current'], 2) }}</h4>
                        </div>
                        <div class="col">
                            <h6 class="mb-1">30 Days</h6>
                            <h4 class="mb-0">R {{ number_format($totals['days30'], 2) }}</h4>
                        </div>
                        <div class="col">
                            <h6 class="mb-1">60 Days</h6>
                            <h4 class="mb-0">R {{ number_format($totals['days60'], 2) }}</h4>
                        </div>
                        <div class="col">
                            <h6 class="mb-1">90 Days</h6>
                            <h4 class="mb-0">R {{ number_format($totals['days90'], 2) }}</h4>
                        </div>
                        <div class="col">
                            <h6 class="mb-1">Over 90</h6>
                            <h4 class="mb-0 text-danger">R {{ number_format($totals['over90'], 2) }}</h4>
                        </div>
                        <div class="col border-start">
                            <h6 class="mb-1">TOTAL OWED</h6>
                            <h3 class="mb-0 text-success">R {{ number_format($totals['total'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ageing Table -->
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Supplier Outstanding Balances</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th class="text-end">Current</th>
                            <th class="text-end">30 Days</th>
                            <th class="text-end">60 Days</th>
                            <th class="text-end">90 Days</th>
                            <th class="text-end">Over 90</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ageingData as $data)
                        <tr>
                            <td>
                                <strong>{{ $data['supplier']->name }}</strong>
                                <br><small class="text-muted">{{ $data['supplier']->supplier_code }}</small>
                            </td>
                            <td class="text-end">R {{ number_format($data['current'], 2) }}</td>
                            <td class="text-end {{ $data['days30'] > 0 ? 'text-warning' : '' }}">R {{ number_format($data['days30'], 2) }}</td>
                            <td class="text-end {{ $data['days60'] > 0 ? 'text-warning' : '' }}">R {{ number_format($data['days60'], 2) }}</td>
                            <td class="text-end {{ $data['days90'] > 0 ? 'text-danger' : '' }}">R {{ number_format($data['days90'], 2) }}</td>
                            <td class="text-end {{ $data['over90'] > 0 ? 'text-danger fw-bold' : '' }}">R {{ number_format($data['over90'], 2) }}</td>
                            <td class="text-end fw-bold">R {{ number_format($data['total'], 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                <p class="text-muted mt-2">No outstanding supplier balances</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($ageingData) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-end">R {{ number_format($totals['current'], 2) }}</th>
                            <th class="text-end">R {{ number_format($totals['days30'], 2) }}</th>
                            <th class="text-end">R {{ number_format($totals['days60'], 2) }}</th>
                            <th class="text-end">R {{ number_format($totals['days90'], 2) }}</th>
                            <th class="text-end">R {{ number_format($totals['over90'], 2) }}</th>
                            <th class="text-end fw-bold">R {{ number_format($totals['total'], 2) }}</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
