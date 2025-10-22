<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Card {{ $jobCard->job_card_number }}</title>
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
                    <div class="info-value">{{ $jobCard->job_card_number }}</div>
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $jobCard->created_at->format('d/m/Y H:i A') }}</div>
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $jobCard->status }}">
                            {{ $jobCard->status_text }}
                        </span>
                    </div>
                    @if($jobCard->booked_at)
                    <div class="info-label">Booked In:</div>
                    <div class="info-value">{{ $jobCard->booked_at->format('d/m/Y H:i A') }}</div>
                    @endif
                    @if($jobCard->completed_at)
                    <div class="info-label">Completed:</div>
                    <div class="info-value">{{ $jobCard->completed_at->format('d/m/Y H:i A') }}</div>
                    @endif
                </div>
                
                <div class="customer-info">
                    <div class="info-label">Customer:</div>
                    <div class="info-value">{{ $jobCard->customer_name }}</div>
                    @if($jobCard->customer_phone)
                    <div class="info-value">{{ $jobCard->customer_phone }}</div>
                    @endif
                    @if($jobCard->customer_email)
                    <div class="info-value">{{ $jobCard->customer_email }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="section-box">
            <h6 style="margin-top:0; margin-bottom:10px; color:#0d6efd;">Vehicle Information</h6>
            <div class="row" style="display: flex;">
                <div style="flex: 1;">
                    @if($jobCard->vehicle_make || $jobCard->vehicle_model)
                    <div class="info-label">Make & Model:</div>
                    <div class="info-value">{{ $jobCard->vehicle_make }} {{ $jobCard->vehicle_model }}</div>
                    @endif
                    @if($jobCard->vehicle_year)
                    <div class="info-label">Year:</div>
                    <div class="info-value">{{ $jobCard->vehicle_year }}</div>
                    @endif
                    @if($jobCard->engine_code)
                    <div class="info-label">Engine Code:</div>
                    <div class="info-value">{{ $jobCard->engine_code }}</div>
                    @endif
                </div>
                <div style="flex: 1;">
                    @if($jobCard->vehicle_registration)
                    <div class="info-label">Registration:</div>
                    <div class="info-value">{{ $jobCard->vehicle_registration }}</div>
                    @endif
                    @if($jobCard->vehicle_vin)
                    <div class="info-label">VIN:</div>
                    <div class="info-value">{{ $jobCard->vehicle_vin }}</div>
                    @endif
                    @if($jobCard->vehicle_mileage)
                    <div class="info-label">Mileage:</div>
                    <div class="info-value">{{ $jobCard->vehicle_mileage }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Job Description -->
        <div class="section-box">
            <h6 style="margin-top:0; margin-bottom:10px; color:#0d6efd;">Job Description</h6>
            <p style="margin:0;">{{ $jobCard->job_description }}</p>
            
            @if($jobCard->customer_complaint)
            <hr style="margin: 10px 0;">
            <div class="info-label">Customer Complaint:</div>
            <p style="margin:5px 0;">{{ $jobCard->customer_complaint }}</p>
            @endif
            
            @if($jobCard->notes)
            <hr style="margin: 10px 0;">
            <div class="info-label">Internal Notes:</div>
            <p style="margin:5px 0;">{{ $jobCard->notes }}</p>
            @endif
        </div>

        <!-- Parts Used -->
        @if($jobCard->items->count() > 0)
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
                @foreach($jobCard->items as $item)
                <tr>
                    <td>{{ $item->product_sku }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ number_format($item->quantity_used, 2) }}</td>
                    <td class="text-right">R {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right"><strong>R {{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Labour -->
        @if($jobCard->labour->count() > 0)
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
                @foreach($jobCard->labour as $labour)
                <tr>
                    <td>{{ $labour->labour_description }}</td>
                    <td>{{ ucfirst($labour->labour_type) }}</td>
                    <td class="text-center">{{ number_format($labour->hours_worked, 2) }}</td>
                    <td class="text-right">R {{ number_format($labour->hourly_rate, 2) }}/hr</td>
                    <td class="text-right"><strong>R {{ number_format($labour->total_amount, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Parts Total:</td>
                    <td class="amount">R {{ number_format($jobCard->parts_total, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Labour Total:</td>
                    <td class="amount">R {{ number_format($jobCard->labour_total, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="label">Grand Total:</td>
                    <td class="amount">R {{ number_format($jobCard->grand_total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>MMP Auto-Meister Workshop Services</strong></p>
            <p>Thank you for choosing us for your vehicle maintenance!</p>
            @if($jobCard->final_invoice_id)
            <p style="margin-top: 15px; color: #28a745; font-weight: bold;">
                ✓ Converted to Invoice: {{ $jobCard->finalInvoice->invoice_number }}
            </p>
            @endif
            <p style="margin-top: 20px; font-size: 10px;">
                Generated on {{ now()->format('d/m/Y H:i:s') }} | Job Card: {{ $jobCard->job_card_number }}
            </p>
        </div>
    </div>
</body>
</html>


