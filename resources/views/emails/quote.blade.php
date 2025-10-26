<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quote->quote_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .quote-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ \App\Models\Setting::get('company_name') ?: 'MMP AUTO-MEISTER' }}</h1>
            <p>Quotation</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $quote->customer->name ?? 'Customer' }},</p>
            
            <p>Thank you for your interest! Please find your quotation details below:</p>
            
            <div class="quote-details">
                <p><strong>Quote Number:</strong> {{ $quote->quote_number }}</p>
                <p><strong>Date:</strong> {{ $quote->created_at->format('d M Y') }}</p>
                <p><strong>Valid Until:</strong> {{ $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('d M Y') : 'N/A' }}</p>
                <p><strong>Total Amount:</strong> R {{ number_format($quote->grand_total, 2) }}</p>
                
                @if($quote->vehicle_make)
                <p><strong>Vehicle:</strong> {{ $quote->vehicle_make }} {{ $quote->vehicle_model }}
                    @if($quote->vehicle_reg)
                        ({{ $quote->vehicle_reg }})
                    @endif
                </p>
                @endif
            </div>
            
            <p>Your quotation PDF is attached to this email.</p>
            
            <p style="color: #007bff;">
                <strong>Note:</strong> This quotation is valid for 30 days from the date of issue.
            </p>
            
            <p>If you have any questions or would like to proceed with this quote, please don't hesitate to contact us.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing us!</p>
            <p><strong>{{ \App\Models\Setting::get('company_name') ?: 'MMP AUTO-MEISTER' }}</strong></p>
            <p>Email: {{ \App\Models\Setting::get('email') ?: 'info@mmpautomeister.co.za' }} | Phone: {{ \App\Models\Setting::get('phone') ?: '[Your Phone]' }}</p>
        </div>
    </div>
</body>
</html>

