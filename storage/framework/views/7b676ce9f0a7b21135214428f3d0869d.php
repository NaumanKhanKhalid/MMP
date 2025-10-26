<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Card <?php echo e($jobCard->job_card_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .job-card-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .job-card-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .job-card-info {
            flex: 1;
        }
        .customer-info {
            flex: 1;
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
            margin-bottom: 5px;
        }
        .section-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table .label {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        .totals-table .amount {
            text-align: right;
            color: #666;
        }
        .totals-table .grand-total {
            background-color: #0d6efd;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .totals-table .grand-total td {
            border-bottom: none;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-booked { background-color: #17a2b8; color: #fff; }
        .status-in_progress { background-color: #0d6efd; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        
        @media print {
            body { margin: 0; padding: 0; }
            .job-card-container { box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="job-card-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">MMP Auto-Meister</div>
                <div class="company-details">
                    Auto Parts & Workshop Services<br>
                    Professional Vehicle Maintenance<br>
                    Email: info@mmpautomeister.co.za | Phone: +27 (0)11 123 4567
                </div>
            </div>
            
            <div class="job-card-title">Workshop Job Card</div>
            
            <div class="details-section">
                <div class="job-card-info">
                    <div class="info-label">Job Card Number:</div>
                    <div class="info-value"><?php echo e($jobCard->job_card_number); ?></div>
                    <div class="info-label">Date:</div>
                    <div class="info-value"><?php echo e($jobCard->created_at->format('d/m/Y H:i A')); ?></div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo e($jobCard->status); ?>">
                            <?php echo e($jobCard->status_text); ?>

                        </span>
                    </div>
                    <?php if($jobCard->booked_at): ?>
                    <div class="info-label">Booked In:</div>
                    <div class="info-value"><?php echo e($jobCard->booked_at->format('d/m/Y H:i A')); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->completed_at): ?>
                    <div class="info-label">Completed:</div>
                    <div class="info-value"><?php echo e($jobCard->completed_at->format('d/m/Y H:i A')); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Customer:</div>
                    <div class="info-value"><?php echo e($jobCard->customer_name); ?></div>
                    <?php if($jobCard->customer_phone): ?>
                    <div class="info-value"><?php echo e($jobCard->customer_phone); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->customer_email): ?>
                    <div class="info-value"><?php echo e($jobCard->customer_email); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="section-box">
            <h6 style="margin-top:0; margin-bottom:10px; color:#0d6efd;">Vehicle Information</h6>
            <div class="row" style="display: flex;">
                <div style="flex: 1;">
                    <?php if($jobCard->vehicle_make || $jobCard->vehicle_model): ?>
                    <div class="info-label">Make & Model:</div>
                    <div class="info-value"><?php echo e($jobCard->vehicle_make); ?> <?php echo e($jobCard->vehicle_model); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->vehicle_year): ?>
                    <div class="info-label">Year:</div>
                    <div class="info-value"><?php echo e($jobCard->vehicle_year); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->engine_code): ?>
                    <div class="info-label">Engine Code:</div>
                    <div class="info-value"><?php echo e($jobCard->engine_code); ?></div>
                    <?php endif; ?>
                </div>
                <div style="flex: 1;">
                    <?php if($jobCard->vehicle_registration): ?>
                    <div class="info-label">Registration:</div>
                    <div class="info-value"><?php echo e($jobCard->vehicle_registration); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->vehicle_vin): ?>
                    <div class="info-label">VIN:</div>
                    <div class="info-value"><?php echo e($jobCard->vehicle_vin); ?></div>
                    <?php endif; ?>
                    <?php if($jobCard->vehicle_mileage): ?>
                    <div class="info-label">Mileage:</div>
                    <div class="info-value"><?php echo e($jobCard->vehicle_mileage); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Job Description -->
        <div class="section-box">
            <h6 style="margin-top:0; margin-bottom:10px; color:#0d6efd;">Job Description</h6>
            <p style="margin:0;"><?php echo e($jobCard->job_description); ?></p>
            
            <?php if($jobCard->customer_complaint): ?>
            <hr style="margin: 10px 0;">
            <div class="info-label">Customer Complaint:</div>
            <p style="margin:5px 0;"><?php echo e($jobCard->customer_complaint); ?></p>
            <?php endif; ?>
            
            <?php if($jobCard->notes): ?>
            <hr style="margin: 10px 0;">
            <div class="info-label">Internal Notes:</div>
            <p style="margin:5px 0;"><?php echo e($jobCard->notes); ?></p>
            <?php endif; ?>
        </div>

        <!-- Parts Used -->
        <?php if($jobCard->items->count() > 0): ?>
        <h6 style="margin-top: 20px; margin-bottom: 10px;">Parts Used</h6>
        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $jobCard->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->product_sku); ?></td>
                    <td><?php echo e($item->product_name); ?></td>
                    <td class="text-center"><?php echo e(number_format($item->quantity_used, 2)); ?></td>
                    <td class="text-right">R <?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="text-right"><strong>R <?php echo e(number_format($item->line_total, 2)); ?></strong></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

        <!-- Labour -->
        <?php if($jobCard->labour->count() > 0): ?>
        <h6 style="margin-top: 20px; margin-bottom: 10px;">Labour</h6>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Type</th>
                    <th class="text-center">Hours</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $jobCard->labour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $labour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($labour->labour_description); ?></td>
                    <td><?php echo e(ucfirst($labour->labour_type)); ?></td>
                    <td class="text-center"><?php echo e(number_format($labour->hours_worked, 2)); ?></td>
                    <td class="text-right">R <?php echo e(number_format($labour->hourly_rate, 2)); ?>/hr</td>
                    <td class="text-right"><strong>R <?php echo e(number_format($labour->total_amount, 2)); ?></strong></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Parts Total:</td>
                    <td class="amount">R <?php echo e(number_format($jobCard->parts_total, 2)); ?></td>
                </tr>
                <tr>
                    <td class="label">Labour Total:</td>
                    <td class="amount">R <?php echo e(number_format($jobCard->labour_total, 2)); ?></td>
                </tr>
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R <?php echo e(number_format($jobCard->grand_total, 2)); ?></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>MMP Auto-Meister Workshop Services</strong></p>
            <p>Thank you for choosing us for your vehicle maintenance!</p>
            <?php if($jobCard->final_invoice_id): ?>
            <p style="margin-top: 15px; color: #28a745; font-weight: bold;">
                ✓ Converted to Invoice: <?php echo e($jobCard->finalInvoice->invoice_number); ?>

            </p>
            <?php endif; ?>
            <p style="margin-top: 20px; font-size: 10px;">
                Generated on <?php echo e(now()->format('d/m/Y H:i:s')); ?> | Job Card: <?php echo e($jobCard->job_card_number); ?>

            </p>
        </div>
    </div>
</body>
</html>


<?php /**PATH C:\xampp\htdocs\MMP\resources\views/job-cards/pdf.blade.php ENDPATH**/ ?>