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
                                    @if ($quote->vehicle_make)
                                        {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}
                                        @if ($quote->vehicle_reg)
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

        // ═══════════════════════════════════════════════════════════════════════
        // POST-SALE MODAL FUNCTIONS (After Quote → Invoice Conversion)
        // ═══════════════════════════════════════════════════════════════════════

        let currentInvoiceId = null;

        function showPostSaleModal(invoiceId, invoiceNumber, grandTotal) {
            currentInvoiceId = invoiceId;
            $('#postSaleInvoiceNumber').text(invoiceNumber);
            $('#postSaleTotal').text(parseFloat(grandTotal).toFixed(2));
            $('#postSaleModal').modal('show');
            toastr.success('Invoice created successfully!');
        }

        function closePostSaleModal() {
            $('#postSaleModal').modal('hide');
            // Stay on quotations page - just close modal
            setTimeout(function() {
                location.reload(); // Reload to show updated quote status
            }, 300);
        }
        
        function viewInvoice() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }
            
            // Get invoice number from modal
            const invoiceNumber = $('#postSaleInvoiceNumber').text();
            
            // Redirect to invoices page with search filter
            const url = '{{ route('invoices.index') }}?search=' + encodeURIComponent(invoiceNumber);
            window.location.href = url;
        }

        function downloadInvoicePDF() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }
            const url = '{{ route('invoices.pdf', ':id') }}'.replace(':id', currentInvoiceId);
            window.open(url, '_blank');
            toastr.success('Downloading invoice PDF...');
        }

        function printInvoiceInline() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }

            const url = '{{ route('invoices.print', ':id') }}'.replace(':id', currentInvoiceId);

            // Create hidden iframe for printing
            let printFrame = document.getElementById('printFrame');
            if (!printFrame) {
                printFrame = document.createElement('iframe');
                printFrame.id = 'printFrame';
                printFrame.style.display = 'none';
                printFrame.style.position = 'fixed';
                printFrame.style.width = '0';
                printFrame.style.height = '0';
                printFrame.style.border = 'none';
                document.body.appendChild(printFrame);
            }

            printFrame.src = url;
            printFrame.onload = function() {
                try {
                    setTimeout(function() {
                        printFrame.contentWindow.focus();
                        printFrame.contentWindow.print();
                    }, 500);
                } catch (e) {
                    toastr.error('Print failed. Please try Download PDF instead.');
                    console.error('Print error:', e);
                }
            };

            toastr.info('Preparing print...');
        }

        function sendWhatsAppFromQuote() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }

            toastr.info('Preparing WhatsApp...');

            const url = '{{ route('invoices.whatsapp', ':id') }}'.replace(':id', currentInvoiceId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.whatsapp_url) {
                        const shareType = data.share_type || 'web';
                        const whatsappTab = window.open(data.whatsapp_url, '_blank');

                        if (whatsappTab) {
                            if (shareType === 'desktop') {
                                const message = data.message || '';
                                if (message) {
                                    copyToClipboardQuote(message);
                                    toastr.success(
                                        'WhatsApp Desktop app opened! Message copied to clipboard. Just paste (Ctrl+V) in the app.', {
                                            timeOut: 5000
                                        });
                                }
                            } else {
                                toastr.success('WhatsApp Web opened! Message is pre-filled and ready to send.');
                            }
                        } else {
                            toastr.warning('Please allow popups to open WhatsApp.');
                        }
                    } else {
                        toastr.error(data.message || 'Failed to generate WhatsApp link');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error sending to WhatsApp');
                });
        }

        function sendEmailFromQuote() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }

            toastr.info('Sending email...');

            const url = '{{ route('invoices.email', ':id') }}'.replace(':id', currentInvoiceId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Email sent successfully!');
                    } else {
                        toastr.error(data.message || 'Failed to send email');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error sending email');
                });
        }

        function downloadPickingListFromQuote() {
            if (!currentInvoiceId) {
                toastr.error('No invoice found');
                return;
            }
            const url = '{{ route('invoices.picking-list', ':id') }}'.replace(':id', currentInvoiceId);
            window.open(url, '_blank');
            toastr.success('Downloading picking list...');
        }

        function copyToClipboardQuote(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    console.log('Message copied to clipboard');
                }).catch(err => {
                    copyToClipboardFallbackQuote(text);
                });
            } else {
                copyToClipboardFallbackQuote(text);
            }
        }

        function copyToClipboardFallbackQuote(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Copy failed:', err);
            }
            document.body.removeChild(textArea);
        }

        // Send Quote via WhatsApp
        function sendQuoteWhatsApp(quoteId) {
            toastr.info('Preparing WhatsApp message...');

            const url = "{{ route('quotes.send-whatsapp', ':id') }}".replace(':id', quoteId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // If desktop app and message needs to be copied
                        if (data.copy_message) {
                            // Copy to clipboard
                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                navigator.clipboard.writeText(data.copy_message)
                                    .then(() => {
                                        toastr.success('Message copied! Opening WhatsApp...');
                                    })
                                    .catch(() => {
                                        copyToClipboardFallbackQuote(data.copy_message);
                                        toastr.success('Message copied! Opening WhatsApp...');
                                    });
                            } else {
                                copyToClipboardFallbackQuote(data.copy_message);
                                toastr.success('Message copied! Opening WhatsApp...');
                            }
                        } else {
                            toastr.success('Opening WhatsApp...');
                        }

                        // Open WhatsApp
                        window.open(data.whatsapp_url, '_blank');
                    } else {
                        toastr.error(data.message || 'Failed to send WhatsApp');
                    }
                })
                .catch(error => {
                    console.error('WhatsApp Error:', error);

                    // Show specific error message
                    if (error.message) {
                        toastr.error(error.message, 'WhatsApp Error', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error('Unable to send WhatsApp. Please check customer phone number.', 'Error', {
                            timeOut: 5000,
                            closeButton: true
                        });
                    }
                });
        }

        // Send Quote via Email
        function sendQuoteEmail(quoteId) {
            toastr.info('Sending email...');

            const url = "{{ route('quotes.send-email', ':id') }}".replace(':id', quoteId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Email sent successfully!', 'Success', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(data.message || 'Failed to send email');
                    }
                })
                .catch(error => {
                    console.error('Email Error:', error);

                    // Show specific error message
                    if (error.message) {
                        toastr.error(error.message, 'Email Error', {
                            timeOut: 5000,
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error('Unable to send email. Please check customer email address.', 'Error', {
                            timeOut: 5000,
                            closeButton: true
                        });
                    }
                });
        }
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
                <button type="button" class="btn btn-primary-light btn-wave me-2 waves-effect waves-light"
                    id="openCreateQuoteModal" title="Create New Quote">
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
                                                <span
                                                    class="badge badge-sm {{ $quote->customer->terms === 'credit' ? 'bg-success-transparent text-success' : 'bg-warning-transparent text-warning' }}">
                                                    {{ $quote->customer->terms === 'credit' ? 'Credit Customer' : 'Cash Customer' }}
                                                </span>
                                            @else
                                                <span class="badge badge-sm bg-info-transparent text-info">Walk-in
                                                    Customer</span>
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
                                            $validDate = $quote->valid_until
                                                ? \Carbon\Carbon::parse($quote->valid_until)
                                                : null;
                                            $isExpired = $validDate && $validDate->isPast();
                                            $displayStatus =
                                                $isExpired && in_array($quote->status, ['draft', 'sent'])
                                                    ? 'expired'
                                                    : $quote->status;
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
                                            @if (!in_array($quote->status, ['accepted', 'declined']) && !$quote->converted_invoice_id)
                                <button class="btn btn-sm btn-success-light btn-icon openEditQuoteModal"
                                    data-id="{{ $quote->id }}" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                            @endif


                                            <!-- Convert to Invoice (Only if NOT already converted) -->
                                            @if (!$quote->converted_invoice_id && !in_array($quote->status, ['declined', 'cancelled']))
                                                <button type="button"
                                                    class="btn btn-sm btn-warning-light btn-icon convert-to-invoice-btn"
                                                    title="Convert to Invoice" data-quote-id="{{ $quote->id }}"
                                                    data-quote-total="{{ $quote->grand_total ?? $quote->items->sum('total') }}">
                                    <i class="ri-arrow-right-circle-line"></i>
                                </button>
                                            @else
                                                <!-- Show "View Invoice" if already converted -->
                                                @if ($quote->converted_invoice_id)
                                                    <a href="{{ route('invoices.index') }}" 
                                                       class="btn btn-sm btn-success-light btn-icon"
                                                       title="View Invoice">
                                                        <i class="ri-file-check-line"></i>
                                                    </a>
                                                @endif
                                            @endif

                                            <!-- Duplicate -->
                                            {{-- <form action="{{ route('quotes.duplicate', $quote->id) }}" method="POST"
                                                class="d-inline duplicate-quote-form">
                                    @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary-light btn-icon"
                                        title="Duplicate Quote">
                                                    <i class="ri-file-copy-line"></i>
                                    </button>
                                            </form> --}}

                                            <!-- Delete (Only if NOT accepted/converted) -->
                                            @if (!in_array($quote->status, ['accepted']) && !$quote->converted_invoice_id)
                                                <button class="btn btn-sm btn-danger-light btn-icon"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteQuote{{ $quote->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif

                                            <!-- More Actions Dropdown -->
                                            <button type="button" class="btn btn-sm btn-primary-light btn-icon"
                                                data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <!-- Print Quote -->
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="printQuote({{ $quote->id }})">
                                                        <i class="ri-printer-line me-2"></i>Print Quote
                                                    </a>
                                                </li>
                                                <!-- Download PDF -->
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('quotes.pdf', $quote->id) }}"
                                                        target="_blank">
                                                        <i class="ri-file-pdf-line me-2"></i>Download PDF
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <!-- Send WhatsApp -->
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="sendQuoteWhatsApp({{ $quote->id }})">
                                                        <i class="ri-whatsapp-line me-2 text-success"></i>Send WhatsApp
                                                    </a>
                                                </li>
                                                <!-- Send Email -->
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="sendQuoteEmail({{ $quote->id }})">
                                                        <i class="ri-mail-line me-2 text-primary"></i>Send Email
                                                    </a>
                                                </li>
                                            </ul>
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
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
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
                <div id="paginationContainer">
                    @include('quotes.partials.pagination')
                </div>
            </div>
        </div>
    </div>

        <!-- Quote Modals -->
        <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl"style="max-width: 95%;">
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
                submitBtn.prop('disabled', true).html(
                    '<i class="ri-loader-4-line ri-spin me-1"></i>Creating...');
                    
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                        if (response.success) {
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
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errors.join('<br>');
                            }
                            toastr.error(errorMsg);
                            console.error('Create error:', xhr);
                        },
                        complete: function() {
                            // Re-enable submit button
                        submitBtn.prop('disabled', false).html(
                            '<i class="ri-save-line"></i> Create Quote');
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
                        if (response.success) {
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
                            if (response.success) {
                                toastr.success(response.message ||
                                    'Quote duplicated successfully!');
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
                
                // 💳 CREDIT CUSTOMER - Show payment modal (same as POS)
                if (customerData && customerData.customer_type === 'credit') {
                    const creditLimit = parseFloat(customerData.credit_limit || 0);
                    const balance = parseFloat(customerData.balance || 0);
                    const availableCredit = creditLimit - Math.abs(balance);
                    
                    // Simple credit customer alert (no limit warning yet)
                    const customerTypeAlert = `
                        <div class="alert alert-info mb-3">
                            <i class="ri-information-line me-2"></i>
                            <strong>Credit Customer</strong><br>
                            You can pay using credit (On Account) or Cash/Card/EFT.<br>
                            <strong>Available Credit: R ${availableCredit.toFixed(2)}</strong>
                        </div>
                    `;
                    
                    // Show payment modal with credit option
                    showQuotePaymentModal(quoteId, quoteTotal, customerTypeAlert, true, availableCredit);
                    return;
                }
                
                // 💵 CASH CUSTOMER - Show payment modal (must pay now)
                const customerTypeAlert = `
                        <div class="alert alert-warning mb-3">
                            <h6 class="mb-2"><i class="ri-alert-line me-2"></i>Cash Customer</h6>
                            <p class="mb-0">Invoice Total: R ${quoteTotal.toFixed(2)}</p>
                            <small class="text-muted">Cash customers must pay immediately. Credit sales not allowed.</small>
                        </div>
                    `;
                showQuotePaymentModal(quoteId, quoteTotal, customerTypeAlert, false, 0);
            });
        }

        // Show Quote Payment Modal (exactly like POS payment modal)
        function showQuotePaymentModal(quoteId, quoteTotal, customerTypeAlert, isCreditCustomer, availableCredit) {

                const modal = `
                <div class="modal fade" id="paymentOptionsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                    <i class="ri-money-dollar-circle-line me-2"></i>Payment
                                    </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                <div id="customerTypeAlert">${customerTypeAlert}</div>
                                        
                                        <div class="mb-3">
                                    <label class="form-label fw-bold">Payment Method</label>
                                    <select class="form-select" id="quotePaymentMethod">
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="eft">EFT</option>
                                        ${isCreditCustomer ? '<option value="credit" id="onAccountOption" selected>Credit</option>' : ''}
                                            </select>
                                        </div>
                                    
                                <div class="mb-3" id="amountPaidRow">
                                    <label class="form-label fw-bold">Amount Paid</label>
                                    <input type="number" class="form-control form-control-lg" id="quoteAmountPaid" 
                                           value="${quoteTotal.toFixed(2)}" step="0.01" min="0">
                                    </div>
                                    
                                <div class="mb-3" id="paymentReferenceRow">
                                    <label class="form-label">Reference</label>
                                    <input type="text" class="form-control" id="quotePaymentReference" placeholder="Optional">
                                    </div>
                                    
                                <div class="alert alert-info mb-0" id="changeRow" style="display: none;">
                                    <div class="d-flex justify-content-between">
                                        <span>Change:</span>
                                        <span class="fw-bold" id="changeAmount">R 0.00</span>
                                            </div>
                                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="confirmQuotePayment(${quoteId}, ${quoteTotal})">
                                    <i class="ri-check-line me-1"></i>Confirm Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#paymentOptionsModal').remove();
            $('body').append(modal);
            
            // Store available credit in modal data for validation
            $('#paymentOptionsModal').data('available-credit', availableCredit);

            $('#paymentOptionsModal').modal('show');
            
            // Setup payment method change handler (same as POS)
            $('#quotePaymentMethod').on('change', function() {
                updateQuotePaymentFields(quoteTotal, isCreditCustomer, availableCredit);
            });

            // Setup amount paid change handler (for credit customers)
            $('#quoteAmountPaid').on('input', function() {
                if (isCreditCustomer && $('#quotePaymentMethod').val() !== 'credit') {
                    calculateQuoteChange(quoteTotal);
                }
            });

            // Initial setup based on customer type
            if (!isCreditCustomer) {
                // Cash customer - amount is readonly and auto-set
                $('#quoteAmountPaid').val(quoteTotal.toFixed(2)).prop('readonly', true);
                $('#amountPaidRow').show();
                $('#changeRow').hide();
            } else {
                // Credit customer - default is "Credit" selected, so hide amount field
                $('#amountPaidRow').hide();
                $('#changeRow').hide();
            }
        }

        // Update payment fields based on selection (same as POS)
        function updateQuotePaymentFields(quoteTotal, isCreditCustomer, availableCredit) {
            const paymentMethod = $('#quotePaymentMethod').val();

            if (paymentMethod === 'credit') {
                // Credit payment - hide amount paid field
                $('#amountPaidRow').hide();
                $('#changeRow').hide();
            } else {
                // Cash/Card/EFT - show amount paid
                $('#amountPaidRow').show();
                $('#quoteAmountPaid').val(quoteTotal.toFixed(2)).prop('readonly', true);
                $('#changeRow').hide(); // Don't show change for quotes
            }
        }

        // Calculate change (same as POS)
        function calculateQuoteChange(quoteTotal) {
            const amountPaid = parseFloat($('#quoteAmountPaid').val()) || 0;
            const change = amountPaid - quoteTotal;

            if (change >= 0) {
                $('#changeAmount').text('R ' + change.toFixed(2));
            } else {
                $('#changeAmount').text('R 0.00');
            }
        }

        // Confirm payment and convert (same as POS confirmPayment)
        function confirmQuotePayment(quoteId, quoteTotal) {
            toastr.clear();

            const paymentMethod = $('#quotePaymentMethod').val();
            const paymentReference = $('#quotePaymentReference').val();
            let amountPaid = 0;

            // Validation for credit payment
            if (paymentMethod === 'credit') {
                // Get available credit from modal data
                const availableCredit = parseFloat($('#paymentOptionsModal').data('available-credit')) || 0;

                // Check credit limit
                if (quoteTotal > availableCredit) {
                    toastr.error(
                        `Insufficient credit limit. Available: R ${availableCredit.toFixed(2)}, Required: R ${quoteTotal.toFixed(2)}`
                    );
                    return;
                }
                
                // Credit payment - amount paid is 0
                amountPaid = 0;
            } else {
                // Cash/Card/EFT - amount paid is full total
                amountPaid = quoteTotal;
            }

            // Close modal
                $('#paymentOptionsModal').modal('hide');
                
            // Convert quote to invoice
            const formData =
                `_token={{ csrf_token() }}&payment_method=${paymentMethod}&amount_paid=${amountPaid}&payment_reference=${paymentReference}`;

                setTimeout(function() {
                convertQuoteToInvoiceWithPayment(quoteId, formData);
                }, 300);
        }

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
                        // Close payment modal
                        $('#paymentOptionsModal, #creditCustomerConfirmModal').modal('hide');

                        // Check if there's a stock warning
                        if (response.message.includes('insufficient stock')) {
                            // Show warning modal with stock details
                            showStockWarningModal(response);
                        } else {
                            // Show post-sale modal with share options
                            showPostSaleModal(response.invoice_id, response.invoice_number, response
                                .grand_total || quoteTotal);
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
                stockDetails +=
                    '<thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Required</th><th>Available</th><th>Short</th></tr></thead><tbody>';
                
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
    <div class="modal fade" id="duplicateConfirmModal" tabindex="-1" aria-labelledby="duplicateConfirmModalLabel"
        aria-hidden="true">
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

    <!-- Post-Sale Modal (After Quote → Invoice Conversion) -->
    <div class="modal fade" id="postSaleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header bg-success text-white">
                    <div class="w-100 text-center">
                        <h5 class="modal-title mb-0">
                            <i class="ri-checkbox-circle-line me-2"></i>Invoice Created Successfully
                        </h5>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body text-center py-4">
                    <!-- Invoice Info -->
                    <div class="card border-primary mb-3">
                        <div class="card-body py-3">
                            <div class="row">
                                <div class="col-6 border-end">
                                    <small class="text-muted d-block mb-1">Invoice Number</small>
                                    <h5 id="postSaleInvoiceNumber" class="text-primary mb-0 fw-bold"></h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Total Amount</small>
                                    <h5 class="text-success mb-0 fw-bold">R <span id="postSaleTotal">0.00</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="downloadInvoicePDF()">
                            <i class="ri-file-pdf-line me-2"></i>Download PDF
                        </button>
                        <button type="button" class="btn btn-danger" onclick="printInvoiceInline()">
                            <i class="ri-printer-line me-2"></i>Print Invoice
                        </button>
                        <button type="button" class="btn btn-success" onclick="sendWhatsAppFromQuote()">
                            <i class="ri-whatsapp-line me-2"></i>Send via WhatsApp
                        </button>
                        <button type="button" class="btn btn-info" onclick="sendEmailFromQuote()">
                            <i class="ri-mail-line me-2"></i>Send via Email
                        </button>
                        <button type="button" class="btn btn-warning" onclick="downloadPickingListFromQuote()">
                            <i class="ri-file-list-3-line me-2"></i>Download Picking List
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-center border-top">
                    <button type="button" class="btn btn-secondary px-4" onclick="closePostSaleModal()">
                        <i class="ri-close-line me-1"></i>Close & Continue
                    </button>
                    <button type="button" class="btn btn-primary px-4" onclick="viewInvoice()">
                        <i class="ri-eye-line me-1"></i>View Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
