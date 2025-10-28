<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation <?php echo e($quote->quote_number); ?></title>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 8px;
            background: white;
            font-size: 11px;
            line-height: 1.3;
        }
        .quotation-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        .company-logo {
            max-height: 60px;
            max-width: 150px;
            object-fit: contain;
        }
        .company-text {
            flex: 1;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 3px;
        }
        .company-details {
            color: #666;
            font-size: 10px;
        }
        .quotation-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .quotation-info {
            flex: 1;
            font-size: 11px;
        }
        .customer-info {
            flex: 1;
            text-align: right;
            font-size: 11px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            color: #666;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
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
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .totals-table {
            width: 250px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 8px;
            border: none;
            font-size: 11px;
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
            background-color: #28a745;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
            page-break-inside: avoid;
        }
        .notes {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .vehicle-details {
            margin-top: 10px;
            padding: 10px;
            background-color: #e8f5e8;
            border-radius: 5px;
            font-size: 11px;
            page-break-inside: avoid;
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
        .status-sent { background-color: #17a2b8; color: white; }
        .status-accepted { background-color: #28a745; color: white; }
        .status-declined { background-color: #dc3545; color: white; }
        
        .page-break {
            page-break-after: always;
            break-after: page;
        }
        
        /* PDF Display Control */
        .pdf-screen-view {
            display: block !important;
        }
        .pdf-print-view {
            display: none !important;
        }
        
        @media print {
            body { 
                margin: 0; 
                padding: 0; 
                font-size: 10px;
            }
            .quotation-container { 
                box-shadow: none; 
                padding: 10px;
                max-width: 100%;
            }
            .no-print { display: none; }
            .items-table { 
                page-break-inside: avoid;
                font-size: 10px;
            }
            .items-table th,
            .items-table td {
                padding: 4px;
            }
            .totals-section { page-break-inside: avoid; }
            
            /* Show both screen and print view for PDF when printing */
            .pdf-screen-view {
                display: block !important;
            }
            .pdf-print-view {
                display: none !important;
            }
            .pdf-screen-view iframe,
            .pdf-screen-view object {
                height: 1000px !important;
                page-break-inside: avoid;
                border: 1px solid #000 !important;
            }
            .footer { page-break-inside: avoid; }
            .page-break {
                page-break-after: always;
                break-after: page;
                height: 0;
                margin: 0;
                padding: 0;
            }
            .terms-page {
                page-break-before: always;
                break-before: page;
            }
            object {
                display: none !important;
            }
            .pdf-fallback {
                display: block !important;
            }
}
</style>
</head>
<body>
    <div class="quotation-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <?php if(\App\Models\Setting::get('company_logo')): ?>
                    <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" 
                         alt="Company Logo" 
                         class="company-logo">
                <?php endif; ?>
                <div class="company-text">
                    <div class="company-name"><?php echo e(\App\Models\Setting::get('company_name', 'MMP Auto-Meister')); ?></div>
                <div class="company-details">
                    Auto Parts & Workshop Services<br>
                        <?php if(\App\Models\Setting::get('company_address')): ?>
                            <?php echo e(\App\Models\Setting::get('company_address')); ?><br>
                        <?php endif; ?>
                        <?php if(\App\Models\Setting::get('company_email')): ?>
                            Email: <?php echo e(\App\Models\Setting::get('company_email')); ?> | 
                        <?php endif; ?>
                        <?php if(\App\Models\Setting::get('company_phone')): ?>
                            Phone: <?php echo e(\App\Models\Setting::get('company_phone')); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="quotation-details">
                <div class="quotation-info">
                    <div class="info-label">Quotation Number:</div>
                    <div class="info-value"><?php echo e($quote->quote_number); ?></div>
                    <div class="info-label">Date:</div>
                    <div class="info-value"><?php echo e($quote->created_at->format('d/m/Y')); ?></div>
                    <div class="info-label">Created by:</div>
                    <div class="info-value"><?php echo e($quote->user->name ?? 'System'); ?></div>
                    <div class="info-label">Valid Until:</div>
                    <div class="info-value"><?php echo e($quote->valid_until ? $quote->valid_until->format('d/m/Y') : $quote->created_at->addDays(30)->format('d/m/Y')); ?></div>
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Quote To:</div>
                    <div class="info-value">
                        <?php if($quote->customer): ?>
                            <strong><?php echo e($quote->customer->name); ?></strong><br>
                            <?php if($quote->customer->email): ?><?php echo e($quote->customer->email); ?><br><?php endif; ?>
                            <?php if($quote->customer->phone): ?><?php echo e($quote->customer->phone); ?><br><?php endif; ?>
                            <?php if($quote->customer->address): ?><?php echo e($quote->customer->address); ?><?php endif; ?>
                        <?php else: ?>
                            <strong><?php echo e($quote->customer_name ?? 'Walk-in Customer'); ?></strong><br>
                            <?php if($quote->customer_email): ?><?php echo e($quote->customer_email); ?><br><?php endif; ?>
                            <?php if($quote->customer_phone): ?><?php echo e($quote->customer_phone); ?><?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo e($quote->status ?? 'draft'); ?>">
                            <?php echo e(ucfirst($quote->status ?? 'draft')); ?>

                        </span>
        </div>
        </div>
    </div>
        </div>

        <!-- Vehicle Details -->
        <?php if($quote->vehicleMake || $quote->vehicleModel || $quote->vehicleEngine || $quote->vehicle_vin || $quote->vehicle_reg): ?>
        <div class="vehicle-details">
            <h4 style="margin-top: 0; color: #28a745;">Vehicle Details</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php if($quote->vehicleMake): ?>
                <div><strong>Make:</strong> <?php echo e($quote->vehicleMake->name); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicleModel): ?>
                <div><strong>Model:</strong> <?php echo e($quote->vehicleModel->name); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicleEngine): ?>
                <div><strong>Engine:</strong> <?php echo e($quote->vehicleEngine->code); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicle_year): ?>
                <div><strong>Year:</strong> <?php echo e($quote->vehicle_year); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicle_vin): ?>
            <div><strong>VIN:</strong> <?php echo e($quote->vehicle_vin); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicle_reg): ?>
                <div><strong>Registration:</strong> <?php echo e($quote->vehicle_reg); ?></div>
                <?php endif; ?>
                <?php if($quote->vehicle_mileage): ?>
                <div><strong>Mileage:</strong> <?php echo e($quote->vehicle_mileage); ?> km</div>
                <?php endif; ?>
        </div>
    </div>
        <?php endif; ?>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 15%;">SKU</th>
                    <th style="width: 35%;">Description</th>
                    <th class="text-center" style="width: 8%;">Qty</th>
                    <th class="text-right" style="width: 12%;">Unit Price</th>
                    <th class="text-right" style="width: 12%;">Discount</th>
                    <th class="text-right" style="width: 18%;">Total</th>
            </tr>
        </thead>
        <tbody>
                <?php $__currentLoopData = $quote->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->product->sku ?? $item->product_id); ?></td>
                    <td><?php echo e($item->product->name ?? 'Product'); ?></td>
                    <td class="text-center"><?php echo e(number_format($item->quantity, 0)); ?></td>
                    <td class="text-right">R <?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="text-right"><?php echo e($item->discount > 0 ? 'R ' . number_format($item->discount, 2) : '-'); ?></td>
                    <td class="text-right"><strong>R <?php echo e(number_format($item->total, 2)); ?></strong></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">R <?php echo e(number_format($quote->items->sum('total'), 2)); ?></td>
                </tr>
                <?php if($quote->total_discount > 0): ?>
                <tr>
                    <td class="label">Total Discount:</td>
                    <td class="amount">-R <?php echo e(number_format($quote->total_discount, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($quote->shipping > 0): ?>
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">R <?php echo e(number_format($quote->shipping, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($quote->vat > 0): ?>
                <tr>
                    <td class="label">VAT (<?php echo e($quote->vat_rate ?? 15); ?>%):</td>
                    <td class="amount">R <?php echo e(number_format($quote->vat, 2)); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R <?php echo e(number_format($quote->grand_total ?? $quote->items->sum('total'), 2)); ?></td>
                </tr>
            </table>
        </div>

        <!-- Payment Notice -->
        <div style="margin-top: 15px; padding: 10px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #28a745; page-break-inside: avoid;">
            <h5 style="margin-top: 0; font-size: 12px; font-weight: bold;">IMMEDIATE PAYMENT & DISPATCH:</h5>
            <p style="margin-bottom: 0; font-size: 11px; font-weight: bold;">ORDERS WILL ONLY BE DISPATCHED ONCE PAYMENT HAS REFLECTED IN OUR ACCOUNT.</p>
        </div>

        <!-- Banking Details -->
        <?php if(\App\Models\Setting::showBankOnQuotes()): ?>
        <div style="margin-top: 15px; padding: 10px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #28a745; page-break-inside: avoid;">
            <h5 style="margin-top: 0; font-size: 12px; font-weight: bold;">Banking Details:</h5>
            <div style="font-size: 11px; line-height: 1.6;">
                <p style="margin: 2px 0;"><strong>Bank:</strong> <?php echo e(\App\Models\Setting::bankName()); ?></p>
                <p style="margin: 2px 0;"><strong>Account Name:</strong> <?php echo e(\App\Models\Setting::bankAccountName()); ?></p>
                <p style="margin: 2px 0;"><strong>Account Type:</strong> <?php echo e(\App\Models\Setting::bankAccountType()); ?></p>
                <p style="margin: 2px 0;"><strong>Account Number:</strong> <?php echo e(\App\Models\Setting::bankAccountNumber()); ?></p>
                <p style="margin: 2px 0;"><strong>Branch Code:</strong> <?php echo e(\App\Models\Setting::bankBranchCode()); ?></p>
                <p style="margin: 2px 0;"><strong>Reference:</strong> <?php echo e(\App\Models\Setting::bankReference()); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Page Break - Start Page 2 -->
        <div class="page-break"></div>

        <!-- Page 2: Terms & Conditions -->
        <div class="terms-page">
            <!-- Page 2 Header -->
            <div style="border-bottom: 2px solid #28a745; padding-bottom: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                <?php if(\App\Models\Setting::get('company_logo')): ?>
                    <img src="<?php echo e(asset(\App\Models\Setting::get('company_logo'))); ?>" 
                         alt="Company Logo" 
                         style="max-height: 50px; max-width: 120px; object-fit: contain;">
                <?php endif; ?>
                <div style="text-align: right;">
                    <div style="font-size: 14px; font-weight: bold; color: #28a745;"><?php echo e(\App\Models\Setting::get('company_name', 'MMP Auto-Meister')); ?></div>
                    <div style="font-size: 10px; color: #666;">Quotation: <?php echo e($quote->quote_number); ?></div>
                </div>
        </div>

        <!-- Terms & Conditions -->
        <div style="margin-top: 0; padding: 8px; background-color: #f8f9fa; border-radius: 3px; border-left: 3px solid #28a745;">
            <h5 style="margin: 0 0 8px 0; color: #28a745; font-size: 12px; font-weight: bold; text-align: center;">
                DELIVERY NOTE/ TERMS & CONDITIONS/ PURCHASE AGREEMENT:
            </h5>
            
            <p style="margin: 8px 0; font-size: 10px; font-weight: bold; text-align: center; color: #333;">
                UPON SIGNING THIS DOCUMENT, IT MEANS THAT THE CLIENT HAS READ AND AGREED TO ALL THE STIPULATED POINTS MENTIONED BELOW:
            </p>
            
            <div style="font-size: 9px; line-height: 1.3; color: #333;">
                <ol style="margin: 8px 0; padding-left: 15px;">
                    <li style="margin-bottom: 4px;">THE PURCHASER CONFIRMS THAT HE/SHE HAS EXAMINED AND/OR INSPECTED THE PARTS BEFORE PURCHASING AND SUPPLIED TO HIM/HER.</li>
                    <li style="margin-bottom: 4px;">PARTS CORRECTLY SUPPLIED ARE NOT RETURNABLE/ REFUNDABLE. NO EXCEPTIONS.</li>
                    <li style="margin-bottom: 4px;">INCORRECTLY SUPPLIED OR FAULTY PARTS MUST BE RETURNED TO THE SUPPLIER WITHIN 3 WORKING DAYS OF PURCHASE UNLESS OTHERWISE ARRANGED IN WRITING WITH MANAGEMENT.</li>
                    <li style="margin-bottom: 4px;">PLEASE ALLOW UP TO 24 HOURS TO PROCESS EXCHANGE/RETURN PARTS.</li>
                    <li style="margin-bottom: 4px;">THERE WILL BE NO RETURN/EXCHANGE OR CREDIT ON AN INCORRECTLY SUPPLIED PART IF:
                        <br>a. PURCHASER FAILS TO RETURN THE PART WITHIN 3 DAYS OF PURCHASE
                        <br>b. PURCHASER ACQUIRES THE PART ELSEWHERE WITHOUT MAKING PRIOR ARRANGEMENTS WITH THE SUPPLIER.
                        <br>c. THE PART HAS BEEN PARTIALLY OR ENTIRELY DISASSEMBLED, PHYSICALLY ALTERED, INSTALLED/AFFIXED/ATTACHED/JOINED/ADDED TO/BLENDED OR COMBINED WITHIN OTHER GOODS OR PROPERTY, TAMPERED WITH, MODIFIED, STRIPPED OR OPENED IN ANY WAY. NO DAMAGE REPORT HAS BEEN FILLED OUT.
                    </li>
                    <li style="margin-bottom: 4px;">THERE IS NO GUARANTEE OR EXCHANGE OF ELECTRICAL PARTS WHATSOEVER.</li>
                    <li style="margin-bottom: 4px;">DO NOT USE SILICONE ON THE ELECTRIC WATER PUMPS!</li>
                    <li style="margin-bottom: 4px;">WATER PUMPS ARE AN ELECTRICAL ITEM AND THUS DO NOT CARRY ANY WARRANTY!</li>
                    <li style="margin-bottom: 4px;">PARTS WILL ONLY BE EXCHANGED ON PRESENTATION OF THE ORIGINAL INVOICE SIGNED BY THE PURCHASER.</li>
                    <li style="margin-bottom: 4px;">CREDIT NOTES ARE VALID FOR 6 MONTHS AND ARE NOT TRANSFERABLE, GOODS WILL ONLY BE SUPPLIED ON CREDIT, ON PRESENTATION OF THE ORIGINAL CREDIT NOTE.</li>
                    <li style="margin-bottom: 4px;">NO CUT SECTIONS WILL BE TAKEN BACK BY THE SUPPLIER. SECTIONS MUST BE PAID FOR IN FULL BEFORE THE SUPPLIER WILL BEGIN CUTTING AND MUST BE MARKED OUT BY THE CLIENT WITH THE SALESMAN.</li>
                    <li style="margin-bottom: 4px;">NO VEHICLE OR SHELLS WILL BE TAKEN BACK BY THE SUPPLIER. 50% DEPOSIT MUST BE PAID FOR IN FULL BEFORE THE SUPPLIER WILL BEGIN STRIPPING AND BALANCE PAID AND RELEVANT DOCUMENTATION PROVIDED BEFORE THE SHELL/VEHICLE IS RELEASED.</li>
                    <li style="margin-bottom: 4px;">ENGINES AND/OR GEARBOX WARRANTYS ARE ONLY VALID IF THE PURCHASER HAS SIGNED AN ENGINE/GEARBOX CONTRACT FORM WITH THE SUPPLIER.</li>
                    <li style="margin-bottom: 4px;">INTERNAL SECONDHAND ENGINE PARTS (INJECTORS, PISTONS, CAMSHAFTS, OIL PUMPS.CRANKSHAFT ETC) CARRY NO GUARANTEE OR WARRANTY UNLESS STATED OTHERWISE IN WRITING.</li>
                    <li style="margin-bottom: 4px;">THE SUPPLIER IS NOT LIABLE FOR ANY COURIER/TRANSPORT/LABOUR/ENGINEERING OR MECHANICAL OR RECURRING COSTS INCURRED SHOULD THE PART BE EXCHANGED.</li>
                    <li style="margin-bottom: 4px;">THE RISK IN AND TO ANY PARTS SHALL BE PASSED ONTO THE PURCHASER ON THE DATE OF DELIVERY/PURCHASE. THE SUPPLIER IS FURTHER NOT LIABLE FOR ANY DAMAGES OR LOSS INCURRED BY THE COURIER COMPANY FOR GOODS IN TRANSIT AND INSURANCE OF SUCH ITEMS IS AT THE DISCRETION OF THE PURCHASER.</li>
                    <li style="margin-bottom: 4px;">A DEPOSIT SALE IS A BINDING CONTRACT BETWEEN BOTH THE SUPPLIER AND THE CLIENT. A DEPOSIT SALE IS TO BE PAID IN FULL WITHIN 30 DAYS UNLESS OTHERWISE STIPULATED. SHOULD THE BALANCE OF THE DEPOSIT NOT BE PAID WITHIN THE PRESCRIBED PERIOD, THERE WILL BE NO REFUND OR CREDIT NOTE ISSUED AND THE DEPOSIT WILL BE FORFEITED. SHOULD THE CLIENT WISH TO CANCEL THE CONTRACT WITHIN THE 30-DAY PERIOD AND ONLY IF SUCH IS AGREED BY THE MANAGEMENT, THE CLIENT WILL BE CHARGED A 20% HANDLING FEE OF THE FULL PRICE OF THE CONTRACT AND THE REFUND OF THE AMOUNT LESS 20% WILL ONLY BE MADE ONCE THE SUPPLIER HAS RESOLD THE PART OR VEHICLE.</li>
                    <li style="margin-bottom: 4px;">NO LIABILITY WHATSOEVER SHALL ARISE FURTHERMORE ON THE PART OF THE SUPPLIER FROM ANY REPRESENTATION(S) MADE OR ALLEGED TO HAVE BEEN MADE AT ANY TIME IN RESPECT OF PARTS SUPPLIED BY THE SUPPLIER AND ITS REPRESENTATIVES TO THE PURCHASER.</li>
                    <li style="margin-bottom: 4px;">GOODS REMAIN ON PROPERTY UNTIL PAID IN FULL</li>
                    <li style="margin-bottom: 4px;">NO REFUNDS ARE GIVEN ONLY CREDIT, UNLESS STIPULATED OTHERWISE BY THE SUPPLIER IN WRITING.</li>
                    <li style="margin-bottom: 4px;">USED PARTS SALES: USED PARTS ARE SOLD "AS IS" AND ARE NOT RETURNABLE, EXCHANGEABLE, OR WARRANTED, UNLESS OTHERWISE STIPULATED IN WRITING BY THE SELLER. NO WARRANTY IS GIVEN ON USED PARTS, UNLESS EXPLICITLY STATED IN WRITING PRIOR TO SALE.</li>
                    <li style="margin-bottom: 4px;">THERE WILL BE A HANDLING FEE OF 5% FOR PARTS SUPPLIED CORRECTLY THAT ARE RETURNED/EXCHANGED DUE TO INCORRECT DIAGNOSIS. THIS STRICTLY EXCLUDES ALL ELECTRICAL PARTS.</li>
                </ol>
                
                <div style="margin-top: 15px; border-top: 1px solid #ccc; padding-top: 10px;">
                    <p style="margin: 5px 0; font-size: 10px; font-weight: bold; text-align: center;">
                        I, ______________________________________ (CLIENT NAME), UNDERSTAND AND AGREE TO THE ABOVE TERMS AND CONDITIONS.
                    </p>
                    <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 9px;">
                        <div style="text-align: center; flex: 1;">
                            <p style="margin: 0; font-weight: bold;">CLIENTS SIGNATURE</p>
                            <div style="border-bottom: 1px solid #000; height: 20px; margin: 5px 10px 0 10px;"></div>
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <p style="margin: 0; font-weight: bold;">INVOICE NUMBER</p>
                            <div style="border-bottom: 1px solid #000; height: 20px; margin: 5px 10px 0 10px;"></div>
                        </div>
                        <div style="text-align: center; flex: 1;">
                            <p style="margin: 0; font-weight: bold;">DATE</p>
                            <div style="border-bottom: 1px solid #000; height: 20px; margin: 5px 10px 0 10px;"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

        <!-- Notes -->
        <?php if($quote->notes): ?>
            <div class="notes" style="margin-top: 15px;">
            <h4 style="margin-top: 0;">Notes</h4>
            <?php echo e($quote->notes); ?>

    </div>
        <?php endif; ?>
        </div>
        <!-- End of Page 2: Terms & Conditions -->

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for considering MMP Auto-Meister for your auto parts needs!</strong></p>
            <p>This quotation was generated on <?php echo e(now()->format('d/m/Y H:i:s')); ?> by MMP Auto-Meister POS System</p>
            <?php if($quote->reference): ?>
            <p><strong>Reference:</strong> <?php echo e($quote->reference); ?></p>
            <?php endif; ?>
    </div>
</div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Print Quotation
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✕ Close
        </button>
    </div>

    <script>
        // Auto-print detection for iframe
        // Note: Print is triggered from parent window via iframe.contentWindow.print()
        // This script is only for direct URL access fallback
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/quotes/print.blade.php ENDPATH**/ ?>