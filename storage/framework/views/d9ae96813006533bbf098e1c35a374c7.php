<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Picking List - <?php echo e($invoice->invoice_number); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Header Section - Matching Invoice */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #007bff;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-logo {
            max-height: 60px;
            max-width: 250px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .contact-info {
            font-size: 11px;
            color: #888;
        }
        
        .no-print {
            display: none !important;
        }
        
        /* Main Title */
        .main-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin: 15px 0;
            text-align: center;
            letter-spacing: 1px;
        }
        
        /* Details Sections */
        .details-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .details-grid > div {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .detail-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #007bff;
        }
        
        .section-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            font-size: 13px;
        }
        
        .detail-row {
            margin-bottom: 8px;
            font-size: 12px;
            color: #333;
        }
        
        
        /* Table Styling - Matching Invoice */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #333;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
            color: #333;
        }
        
        tbody tr {
            background: #fff;
        }
        
        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .checkbox-cell {
            text-align: center;
            width: 40px;
        }
        
        .checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #007bff;
            display: inline-block;
            border-radius: 3px;
        }
        
        .number-cell {
            text-align: center;
            width: 50px;
            font-weight: bold;
            color: #007bff;
        }
        
        .product-name {
            font-weight: 600;
            color: #333;
            font-size: 12px;
        }
        
        .product-brand {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
            font-style: italic;
        }
        
        .barcode-cell {
            text-align: center;
        }
        
        .barcode-svg {
            max-width: 100%;
            height: auto;
        }
        
        /* Picking Instructions */
        .instructions-box {
            background: #e3f2fd;
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .instructions-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 12px;
            font-size: 13px;
        }
        
        .instructions-list {
            list-style: none;
            padding: 0;
        }
        
        .instructions-list li {
            padding-left: 20px;
            margin-bottom: 8px;
            position: relative;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
        }
        
        .instructions-list li:before {
            content: "•";
            color: #007bff;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 16px;
        }
        
        /* Signatures */
        .signatures {
            display: table;
            width: 100%;
            margin: 30px 0 20px 0;
        }
        
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 2px solid #007bff;
            padding-top: 8px;
            margin-top: 50px;
            font-size: 11px;
            color: #007bff;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #007bff;
        }
        
        .footer-line {
            margin-bottom: 5px;
        }
        
        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .container {
                max-width: 100%;
                box-shadow: none;
                padding: 20px;
            }
            
            .no-print {
                display: none !important;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
    <div class="header">
            <?php if(\App\Models\Setting::get('company_logo')): ?>
                <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" alt="Company Logo" class="company-logo">
            <?php else: ?>
                <div class="company-name"><?php echo e(\App\Models\Setting::companyName()); ?></div>
            <?php endif; ?>
            <div class="subtitle">Picking List - Warehouse Operations</div>
            <div class="contact-info">
                <?php if(\App\Models\Setting::companyPhone()): ?>
                    Phone: <?php echo e(\App\Models\Setting::companyPhone()); ?>

                <?php endif; ?>
                <?php if(\App\Models\Setting::companyEmail()): ?>
                     | Email: <?php echo e(\App\Models\Setting::companyEmail()); ?>

                <?php endif; ?>
            </div>
    </div>

        <!-- Main Title -->
        <div class="main-title">PICKING LIST</div>
        
        <!-- Order and Customer Details -->
        <div class="details-grid">
            <!-- Order Details -->
            <div class="detail-section">
                <div class="section-title">Order Details</div>
                <div class="detail-row">Invoice #: <?php echo e($invoice->invoice_number); ?></div>
                <div class="detail-row">Date: <?php echo e($invoice->created_at->format('d/m/Y')); ?></div>
                <div class="detail-row">Time: <?php echo e($invoice->created_at->format('H:i A')); ?></div>
        </div>
            
            <!-- Customer Details -->
            <div class="detail-section">
                <div class="section-title">Customer Details</div>
                <div class="detail-row">
                    <?php if($invoice->customer): ?>
                        <?php echo e($invoice->customer->name); ?>

                    <?php elseif($invoice->customer_name): ?>
                        <?php echo e($invoice->customer_name); ?>

                    <?php else: ?>
                        Walk-in Customer
        <?php endif; ?>
                </div>
            </div>
    </div>

        <!-- Products Table -->
    <table>
        <thead>
            <tr>
                    <th class="checkbox-cell">#</th>
                    <th>Product Description</th>
                    <th style="width: 80px;">SKU</th>
                    <th style="width: 100px;">Barcode</th>
                    <th style="width: 80px; text-align: center;">Qty</th>
                    <th style="width: 90px; text-align: center;">Location</th>
                    <th style="width: 60px; text-align: center;">Picked</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                    <td class="number-cell"><?php echo e($index + 1); ?></td>
                    <td>
                        <div class="product-name"><?php echo e($item->product_name); ?></div>
                        <?php if($item->product && $item->product->brand): ?>
                            <div class="product-brand">Brand: <?php echo e($item->product->brand->name); ?></div>
                    <?php endif; ?>
                </td>
                <td><?php echo e($item->product_sku); ?></td>
                    <td class="barcode-cell">
                        <?php
                            $barcodeValue = $item->product->barcode ?? 'MMP' . str_pad($item->product_sku, 4, '0', STR_PAD_LEFT);
                        ?>
                        <svg class="barcode-svg barcode" data-barcode="<?php echo e($barcodeValue); ?>"></svg>
                        <div style="font-size: 9px; margin-top: 2px;"><?php echo e($barcodeValue); ?></div>
                    </td>
                    <td style="text-align: center; font-weight: bold;"><?php echo e(number_format($item->quantity, 3)); ?></td>
                    <td style="text-align: center; font-weight: bold;"><?php echo e($item->product->bin_location ?? 'N/A'); ?></td>
                    <td class="checkbox-cell"><span class="checkbox"></span></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

        <!-- Picking Instructions -->
        <div class="instructions-box">
            <div class="instructions-title">Picking Instructions</div>
            <ul class="instructions-list">
                <li>Check each item against the SKU and barcode before picking</li>
                <li>Verify quantity matches the order exactly</li>
                <li>Check for any damage or defects before packing</li>
                <li>Place items in designated packing area after picking</li>
                <li>Mark each item as picked in the "Picked" column</li>
                <li>Report any discrepancies immediately to supervisor</li>
                <li>Ensure all items are properly packaged for delivery</li>
            </ul>
        </div>
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line">Picker Signature</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">Supervisor Signature</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-line">
                <strong>Total Items to Pick:</strong> <?php echo e($invoice->items->sum('quantity')); ?> items
            </div>
            <div class="footer-line">
                <strong>Generated on:</strong> <?php echo e(now()->format('d/m/Y H:i A')); ?>

            </div>
            <div class="footer-line">
                <strong>Picking List ID:</strong> PL-<?php echo e($invoice->id); ?>-<?php echo e($invoice->created_at->format('Ymd')); ?>

            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Picking List
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>

    <script>
        // Generate barcodes on page load
        window.addEventListener('load', function() {
            // Generate all barcodes
            const barcodes = document.querySelectorAll('.barcode');
            barcodes.forEach(function(barcode) {
                const value = barcode.getAttribute('data-barcode');
                if (value) {
                    try {
                        JsBarcode(barcode, value, {
                            format: 'CODE128',
                            width: 1.5,
                            height: 40,
                            displayValue: false,
                            margin: 2,
                            fontSize: 10,
                            background: '#ffffff',
                            lineColor: '#000000'
                        });
                    } catch (e) {
                        console.error('Barcode generation error:', e);
                    }
                }
            });
            
            // Check if page is loaded in iframe
            const isInIframe = window.self !== window.top;
            
            if (!isInIframe) {
                // Only auto-print if opened directly in new tab
                setTimeout(function() {
                    window.print();
                }, 1000); // Increased delay to ensure barcodes are generated
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/invoices/picking-list.blade.php ENDPATH**/ ?>