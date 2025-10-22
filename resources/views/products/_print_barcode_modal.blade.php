<!-- Single Product Barcode Print Modal -->
<div class="modal fade" id="singleBarcodePrintModal" tabindex="-1" aria-labelledby="singleBarcodePrintModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h5 class="modal-title" id="singleBarcodePrintModalLabel">
                    <i class="ri-barcode-line me-2"></i>Print Barcode
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="singleBarcodePreview" class="d-flex justify-content-center">
                    <!-- Single barcode will be shown here -->
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="printSingleBarcodeBtn">
                    <i class="ri-printer-line me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Barcode Modal -->
<div class="modal fade" id="printBarcodeModal" tabindex="-1" aria-labelledby="printBarcodeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h5 class="modal-title" id="printBarcodeModalLabel">
                    <i class="ri-barcode-line me-2"></i>Print Product Barcodes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search & Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" class="form-control" id="barcodeProductSearch"
                                placeholder="Search products...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="barcodeCategoryFilter">
                            <option value="">All Categories</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="barcodeStatusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Products Selection Table -->
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-striped table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAllProducts" class="form-check-input">
                                </th>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th style="width: 120px;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="barcodeProductsTable">
                            <!-- Products will be loaded via AJAX -->
                            <tr id="loadingRow">
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2">Loading products...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-secondary" id="previewLabelsBtn" onclick="previewLabels()">
                    <i class="ri-eye-line me-1"></i>Preview Labels
                </button>
                <button type="button" class="btn btn-primary" id="printLabelsBtn" onclick="printLabels()">
                    <i class="ri-printer-line me-1"></i>Print Labels
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Label Preview Modal -->
<div class="modal fade" id="labelPreviewModal" tabindex="-1" aria-labelledby="labelPreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info-transparent">
                <h5 class="modal-title" id="labelPreviewModalLabel">
                    <i class="ri-eye-line me-2"></i>Barcode Labels Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="labelsPreviewContent" class="d-flex flex-wrap gap-2">
                    <!-- Labels will be generated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printPreviewLabels()">
                    <i class="ri-printer-line me-1"></i>Print These Labels
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include JsBarcode Library -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
    /* Barcode Label Styles */
    .barcode-label {
        width: 2.5in;
        height: 1.5in;
        border: 1px dashed #999;
        padding: 6px;
        margin: 3px;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-family: Arial, sans-serif;
        font-size: 10px;
        page-break-inside: avoid;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .barcode-label .product-name {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 6px;
        text-align: center;
        line-height: 1.2;
        color: #000;
        text-transform: none;
        letter-spacing: 0px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .barcode-label .product-details {
        text-align: center;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .barcode-label .sku-line {
        font-size: 10px;
        color: #000;
        margin-bottom: 1px;
    }

    .barcode-label .oe-line {
        font-size: 10px;
        color: #000;
    }

    .barcode-label .barcode-container {
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: white;
        padding: 2px;
        min-height: 45px;
    }

    .barcode-label .barcode-container svg {
        width: 120px !important;
        height: 35px !important;
        max-width: 120px;
    }

    .barcode-label .barcode-text {
        font-size: 10px;
        margin-top: 2px;
        text-align: center;
        font-family: Arial, sans-serif;
        font-weight: normal;
        color: #000;
        letter-spacing: 0px;
    }

    /* Preview Modal Styles */
    #labelsPreviewContent {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        min-height: 500px;
        max-height: 700px;
        overflow-y: auto;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 8px;
    }

    /* Modal styling improvements */
    .modal-xl {
        max-width: 95%;
    }

    .modal-body {
        padding-top: 58px;
        padding-bottom: 60px;
    }

    @media print {
        .barcode-label {
            width: 2.9in;
            height: 1.9in;
            margin: 0.05in !important;
            border: 1px dashed #000 !important;
            box-shadow: none !important;
            padding: 4px !important;
        }

        .barcode-label .product-name {
            font-size: 11px !important;
            height: 18px !important;
        }

        .barcode-label .sku-line,
        .barcode-label .oe-line {
            font-size: 9px !important;
        }

        .barcode-label .barcode-text {
            font-size: 9px !important;
        }

        .barcode-label .barcode-container {
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .barcode-label .barcode-container svg {
            width: 110px !important;
            height: 32px !important;
        }

        .no-print {
            display: none !important;

        }
    }
</style>
