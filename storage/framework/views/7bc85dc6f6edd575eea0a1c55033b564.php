<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo e($invoice->invoice_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            page-break-inside: avoid;
        }
        .header {
            border-bottom: 3px solid #007bff;
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
            color: #007bff;
            margin-bottom: 5px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .invoice-details {
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
            border: none;
        }
        .invoice-details td {
            vertical-align: top;
            padding: 0;
            border: none;
        }
        .invoice-info {
            width: 50%;
        }
        .customer-info {
            width: 50%;
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        .info-value {
            color: #666;
            margin-bottom: 8px;
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
        .vehicle-details {
            background: #e3f2fd;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #1976d2;
            font-size: 11px;
            line-height: 1.8;
        }
        .vehicle-details h4 {
            display: inline;
            margin: 0;
            margin-right: 10px;
            color: #1976d2;
        }
        .vehicle-details span {
            display: inline;
            line-height: 1.8;
        }
        .totals-section {
            margin-top: 30px;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            border: none;
        }
        .totals-table .label {
            font-weight: bold;
            text-align: right;
        }
        .totals-table .amount {
            text-align: right;
            color: #333;
        }
        .grand-total {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .vehicle-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background-color: #ffc107; color: #000; }
        .status-posted { background-color: #17a2b8; color: white; }
        .status-paid { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        
        .no-print {
            display: none !important;
        }
        
        .page-break {
            page-break-before: always;
            break-before: page;
            display: block;
            height: 0;
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice-container { 
                box-shadow: none;
                page-break-after: auto;
            }
            .no-print { display: none !important; }
            .page-break { 
                page-break-before: always !important;
                break-before: page !important;
                display: block !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
        
        /* Terms Page Styles - Compact for single page */
        .terms-page {
            margin-top: 0;
            padding-top: 20px;
        }
        
        .terms-header {
            text-align: center;
            background: #e3f2fd;
            padding: 8px;
            border-radius: 3px;
            margin-bottom: 10px;
            border-left: 3px solid #007bff;
        }
        
        .terms-title {
            margin: 0 0 4px 0;
            color: #007bff;
            font-size: 11px;
            font-weight: bold;
        }
        
        .terms-notice {
            margin: 3px 0;
            font-size: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .terms-content {
            font-size: 7.5px;
            line-height: 1.3;
            color: #333;
        }
        
        .terms-content ol {
            margin: 5px 0;
            padding-left: 18px;
        }
        
        .terms-content li {
            margin-bottom: 3px;
        }
        
        .agreement-section {
            margin-top: 10px;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 3px;
            border-top: 2px solid #007bff;
        }
        
        .agreement-text {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            color: #007bff;
        }
        
        .signature-grid {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
        }
        
        .signature-label {
            font-size: 7px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #007bff;
        }
        
        .signature-underline {
            border-bottom: 1px solid #007bff;
            height: 20px;
            margin: 3px 8px 0 8px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <?php if(\App\Models\Setting::get('company_logo')): ?>
                    <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" alt="<?php echo e(\App\Models\Setting::companyName()); ?>" style="max-height: 80px; max-width: 250px; object-fit: contain; margin-bottom: 10px;">
                <?php endif; ?>
                <div class="company-name"><?php echo e(\App\Models\Setting::companyName()); ?></div>
                <div class="company-details">
                    <?php if(\App\Models\Setting::companyAddress()): ?>
                        <?php echo e(\App\Models\Setting::companyAddress()); ?><br>
                    <?php endif; ?>
                    <?php if(\App\Models\Setting::companyEmail() || \App\Models\Setting::companyPhone()): ?>
                        Email: <?php echo e(\App\Models\Setting::companyEmail() ?? 'N/A'); ?> | Phone: <?php echo e(\App\Models\Setting::companyPhone() ?? 'N/A'); ?>

                    <?php endif; ?>
                </div>
            </div>
            
            <div class="invoice-details">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td class="invoice-info" style="vertical-align: top; width: 50%;">
                    <div class="info-label">Invoice Number:</div>
                    <div class="info-value"><?php echo e($invoice->invoice_number); ?></div>
                    <div class="info-label">Date:</div>
                    <div class="info-value"><?php echo e($invoice->created_at->format('d/m/Y')); ?></div>
                    <div class="info-label">Created by:</div>
                    <div class="info-value"><?php echo e($invoice->user->name); ?></div>
                    <?php if($invoice->quote_id): ?>
                    <div class="info-label">From Quote:</div>
                    <div class="info-value"><?php echo e($invoice->quote->quote_number); ?></div>
                    <?php endif; ?>
                        </td>
                
                        <td class="customer-info" style="vertical-align: top; width: 50%; text-align: right;">
                    <div class="info-label">Bill To:</div>
                    <div class="info-value">
                        <?php if($invoice->customer): ?>
                            <strong><?php echo e($invoice->customer->name); ?></strong><br>
                            <?php if($invoice->customer->email): ?><?php echo e($invoice->customer->email); ?><br><?php endif; ?>
                            <?php if($invoice->customer->phone): ?><?php echo e($invoice->customer->phone); ?><br><?php endif; ?>
                            <?php if($invoice->customer->address): ?><?php echo e($invoice->customer->address); ?><?php endif; ?>
                        <?php else: ?>
                            <strong><?php echo e($invoice->customer_name ?? 'Cash Sale'); ?></strong><br>
                            <?php if($invoice->customer_email): ?><?php echo e($invoice->customer_email); ?><br><?php endif; ?>
                            <?php if($invoice->customer_phone): ?><?php echo e($invoice->customer_phone); ?><?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo e($invoice->payment_status); ?>">
                            <?php echo e(ucfirst($invoice->payment_status)); ?>

                        </span>
                    </div>
                            <div class="info-label">Payment Method:</div>
                            <div class="info-value">
                                <?php switch($invoice->payment_method):
                                    case ('cash'): ?>
                                        Cash
                                        <?php break; ?>
                                    <?php case ('card'): ?>
                                        Card
                                        <?php break; ?>
                                    <?php case ('eft'): ?>
                                        EFT
                                        <?php break; ?>
                                    <?php case ('credit'): ?>
                                        Credit
                                        <?php break; ?>
                                    <?php default: ?>
                                        <?php echo e(ucfirst($invoice->payment_method)); ?>

                                <?php endswitch; ?>
                </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Vehicle Details -->
        <?php if($invoice->vehicle_make || $invoice->vehicle_model || $invoice->vehicle_vin || $invoice->vehicle_reg): ?>
        <div class="vehicle-details">
            <h4 style="margin-top: 0; color: #1976d2; display: inline; margin-right: 15px;">Vehicle Details:</h4>
            <span style="font-size: 11px; color: #555;">
                <?php if($invoice->vehicle_make): ?><strong>Make:</strong> <?php echo e($invoice->vehicle_make); ?> &nbsp;&nbsp;|&nbsp;&nbsp; <?php endif; ?>
                <?php if($invoice->vehicle_model): ?><strong>Model:</strong> <?php echo e($invoice->vehicle_model); ?> &nbsp;&nbsp;|&nbsp;&nbsp; <?php endif; ?>
                <?php if($invoice->vehicle_reg): ?><strong>Registration:</strong> <?php echo e($invoice->vehicle_reg); ?> &nbsp;&nbsp;|&nbsp;&nbsp; <?php endif; ?>
                <?php if($invoice->vehicle_vin): ?><strong>VIN:</strong> <?php echo e($invoice->vehicle_vin); ?> &nbsp;&nbsp;|&nbsp;&nbsp; <?php endif; ?>
                <?php if($invoice->vehicle_mileage): ?><strong>Mileage:</strong> <?php echo e(number_format($invoice->vehicle_mileage)); ?> km <?php endif; ?>
            </span>
        </div>
        <?php endif; ?>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-center">Disc %</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->product_sku); ?></td>
                    <td><?php echo e($item->product_name); ?></td>
                    <td class="text-center"><?php echo e(number_format($item->quantity, 0)); ?></td>
                    <td class="text-right">R <?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="text-center"><?php echo e($item->discount_percentage > 0 ? number_format($item->discount_percentage, 1) . '%' : '-'); ?></td>
                    <td class="text-right"><?php echo e($item->discount_amount > 0 ? 'R ' . number_format($item->discount_amount, 2) : '-'); ?></td>
                    <td class="text-right"><strong>R <?php echo e(number_format($item->line_total, 2)); ?></strong></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">R <?php echo e(number_format($invoice->subtotal, 2)); ?></td>
                </tr>
                <?php if($invoice->discount_amount > 0): ?>
                <tr>
                    <td class="label">Total Discount:</td>
                    <td class="amount">-R <?php echo e(number_format($invoice->discount_amount, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($invoice->shipping > 0): ?>
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">R <?php echo e(number_format($invoice->shipping, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($invoice->vat_amount > 0): ?>
                <tr>
                    <td class="label">VAT (<?php echo e($invoice->vat_rate); ?>%):</td>
                    <td class="amount">R <?php echo e(number_format($invoice->vat_amount, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R <?php echo e(number_format($invoice->grand_total, 2)); ?></td>
                </tr>
                <tr>
                    <td class="label">Amount Paid:</td>
                    <td class="amount">R <?php echo e(number_format($invoice->amount_paid, 2)); ?></td>
                </tr>
                <tr>
                    <td class="label">Balance Due:</td>
                    <td class="amount <?php echo e($invoice->balance_due > 0 ? 'text-danger' : 'text-success'); ?>">
                        R <?php echo e(number_format($invoice->balance_due, 2)); ?>

                    </td>
                </tr>
            </table>
        </div>

        <!-- Banking Details -->
        <?php if(\App\Models\Setting::showBankOnInvoices()): ?>
        <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">Banking Details:</h4>
            <div style="font-size: 14px; color: #856404;">
                <strong>Bank:</strong> <?php echo e(\App\Models\Setting::bankName()); ?><br>
                <strong>Account Name:</strong> <?php echo e(\App\Models\Setting::bankAccountName()); ?><br>
                <strong>Account Type:</strong> <?php echo e(\App\Models\Setting::bankAccountType()); ?><br>
                <strong>Account Number:</strong> <?php echo e(\App\Models\Setting::bankAccountNumber()); ?><br>
                <strong>Branch Code:</strong> <?php echo e(\App\Models\Setting::bankBranchCode()); ?><br>
                <strong>Reference:</strong> <?php echo e(\App\Models\Setting::bankReference()); ?>

            </div>
        </div>
        <?php endif; ?>

        <!-- Notes -->
        

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated on <?php echo e(now()->format('d/m/Y H:i:s')); ?> by MMP Auto-Meister POS System</p>
            <?php if($invoice->reference): ?>
            <p><strong>Reference:</strong> <?php echo e($invoice->reference); ?></p>
            <?php endif; ?>
            <p style="font-weight: bold; margin-top: 10px;">Page 1/2</p>
        </div>
        </div>

    <!-- Page Break for Terms & Conditions -->
        <div class="page-break"></div>
        
    <!-- Page 2: Terms & Conditions -->
    <div class="invoice-container">
        <div class="terms-page">
            <!-- Compact Header for Page 2 -->
            <div style="text-align: center; margin-bottom: 8px; padding-bottom: 5px; border-bottom: 2px solid #007bff;">
                <?php if(\App\Models\Setting::get('company_logo')): ?>
                    <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" alt="<?php echo e(\App\Models\Setting::companyName()); ?>" style="max-height: 35px; margin-bottom: 3px;">
                <?php else: ?>
                    <h4 style="margin: 0; color: #007bff; font-size: 14px;"><?php echo e(\App\Models\Setting::companyName()); ?></h4>
                <?php endif; ?>
                <p style="margin: 0; font-size: 8px; color: #666;"><?php echo e($invoice->invoice_number); ?> | <?php echo e(now()->format('d/m/Y')); ?></p>
            </div>
            
            <!-- Terms & Conditions -->
            <div class="terms-header">
                <div class="terms-title">DELIVERY NOTE/ TERMS & CONDITIONS/ PURCHASE AGREEMENT</div>
                <div class="terms-notice">
                UPON SIGNING THIS DOCUMENT, IT MEANS THAT THE CLIENT HAS READ AND AGREED TO ALL THE STIPULATED POINTS MENTIONED BELOW:
                </div>
            </div>
            
            <div class="terms-content">
                <ol>
                    <li>THE PURCHASER CONFIRMS THAT HE/SHE HAS EXAMINED AND/OR INSPECTED THE PARTS BEFORE PURCHASING AND SUPPLIED TO HIM/HER.</li>
                    <li>PARTS CORRECTLY SUPPLIED ARE NOT RETURNABLE/ REFUNDABLE. NO EXCEPTIONS.</li>
                    <li>INCORRECTLY SUPPLIED OR FAULTY PARTS MUST BE RETURNED TO THE SUPPLIER WITHIN 3 WORKING DAYS OF PURCHASE UNLESS OTHERWISE ARRANGED IN WRITING WITH MANAGEMENT.</li>
                    <li>PLEASE ALLOW UP TO 24 HOURS TO PROCESS EXCHANGE/RETURN PARTS.</li>
                    <li>THERE WILL BE NO RETURN/EXCHANGE OR CREDIT ON AN INCORRECTLY SUPPLIED PART IF:
                        <br>a. PURCHASER FAILS TO RETURN THE PART WITHIN 3 DAYS OF PURCHASE
                        <br>b. PURCHASER ACQUIRES THE PART ELSEWHERE WITHOUT MAKING PRIOR ARRANGEMENTS WITH THE SUPPLIER.
                        <br>c. THE PART HAS BEEN PARTIALLY OR ENTIRELY DISASSEMBLED, PHYSICALLY ALTERED, INSTALLED/AFFIXED/ATTACHED/JOINED/ADDED TO/BLENDED OR COMBINED WITHIN OTHER GOODS OR PROPERTY, TAMPERED WITH, MODIFIED, STRIPPED OR OPENED IN ANY WAY. NO DAMAGE REPORT HAS BEEN FILLED OUT.
                    </li>
                    <li>THERE IS NO GUARANTEE OR EXCHANGE OF ELECTRICAL PARTS WHATSOEVER.</li>
                    <li>DO NOT USE SILICONE ON THE ELECTRIC WATER PUMPS!</li>
                    <li>WATER PUMPS ARE AN ELECTRICAL ITEM AND THUS DO NOT CARRY ANY WARRANTY!</li>
                    <li>PARTS WILL ONLY BE EXCHANGED ON PRESENTATION OF THE ORIGINAL INVOICE SIGNED BY THE PURCHASER.</li>
                    <li>CREDIT NOTES ARE VALID FOR 6 MONTHS AND ARE NOT TRANSFERABLE, GOODS WILL ONLY BE SUPPLIED ON CREDIT, ON PRESENTATION OF THE ORIGINAL CREDIT NOTE.</li>
                    <li>NO CUT SECTIONS WILL BE TAKEN BACK BY THE SUPPLIER. SECTIONS MUST BE PAID FOR IN FULL BEFORE THE SUPPLIER WILL BEGIN CUTTING AND MUST BE MARKED OUT BY THE CLIENT WITH THE SALESMAN.</li>
                    <li>NO VEHICLE OR SHELLS WILL BE TAKEN BACK BY THE SUPPLIER. 50% DEPOSIT MUST BE PAID FOR IN FULL BEFORE THE SUPPLIER WILL BEGIN STRIPPING AND BALANCE PAID AND RELEVANT DOCUMENTATION PROVIDED BEFORE THE SHELL/VEHICLE IS RELEASED.</li>
                    <li>ENGINES AND/OR GEARBOX WARRANTYS ARE ONLY VALID IF THE PURCHASER HAS SIGNED AN ENGINE/GEARBOX CONTRACT FORM WITH THE SUPPLIER.</li>
                    <li>INTERNAL SECONDHAND ENGINE PARTS (INJECTORS, PISTONS, CAMSHAFTS, OIL PUMPS.CRANKSHAFT ETC) CARRY NO GUARANTEE OR WARRANTY UNLESS STATED OTHERWISE IN WRITING.</li>
                    <li>THE SUPPLIER IS NOT LIABLE FOR ANY COURIER/TRANSPORT/LABOUR/ENGINEERING OR MECHANICAL OR RECURRING COSTS INCURRED SHOULD THE PART BE EXCHANGED.</li>
                    <li>THE RISK IN AND TO ANY PARTS SHALL BE PASSED ONTO THE PURCHASER ON THE DATE OF DELIVERY/PURCHASE. THE SUPPLIER IS FURTHER NOT LIABLE FOR ANY DAMAGES OR LOSS INCURRED BY THE COURIER COMPANY FOR GOODS IN TRANSIT AND INSURANCE OF SUCH ITEMS IS AT THE DISCRETION OF THE PURCHASER.</li>
                    <li>A DEPOSIT SALE IS A BINDING CONTRACT BETWEEN BOTH THE SUPPLIER AND THE CLIENT. A DEPOSIT SALE IS TO BE PAID IN FULL WITHIN 30 DAYS UNLESS OTHERWISE STIPULATED. SHOULD THE BALANCE OF THE DEPOSIT NOT BE PAID WITHIN THE PRESCRIBED PERIOD, THERE WILL BE NO REFUND OR CREDIT NOTE ISSUED AND THE DEPOSIT WILL BE FORFEITED. SHOULD THE CLIENT WISH TO CANCEL THE CONTRACT WITHIN THE 30-DAY PERIOD AND ONLY IF SUCH IS AGREED BY THE MANAGEMENT, THE CLIENT WILL BE CHARGED A 20% HANDLING FEE OF THE FULL PRICE OF THE CONTRACT AND THE REFUND OF THE AMOUNT LESS 20% WILL ONLY BE MADE ONCE THE SUPPLIER HAS RESOLD THE PART OR VEHICLE.</li>
                    <li>NO LIABILITY WHATSOEVER SHALL ARISE FURTHERMORE ON THE PART OF THE SUPPLIER FROM ANY REPRESENTATION(S) MADE OR ALLEGED TO HAVE BEEN MADE AT ANY TIME IN RESPECT OF PARTS SUPPLIED BY THE SUPPLIER AND ITS REPRESENTATIVES TO THE PURCHASER.</li>
                    <li>GOODS REMAIN ON PROPERTY UNTIL PAID IN FULL</li>
                    <li>NO REFUNDS ARE GIVEN ONLY CREDIT, UNLESS STIPULATED OTHERWISE BY THE SUPPLIER IN WRITING.</li>
                    <li>USED PARTS SALES: USED PARTS ARE SOLD "AS IS" AND ARE NOT RETURNABLE, EXCHANGEABLE, OR WARRANTED, UNLESS OTHERWISE STIPULATED IN WRITING BY THE SELLER. NO WARRANTY IS GIVEN ON USED PARTS, UNLESS EXPLICITLY STATED IN WRITING PRIOR TO SALE.</li>
                    <li>THERE WILL BE A HANDLING FEE OF 5% FOR PARTS SUPPLIED CORRECTLY THAT ARE RETURNED/EXCHANGED DUE TO INCORRECT DIAGNOSIS. THIS STRICTLY EXCLUDES ALL ELECTRICAL PARTS.</li>
                </ol>
            </div>
                
            <!-- Agreement Section -->
            <div class="agreement-section">
                <div class="agreement-text">
                        I, ______________________________________ (CLIENT NAME), UNDERSTAND AND AGREE TO THE ABOVE TERMS AND CONDITIONS.
                        </div>
                <div class="signature-grid">
                    <div class="signature-box">
                        <div class="signature-underline"></div>
                        <div class="signature-label">CLIENT'S SIGNATURE</div>
                        </div>
                    <div class="signature-box">
                        <div class="signature-underline"></div>
                        <div class="signature-label">INVOICE NUMBER</div>
                        </div>
                    <div class="signature-box">
                        <div class="signature-underline"></div>
                        <div class="signature-label">DATE</div>
                    </div>
                </div>
            </div>
            
            <!-- Page 2 Footer -->
            <div class="footer" style="margin-top: 15px; padding-top: 8px; border-top: 1px solid #ddd;">
                <p style="font-size: 9px; margin: 3px 0;"><strong>Thank you for your business!</strong></p>
                <p style="font-size: 7px; margin: 3px 0;">Generated: <?php echo e(now()->format('d/m/Y H:i')); ?> | Page 2/2</p>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Invoice
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>

    <script>
        // Auto-print on page load (only if opened directly, not in iframe)
        window.addEventListener('load', function() {
            // Check if page is loaded in iframe
            const isInIframe = window.self !== window.top;
            
            if (!isInIframe) {
                // Only auto-print if opened directly in new tab
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\MMP\resources\views/invoices/print.blade.php ENDPATH**/ ?>