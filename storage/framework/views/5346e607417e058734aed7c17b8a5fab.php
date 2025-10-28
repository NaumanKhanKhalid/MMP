<?php $__env->startPush('styles'); ?>
    <style>
        .clickable-row {
            transition: background-color 0.2s ease;
        }

        .clickable-row:hover {
            background-color: #f8f9fa !important;
        }

        /* Payment Status Badge Colors */
        .badge-unpaid {
            background-color: #f1416c !important;
            color: white !important;
        }

        .badge-partially_paid {
            background-color: #ffc700 !important;
            color: #000 !important;
        }

        .badge-partial {
            background-color: #ffc700 !important;
            color: #000 !important;
        }
    </style>

    <script>
        function openViewInvoiceModal(invoiceId) {
            // Check if the click came from a button or form
            if (event.target.closest('button') || event.target.closest('form')) {
                return; // Don't open modal if clicking on buttons/forms
            }

            // Open the view modal
            $.get("<?php echo e(route('invoices.view-modal', ':id')); ?>".replace(':id', invoiceId), function(html) {
                $('#invoiceModalContent').html(html);
                $('#invoiceModal').modal('show');
            });
        }

        function printInvoices() {
            try {
                // Get invoice data for summary
                const totalInvoices = <?php echo e($invoices->total()); ?>;
                const draftInvoices = <?php echo e($invoices->where('payment_status', 'draft')->count()); ?>;
                const paidInvoices = <?php echo e($invoices->where('payment_status', 'paid')->count()); ?>;
                const unpaidInvoices = <?php echo e($invoices->where('payment_status', 'unpaid')->count()); ?>;
                const partiallyPaidInvoices = <?php echo e($invoices->where('payment_status', 'partially_paid')->count()); ?>;
                const partialInvoices = <?php echo e($invoices->where('payment_status', 'partial')->count()); ?>;

                // Create new window for printing
                const printWindow = window.open('', '_blank', 'width=1200,height=800');

                const printHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Invoices List - ${new Date().toLocaleDateString()}</title>
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
                    .badge-posted { background-color: #17a2b8; color: #fff; }
                    .badge-paid { background-color: #28a745; color: #fff; }
                    .badge-partial { background-color: #fd7e14; color: #fff; }
                    .badge-cancelled { background-color: #dc3545; color: #fff; }
                    
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
                    <h2>Invoices Report</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                
                <div class="summary">
                    <strong>Total Invoices:</strong> ${totalInvoices} | 
                    <strong>Draft:</strong> ${draftInvoices} | 
                    <strong>Paid:</strong> ${paidInvoices} | 
                    <strong>Unpaid:</strong> ${unpaidInvoices} | 
                    <strong>Partially Paid:</strong> ${partiallyPaidInvoices} | 
                    <strong>Partial:</strong> ${partialInvoices}
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 12%;">Invoice Number</th>
                            <th style="width: 20%;">Customer</th>
                            <th style="width: 15%;">Vehicle</th>
                            <th style="width: 10%;">Items</th>
                            <th style="width: 10%;">Grand Total</th>
                            <th style="width: 8%;">Status</th>
                            <th style="width: 10%;">Paid</th>
                            <th style="width: 10%;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td><strong><?php echo e($invoice->invoice_number); ?></strong></td>
                                <td><?php echo e($invoice->customer->name ?? ($invoice->customer_name ?? 'Cash Sale')); ?></td>
                                <td>
                                    <?php if($invoice->vehicle_make): ?>
                                        <?php echo e($invoice->vehicle_make); ?> <?php echo e($invoice->vehicle_model); ?>

                                        <?php if($invoice->vehicle_reg): ?>
                                            <br><small>(<?php echo e($invoice->vehicle_reg); ?>)</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo e($invoice->items->count() ?? 0); ?></td>
                                <td class="text-end">R <?php echo e(number_format($invoice->grand_total ?? 0, 2)); ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?php echo e($invoice->payment_status); ?>"><?php echo e(ucfirst($invoice->payment_status)); ?></span>
                                </td>
                                <td class="text-end">R <?php echo e(number_format($invoice->amount_paid ?? 0, 2)); ?></td>
                                <td class="text-center"><?php echo e($invoice->created_at->format('d/m/Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    filterInvoices();
                }, 500);
            });

            // Select filters with immediate response
            $('#statusFilter, #customerFilter').on('change', function() {
                filterInvoices();
            });

            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#filterForm')[0].reset();
                window.location.href = '<?php echo e(route('invoices.index')); ?>';
            });

            // Filter function
            function filterInvoices() {
                const formData = $('#filterForm').serialize();

                $.ajax({
                    url: '<?php echo e(route('invoices.index')); ?>',
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
                        toastr.error('Failed to filter invoices. Please try again.');
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
                        const invoiceId = $(this).data('invoice-id');
                        if (invoiceId) {
                            openViewInvoiceModal(invoiceId);
                        }
                    }
                });
            }

            // Initialize on page load
            initializeRowClickHandlers();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 me-3">Invoices</h4>
                </div>
            
    </div>

        
        <div class="card shadow-sm mb-3">
                <div class="card-body">
                <form id="filterForm" method="GET" action="<?php echo e(route('invoices.index')); ?>">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" id="searchInput" class="form-control"
                                placeholder="Search by invoice number, customer..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="posted" <?php echo e(request('status') == 'posted' ? 'selected' : ''); ?>>Posted</option>
                                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                <option value="partial" <?php echo e(request('status') == 'partial' ? 'selected' : ''); ?>>Partial</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="customer" id="customerFilter" class="form-select">
                                <option value="">All Customers</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(request('customer') == $customer->id ? 'selected' : ''); ?>>
                                        <?php echo e($customer->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

        
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="card-title">
                    Invoices<span
                        class="badge bg-light text-default rounded ms-1 fs-12 align-middle"><?php echo e($invoices->total()); ?></span>
                </div>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Print & Export Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="printInvoices()">
                                        <i class="ri-printer-line me-2 text-secondary"></i>Print
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo e(route('invoices.export', ['format' => 'pdf'])); ?>">
                                        <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('invoices.export', ['format' => 'csv'])); ?>">
                                        <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                                    </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('invoices.export', ['format' => 'excel'])); ?>">
                                        <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                                    </a></li>
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="card-body">
                <div class="table-responsive position-relative" id="invoicesTable">
                    <table class="table table-striped align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Invoice Details</th>
                                    <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Items</th>
                                <th>Grand Total</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                    <th>Status</th>
                                <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="clickable-row" data-invoice-id="<?php echo e($invoice->id); ?>" style="cursor: pointer;">
                                    <td><?php echo e($loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage()); ?></td>

                                    
                                    <td>
                                        <div class="d-flex">
                                            <span class="avatar avatar-md avatar-square bg-primary-transparent p-2">
                                                <i class="ri-file-text-line fs-18"></i>
                                            </span>
                                            <div class="ms-2">
                                                <p class="fw-semibold mb-0 d-flex align-items-center">
                                                    <?php echo e($invoice->invoice_number); ?>

                                                </p>
                                                <p class="fs-12 text-muted mb-0">Created:
                                                    <?php echo e($invoice->created_at->format('d M Y')); ?></p>
                                        <?php if($invoice->quote_id): ?>
                                                    <p class="fs-12 text-muted mb-0">From Quote: <?php echo e($invoice->quote->quote_number ?? '#'.$invoice->quote_id); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td>
                                        <div>
                                            <p class="fw-semibold mb-0"><?php echo e($invoice->customer->name ?? ($invoice->customer_name ?? 'Cash Sale')); ?></p>
                                            <?php if($invoice->customer): ?>
                                                <p class="fs-12 text-muted mb-0"><?php echo e($invoice->customer->email); ?></p>
                                            <?php elseif($invoice->customer_phone): ?>
                                                <p class="fs-12 text-muted mb-0"><?php echo e($invoice->customer_phone); ?></p>
                                        <?php endif; ?>
                                        </div>
                                    </td>

                                    
                                    <td>
                                        <?php if($invoice->vehicle_make): ?>
                                            <p class="mb-0"><?php echo e($invoice->vehicle_make); ?> <?php echo e($invoice->vehicle_model); ?></p>
                                            <?php if($invoice->vehicle_reg): ?>
                                                <p class="fs-12 text-muted mb-0">Reg: <?php echo e($invoice->vehicle_reg); ?></p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td>
                                        <span class="badge bg-info-transparent rounded-pill"><?php echo e($invoice->items->count()); ?>

                                            items</span>
                                    </td>

                                    
                                    <td>
                                        <span class="fw-bold text-success">R
                                            <?php echo e(number_format($invoice->grand_total ?? 0, 2)); ?></span>
                                    </td>

                                    
                                    <td>
                                        <span class="fw-semibold text-primary">R
                                            <?php echo e(number_format($invoice->amount_paid ?? 0, 2)); ?></span>
                                    </td>

                                    
                                    <td>
                                        <?php
                                            $balance = $invoice->balance_due ?? 0;
                                        ?>
                                        <span class="fw-semibold <?php echo e($balance > 0 ? 'text-danger' : 'text-success'); ?>">R
                                            <?php echo e(number_format($balance, 2)); ?></span>
                                    </td>

                                    
                                    <td>
                                        <?php if($invoice->payment_status === 'draft'): ?>
                                            <span class="badge rounded-pill bg-warning-transparent">Draft</span>
                                        <?php elseif($invoice->payment_status === 'posted'): ?>
                                            <span class="badge rounded-pill bg-info-transparent">Posted</span>
                                        <?php elseif($invoice->payment_status === 'paid'): ?>
                                            <span class="badge rounded-pill bg-success-transparent">Paid</span>
                                        <?php elseif($invoice->payment_status === 'unpaid'): ?>
                                            <span class="badge rounded-pill bg-danger-transparent">Unpaid</span>
                                        <?php elseif($invoice->payment_status === 'partially_paid'): ?>
                                            <span class="badge rounded-pill bg-warning-transparent">Partially Paid</span>
                                        <?php elseif($invoice->payment_status === 'partial'): ?>
                                            <span class="badge rounded-pill bg-warning-transparent">Partial</span>
                                        <?php elseif($invoice->payment_status === 'cancelled'): ?>
                                            <span class="badge rounded-pill bg-danger-transparent">Cancelled</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-light"><?php echo e(ucfirst($invoice->payment_status)); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <!-- View -->
                                            <button class="btn btn-sm btn-info-light btn-icon openViewInvoiceModalBtn"
                                                data-id="<?php echo e($invoice->id); ?>" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </button>

                                          

                                            <!-- Return (Only if Posted/Paid/Partially Paid) -->
                                            <?php if(in_array($invoice->payment_status, ['posted', 'paid', 'partially_paid', 'unpaid'])): ?>
                                                <button type="button" onclick="openReturnModal('<?php echo e($invoice->invoice_number); ?>')"
                                                    class="btn btn-sm btn-warning-light btn-icon" title="Process Return">
                                                    <i class="ri-refund-2-line"></i>
                                                </button>
                                            <?php endif; ?>

                                              <!-- Actions Dropdown -->
                                              
                                                <button type="button" class="btn btn-sm btn-primary-light" 
                                                    data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                                    <i class="ri-more-2-fill"></i>
                                            </button>
                                                <ul class="dropdown-menu">
                                                    <!-- Print -->
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="printInvoice(<?php echo e($invoice->id); ?>)">
                                                            <i class="ri-printer-line me-2"></i>Print Invoice
                                                        </a>
                                                    </li>
                                                    <!-- Download PDF -->
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('invoices.pdf', $invoice->id)); ?>" target="_blank">
                                                            <i class="ri-file-pdf-line me-2"></i>Download PDF
                                                        </a>
                                                    </li>
                                                    <!-- Picking List -->
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('invoices.picking-list', $invoice->id)); ?>" target="_blank">
                                                            <i class="ri-file-list-3-line me-2"></i>Picking List
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <!-- Send WhatsApp -->
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="sendWhatsAppFromList(<?php echo e($invoice->id); ?>)">
                                                            <i class="ri-whatsapp-line me-2 text-success"></i>Send WhatsApp
                                                        </a>
                                                    </li>
                                                    <!-- Send Email -->
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="sendEmailFromList(<?php echo e($invoice->id); ?>)">
                                                            <i class="ri-mail-line me-2 text-info"></i>Send Email
                                                        </a>
                                                    </li>
                                                </ul>
                                            
                                        </div>
                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="deleteInvoice<?php echo e($invoice->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form method="POST" action="<?php echo e(route('invoices.destroy', $invoice->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete invoice
                                                    <strong><?php echo e($invoice->invoice_number); ?></strong>?
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
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="ri-file-list-line ri-3x mb-3 d-block"></i>
                                        <p class="mb-0">No invoices found</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="card-footer">
                <div id="paginationContainer">
                    <?php echo $__env->make('invoices.partials.pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modals -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
            <div class="modal-content" id="invoiceModalContent"></div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
        $(function() {
            // Create Invoice Modal
            $('#openCreateInvoiceModal').on('click', function(e) {
                e.preventDefault(); // Prevent any default action
                e.stopPropagation(); // Stop event bubbling
                
                console.log('Opening create invoice modal...');
                
                // IMPORTANT: Keep main invoices table visible
                $('#invoicesTable').css('display', 'block').show();
                $('.table-responsive').not('#invoiceModal .table-responsive').show();
                
                $.get("<?php echo e(route('invoices.create')); ?>", function(html) {
                    console.log('Modal HTML loaded');
                    $('#invoiceModalContent').html(html);
                    
                    // Double-check: Ensure main table stays visible after modal loads
                    setTimeout(function() {
                        $('#invoicesTable').css('display', 'block').show();
                        $('.table-responsive').not('#invoiceModal .table-responsive').show();
                        $('body > .container-fluid .table-responsive').show();
                    }, 100);
                    
                    $('#invoiceModal').modal('show');
                }).fail(function(xhr) {
                    console.error('Failed to load modal:', xhr);
                    toastr.error('Failed to load create modal. Please try again.');
    });
});

            // View Invoice Modal
            $(document).on('click', '.openViewInvoiceModalBtn', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                $.get("<?php echo e(route('invoices.view-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#invoiceModalContent').html(html);
                    $('#invoiceModal').modal('show');
                });
            });

            // Edit Invoice Modal
            $(document).on('click', '.openEditInvoiceModalBtn', function(e) {
                e.stopPropagation();
                var id = $(this).data('id');
                $.get("<?php echo e(route('invoices.edit-modal', ':id')); ?>".replace(':id', id), function(html) {
                    $('#invoiceModalContent').html(html);
                    $('#invoiceModal').modal('show');
                });
            });

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Ensure table stays visible when modal closes
            $('#invoiceModal').on('hidden.bs.modal', function() {
                console.log('Modal closed, ensuring table visibility...');
                $('#invoicesTable').css('display', 'block').show();
                $('.table-responsive').not('#invoiceModal .table-responsive').show();
                $('tbody').show();
            });

            // Ensure table stays visible when modal opens
            $('#invoiceModal').on('shown.bs.modal', function() {
                console.log('Modal opened, ensuring table visibility...');
                $('#invoicesTable').css('display', 'block').show();
                $('.table-responsive').not('#invoiceModal .table-responsive').show();
            });

            // Print Invoice Function - Opens print dialog directly
            window.printInvoice = function(invoiceId) {
                // Create a hidden iframe
                const printUrl = "<?php echo e(url('invoices')); ?>/" + invoiceId + "/print";
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

            // Open Return Modal Function
            window.openReturnModal = function(invoiceNumber) {
                $('#returnInvoiceNumber').val(invoiceNumber);
                $('#processReturnModal').modal('show');
            };

            // Reload Invoices Table Function
            window.reloadInvoicesTable = function() {
                location.reload();
            };

            // Send WhatsApp from list
            window.sendWhatsAppFromList = function(invoiceId) {
                toastr.info('Preparing WhatsApp...');
                
                const url = '<?php echo e(route("invoices.whatsapp", ":id")); ?>'.replace(':id', invoiceId);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.whatsapp_url) {
                        const shareType = data.share_type || 'web';
                        const whatsappTab = window.open(data.whatsapp_url, '_blank');
                        
                        if (whatsappTab) {
                            if (shareType === 'desktop') {
                                // Auto-copy message for desktop
                                const message = data.message || '';
                                if (message) {
                                    copyToClipboardList(message);
                                    toastr.success('WhatsApp Desktop app opened! Message copied to clipboard. Just paste (Ctrl+V) in the app.', {
                                        timeOut: 5000
                                    });
                                } else {
                                    toastr.warning('WhatsApp Desktop app opened.');
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
            };

            // Send Email from list
            window.sendEmailFromList = function(invoiceId) {
                toastr.info('Sending email...');
                
                const url = '<?php echo e(route("invoices.email", ":id")); ?>'.replace(':id', invoiceId);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
            };

            // Copy to clipboard helper
            function copyToClipboardList(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(() => {
                        console.log('Message copied to clipboard');
                    }).catch(err => {
                        copyToClipboardFallbackList(text);
                    });
                } else {
                    copyToClipboardFallbackList(text);
                }
            }

            function copyToClipboardFallbackList(text) {
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
        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('credit-notes.partials.return_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/invoices/index.blade.php ENDPATH**/ ?>