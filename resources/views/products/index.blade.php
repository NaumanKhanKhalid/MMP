@php
    use Milon\Barcode\DNS1D;
    $barcodeGen = new DNS1D();
@endphp
{{-- resources/views/products/index.blade.php --}}

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
        function openViewModal(productId) {
            // Check if the click came from a button or form
            if (event.target.closest('button') || event.target.closest('form')) {
                return; // Don't open modal if clicking on buttons/forms
            }

            // Open the view modal
            const modal = new bootstrap.Modal(document.getElementById('viewProductModal-' + productId));
            modal.show();
        }

        function printProducts() {
            try {
                // Get product data for summary
                const totalProducts = {{ $products->total() }};
                const activeProducts = {{ $products->where('status', 'active')->count() }};
                const inactiveProducts = {{ $products->where('status', 'inactive')->count() }};

                // Create new window for printing
                const printWindow = window.open('', '_blank', 'width=1200,height=800');

                const printHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Products List - ${new Date().toLocaleDateString()}</title>
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
                        font-size: 8px;
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
                    
                    .badge-active {
                        background-color: #d4edda;
                        color: #155724;
                    }
                    
                    .badge-inactive {
                        background-color: #f8d7da;
                        color: #721c24;
                    }
                    
                    .badge-stock {
                        background-color: #d1ecf1;
                        color: #0c5460;
                    }
                    
                    .text-end {
                        text-align: right;
                    }
                    
                    .text-center {
                        text-align: center;
                    }
                    
                    .avatar {
                        width: 30px;
                        height: 30px;
                        border-radius: 4px;
                        object-fit: cover;
                    }
                    
                    .d-flex {
                        display: flex;
                        align-items: center;
                    }
                    
                    .ms-2 {
                        margin-left: 8px;
                    }
                    
                    .fw-semibold {
                        font-weight: 600;
                    }
                    
                    .fs-12 {
                        font-size: 10px;
                    }
                    
                    .text-muted {
                        color: #6c757d;
                    }
                    
                    .mb-0 {
                        margin-bottom: 0;
                    }
                    
                    .price {
                        font-weight: bold;
                        color: #28a745;
                    }
                    
                    .cost {
                        color: #dc3545;
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
                        .table { font-size: 7px; }
                        .avatar { width: 25px; height: 25px; }
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
                    <h2>Products Inventory Report</h2>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                </div>
                
                <div class="summary">
                    <strong>Total Products:</strong> ${totalProducts} | 
                    <strong>Active Products:</strong> ${activeProducts} | 
                    <strong>Inactive Products:</strong> ${inactiveProducts}
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 8%;">SKU</th>
                            <th style="width: 15%;">Product Name</th>
                            <th style="width: 6%;">Brand</th>
                            <th style="width: 8%;">Category</th>
                            <th style="width: 6%;">Unit</th>
                            <th style="width: 6%;">Last Cost</th>
                            <th style="width: 6%;">Stock</th>
                            <th style="width: 6%;">Normal Price</th>
                            <th style="width: 6%;">Online Price</th>
                            <th style="width: 6%;">Workshop Price</th>
                            <th style="width: 8%;">OE Numbers</th>
                            <th style="width: 8%;">Cross Ref</th>
                            <th style="width: 6%;">Bin Location</th>
                            <th style="width: 4%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            @php
                                $lastCost = $product->stockBatches->first() ? $product->stockBatches->first()->landed_unit_cost : 0;
                                $totalStock = $product->stockBatches->sum('qty_left');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $product->sku }}</strong></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand ? $product->brand->name : '-' }}</td>
                                <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                <td class="text-center">{{ $product->unit }}</td>
                                <td class="text-end cost">R {{ number_format($lastCost, 2) }}</td>
                                <td class="text-end badge-stock">{{ number_format($totalStock, 2) }}</td>
                                <td class="text-end price">R {{ number_format($product->normal_price, 2) }}</td>
                                <td class="text-end price">R {{ number_format($product->online_price, 2) }}</td>
                                <td class="text-end price">R {{ number_format($product->workshop_price, 2) }}</td>
                                <td>
                                    @if ($product->oeNumbers->count() > 0)
                                        {{ $product->oeNumbers->take(2)->pluck('oe_number')->implode(', ') }}
                                        @if ($product->oeNumbers->count() > 2)
                                            <br><small>+{{ $product->oeNumbers->count() - 2 }} more</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($product->crossRefs->count() > 0)
                                        {{ $product->crossRefs->take(2)->pluck('cross_ref_number')->implode(', ') }}
                                        @if ($product->crossRefs->count() > 2)
                                            <br><small>+{{ $product->crossRefs->count() - 2 }} more</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $product->bin_location ?: '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $product->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>This report was generated by MMP Auto-Meister POS System</p>
                    <p>© ${new Date().getFullYear()} MMP Auto-Meister. All rights reserved.</p>
                    <br>
                    <button onclick="window.print()" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        Print This Report
                    </button>
                </div>
            </body>
            </html>
        `;

                printWindow.document.write(printHTML);
                printWindow.document.close();

                // Wait for content to load then show preview (don't auto-print)
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.focus();
                        // Don't auto-print - let user decide when to print
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
                    currentPage = 1;
                    filterProducts();
                }, 500);
            });

            // Select filters with immediate response
            $('#brandFilter, #categoryFilter, #supplierFilter, #statusFilter').on('change', function() {
                currentPage = 1;
                filterProducts();
            });

            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#filterForm')[0].reset();
                window.location.href = '{{ route('products.index') }}';
            });

            // Global variables
            let currentPage = {{ $products->currentPage() }};
            let isLoading = false;

            // Pagination click handler (delegated)
            $(document).on('click', '#paginationContainer .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    const urlParams = new URLSearchParams(url.split('?')[1]);
                    currentPage = urlParams.get('page') || 1;
                    filterProducts();
                }
            });

            // Filter function
            function filterProducts() {
                if (isLoading) return;
                
                isLoading = true;
                const params = $('#filterForm').serializeArray();
                params.push({ name: 'page', value: currentPage });
                params.push({ name: 'ajax', value: '2' });

                $('#productsTableBody').html('<tr><td colspan="12" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><div class="mt-2">Loading products...</div></td></tr>');

                $.ajax({
                    url: '{{ route('products.index') }}',
                    type: 'GET',
                    data: $.param(params),
                    success: function(response) {
                        if (response.success) {
                            $('#productsTableBody').html(response.table);
                            $('#paginationContainer').html(response.pagination);
                            
                            // Update URL without page reload
                            const newUrl = `{{ route('products.index') }}?${$('#filterForm').serialize()}&page=${currentPage}`;
                            window.history.pushState({}, '', newUrl);
                        }
                        isLoading = false;
                    },
                    error: function(xhr) {
                        console.error('Filter error:', xhr);
                        toastr.error('Failed to filter products. Please try again.');
                        isLoading = false;
                    }
                });
            }

            // Initialize row click handlers
            function initializeRowClickHandlers() {
                $('.clickable-row').off('click').on('click', function(e) {
                    if (!$(e.target).closest('button, form').length) {
                        const productId = $(this).data('product-id') || $(this).attr('data-bs-target')
                            ?.match(/\d+/)?.[0];
                        if (productId) {
                            openViewModal(productId);
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
                <h4 class="mb-0 me-3">Products</h4>
                {{-- <span class="badge bg-primary-transparent"> Products</span> --}}
            </div>
            <div class="d-flex gap-2 flex-wrap">


                <!-- Quick Add Button -->
                <button class="btn btn-warning-light btn-wave me-2 waves-effect waves-light" data-bs-toggle="modal"
                    data-bs-target="#quickAddModal" title="Quick Add Product">
                    <i class="ri-flashlight-line me-1"></i>Quick Add
                </button>

                <!-- Add Product Button -->
                <button class="btn btn-primary-light btn-wave me-2 waves-effect waves-light" data-bs-toggle="modal"
                    data-bs-target="#createProductModal" title="Add New Product">
                    <i class="ri-add-line me-1"></i>Add Product
                </button>


            </div>
        </div>

        {{-- Filters --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('products.index') }}">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" id="searchInput" class="form-control"
                                placeholder="Search: Name, SKU, Description, OE#, Supplier Code, Brand Code, Bin..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="brand_id" id="brandFilter" class="form-select">
                                <option value="">All Brands</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" id="categoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="supplier_id" id="supplierFilter" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-2">
                            <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div> --}}
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

        {{-- Products Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="card-title">
                        Products<span
                            class="badge bg-light text-default rounded ms-1 fs-12 align-middle">{{ $products->total() }}</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        
                        <!-- Print Barcodes Button -->

                        
                        <!-- Print & Export Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave waves-effect waves-light"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Print / Export<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="printProducts()">
                                        <i class="ri-printer-line me-2 text-secondary"></i>Print
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('products.export', ['format' => 'pdf']) }}">
                                        <i class="ri-file-pdf-line me-2 text-danger"></i>Export as PDF
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('products.export', ['format' => 'csv']) }}">
                                        <i class="ri-file-text-line me-2 text-info"></i>Export as CSV
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('products.export', ['format' => 'excel']) }}">
                                        <i class="ri-file-excel-line me-2 text-success"></i>Export as Excel
                                    </a></li>

                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#printBarcodeModal">
                                        <i class="ri-barcode-line me-2 text-primary"></i>Print Barcodes
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="table-responsive position-relative">
                <table class="table table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Supplier Code</th>
                            <th>Last Cost</th>
                            <th>Total Stock</th>
                            <th>Normal Price</th>
                            <th>Online Price</th>
                            <th>Workshop Price</th>
                            <th>OE Number</th>
                            <th>Cross Ref</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        @include('products.partials.table')
                    </tbody>
                </table>
                </div>
            </div>
            <div class="card-footer">
                <div id="paginationContainer">
                    @include('products.partials.pagination')
                </div>
            </div>
        </div> {{-- Modals --}}
        @include('products._create_modal')
        @include('products._quick_add_modal')
        @include('products._print_barcode_modal')
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize barcode modal functionality
            initializeBarcodeModal();
        });

        // Print Single Barcode
        function printSingleBarcode(productId, productName, sku, barcode) {
            const labels = [{
                name: productName,
                sku: sku,
                barcode: barcode,
                price: 'R 0.00', // You can get this from the product data
                quantity: 1
            }];
            
            // Show preview first, don't auto-print
            generateBarcodeLabels(labels, false);
            $('#labelPreviewModal').modal('show');
        }

        // Initialize Barcode Modal
        function initializeBarcodeModal() {
            // Load products when modal opens
            $('#printBarcodeModal').on('show.bs.modal', function() {
                loadProductsForBarcode();
            });

            // Select All checkbox (delegated event)
            $(document).on('change', '#selectAllProducts', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateButtonStates();
            });

            // Individual checkboxes (delegated events)
            $(document).on('change', '.product-checkbox', updateButtonStates);
            $(document).on('input', '.quantity-input', updateButtonStates);

            // Search functionality with debounce
            let searchTimeout;
            document.getElementById('barcodeProductSearch').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadProductsForBarcode();
                }, 300);
            });

            // Category filter
            document.getElementById('barcodeCategoryFilter').addEventListener('change', function() {
                loadProductsForBarcode();
            });

            // Status filter
            document.getElementById('barcodeStatusFilter').addEventListener('change', function() {
                loadProductsForBarcode();
            });
        }

        // Load Products for Barcode Modal via AJAX
        function loadProductsForBarcode() {
            const search = document.getElementById('barcodeProductSearch').value;
            const category = document.getElementById('barcodeCategoryFilter').value;
            const status = document.getElementById('barcodeStatusFilter').value;

            // Show loading
            document.getElementById('barcodeProductsTable').innerHTML = `
                <tr id="loadingRow">
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Loading products...</div>
                    </td>
                </tr>
            `;

            // Fetch products via AJAX
            fetch(`{{ route('products.index') }}?ajax=1&search=${encodeURIComponent(search)}&category=${category}&status=${status}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.products) {
                    renderProductsTable(data.products);
                } else {
                    showNoProductsMessage();
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                showErrorMessage();
            });
        }

        // Render Products Table
        function renderProductsTable(products) {
            const tbody = document.getElementById('barcodeProductsTable');
            
            if (products.length === 0) {
                showNoProductsMessage();
                return;
            }

            tbody.innerHTML = products.map(product => `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input product-checkbox" 
                               value="${product.id}" 
                               data-name="${product.name}"
                               data-sku="${product.sku}"
                               data-barcode="${product.barcode_primary}"
                               data-price="${product.price_normal}">
                    </td>
                    <td>
                        <div class="fw-semibold">${product.name}</div>
                        <small class="text-muted">${product.brand_name || 'No Brand'}</small>
                    </td>
                    <td><span class="badge bg-light text-dark">${product.sku}</span></td>
                    <td><code>${product.barcode_primary}</code></td>
                    <td>R ${parseFloat(product.price_normal).toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" 
                               value="1" min="1" max="100" style="width: 80px;">
                    </td>
                </tr>
            `).join('');
        }

        // Show No Products Message
        function showNoProductsMessage() {
            document.getElementById('barcodeProductsTable').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="ri-inbox-line fs-1 d-block mb-2"></i>
                        No products found matching your criteria.
                    </td>
                </tr>
            `;
        }

        // Show Error Message
        function showErrorMessage() {
            document.getElementById('barcodeProductsTable').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-danger">
                        <i class="ri-error-warning-line fs-1 d-block mb-2"></i>
                        Error loading products. Please try again.
                    </td>
                </tr>
            `;
        }


        // Update Button States
        function updateButtonStates() {
            const selectedItems = [];

            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const quantity = parseInt(row.querySelector('.quantity-input').value) || 1;
                
                selectedItems.push({
                    name: checkbox.getAttribute('data-name'),
                    sku: checkbox.getAttribute('data-sku'),
                    barcode: checkbox.getAttribute('data-barcode'),
                    price: checkbox.getAttribute('data-price'),
                    quantity: quantity
                });
            });

            // Update button states
            const previewBtn = document.getElementById('previewLabelsBtn');
            const printBtn = document.getElementById('printLabelsBtn');
            const hasSelection = selectedItems.length > 0;
            
            previewBtn.disabled = !hasSelection;
            printBtn.disabled = !hasSelection;
        }

        // Preview Labels
        function previewLabels() {
            const selectedItems = getSelectedItems();
            if (selectedItems.length === 0) {
                toastr.warning('Please select at least one product');
                return;
            }
            
            // Show preview in modal
            const container = document.getElementById('labelsPreviewContent');
            container.innerHTML = '';

            selectedItems.forEach((label, index) => {
                const labelDiv = document.createElement('div');
                labelDiv.className = 'barcode-label';
                labelDiv.innerHTML = `
                    <div class="product-name">${label.name}</div>
                    <div class="product-details">
                        <div class="sku-line">SKU: ${label.sku} | ${label.brand_name || 'No Brand'}</div>
                        <div class="oe-line">OE: ${label.sku}</div>
                    </div>
                    <div class="barcode-container">
                        <svg class="barcode" data-barcode="${label.barcode}"></svg>
                        <div class="barcode-text">${label.barcode}</div>
                    </div>
                `;
                container.appendChild(labelDiv);
            });

            // Generate barcodes
            setTimeout(() => {
                document.querySelectorAll('.barcode').forEach(svg => {
                    try {
                        JsBarcode(svg, svg.getAttribute('data-barcode'), {
                            format: "CODE128",
                            width: 2,
                            height: 40,
                            displayValue: false,
                            margin: 2
                        });
                    } catch (e) {
                        console.error('Barcode generation error:', e);
                        svg.innerHTML = '<text>Barcode Error</text>';
                    }
                });
            }, 100);

            $('#labelPreviewModal').modal('show');
        }

        // Print Labels
        function printLabels() {
            const selectedItems = getSelectedItems();
            if (selectedItems.length === 0) {
                toastr.warning('Please select at least one product');
                return;
            }
            
            // Print directly without showing preview
            generateBarcodeLabels(selectedItems, true);
        }

        // Get Selected Items
        function getSelectedItems() {
            const selectedItems = [];
            
            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const quantity = parseInt(row.querySelector('.quantity-input').value) || 1;
                
                for (let i = 0; i < quantity; i++) {
                    selectedItems.push({
                        name: checkbox.getAttribute('data-name'),
                        sku: checkbox.getAttribute('data-sku'),
                        barcode: checkbox.getAttribute('data-barcode'),
                        price: 'R ' + parseFloat(checkbox.getAttribute('data-price')).toFixed(2)
                    });
                }
            });
            
            return selectedItems;
        }

        // Generate Barcode Labels
        function printPreviewLabels() {
            const labels = [];
            document.querySelectorAll('#labelsPreviewContent .barcode-label').forEach(labelDiv => {
                const productName = labelDiv.querySelector('.product-name')?.textContent || '';
                const skuLine = labelDiv.querySelector('.sku-line');
                const barcodeElement = labelDiv.querySelector('.barcode-text');

                if (skuLine && barcodeElement) {
                    const skuMatch = skuLine.textContent.match(/SKU:\s*([^|]+)/);
                    const sku = skuMatch ? skuMatch[1].trim() : '';
                    const barcode = barcodeElement.textContent;

                    labels.push({
                        name: productName,
                        sku: sku,
                        barcode: barcode,
                        price: 'R 0.00'
                    });
                }
            });

            if (labels.length > 0) {
                generateBarcodeLabels(labels, true);
            } else {
                toastr.error('No labels found to print');
            }
        }

        // Print Single Barcode
        function printSingleBarcode(productId, productName, sku, barcode) {
            // Show modal with barcode preview
            const modal = document.getElementById('singleBarcodePrintModal');
            const preview = document.getElementById('singleBarcodePreview');

            // Generate barcode preview
            preview.innerHTML = `
            <div class="barcode-label" style="width: 2.5in; height: 1.8in; border: 1px dashed #999; padding: 6px; background: white;">
                <div class="product-name" style="font-weight: bold; font-size: 12px; text-align: center; margin-bottom: 6px; height: 20px; display: flex; align-items: center; justify-content: center;">${productName}</div>
                <div class="product-details" style="text-align: center; font-size: 9px; margin-bottom: 8px;">
                    <div class="sku-line" style="margin-bottom: 2px;">SKU: ${sku} | No Brand</div>
                    <div class="oe-line">OE: ${sku}</div>
                    </div>
                <div class="barcode-container" style="text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <svg class="barcode" data-barcode="${barcode}"></svg>
                    <div class="barcode-text" style="font-size: 10px; margin-top: 2px; text-align: center;">${barcode}</div>
                </div>
                    </div>
                `;
                
            // Generate barcode
            setTimeout(() => {
                const svg = preview.querySelector('.barcode');
                if (svg) {
                    JsBarcode(svg, barcode, {
                            format: "CODE128",
                            width: 2,
                            height: 40,
                            displayValue: false,
                            margin: 2
                        });
                }
            }, 100);

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Handle print button click
            document.getElementById('printSingleBarcodeBtn').onclick = function() {
                bsModal.hide();

                // Generate print window
                const label = {
                    name: productName,
                    sku: sku,
                    barcode: barcode
                };

                generateBarcodeLabels([label], true);
            };
        }

        function generateBarcodeLabels(labels, shouldPrint = false) {
            if (!shouldPrint) {
                // Just show preview in modal
                return;
            }

            // Create print container on same page
                    const printContainer = document.createElement('div');
            printContainer.id = 'printBarcodeContainer';
                    printContainer.style.cssText = `
                        position: fixed;
            top: 0;
            left: 0;
                        width: 100%;
                        height: 100%;
                        background: white;
            z-index: 99999;
            padding: 20px;
            overflow: auto;
        `;

            // Generate labels HTML
            let labelsHTML = '';
            labels.forEach((label) => {
                labelsHTML += `
                <div class="barcode-label" style="width: 2.5in; height: 1.8in; border: 1px dashed #000; padding: 6px; margin: 3px; background: white; display: inline-block; vertical-align: top; page-break-inside: avoid;">
                    <div class="product-name" style="font-weight: bold; font-size: 12px; text-align: center; margin-bottom: 6px; height: 20px; display: flex; align-items: center; justify-content: center;">${label.name}</div>
                    <div class="product-details" style="text-align: center; font-size: 9px; margin-bottom: 8px;">
                        <div class="sku-line" style="margin-bottom: 2px;">SKU: ${label.sku} | No Brand</div>
                        <div class="oe-line">OE: ${label.sku}</div>
                    </div>
                    <div class="barcode-container" style="text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 45px;">
                        <svg class="barcode" data-barcode="${label.barcode}"></svg>
                        <div class="barcode-text" style="font-size: 10px; margin-top: 2px; text-align: center;">${label.barcode}</div>
                    </div>
                </div>
            `;
            });
                    
                    printContainer.innerHTML = `
                        <style>
                            @media print {
                                body { margin: 0; padding: 0; }
                                .barcode-label {
                        width: 2.9in !important;
                        height: 1.9in !important;
                                    margin: 0.05in !important;
                                }
                            }
                        </style>
                        ${labelsHTML}
                    `;
                    
            // Add to body
                    document.body.appendChild(printContainer);
                    
            // Generate barcodes
                    setTimeout(() => {
                        printContainer.querySelectorAll('.barcode').forEach(svg => {
                            try {
                                JsBarcode(svg, svg.getAttribute('data-barcode'), {
                                    format: "CODE128",
                                    width: 2,
                                    height: 32,
                                    displayValue: false,
                                    margin: 2
                                });
                            } catch (e) {
                                console.error('Barcode generation error:', e);
                    }
                });

                // Print after barcodes are generated
                        setTimeout(() => {
                            window.print();
                            
                    // Clean up after print
                    setTimeout(() => {
                        if (document.body.contains(printContainer)) {
                            document.body.removeChild(printContainer);
                        }
                    }, 500);
                        }, 500);
                    }, 100);
        }
    </script>
@endpush
