<!DOCTYPE html>
<html>
<head>
    <title>Job Cards Export - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .summary strong {
            color: #007bff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }
        
        th {
            background-color: #007bff;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0056b3;
        }
        
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-booked {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-in_progress {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-delivered {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .text-end {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .fw-semibold {
            font-weight: 600;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .mb-0 {
            margin-bottom: 0;
        }
        
        .total {
            font-weight: bold;
            color: #28a745;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        @media print {
            body { margin: 0; }
            .table { font-size: 7px; }
        }
        
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MMP Auto-Meister</h1>
        <h2>Workshop Job Cards Report</h2>
        <p>Generated on: {{ date('d M Y H:i:s') }}</p>
    </div>
    
    <div class="summary">
        <strong>Total Job Cards:</strong> {{ $jobCards->count() }} | 
        <strong>Pending:</strong> {{ $jobCards->where('status', 'pending')->count() }} | 
        <strong>Booked:</strong> {{ $jobCards->where('status', 'booked')->count() }} | 
        <strong>In Progress:</strong> {{ $jobCards->where('status', 'in_progress')->count() }} | 
        <strong>Completed:</strong> {{ $jobCards->where('status', 'completed')->count() }} | 
        <strong>Delivered:</strong> {{ $jobCards->where('status', 'delivered')->count() }} | 
        <strong>Cancelled:</strong> {{ $jobCards->where('status', 'cancelled')->count() }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 8%;">Job Card #</th>
                <th style="width: 12%;">Customer</th>
                <th style="width: 10%;">Vehicle</th>
                <th style="width: 25%;">Job Description</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Parts Total</th>
                <th style="width: 8%;">Labour Total</th>
                <th style="width: 8%;">Grand Total</th>
                <th style="width: 10%;">Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jobCards as $index => $jobCard)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $jobCard->job_card_number }}</strong></td>
                    <td>
                        {{ $jobCard->customer_name }}
                        @if($jobCard->customer_phone)
                            <br><small class="text-muted">{{ $jobCard->customer_phone }}</small>
                        @endif
                    </td>
                    <td>
                        {{ $jobCard->vehicle_make }} {{ $jobCard->vehicle_model }}
                        @if($jobCard->vehicle_registration)
                            <br><small class="text-muted">{{ $jobCard->vehicle_registration }}</small>
                        @endif
                    </td>
                    <td>{{ Str::limit($jobCard->job_description, 60) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $jobCard->status }}">
                            {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
                        </span>
                    </td>
                    <td class="text-end">R {{ number_format($jobCard->parts_total, 2) }}</td>
                    <td class="text-end">R {{ number_format($jobCard->labour_total, 2) }}</td>
                    <td class="text-end total">R {{ number_format($jobCard->grand_total, 2) }}</td>
                    <td>{{ $jobCard->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated by MMP Auto-Meister POS System</p>
        <p>© {{ date('Y') }} MMP Auto-Meister. All rights reserved.</p>
    </div>
</body>
</html>

