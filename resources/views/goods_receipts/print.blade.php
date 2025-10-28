<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Receipt Note - {{ $grn->grn_number }}</title>
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
        <div class="grn-title">
            <h2>GOODS RECEIPT NOTE</h2>
            <p><strong>GRN Number:</strong> {{ $grn->grn_number }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $grn->status }}">
                    {{ strtoupper($grn->status) }}
                </span>
            </p>
            <p><strong>Date:</strong> {{ $grn->received_date->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Supplier & GRN Information -->
    <div class="info-section">
        <div class="info-box">
            <h3>SUPPLIER INFORMATION</h3>
            <div class="info-row">
                <strong>Supplier Name:</strong>
                <span>{{ $grn->supplier->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <strong>Email:</strong>
                <span>{{ $grn->supplier->email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <strong>Phone:</strong>
                <span>{{ $grn->supplier->phone ?? '-' }}</span>
            </div>
            <div class="info-row">
                <strong>Address:</strong>
                <span>{{ $grn->supplier->address ?? '-' }}</span>
            </div>
        </div>

        <div class="info-box">
            <h3>GRN INFORMATION</h3>
            <div class="info-row">
                <strong>GRN Number:</strong>
                <span>{{ $grn->grn_number }}</span>
            </div>
            <div class="info-row">
                <strong>Received Date:</strong>
                <span>{{ $grn->received_date->format('d M Y') }}</span>
            </div>
            @if($grn->purchaseOrder)
            <div class="info-row">
                <strong>Linked PO:</strong>
                <span>{{ $grn->purchaseOrder->po_number }}</span>
            </div>
            @endif
            @if($grn->invoice_number)
            <div class="info-row">
                <strong>Invoice Number:</strong>
                <span>{{ $grn->invoice_number }}</span>
            </div>
            @endif
            <div class="info-row">
                <strong>Created By:</strong>
                <span>{{ $grn->user->name ?? 'N/A' }}</span>
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
            @foreach($grn->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Product not found' }}</strong><br>
                    <small>SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                </td>
                <td class="text-center">{{ $item->ordered_qty }}</td>
                <td class="text-center"><strong>{{ $item->received_qty }}</strong></td>
                <td class="text-right">R {{ number_format($item->unit_cost, 2) }}</td>
                <td class="text-right"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="notes-section">
            @if($grn->notes)
            <div class="notes-box">
                <h4>NOTES / SPECIAL INSTRUCTIONS</h4>
                <p>{{ $grn->notes }}</p>
            </div>
            @endif
        </div>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Total Items:</td>
                    <td>{{ $grn->items->count() }}</td>
                </tr>
                <tr>
                    <td>Total Quantity:</td>
                    <td>{{ $grn->items->sum('received_qty') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>GRAND TOTAL:</td>
                    <td>R {{ number_format($grn->total_amount ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Page Break - Start Page 2 -->
    <div class="page-break"></div>

    <!-- Page 2: Terms & Conditions -->
    <div class="terms-page">
        <!-- Page 2 Header -->
        <div style="border-bottom: 2px solid #28a745; padding-bottom: 8px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
            @if(\App\Models\Setting::get('company_logo'))
                <img src="{{ asset(\App\Models\Setting::get('company_logo')) }}" 
                     alt="Company Logo" 
                     style="max-height: 50px; max-width: 120px; object-fit: contain;">
            @endif
            <div style="text-align: right;">
                <div style="font-size: 14px; font-weight: bold; color: #28a745;">{{ \App\Models\Setting::get('company_name', 'Your Company Name') }}</div>
                <div style="font-size: 10px; color: #666;">GRN: {{ $grn->grn_number }}</div>
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
                            <p style="margin: 0; font-weight: bold;">GRN NUMBER</p>
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
        <p>This is a system-generated goods receipt note. For queries, please contact us.</p>
        <p>Printed on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>

