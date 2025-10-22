<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info {
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
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice-container { box-shadow: none; }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
        
        /* Terms and Conditions Styles - Compact for single page */
        .terms-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .terms-title {
            margin: 0 0 8px 0;
            color: #007bff;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        
        .terms-subtitle {
            margin: 8px 0 6px 0;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            color: #333;
        }
        
        .terms-content {
            font-size: 9px;
            line-height: 1.3;
            color: #333;
        }
        
        .terms-list {
            margin: 6px 0;
            padding-left: 15px;
        }
        
        .terms-list li {
            margin-bottom: 3px;
        }
        
        .signature-section {
            margin-top: 15px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .signature-text {
            margin: 3px 0;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
        }
        
        .signature-fields {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 9px;
        }
        
        .signature-field {
            text-align: center;
            flex: 1;
        }
        
        .signature-field p {
            margin: 0;
            font-weight: bold;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 20px;
            margin: 3px 10px 0 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">MMP Auto-Meister</div>
                <div class="company-details">
                    Auto Parts & Workshop Services<br>
                    Point of Sale & Inventory System<br>
                    Email: info@mmpautomeister.co.za | Phone: +27 (0)11 123 4567
                </div>
            </div>
            
            <div class="invoice-details">
                <div class="invoice-info">
                    <div class="info-label">Invoice Number:</div>
                    <div class="info-value">{{ $invoice->invoice_number }}</div>
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $invoice->created_at->format('d/m/Y') }}</div>
                    <div class="info-label">Created by:</div>
                    <div class="info-value">{{ $invoice->user->name }}</div>
                    @if($invoice->quote_id)
                    <div class="info-label">From Quote:</div>
                    <div class="info-value">{{ $invoice->quote->quote_number }}</div>
                    @endif
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Bill To:</div>
                    <div class="info-value">
                        @if($invoice->customer)
                            <strong>{{ $invoice->customer->name }}</strong><br>
                            @if($invoice->customer->email){{ $invoice->customer->email }}<br>@endif
                            @if($invoice->customer->phone){{ $invoice->customer->phone }}<br>@endif
                            @if($invoice->customer->address){{ $invoice->customer->address }}@endif
                        @else
                            <strong>{{ $invoice->customer_name ?? 'Cash Sale' }}</strong><br>
                            @if($invoice->customer_email){{ $invoice->customer_email }}<br>@endif
                            @if($invoice->customer_phone){{ $invoice->customer_phone }}@endif
                        @endif
                    </div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $invoice->payment_status }}">
                            {{ ucfirst($invoice->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Details -->
        @if($invoice->vehicle_make || $invoice->vehicle_model || $invoice->vehicle_vin || $invoice->vehicle_reg)
        <div class="vehicle-details">
            <h4 style="margin-top: 0; color: #1976d2;">Vehicle Details</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                @if($invoice->vehicle_make)
                <div><strong>Make:</strong> {{ $invoice->vehicle_make }}</div>
                @endif
                @if($invoice->vehicle_model)
                <div><strong>Model:</strong> {{ $invoice->vehicle_model }}</div>
                @endif
                @if($invoice->vehicle_vin)
                <div><strong>VIN:</strong> {{ $invoice->vehicle_vin }}</div>
                @endif
                @if($invoice->vehicle_reg)
                <div><strong>Registration:</strong> {{ $invoice->vehicle_reg }}</div>
                @endif
                @if($invoice->vehicle_mileage)
                <div><strong>Mileage:</strong> {{ $invoice->vehicle_mileage }} km</div>
                @endif
            </div>
        </div>
        @endif

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
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_sku }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ $item->discount_percentage > 0 ? number_format($item->discount_percentage, 1) . '%' : '-' }}</td>
                    <td class="text-right">{{ $item->discount_amount > 0 ? 'R ' . number_format($item->discount_amount, 2) : '-' }}</td>
                    <td class="text-right"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">R {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->discount_amount > 0)
                <tr>
                    <td class="label">Total Discount:</td>
                    <td class="amount">-R {{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->shipping > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount">R {{ number_format($invoice->shipping, 2) }}</td>
                </tr>
                @endif
                @if($invoice->vat_amount > 0)
                <tr>
                    <td class="label">VAT ({{ $invoice->vat_rate }}%):</td>
                    <td class="amount">R {{ number_format($invoice->vat_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R {{ number_format($invoice->grand_total, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Amount Paid:</td>
                    <td class="amount">R {{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Balance Due:</td>
                    <td class="amount {{ $invoice->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                        R {{ number_format($invoice->balance_due, 2) }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Payment Method -->
        <div style="margin-top: 20px; padding: 15px; background-color: #e8f5e8; border-radius: 5px;">
            <strong>Payment Method:</strong> 
            @switch($invoice->payment_method)
                @case('cash')
                    💵 Cash
                    @break
                @case('card')
                    💳 Card
                    @break
                @case('eft')
                    🏦 EFT
                    @break
                @case('on_account')
                    👤 On Account
                    @break
            @endswitch
        </div>

        <!-- Banking Details -->
        @if(\App\Models\Setting::showBankOnInvoices())
        <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">Banking Details:</h4>
            <div style="font-size: 14px; color: #856404;">
                <strong>Bank:</strong> {{ \App\Models\Setting::bankName() }}<br>
                <strong>Account Name:</strong> {{ \App\Models\Setting::bankAccountName() }}<br>
                <strong>Account Type:</strong> {{ \App\Models\Setting::bankAccountType() }}<br>
                <strong>Account Number:</strong> {{ \App\Models\Setting::bankAccountNumber() }}<br>
                <strong>Branch Code:</strong> {{ \App\Models\Setting::bankBranchCode() }}<br>
                <strong>Reference:</strong> {{ \App\Models\Setting::bankReference() }}
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <h4 style="margin-top: 0;">Notes</h4>
            {{ $invoice->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated on {{ now()->format('d/m/Y H:i:s') }} by MMP Auto-Meister POS System</p>
            @if($invoice->reference)
            <p><strong>Reference:</strong> {{ $invoice->reference }}</p>
            @endif
        </div>

        <!-- Terms and Conditions -->
        <div class="page-break"></div>
        
        <div class="terms-container">
            <h3 class="terms-title">DELIVERY NOTE/ TERMS & CONDITIONS/ PURCHASE AGREEMENT:</h3>
            
            <p class="terms-subtitle">
                UPON SIGNING THIS DOCUMENT, IT MEANS THAT THE CLIENT HAS READ AND AGREED TO ALL THE STIPULATED POINTS MENTIONED BELOW:
            </p>
            
            <div class="terms-content">
                <ol class="terms-list">
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
                
                <div class="signature-section">
                    <p class="signature-text">
                        I, ______________________________________ (CLIENT NAME), UNDERSTAND AND AGREE TO THE ABOVE TERMS AND CONDITIONS.
                    </p>
                    
                    <div class="signature-fields">
                        <div class="signature-field">
                            <p>CLIENTS SIGNATURE</p>
                            <div class="signature-line"></div>
                        </div>
                        <div class="signature-field">
                            <p>INVOICE NUMBER</p>
                            <div class="signature-line"></div>
                        </div>
                        <div class="signature-field">
                            <p>DATE</p>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
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
</body>
</html>