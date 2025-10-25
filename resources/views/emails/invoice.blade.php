<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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
        .invoice-details {
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
            <h1>MMP AUTO-MEISTER</h1>
            <p>Invoice Notification</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $invoice->customer->name ?? 'Customer' }},</p>
            
            <p>Thank you for your purchase! Please find your invoice details below:</p>
            
            <div class="invoice-details">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                <p><strong>Total Amount:</strong> R {{ number_format($invoice->grand_total, 2) }}</p>
                <p><strong>Amount Paid:</strong> R {{ number_format($invoice->amount_paid ?? 0, 2) }}</p>
                <p><strong>Balance Due:</strong> R {{ number_format($invoice->balance_due ?? 0, 2) }}</p>
            </div>
            
            <p>Your invoice PDF is attached to this email.</p>
            
            @if($invoice->balance_due > 0)
            <p style="color: #dc3545;"><strong>Payment Required:</strong> R {{ number_format($invoice->balance_due, 2) }}</p>
            @endif
            
            <p>If you have any questions, please don't hesitate to contact us.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p><strong>MMP AUTO-MEISTER</strong></p>
            <p>Email: info@mmpautomeister.co.za | Phone: [Your Phone]</p>
        </div>
    </div>
</body>
</html>

