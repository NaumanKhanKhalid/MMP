<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
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

        .page-break {
            page-break-after: always;
            break-after: page;
        }
        
        .terms-page {
            page-break-before: always;
            break-before: page;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
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
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            @if(\App\Models\Setting::get('company_logo'))
                <img src="{{ asset(\App\Models\Setting::get('company_logo')) }}" alt="Company Logo" class="company-logo">
            @endif
            <h1>{{ \App\Models\Setting::get('company_name', 'Your Company Name') }}</h1>
            <p>{{ \App\Models\Setting::get('company_address', '') }}</p>
            <p>Tel: {{ \App\Models\Setting::get('company_phone', '') }} | Email: {{ \App\Models\Setting::get('company_email', '') }}</p>
            @if(\App\Models\Setting::get('company_vat_number'))
                <p>VAT Reg: {{ \App\Models\Setting::get('company_vat_number') }}</p>
            @endif
        </div>
        <div class="po-title">
            <h2>PURCHASE ORDER</h2>
            <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $purchaseOrder->status }}">
                    {{ strtoupper(str_replace('_', ' ', $purchaseOrder->status)) }}
                </span>
            </p>
            <p><strong>Date:</strong> {{ $purchaseOrder->order_date->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Supplier & Order Information -->
    <div class="info-section">
        <div class="info-box">
            <h3>SUPPLIER DETAILS</h3>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person:</span>
                <span class="info-value">{{ $purchaseOrder->supplier->contact_person ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $purchaseOrder->supplier->email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $purchaseOrder->supplier->phone ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $purchaseOrder->supplier->address ?? '-' }}</span>
            </div>
        </div>

        <div class="info-box">
            <h3>ORDER INFORMATION</h3>
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $purchaseOrder->order_date->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Expected Delivery:</span>
                <span class="info-value">
                    {{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('d M Y') : 'Not specified' }}
                </span>
            </div>
            @if($purchaseOrder->received_date)
            <div class="info-row">
                <span class="info-label">Received Date:</span>
                <span class="info-value">{{ $purchaseOrder->received_date->format('d M Y') }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Created By:</span>
                <span class="info-value">{{ $purchaseOrder->user->name ?? 'N/A' }}</span>
            </div>
            @if($purchaseOrder->payment_terms)
            <div class="info-row">
                <span class="info-label">Payment Terms:</span>
                <span class="info-value">{{ $purchaseOrder->payment_terms }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($purchaseOrder->delivery_address)
    <div class="info-box" style="width: 100%; margin-bottom: 25px;">
        <h3>DELIVERY ADDRESS</h3>
        <p>{{ $purchaseOrder->delivery_address }}</p>
    </div>
    @endif

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
            @foreach($purchaseOrder->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Product not found' }}</strong><br>
                    <small>SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                    @if($item->product && $item->product->description)
                        <br><small>{{ Str::limit($item->product->description, 80) }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right"><strong>R {{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="notes-section">
            @if($purchaseOrder->notes)
            <div class="notes-box">
                <h4>NOTES / SPECIAL INSTRUCTIONS</h4>
                <p>{{ $purchaseOrder->notes }}</p>
            </div>
            @endif
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>R {{ number_format($purchaseOrder->subtotal, 2) }}</td>
                </tr>
                @if($purchaseOrder->total_discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td>- R {{ number_format($purchaseOrder->total_discount, 2) }}</td>
                </tr>
                @endif
                @if($purchaseOrder->shipping > 0)
                <tr>
                    <td>Shipping:</td>
                    <td>R {{ number_format($purchaseOrder->shipping, 2) }}</td>
                </tr>
                @endif
                @if($purchaseOrder->vat_enabled && $purchaseOrder->vat > 0)
                <tr>
                    <td>VAT ({{ \App\Models\Setting::get('vat_rate', 15) }}%):</td>
                    <td>R {{ number_format($purchaseOrder->vat, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td>GRAND TOTAL:</td>
                    <td>R {{ number_format($purchaseOrder->grand_total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Page Break - Start Page 2 -->
    <div class="page-break"></div>

    <!-- Page 2: Terms & Conditions -->
    <div class="terms-page">
        <!-- Page 2 Header -->
        <div style="border-bottom: 2px solid #007bff; padding-bottom: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
            @if(\App\Models\Setting::get('company_logo'))
                <img src="{{ asset(\App\Models\Setting::get('company_logo')) }}" 
                     alt="Company Logo" 
                     style="max-height: 50px; max-width: 120px; object-fit: contain;">
            @endif
            <div style="text-align: right;">
                <div style="font-size: 14px; font-weight: bold; color: #007bff;">{{ \App\Models\Setting::get('company_name', 'Your Company Name') }}</div>
                <div style="font-size: 10px; color: #666;">Purchase Order: {{ $purchaseOrder->po_number }}</div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div style="margin-top: 0; padding: 8px; background-color: #f8f9fa; border-radius: 3px; border-left: 3px solid #007bff;">
            <h5 style="margin: 0 0 8px 0; color: #007bff; font-size: 12px; font-weight: bold; text-align: center;">
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
    </div>
    <!-- End of Page 2: Terms & Conditions -->

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ \App\Models\Setting::get('company_name', 'Your Company Name') }}</strong></p>
        <p>This is a system-generated purchase order. For queries, please contact us.</p>
        <p>Printed on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
