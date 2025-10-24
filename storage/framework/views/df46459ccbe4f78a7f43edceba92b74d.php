<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Receipt Note - <?php echo e($grn->grn_number); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            max-width: 200px;
            max-height: 80px;
            margin-bottom: 10px;
        }

        .company-info h1 {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 5px;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .grn-title {
            text-align: right;
            flex: 1;
        }

        .grn-title h2 {
            font-size: 28px;
            color: #28a745;
            margin-bottom: 10px;
        }

        .grn-title p {
            margin: 3px 0;
            font-size: 13px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .info-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .info-box h3 {
            font-size: 14px;
            color: #28a745;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #28a745;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row strong {
            color: #555;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .items-table thead {
            background-color: #28a745;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e7e34;
        }

        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .items-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .notes-section {
            width: 48%;
        }

        .notes-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .notes-box h4 {
            font-size: 13px;
            color: #28a745;
            margin-bottom: 8px;
        }

        .notes-box p {
            font-size: 11px;
            line-height: 1.5;
        }

        .totals-section {
            width: 48%;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        .totals-table td:first-child {
            text-align: right;
            font-weight: bold;
            width: 60%;
        }

        .totals-table td:last-child {
            text-align: right;
            width: 40%;
        }

        .totals-table .grand-total {
            background-color: #28a745;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .totals-table .grand-total td {
            border-bottom: none;
        }

        .footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid #28a745;
            margin-top: 30px;
        }

        .footer p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-completed {
            background-color: #28a745;
            color: #fff;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <?php if(\App\Models\Setting::get('company_logo')): ?>
                <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" alt="Company Logo" class="company-logo">
            <?php endif; ?>
            <h1><?php echo e(\App\Models\Setting::get('company_name', 'Your Company Name')); ?></h1>
            <p><?php echo e(\App\Models\Setting::get('company_address', '')); ?></p>
            <p>Tel: <?php echo e(\App\Models\Setting::get('company_phone', '')); ?> | Email: <?php echo e(\App\Models\Setting::get('company_email', '')); ?></p>
            <?php if(\App\Models\Setting::get('company_vat_number')): ?>
                <p>VAT Reg: <?php echo e(\App\Models\Setting::get('company_vat_number')); ?></p>
            <?php endif; ?>
        </div>
        <div class="grn-title">
            <h2>GOODS RECEIPT NOTE</h2>
            <p><strong>GRN Number:</strong> <?php echo e($grn->grn_number); ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?php echo e($grn->status); ?>">
                    <?php echo e(strtoupper($grn->status)); ?>

                </span>
            </p>
            <p><strong>Date:</strong> <?php echo e($grn->received_date->format('d M Y')); ?></p>
        </div>
    </div>

    <!-- Supplier & GRN Information -->
    <div class="info-section">
        <div class="info-box">
            <h3>SUPPLIER INFORMATION</h3>
            <div class="info-row">
                <strong>Supplier Name:</strong>
                <span><?php echo e($grn->supplier->name ?? 'N/A'); ?></span>
            </div>
            <div class="info-row">
                <strong>Email:</strong>
                <span><?php echo e($grn->supplier->email ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>Phone:</strong>
                <span><?php echo e($grn->supplier->phone ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>Address:</strong>
                <span><?php echo e($grn->supplier->address ?? '-'); ?></span>
            </div>
        </div>

        <div class="info-box">
            <h3>GRN INFORMATION</h3>
            <div class="info-row">
                <strong>GRN Number:</strong>
                <span><?php echo e($grn->grn_number); ?></span>
            </div>
            <div class="info-row">
                <strong>Received Date:</strong>
                <span><?php echo e($grn->received_date->format('d M Y')); ?></span>
            </div>
            <?php if($grn->purchaseOrder): ?>
            <div class="info-row">
                <strong>Linked PO:</strong>
                <span><?php echo e($grn->purchaseOrder->po_number); ?></span>
            </div>
            <?php endif; ?>
            <?php if($grn->invoice_number): ?>
            <div class="info-row">
                <strong>Invoice Number:</strong>
                <span><?php echo e($grn->invoice_number); ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <strong>Created By:</strong>
                <span><?php echo e($grn->user->name ?? 'N/A'); ?></span>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="40%">Product Description</th>
                <th width="15%" class="text-center">Ordered Qty</th>
                <th width="15%" class="text-center">Received Qty</th>
                <th width="15%" class="text-right">Unit Cost</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $grn->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td>
                    <strong><?php echo e($item->product->name ?? 'Product not found'); ?></strong><br>
                    <small>SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></small>
                </td>
                <td class="text-center"><?php echo e($item->ordered_qty); ?></td>
                <td class="text-center"><strong><?php echo e($item->received_qty); ?></strong></td>
                <td class="text-right">R <?php echo e(number_format($item->unit_cost, 2)); ?></td>
                <td class="text-right"><strong>R <?php echo e(number_format($item->line_total, 2)); ?></strong></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="notes-section">
            <?php if($grn->notes): ?>
            <div class="notes-box">
                <h4>NOTES / SPECIAL INSTRUCTIONS</h4>
                <p><?php echo e($grn->notes); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Total Items:</td>
                    <td><?php echo e($grn->items->count()); ?></td>
                </tr>
                <tr>
                    <td>Total Quantity:</td>
                    <td><?php echo e($grn->items->sum('received_qty')); ?></td>
                </tr>
                <tr class="grand-total">
                    <td>GRAND TOTAL:</td>
                    <td>R <?php echo e(number_format($grn->total_amount ?? 0, 2)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong><?php echo e(\App\Models\Setting::get('company_name', 'Your Company Name')); ?></strong></p>
        <p>This is a system-generated goods receipt note. For queries, please contact us.</p>
        <p>Printed on <?php echo e(now()->format('d M Y H:i')); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\MMP\resources\views/goods_receipts/print.blade.php ENDPATH**/ ?>