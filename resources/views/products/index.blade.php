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

        .expand-icon {
            transition: all 0.2s ease;
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
            padding: 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            min-height: 32px;
        }

        .expand-icon:hover {
            background-color: #f8f9fa;
            color: #495057;
            transform: scale(1.1);
        }

        .batches-row {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .batches-row td {
            border-top: none !important;
        }

        /* Image Slider Styles */
        .image-slider-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .main-image-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .thumbnail-container {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .thumbnail-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .thumbnail-container.active img {
            border-width: 3px !important;
        }

        .thumbnail-gallery {
            margin-top: 15px;
        }
    </style>

    <script>
        function toggleBatches(productId) {
            // Check if the click came from a button or form
            if (event.target.closest('button') || event.target.closest('form')) {
                return; // Don't toggle if clicking on buttons/forms
            }

            const batchesRow = document.getElementById('batches-row-' + productId);
            const expandIcon = document.getElementById('expand-icon-' + productId);
            
            if (batchesRow.style.display === 'none') {
                // Show batches
                batchesRow.style.display = '';
                expandIcon.classList.remove('ri-arrow-down-s-line');
                expandIcon.classList.add('ri-arrow-up-s-line');
            } else {
                // Hide batches
                batchesRow.style.display = 'none';
                expandIcon.classList.remove('ri-arrow-up-s-line');
                expandIcon.classList.add('ri-arrow-down-s-line');
            }
        }

        function openViewModal(productId) {
            // Open the view modal
            const modal = new bootstrap.Modal(document.getElementById('viewProductModal-' + productId));
            modal.show();
        }

        // Image Modal Functions
        let currentProductImages = [];
        let currentImageIndex = 0;

        function openImageModal(productId, imageUrl, productName, allImages = []) {
            // Use all images if provided, otherwise use single image
            currentProductImages = allImages.length > 0 ? allImages : [imageUrl];
            currentImageIndex = 0;
            
            document.getElementById('modalImage').src = currentProductImages[currentImageIndex];
            document.getElementById('imageModalTitle').innerHTML = '<i class="ri-image-line me-2"></i>' + productName;
            document.getElementById('imageModalDescription').textContent = 'Product: ' + productName;
            
            // Hide navigation if only one image
            if (currentProductImages.length <= 1) {
                document.getElementById('prevImageBtn').style.display = 'none';
                document.getElementById('nextImageBtn').style.display = 'none';
                document.getElementById('imageCounter').style.display = 'none';
            } else {
                document.getElementById('prevImageBtn').style.display = 'block';
                document.getElementById('nextImageBtn').style.display = 'block';
                document.getElementById('imageCounter').style.display = 'block';
                document.getElementById('currentImageNumber').textContent = currentImageIndex + 1;
                document.getElementById('totalImages').textContent = currentProductImages.length;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        function changeImage(direction) {
            if (currentProductImages.length <= 1) return;
            
            currentImageIndex += direction;
            
            // Loop around
            if (currentImageIndex < 0) {
                currentImageIndex = currentProductImages.length - 1;
            } else if (currentImageIndex >= currentProductImages.length) {
                currentImageIndex = 0;
            }
            
            document.getElementById('modalImage').src = currentProductImages[currentImageIndex];
            document.getElementById('currentImageNumber').textContent = currentImageIndex + 1;
        }

        // Image Slider Functions for View Modal
        function changeMainImage(productId, direction) {
            const mainImage = document.getElementById('mainImage-' + productId);
            const currentNumber = document.getElementById('currentImageNumber-' + productId);
            const thumbnails = document.querySelectorAll('#viewProductModal-' + productId + ' .thumbnail-container');
            
            // Get current image index
            let currentIndex = parseInt(currentNumber.textContent) - 1;
            
            // Calculate new index
            currentIndex += direction;
            
            // Get total images count
            const totalImages = thumbnails.length;
            
            // Loop around
            if (currentIndex < 0) {
                currentIndex = totalImages - 1;
            } else if (currentIndex >= totalImages) {
                currentIndex = 0;
            }
            
            // Update main image
            const newImageSrc = thumbnails[currentIndex].querySelector('img').src;
            mainImage.src = newImageSrc;
            
            // Update counter
            currentNumber.textContent = currentIndex + 1;
            
            // Update thumbnail selection
            thumbnails.forEach((thumb, index) => {
                const img = thumb.querySelector('img');
                if (index === currentIndex) {
                    thumb.classList.add('active');
                    img.classList.remove('border-secondary');
                    img.classList.add('border-primary');
                } else {
                    thumb.classList.remove('active');
                    img.classList.remove('border-primary');
                    img.classList.add('border-secondary');
                }
            });
        }

        function selectMainImage(productId, imageIndex) {
            const mainImage = document.getElementById('mainImage-' + productId);
            const currentNumber = document.getElementById('currentImageNumber-' + productId);
            const thumbnails = document.querySelectorAll('#viewProductModal-' + productId + ' .thumbnail-container');
            
            // Update main image
            const newImageSrc = thumbnails[imageIndex].querySelector('img').src;
            mainImage.src = newImageSrc;
            
            // Update counter
            currentNumber.textContent = imageIndex + 1;
            
            // Update thumbnail selection
            thumbnails.forEach((thumb, index) => {
                const img = thumb.querySelector('img');
                if (index === imageIndex) {
                    thumb.classList.add('active');
                    img.classList.remove('border-secondary');
                    img.classList.add('border-primary');
                } else {
                    thumb.classList.remove('active');
                    img.classList.remove('border-primary');
                    img.classList.add('border-secondary');
                }
            });
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
                            <th style="width: 3%;"></th>
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
                                $totalStock = $product->stockBatches->sum('qty_left'); // Calculate from batches (proper way)
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

            // Prevent form submission and use AJAX instead
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                currentPage = 1; // Reset to first page when filtering
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

            // Keep URL clean - don't load parameters from URL
            // $(document).ready(function() {
            //     const urlParams = new URLSearchParams(window.location.search);
            //     urlParams.forEach((value, key) => {
            //         const input = $(`[name="${key}"]`);
            //         if (input.length && value) {
            //             input.val(value);
            //         }
            //     });
            //     
            //     // Set current page from URL
            //     const pageParam = urlParams.get('page');
            //     if (pageParam) {
            //         currentPage = parseInt(pageParam);
            //     }
            // });

            // Load products with specific page
            function loadProductsPage(page) {
                console.log('=== loadProductsPage called ===');
                console.log('Requested page:', page);
                console.log('Current page before:', currentPage);
                console.log('Is loading:', isLoading);
                
                if (isLoading) {
                    console.log('Already loading, skipping loadProductsPage');
                    return;
                }
                
                // Ensure page is a valid number
                page = parseInt(page);
                if (isNaN(page) || page < 1) {
                    console.error('Invalid page number:', page);
                    return;
                }
                
                // Update current page
                currentPage = page;
                console.log('Current page updated to:', currentPage);
                
                // Call filterProducts directly
                console.log('Calling filterProducts...');
                filterProducts();
            }
            
            // Debug function to test pagination
            window.testPagination = function() {
                console.log('Testing pagination...');
                console.log('Current page:', currentPage);
                loadProductsPage(2);
            };
            
            // Add debug button
            // $(document).ready(function() {
            //     if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            //         $('body').append('<div style="position: fixed; top: 10px; right: 10px; z-index: 9999; background: #007bff; color: white; padding: 10px; border-radius: 5px;"><button onclick="testPagination()" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-right: 5px;">Test Page 2</button><button onclick="loadProductsPage(3)" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Test Page 3</button></div>');
            //     }
            // });

            // Single pagination click handler
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('=== PAGINATION CLICKED ===');
                console.log('Clicked element:', $(this));
                console.log('Current page before click:', currentPage);
                
                // Try multiple ways to get the page number
                let page = null;
                
                // Method 1: Check onclick attribute
                const onclick = $(this).attr('onclick');
                console.log('onclick attribute:', onclick);
                if (onclick) {
                    const match = onclick.match(/loadProductsPage\((\d+)\)/);
                    if (match) {
                        page = parseInt(match[1]);
                        console.log('Found page from onclick:', page);
                    }
                }
                
                // Method 2: Check data-page attribute
                if (!page) {
                    page = $(this).data('page');
                    console.log('Found page from data-page:', page);
                }
                
                // Method 3: Check text content for page numbers
                if (!page) {
                    const text = $(this).text().trim();
                    console.log('Text content:', text);
                    if (/^\d+$/.test(text)) {
                        page = parseInt(text);
                        console.log('Found page from text:', page);
                    }
                }
                
                // Method 4: Handle Previous/Next buttons
                if (!page) {
                    const text = $(this).text().trim().toLowerCase();
                    console.log('Text for prev/next:', text);
                    if (text.includes('previous')) {
                        page = Math.max(1, currentPage - 1);
                        console.log('Previous button, page:', page);
                    } else if (text.includes('next')) {
                        page = currentPage + 1;
                        console.log('Next button, page:', page);
                    }
                }
                
                console.log('Final extracted page:', page);
                
                if (page && page > 0) {
                    console.log('Calling loadProductsPage with page:', page);
                    loadProductsPage(page);
                } else {
                    console.error('Could not determine page number from:', $(this));
                    console.error('onclick:', onclick);
                    console.error('data-page:', $(this).data('page'));
                    console.error('text:', $(this).text().trim());
                }
            });

            // Filter function
            function filterProducts() {
                if (isLoading) {
                    console.log('Already loading, skipping...');
                    return;
                }
                
                console.log('=== Starting filterProducts ===');
                console.log('Current page:', currentPage);
                console.log('Is loading:', isLoading);
                
                isLoading = true;
                const params = $('#filterForm').serializeArray();
                params.push({ name: 'page', value: currentPage });
                params.push({ name: 'ajax', value: '2' });

                console.log('Filter params:', params);
                console.log('Current page being sent:', currentPage);
                console.log('Page parameter in params:', params.find(p => p.name === 'page'));

                $('#productsTableBody').html('<tr><td colspan="16" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><div class="mt-2">Loading products...</div></td></tr>');

                $.ajax({
                    url: '{{ route('products.index') }}',
                    type: 'GET',
                    data: $.param(params),
                    success: function(response) {
                        console.log('AJAX response:', response);
                        if (response.success) {
                            $('#productsTableBody').html(response.table);
                            $('#paginationContainer').html(response.pagination);
                            
                            // Keep URL clean - don't update URL parameters
                            // const filterParams = $('#filterForm').serialize();
                            // const newUrl = `{{ route('products.index') }}?${filterParams}&page=${currentPage}`;
                            // window.history.pushState({}, '', newUrl);
                        } else {
                            console.error('AJAX response error:', response);
                            toastr.error('Failed to load products: ' + (response.message || 'Unknown error'));
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
                                <button class="btn btn-light w-100" id="clearFilters">
                                    <i class="ri-refresh-line"></i> </button>
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
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#importProductsModal">
                                        <i class="ri-upload-line me-2 text-warning"></i>Import from Excel
                                    </a>
                                </li>
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
                <div class="table-responsive">
                    <table class="table table-striped align-middle table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Product Name</th>
                            <th>Brand Code</th>
                            <th>Supplier Code</th>
                            <th>Last Cost</th>
                            <th>Total Stock</th>
                            <th>Normal Price</th>
                            <th>Online Price</th>
                            <th>Workshop Price</th>
                            <th>FIFO Cost</th>
                            <th>Profit %</th>
                            <th>Profit R</th>
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

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">
                        <i class="ri-image-line me-2"></i>Product Image
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="position-relative">
                        <img id="modalImage" src="" class="img-fluid rounded" alt="Product Image" style="max-height: 500px;">
                        
                        <!-- Navigation arrows for multiple images -->
                        <button class="btn btn-outline-primary position-absolute top-50 start-0 translate-middle-y ms-3" id="prevImageBtn" onclick="changeImage(-1)" style="display: none;">
                            <i class="ri-arrow-left-line"></i>
                        </button>
                        <button class="btn btn-outline-primary position-absolute top-50 end-0 translate-middle-y me-3" id="nextImageBtn" onclick="changeImage(1)" style="display: none;">
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                    
                    <!-- Image counter -->
                    <div class="mt-2" id="imageCounter" style="display: none;">
                        <span class="badge bg-primary-transparent" id="currentImageNumber">1</span> / <span id="totalImages">1</span>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted mb-0" id="imageModalDescription"></p>
                    </div>
                </div>
            </div>
        </div>
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

{{-- Import Products Modal --}}
<div class="modal fade" id="importProductsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-upload-line me-2"></i>Import Products from Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Select Excel/CSV File</label>
                        <input type="file" class="form-control" id="import_file" name="import_file" 
                               accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Supported formats: Excel (.xlsx, .xls) and CSV (.csv)</small>
                    </div>
                    
                           <div class="alert alert-info">
                               <h6><i class="ri-information-line me-2"></i>Import Format</h6>
                               <p class="mb-2">Your Excel/CSV file should have these columns:</p>
                               <ul class="mb-2">
                                   <li><strong>name</strong> - Product Name (required)</li>
                                   <li><strong>sku</strong> - Product SKU (auto-generated if empty)</li>
                                   <li><strong>supplier_code</strong> - Supplier Code</li>
                                   <li><strong>brand_code</strong> - Brand Code</li>
                                   <li><strong>price_normal</strong> - Normal Price (required)</li>
                                   <li><strong>price_online</strong> - Online Price</li>
                                   <li><strong>price_workshop</strong> - Workshop Price</li>
                                   <li><strong>unit</strong> - Unit (default: PCS)</li>
                                   <li><strong>status</strong> - Status (default: active)</li>
                                   <li><strong>bin_location</strong> - Bin Location</li>
                                   <li><strong>reorder_level</strong> - Reorder Level</li>
                                   <li><strong>notes</strong> - Notes</li>
                               </ul>
                               <div class="d-flex justify-content-between align-items-center">
                                   <small class="text-muted">Download sample file to see the exact format</small>
                                   <a href="{{ route('products.sample-import') }}" class="btn btn-sm btn-outline-primary">
                                       <i class="ri-download-line me-1"></i>Download Sample
                                   </a>
                               </div>
                           </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-upload-line me-1"></i>Import Products
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
