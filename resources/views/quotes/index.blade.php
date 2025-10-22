@extends('layouts.app')

@push('styles')
    <style>
        .clickable-row {
            transition: background-color 0.2s ease;
        }

        .clickable-row:hover {
            background-color: #f8f9fa !important;
        }
    </style>

    <script>
        function openViewModal(quoteId) {
            // Check if the click came from a button or form
            if (event.target.closest('button') || event.target.closest('form')) {
                return; // Don't open modal if clicking on buttons/forms
            }

            // Open the view modal
            $.get("{{ route('quotes.view-modal', ':id') }}".replace(':id', quoteId), function(html) {
                $('#quoteModalContent').html(html);
                $('#quoteModal').modal('show');
            });
        }

        function printQuotes() {
            try {
                // Get quote data for summary
                const totalQuotes = {{ $quotes->total() }};
                const draftQuotes = {{ $quotes->where('status', 'draft')->count() }};
                const sentQuotes = {{ $quotes->where('status', 'sent')->count() }};
                const acceptedQuotes = {{ $quotes->where('status', 'accepted')->count() }};

                // Create new window for printing
                const printWindow = window.open('', '_blank', 'width=1200,height=800');

                const printHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Quotes List - ${new Date().toLocaleDateString()}</title>
                <style>
                    body {
                        font-family: 'DejaVu Sans', sans-serif;
                        font-size: 10px;
                        margin: 0;
                        padding: 20px;
                        color: #333;
                    }
                    
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #007bff;
                        padding-bottom: 15px;
                    }
                    
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                        color: #007bff;
                    }
                    
                    .header h2 {
                        margin: 5px 0;
                        font-size: 18px;
                        color: #333;
                    }
                    
                    .header p {
                        margin: 5px 0 0 0;
                        color: #666;
                    }
                    
                    .summary {
                        background-color: #f8f9fa;
                        padding: 10px;
                        border-radius: 5px;
                        margin-bottom: 20px;
                        border-left: 4px solid #007bff;
                    }
                    
                    .summary strong {
                        color: #007bff;
                    }
                    
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                        font-size: 9px;
                    }
                    
                    th {
                        background-color: #007bff;
                        color: white;
                        padding: 8px 4px;
                        text-align: left;
                        font-weight: bold;
                        border: 1px solid #0056b3;
                    }
                    
                    td {
                        padding: 6px 4px;
                        border: 1px solid #ddd;
                        vertical-align: top;
                    }
                    
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    
                    .badge {
                        padding: 2px 6px;
                        border-radius: 4px;
                        font-size: 7px;
                        font-weight: bold;
                        text-transform: uppercase;
                    }
                    
                    .badge-draft { background-color: #ffc107; color: #000; }
                    .badge-sent { background-color: #17a2b8; color: #fff; }
                    .badge-accepted { background-color: #28a745; color: #fff; }
                    .badge-declined { background-color: #dc3545; color: #fff; }
                    .badge-expired { background-color: #6c757d; color: #fff; }
                    
                    .text-end {
                        text-align: right;
                    }
                    
                    .text-center {
                        text-align: center;
                    }
                    
                    .footer {
                        margin-top: 30px;
                        text-align: center;
                        font-size: 8px;
                        color: #666;
                        border-top: 1px solid #ddd;
                        padding-top: 10px;
                    }
                    
                    @media print {
                        body { margin: 0; }
                    }
                    
                    @page {
                        margin: 1cm;
                        size: A4 landscape;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MMP Auto-Meister</h1>
                    <h2>Quotations Report</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                
                <div class="summary">
                    <strong>Total Quotes:</strong> ${totalQuotes} | 
                    <strong>Draft:</strong> ${draftQuotes} | 
                    <strong>Sent:</strong> ${sentQuotes} | 
                    <strong>Accepted:</strong> ${acceptedQuotes}
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 12%;">Quote Number</th>
                            <th style="width: 20%;">Customer</th>
                            <th style="width: 15%;">Vehicle</th>
                            <th style="width: 10%;">Items</th>
                            <th style="width: 10%;">Grand Total</th>
                            <th style="width: 8%;">Status</th>
                            <th style="width: 10%;">Valid Until</th>
                            <th style="width: 10%;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotes as $index => $quote)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $quote->quote_number }}</strong></td>
                                <td>{{ $quote->customer->name ?? 'Cash Sale' }}</td>
                                <td>
                                    @if($quote->vehicle_make)
                                        {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}
                                        @if($quote->vehicle_reg)
                                            <br><small>({{ $quote->vehicle_reg }})</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $quote->items->count() ?? 0 }}</td>
                                <td class="text-end">R {{ number_format($quote->grand_total ?? 0, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $quote->status }}">{{ ucfirst($quote->status) }}</span>
                                </td>
                                <td class="text-center">{{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') : '-' }}</td>
                                <td class="text-center">{{ $quote->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>This report was generated by MMP Auto-Meister POS System</p>
                    <p>© ${new Date().getFullYear()} MMP Auto-Meister. All rights reserved.</p>
                </div>
            </body>
            </html>
        `;

                printWindow.document.write(printHTML);
                printWindow.document.close();

                // Wait for content to load then print
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.focus();
                        printWindow.print();
                        printWindow.close();
                    }, 1000);
                };

            } catch (error) {
                console.error('Print error:', error);
                alert('Print failed: ' + error.message);
            }
        }

        // AJAX Filter Functionality
        $(document).ready(function() {
            let searchTimeout;

            // Search input with debounce
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    filterQuotes();
                }, 500);
            });

            // Select filters with immediate response
            $('#statusFilter, #customerFilter').on('change', function() {
                filterQuotes();
            });

            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#filterForm')[0].reset();
                window.location.href = '{{ route('quotes.index') }}';
            });

            // Filter function
            function filterQuotes() {
                const formData = $('#filterForm').serialize();

                $.ajax({
                    url: '{{ route('quotes.index') }}',
                    type: 'GET',
                    data: formData,
                    beforeSend: function() {
                        // Show loading overlay
                        $('.table-responsive').append(
                            '<div class="position-absolute top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                    success: function(response) {
                        // Parse the response and update the table
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(response, 'text/html');
                        const newTable = doc.querySelector('.table');
                        const newPagination = doc.querySelector('.pagination');

                        if (newTable) {
                            $('.table').replaceWith(newTable);
                        }

                        if (newPagination) {
                            $('.pagination').replaceWith(newPagination);
                        }

                        // Update URL without page reload
                        const url = new URL(window.location);
                        url.search = new URLSearchParams(formData).toString();
                        window.history.pushState({}, '', url);

                        // Re-initialize click handlers for new content
                        initializeRowClickHandlers();
                    },
                    error: function(xhr) {
                        console.error('Filter error:', xhr);
                        toastr.error('Failed to filter quotes. Please try again.');
                    },
                    complete: function() {
                        $('.spinner-border').remove();
                    }
                });
            }

            // Initialize row click handlers
            function initializeRowClickHandlers() {
                $('.clickable-row').off('click').on('click', function(e) {
                    if (!$(e.target).closest('button, form, a').length) {
                        const quoteId = $(this).data('quote-id');
                        if (quoteId) {
                            openViewModal(quoteId);
                        }
                    }
                });
            }

            // Initialize on page load
            initializeRowClickHandlers();
        });
    </script>
@endpush

@section('content')



    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 me-3">Quotations</h4>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <!-- New Quote Button -->
                <button type="button" class="btn btn-primary-light btn-wave me-2 waves-effect waves-light" id="openCreateQuoteModal"
                    title="Create New Quote">
                    <i class="ri-file-add-line me-1"></i>New Quote
            </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('quotes.index') }}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" id="searchInput" class="form-control"
                                placeholder="Search by quote number, customer..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted
                                </option>
                                <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Declined
                                </option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                                </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <div class="d-grid gap-1">
                                <button type="button" class="btn btn-outline-info" id="clearFilters">
                                    Reset </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quotes Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="card-title">
                        Quotations<span
                            class="badge bg-light text-default rounded ms-1 fs-12 align-middle">{{ $quotes->total() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Print & Export Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="printQuotes()">
                                        <i class="ri-printer-line me-2 text-secondary"></i>Print
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('quotes.export', ['format' => 'pdf']) }}">
                                        <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('quotes.export', ['format' => 'csv']) }}">
                                        <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('quotes.export', ['format' => 'excel']) }}">
                                        <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="table-responsive position-relative" id="quotesTable">
                    <table class="table table-striped align-middle table-hover">
                        <thead class="table-light">
                <tr>
                    <th>#</th>
                                <th>Quote Details</th>
                    <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Items</th>
                                <th>Grand Total</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                                <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>

                            @forelse ($quotes as $quote)
                                <tr class="clickable-row" data-quote-id="{{ $quote->id }}" style="cursor: pointer;">
                                    <td>{{ $loop->iteration + ($quotes->currentPage() - 1) * $quotes->perPage() }}</td>

                                    {{-- Quote Details --}}
                                    <td>
                                        <div class="d-flex">
                                            <span class="avatar avatar-md avatar-square bg-primary-transparent p-2">
                                                <i class="ri-file-text-line fs-18"></i>
                                            </span>
                                            <div class="ms-2">
                                                <p class="fw-semibold mb-0 d-flex align-items-center">
                                                    {{ $quote->quote_number }}
                                                </p>
                                                <p class="fs-12 text-muted mb-0">Created:
                                                    {{ $quote->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Customer --}}
                                    <td>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $quote->customer->name ?? 'Cash Sale' }}</p>
                                            @if ($quote->customer)
                                                <p class="fs-12 text-muted mb-0">{{ $quote->customer->email }}</p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Vehicle --}}
                                    <td>
                                        @if ($quote->vehicle_make)
                                            <p class="mb-0">{{ $quote->vehicle_make }} {{ $quote->vehicle_model }}</p>
                                            @if ($quote->vehicle_reg)
                                                <p class="fs-12 text-muted mb-0">Reg: {{ $quote->vehicle_reg }}</p>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Items Count --}}
                                    <td>
                                        <span class="badge bg-info-transparent rounded-pill">{{ $quote->items->count() }}
                                            items</span>
                                    </td>

                                    {{-- Grand Total --}}
                                    <td>
                                        <span class="fw-bold text-success">R
                                            {{ number_format($quote->grand_total ?? 0, 2) }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @php
                                            // Auto-check if quote is expired
                                            $validDate = $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until) : null;
                                            $isExpired = $validDate && $validDate->isPast();
                                            $displayStatus = ($isExpired && in_array($quote->status, ['draft', 'sent'])) ? 'expired' : $quote->status;
                                        @endphp
                                        
                                        @if ($displayStatus === 'draft')
                                            <span class="badge rounded-pill bg-warning-transparent">Draft</span>
                                        @elseif($displayStatus === 'sent')
                                            <span class="badge rounded-pill bg-info-transparent">Sent</span>
                                        @elseif($displayStatus === 'accepted')
                                            <span class="badge rounded-pill bg-success-transparent">Accepted</span>
                                        @elseif($displayStatus === 'declined')
                                            <span class="badge rounded-pill bg-danger-transparent">Declined</span>
                                        @elseif($displayStatus === 'expired')
                                            <span class="badge rounded-pill bg-secondary-transparent">Expired</span>
                                        @else
                                            <span class="badge rounded-pill bg-light">{{ ucfirst($displayStatus) }}</span>
                                        @endif
                                    </td>

                                    {{-- Valid Until --}}
                                    <td>
                                        @if ($quote->valid_until)
                                            @php
                                                $validDate = \Carbon\Carbon::parse($quote->valid_until);
                                                $isExpired = $validDate->isPast();
                                            @endphp
                                            <span class="{{ $isExpired ? 'text-danger' : 'text-muted' }}">
                                                {{ $validDate->format('d M Y') }}
                                            </span>
                                            @if ($isExpired)
                                                <br><small class="badge bg-danger-transparent">Expired</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <!-- View -->
                                            <button class="btn btn-sm btn-info-light btn-icon openViewQuoteModal"
                                                data-id="{{ $quote->id }}" title="View Details">
                                    <i class="ri-eye-line"></i>
                                </button>

                                            <!-- Edit (Only if NOT accepted/declined/converted) -->
                                            @if(!in_array($quote->status, ['accepted', 'declined']) && !$quote->converted_invoice_id)
                                <button class="btn btn-sm btn-success-light btn-icon openEditQuoteModal"
                                    data-id="{{ $quote->id }}" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                            @endif

                                            <!-- Print -->
                                            <button type="button" onclick="printQuote({{ $quote->id }})"
                                    class="btn btn-sm btn-primary-light btn-icon" title="Print">
                                                <i class="ri-printer-line"></i>
                                    </button>

                                            <!-- Convert to Invoice (Only if NOT already converted) -->
                                            @if(!$quote->converted_invoice_id && !in_array($quote->status, ['declined', 'cancelled']))
                                <button type="button" class="btn btn-sm btn-warning-light btn-icon convert-to-invoice-btn"
                                    title="Convert to Invoice" data-quote-id="{{ $quote->id }}" data-quote-total="{{ $quote->grand_total ?? $quote->items->sum('total') }}">
                                    <i class="ri-arrow-right-circle-line"></i>
                                </button>
                                            @else
                                                <!-- Show "View Invoice" if already converted -->
                                                @if($quote->converted_invoice_id)
                                                    <a href="{{ route('invoices.index') }}" 
                                                       class="btn btn-sm btn-success-light btn-icon"
                                                       title="View Invoice">
                                                        <i class="ri-file-check-line"></i>
                                                    </a>
                                                @endif
                                            @endif

                                            <!-- Duplicate -->
                                <form action="{{ route('quotes.duplicate', $quote->id) }}" method="POST"
                                                class="d-inline duplicate-quote-form">
                                    @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary-light btn-icon"
                                        title="Duplicate Quote">
                                                    <i class="ri-file-copy-line"></i>
                                    </button>
                                </form>

                                            <!-- Delete (Only if NOT accepted/converted) -->
                                            @if(!in_array($quote->status, ['accepted']) && !$quote->converted_invoice_id)
                                                <button class="btn btn-sm btn-danger-light btn-icon" data-bs-toggle="modal"
                                                    data-bs-target="#deleteQuote{{ $quote->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                            </div>
                        </td>
                    </tr>

                                {{-- Delete Modal --}}
                                <div class="modal fade" id="deleteQuote{{ $quote->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('quotes.destroy', $quote->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete quote
                                                    <strong>{{ $quote->quote_number }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="ri-file-list-line ri-3x mb-3 d-block"></i>
                                        <p class="mb-0">No quotes found</p>
                                    </td>
                                </tr>
                            @endforelse
            </tbody>
        </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $quotes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

        <!-- Quote Modals -->
        <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="quoteModalContent"></div>
            </div>
        </div>

@endsection

    @push('scripts')
        <script>
            $(function() {
                // Create Quote Modal
                $('#openCreateQuoteModal').on('click', function(e) {
                    e.preventDefault(); // Prevent any default action
                    e.stopPropagation(); // Stop event bubbling
                    
                    console.log('Opening create modal...');
                    
                    // IMPORTANT: Keep main quotes table visible
                    $('#quotesTable').css('display', 'block').show();
                    $('.table-responsive').not('#quoteModal .table-responsive').show();
                    
                    $.get("{{ route('quotes.create') }}", function(html) {
                        console.log('Modal HTML loaded');
                        $('#quoteModalContent').html(html);
                        
                        // Double-check: Ensure main table stays visible after modal loads
                        setTimeout(function() {
                            $('#quotesTable').css('display', 'block').show();
                            $('.table-responsive').not('#quoteModal .table-responsive').show();
                            $('body > .container-fluid .table-responsive').show();
                        }, 100);
                        
                        $('#quoteModal').modal('show');
                    }).fail(function(xhr) {
                        console.error('Failed to load modal:', xhr);
                        toastr.error('Failed to load create modal. Please try again.');
                    });
                });

                // Handle Create Quote Form Submission via AJAX
                $(document).on('submit', '#quoteCreateForm', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const formData = new FormData(this);
                    const submitBtn = form.find('button[type="submit"]');
                    
                    // Disable submit button
                    submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Creating...');
                    
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if(response.success) {
                                toastr.success(response.message || 'Quote created successfully!');
                                $('#quoteModal').modal('hide');
                                
                                // Reload the page to show the new quote
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Failed to create quote. Please try again.';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if(xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errors.join('<br>');
                            }
                            toastr.error(errorMsg);
                            console.error('Create error:', xhr);
                        },
                        complete: function() {
                            // Re-enable submit button
                            submitBtn.prop('disabled', false).html('<i class="ri-save-line"></i> Create Quote');
                        }
                    });
                });

                // View Quote Modal
            $(document).on('click', '.openViewQuoteModal', function(e) {
                e.stopPropagation();
                    var id = $(this).data('id');
                    $.get("{{ route('quotes.view-modal', ':id') }}".replace(':id', id), function(html) {
                        $('#quoteModalContent').html(html);
                        $('#quoteModal').modal('show');
                    });
                });

                // Edit Quote Modal
            $(document).on('click', '.openEditQuoteModal', function(e) {
                e.stopPropagation();
                    var id = $(this).data('id');
                    $.get("{{ route('quotes.edit-modal', ':id') }}".replace(':id', id), function(html) {
                        $('#quoteModalContent').html(html);
                        $('#quoteModal').modal('show');
                    });
                });

            // Handle edit form submission
            $(document).on('submit', '#quoteEditForm', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const url = form.attr('action');
                const formData = form.serialize();
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message || 'Quote updated successfully!');
                            $('#quoteModal').modal('hide');
                            location.reload(); // Reload to show updated data
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to update quote. Please try again.');
                        console.error('Edit error:', xhr);
                    }
                });
            });

            // Handle duplicate quote
            let currentDuplicateForm = null;
            
            $(document).on('click', '.duplicate-quote-form button[type="submit"]', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                currentDuplicateForm = $(this).closest('form');
                
                // Show Bootstrap confirmation modal
                $('#duplicateConfirmModal').modal('show');
            });

            // Handle confirmation from modal
            $('#confirmDuplicate').on('click', function() {
                if (currentDuplicateForm) {
                    const form = currentDuplicateForm;
                    const url = form.attr('action');
                    
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: form.serialize(),
                        beforeSend: function() {
                            toastr.info('Duplicating quote...');
                            $('#duplicateConfirmModal').modal('hide');
                        },
                        success: function(response) {
                            if(response.success) {
                                toastr.success(response.message || 'Quote duplicated successfully!');
                                setTimeout(() => location.reload(), 1000);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to duplicate quote. Please try again.');
                            console.error('Duplicate error:', xhr);
                        }
                    });
                    
                    currentDuplicateForm = null;
                }
            });

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Ensure table stays visible when modal closes
            $('#quoteModal').on('hidden.bs.modal', function() {
                console.log('Modal closed, ensuring table visibility...');
                $('#quotesTable').css('display', 'block').show();
                $('.table-responsive').not('#quoteModal .table-responsive').show();
                $('tbody').show();
            });

            // Ensure table stays visible when modal opens
            $('#quoteModal').on('shown.bs.modal', function() {
                console.log('Modal opened, ensuring table visibility...');
                $('#quotesTable').css('display', 'block').show();
                $('.table-responsive').not('#quoteModal .table-responsive').show();
                });
            });

        // Print Quote Function - Opens print dialog directly (GLOBAL FUNCTION)
        window.printQuote = function(quoteId) {
            // Create a hidden iframe
            const printUrl = "{{ url('quotes') }}/" + quoteId + "/print";
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = printUrl;
            document.body.appendChild(iframe);
            
            // Wait for iframe to load, then trigger print
            iframe.onload = function() {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    
                    // Remove iframe after printing (with delay to ensure print dialog appears)
                    setTimeout(function() {
                        document.body.removeChild(iframe);
                    }, 1000);
                } catch (e) {
                    console.error('Print error:', e);
                    // Fallback: open in new window
                    window.open(printUrl, '_blank');
                    document.body.removeChild(iframe);
                }
            };
        };

        // Handle Convert to Invoice Button Click
        $(document).on('click', '.convert-to-invoice-btn', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const quoteId = button.data('quote-id');
            const quoteTotal = parseFloat(button.data('quote-total'));
            
            // Show payment options modal
            showPaymentOptionsModal(quoteId, quoteTotal);
        });

        // Show Payment Options Modal
        function showPaymentOptionsModal(quoteId, quoteTotal) {
            // First get customer info to determine available payment methods
            $.get("{{ route('quotes.customer-info', ':id') }}".replace(':id', quoteId), function(customerData) {
                
                // 💳 CREDIT CUSTOMER - Direct conversion (no payment modal)
                if (customerData && customerData.customer_type === 'credit') {
                    const creditLimit = parseFloat(customerData.credit_limit || 0);
                    const balance = parseFloat(customerData.balance || 0);
                    const availableCredit = creditLimit - Math.abs(balance);
                    
                    // Check credit limit
                    if (quoteTotal > availableCredit) {
                        toastr.error(`Credit limit exceeded!<br>Available Credit: R ${availableCredit.toFixed(2)}<br>Required: R ${quoteTotal.toFixed(2)}`, 'Credit Limit Error', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true
                        });
                        return;
                    }
                    
                    // Show confirmation modal for credit customer (NO payment fields)
                    const confirmModal = `
                        <div class="modal fade" id="creditCustomerConfirmModal" tabindex="-1" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info-transparent">
                                        <h5 class="modal-title">
                                            <i class="ri-user-line me-2"></i>Credit Customer - Convert to Invoice
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info border-info">
                                            <div class="d-flex align-items-start">
                                                <i class="ri-information-line fs-4 me-3 text-info"></i>
                                                <div>
                                                    <h6 class="mb-2 fw-bold">This customer is on credit.</h6>
                                                    <p class="mb-0">Payment will be recorded later via <strong>Receive Payment</strong> module.</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-6 text-muted">Customer:</div>
                                                    <div class="col-6 text-end fw-bold">${customerData.customer_name}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6 text-muted">Invoice Total:</div>
                                                    <div class="col-6 text-end fw-bold text-primary">R ${quoteTotal.toFixed(2)}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6 text-muted">Credit Limit:</div>
                                                    <div class="col-6 text-end fw-bold">R ${creditLimit.toFixed(2)}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 text-muted">Available Credit:</div>
                                                    <div class="col-6 text-end fw-bold text-success">R ${availableCredit.toFixed(2)}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-warning mb-0">
                                            <small>
                                                <i class="ri-alert-line me-1"></i>
                                                Invoice will be created as <strong>Unpaid</strong>. Outstanding balance will be added to customer ledger.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            <i class="ri-close-line me-1"></i>Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary" id="confirmCreditConvert">
                                            <i class="ri-check-line me-1"></i>Create Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Remove existing modal
                    $('#creditCustomerConfirmModal').remove();
                    $('body').append(confirmModal);
                    $('#creditCustomerConfirmModal').modal('show');
                    
                    // Handle confirmation
                    $('#confirmCreditConvert').on('click', function() {
                        // Close the credit customer modal first
                        $('#creditCustomerConfirmModal').modal('hide');
                        
                        // Small delay to ensure modal is closed before conversion
                        setTimeout(function() {
                            // Convert with on_account payment, amount_paid = 0
                            const formData = `_token={{ csrf_token() }}&payment_method=on_account&amount_paid=0&payment_reference=Credit Sale`;
                            convertQuoteToInvoiceWithPayment(quoteId, formData);
                        }, 300);
                    });
                    
                    return; // Exit - no payment modal for credit customers
                }
                
                // 💵 CASH CUSTOMER - Show payment modal (must pay now)
                let paymentOptions = '';
                let customerTypeInfo = '';
                
                if (customerData && customerData.customer_type === 'cash') {
                    paymentOptions = `
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="eft">EFT</option>
                    `;
                    customerTypeInfo = `
                        <div class="alert alert-warning mb-3">
                            <h6 class="mb-2"><i class="ri-alert-line me-2"></i>Cash Customer</h6>
                            <p class="mb-0">Invoice Total: R ${quoteTotal.toFixed(2)}</p>
                            <small class="text-muted">Cash customers must pay immediately. Credit sales not allowed.</small>
                        </div>
                    `;
                } else {
                    // Default options for unknown customer type
                    paymentOptions = `
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="eft">EFT</option>
                    `;
                    customerTypeInfo = `
                        <div class="alert alert-info mb-3">
                            <h6 class="mb-2"><i class="ri-information-line me-2"></i>Invoice Total: R ${quoteTotal.toFixed(2)}</h6>
                            <p class="mb-0">Please select payment method and amount paid:</p>
                        </div>
                    `;
                }

                const modal = `
                    <div class="modal fade" id="paymentOptionsModal" tabindex="-1" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary-transparent">
                                    <h5 class="modal-title">
                                        <i class="ri-money-dollar-circle-line me-2"></i>Convert to Invoice - Payment Options
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${customerTypeInfo}
                                    
                                    <form id="convertToInvoiceForm">
                                        @csrf
                                        <input type="hidden" name="quote_id" value="${quoteId}">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method</label>
                                            <select class="form-select" name="payment_method" id="paymentMethod" required>
                                                ${paymentOptions}
                                            </select>
                                        </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Amount Paid <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">R</span>
                                            <input type="number" class="form-control" name="amount_paid" id="amountPaid" 
                                                   value="${quoteTotal}" min="${quoteTotal}" max="${quoteTotal}" step="0.01" 
                                                   placeholder="${quoteTotal.toFixed(2)}" required>
                                        </div>
                                        <small class="form-text ${customerData && customerData.customer_type === 'cash' ? 'text-danger fw-bold' : 'text-muted'}">
                                            ${customerData && customerData.customer_type === 'cash' ? 'Cash customers must pay in full.' : 'Enter amount paid now.'}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Payment Reference (Optional)</label>
                                        <input type="text" class="form-control" name="payment_reference" 
                                               placeholder="Transaction reference, receipt number, etc.">
                                    </div>
                                    
                                    <div class="alert alert-light mb-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <strong>Invoice Total:</strong><br>
                                                <span class="h5 text-primary">R ${quoteTotal.toFixed(2)}</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>Balance Due:</strong><br>
                                                <span class="h5 text-danger" id="balanceDue">R ${quoteTotal.toFixed(2)}</span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1"></i>Cancel
                                </button>
                                <button type="button" class="btn btn-primary" id="confirmConvert">
                                    <i class="ri-arrow-right-circle-line me-1"></i>Convert to Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#paymentOptionsModal').remove();
            
            // Add modal to body
            $('body').append(modal);
            
            // Show modal
            $('#paymentOptionsModal').modal('show');
            
            // Handle amount paid change
            $('#amountPaid').on('input', function() {
                const amountPaid = parseFloat($(this).val()) || 0;
                const balanceDue = quoteTotal - amountPaid;
                $('#balanceDue').text('R ' + balanceDue.toFixed(2));
                
                if (balanceDue <= 0) {
                    $('#balanceDue').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#balanceDue').removeClass('text-success').addClass('text-danger');
                }
            });
            
            // Handle form submission
            $('#confirmConvert').on('click', function() {
                const form = $('#convertToInvoiceForm');
                const paymentMethod = $('#paymentMethod').val();
                const amountPaid = parseFloat($('#amountPaid').val()) || 0;
                
                if (!paymentMethod) {
                    toastr.error('Please select a payment method');
                    return;
                }
                
                // Cash customer validation - MUST pay in full
                if (customerData && customerData.customer_type === 'cash') {
                    if (amountPaid < quoteTotal) {
                        toastr.error('Cash customers must pay in full. Amount paid must equal R ' + quoteTotal.toFixed(2), 'Payment Required', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true
                        });
                        return;
                    }
                }
                
                if (amountPaid > quoteTotal) {
                    toastr.error('Amount paid cannot exceed invoice total');
                    return;
                }
                
                // Close payment modal first
                $('#paymentOptionsModal').modal('hide');
                
                // Small delay to ensure modal is closed before conversion
                setTimeout(function() {
                    // Convert quote to invoice with payment info
                    convertQuoteToInvoiceWithPayment(quoteId, form.serialize());
                }, 300);
            });
            
            }); // Close $.get() callback
        } // Close showPaymentOptionsModal function

        // Convert Quote to Invoice with Payment Info
        function convertQuoteToInvoiceWithPayment(quoteId, formData) {
            const button = $('#confirmConvert');
            const originalHtml = button.html();
            
            // Remove any existing modals to prevent overlap
            $('#paymentOptionsModal, #creditCustomerConfirmModal, #stockWarningModal').remove();
            
            // Disable button and show loading
            button.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i>Converting...');
            
            $.ajax({
                url: "{{ route('quotes.convert-to-invoice', ':id') }}".replace(':id', quoteId),
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Check if there's a stock warning
                        if (response.message.includes('insufficient stock')) {
                            // Show warning modal with stock details
                            showStockWarningModal(response);
                        } else {
                            // Show simple success message
                            toastr.success('Quote converted to invoice successfully!');
                            
                            // Close payment modal
                            $('#paymentOptionsModal').modal('hide');
                            
                            // Redirect to invoices page
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to convert quote to invoice.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                        
                        // Check if it's a stock error (setting OFF)
                        if (xhr.responseJSON.stock_issues) {
                            $('#paymentOptionsModal').modal('hide');
                            showStockErrorModal(xhr.responseJSON);
                            return;
                        }
                    }
                    
                    toastr.error(errorMsg);
                },
                complete: function() {
                    // Re-enable button
                    button.prop('disabled', false).html(originalHtml);
                }
            });
        }

        // Show Stock Warning Modal (Setting ON - negative stock allowed)
        function showStockWarningModal(response) {
            const modal = `
                <div class="modal fade" id="stockWarningModal" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning-transparent">
                                <h5 class="modal-title">
                                    <i class="ri-error-warning-line me-2"></i>Quote Converted with Stock Warning
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-success mb-3">
                                    <i class="ri-check-circle-line me-2"></i>
                                    <strong>Invoice Created: ${response.invoice_number}</strong>
                                </div>
                                
                                <div class="alert alert-warning mb-0">
                                    <h6 class="mb-2"><i class="ri-alert-line me-2"></i>Stock Warning:</h6>
                                    <p class="mb-0">${response.message.replace('Quote converted to invoice successfully! ', '')}</p>
                                    <hr>
                                    <small class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        The invoice has been created with negative stock. Stock will be reconciled when new inventory is received.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <a href="${response.redirect}" class="btn btn-primary">
                                    <i class="ri-file-list-line me-1"></i>View Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#stockWarningModal').remove();
            
            // Add modal to body
            $('body').append(modal);
            
            // Show modal
            $('#stockWarningModal').modal('show');
            
            // Reload table after modal closes
            $('#stockWarningModal').on('hidden.bs.modal', function() {
                location.reload();
            });
        }

        // Show Stock Error Modal (Setting OFF - negative stock blocked)
        function showStockErrorModal(response) {
            let stockDetails = '';
            if (response.stock_issues && response.stock_issues.length > 0) {
                stockDetails = '<div class="table-responsive mt-3"><table class="table table-bordered table-sm">';
                stockDetails += '<thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Required</th><th>Available</th><th>Short</th></tr></thead><tbody>';
                
                response.stock_issues.forEach(function(issue) {
                    stockDetails += `
                        <tr>
                            <td><strong>${issue.product}</strong></td>
                            <td>${issue.sku}</td>
                            <td class="text-center">${issue.required}</td>
                            <td class="text-center">${issue.available}</td>
                            <td class="text-center text-danger"><strong>${issue.short}</strong></td>
                        </tr>
                    `;
                });
                
                stockDetails += '</tbody></table></div>';
            }
            
            const modal = `
                <div class="modal fade" id="stockErrorModal" tabindex="-1" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="ri-close-circle-line me-2"></i>Insufficient Stock
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger mb-3">
                                    <h6 class="mb-2"><i class="ri-error-warning-line me-2"></i>Cannot Convert Quote to Invoice</h6>
                                    <p class="mb-0">The following items have insufficient stock:</p>
                                </div>
                                
                                ${stockDetails}
                                
                                <div class="alert alert-info mb-0 mt-3">
                                    <small>
                                        <i class="ri-information-line me-1"></i>
                                        <strong>Note:</strong> Out-of-stock sales are currently disabled in settings. 
                                        Please add stock via GRN or enable "Allow Out-of-Stock Sale" in POS settings.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1"></i>Close
                                </button>
                                <a href="{{ route('goods-receipts.index') }}" class="btn btn-primary">
                                    <i class="ri-inbox-line me-1"></i>Add Stock (GRN)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#stockErrorModal').remove();
            
            // Add modal to body
            $('body').append(modal);
            
            // Show modal
            $('#stockErrorModal').modal('show');
        }
        </script>

        <!-- Duplicate Quote Confirmation Modal -->
        <div class="modal fade" id="duplicateConfirmModal" tabindex="-1" aria-labelledby="duplicateConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning-transparent">
                        <h5 class="modal-title" id="duplicateConfirmModalLabel">
                            <i class="ri-file-copy-line me-2"></i>Duplicate Quote
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <i class="ri-question-line ri-3x text-warning"></i>
                        </div>
                        <h6 class="mb-3">Are you sure you want to duplicate this quote?</h6>
                        <p class="text-muted mb-0">This will create a new quote with the same items and details.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-warning" id="confirmDuplicate">
                            <i class="ri-file-copy-line me-1"></i>Yes, Duplicate
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
