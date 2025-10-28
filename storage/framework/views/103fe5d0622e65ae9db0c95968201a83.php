<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?php echo e($purchaseOrder->po_number); ?></title>
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
            border-bottom: 3px solid #007bff;
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
            color: #007bff;
            margin-bottom: 5px;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .po-title {
            text-align: right;
            flex: 1;
        }

        .po-title h2 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .po-title p {
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
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 140px;
            color: #555;
        }

        .info-value {
            flex: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .items-table thead {
            background-color: #007bff;
            color: white;
        }

        .items-table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .items-table th.text-center {
            text-align: center;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #ddd;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .items-table td {
            padding: 10px;
        }

        .items-table td.text-center {
            text-align: center;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .notes-section {
            width: 55%;
        }

        .notes-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            min-height: 120px;
        }

        .notes-box h4 {
            font-size: 14px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .totals-section {
            width: 40%;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table tr {
            border-bottom: 1px solid #eee;
        }

        .totals-table td {
            padding: 8px 5px;
        }

        .totals-table td:first-child {
            text-align: right;
            font-weight: bold;
            color: #555;
        }

        .totals-table td:last-child {
            text-align: right;
            width: 120px;
        }

        .totals-table tr.grand-total {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        .totals-table tr.grand-total td {
            padding: 12px 5px;
            border: none;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-draft {
            background-color: #6c757d;
            color: white;
        }

        .status-sent {
            background-color: #17a2b8;
            color: white;
        }

        .status-partially_received {
            background-color: #ffc107;
            color: #000;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #007bff;
            text-align: center;
            font-size: 10px;
            color: #666;
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
        <div class="po-title">
            <h2>PURCHASE ORDER</h2>
            <p><strong>PO Number:</strong> <?php echo e($purchaseOrder->po_number); ?></p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?php echo e($purchaseOrder->status); ?>">
                    <?php echo e(strtoupper(str_replace('_', ' ', $purchaseOrder->status))); ?>

                </span>
            </p>
            <p><strong>Date:</strong> <?php echo e($purchaseOrder->order_date->format('d M Y')); ?></p>
        </div>
    </div>

    <!-- Supplier & Order Information -->
    <div class="info-section">
        <div class="info-box">
            <h3>SUPPLIER DETAILS</h3>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value"><?php echo e($purchaseOrder->supplier->name ?? 'N/A'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person:</span>
                <span class="info-value"><?php echo e($purchaseOrder->supplier->contact_person ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo e($purchaseOrder->supplier->email ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value"><?php echo e($purchaseOrder->supplier->phone ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value"><?php echo e($purchaseOrder->supplier->address ?? '-'); ?></span>
            </div>
        </div>

        <div class="info-box">
            <h3>ORDER INFORMATION</h3>
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value"><?php echo e($purchaseOrder->order_date->format('d M Y')); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Expected Delivery:</span>
                <span class="info-value">
                    <?php echo e($purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('d M Y') : 'Not specified'); ?>

                </span>
            </div>
            <?php if($purchaseOrder->received_date): ?>
            <div class="info-row">
                <span class="info-label">Received Date:</span>
                <span class="info-value"><?php echo e($purchaseOrder->received_date->format('d M Y')); ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Created By:</span>
                <span class="info-value"><?php echo e($purchaseOrder->user->name ?? 'N/A'); ?></span>
            </div>
            <?php if($purchaseOrder->payment_terms): ?>
            <div class="info-row">
                <span class="info-label">Payment Terms:</span>
                <span class="info-value"><?php echo e($purchaseOrder->payment_terms); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($purchaseOrder->delivery_address): ?>
    <div class="info-box" style="width: 100%; margin-bottom: 25px;">
        <h3>DELIVERY ADDRESS</h3>
        <p><?php echo e($purchaseOrder->delivery_address); ?></p>
    </div>
    <?php endif; ?>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="40%">Product Description</th>
                <th width="15%" class="text-center">Quantity</th>
                <th width="20%" class="text-right">Unit Price</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $purchaseOrder->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td>
                    <strong><?php echo e($item->product->name ?? 'Product not found'); ?></strong><br>
                    <small>SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></small>
                    <?php if($item->product && $item->product->description): ?>
                        <br><small><?php echo e(Str::limit($item->product->description, 80)); ?></small>
                    <?php endif; ?>
                </td>
                <td class="text-center"><?php echo e($item->quantity); ?></td>
                <td class="text-right">R <?php echo e(number_format($item->unit_price, 2)); ?></td>
                <td class="text-right"><strong>R <?php echo e(number_format($item->total, 2)); ?></strong></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="notes-section">
            <?php if($purchaseOrder->notes): ?>
            <div class="notes-box">
                <h4>NOTES / SPECIAL INSTRUCTIONS</h4>
                <p><?php echo e($purchaseOrder->notes); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>R <?php echo e(number_format($purchaseOrder->subtotal, 2)); ?></td>
                </tr>
                <?php if($purchaseOrder->total_discount > 0): ?>
                <tr>
                    <td>Discount:</td>
                    <td>- R <?php echo e(number_format($purchaseOrder->total_discount, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($purchaseOrder->shipping > 0): ?>
                <tr>
                    <td>Shipping:</td>
                    <td>R <?php echo e(number_format($purchaseOrder->shipping, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($purchaseOrder->vat_enabled && $purchaseOrder->vat > 0): ?>
                <tr>
                    <td>VAT (<?php echo e(\App\Models\Setting::get('vat_rate', 15)); ?>%):</td>
                    <td>R <?php echo e(number_format($purchaseOrder->vat, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td>GRAND TOTAL:</td>
                    <td>R <?php echo e(number_format($purchaseOrder->grand_total, 2)); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong><?php echo e(\App\Models\Setting::get('company_name', 'Your Company Name')); ?></strong></p>
        <p>This is a system-generated purchase order. For queries, please contact us.</p>
        <p>Printed on <?php echo e(now()->format('d M Y H:i')); ?></p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/purchase_orders/print.blade.php ENDPATH**/ ?>