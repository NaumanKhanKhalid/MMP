<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Export - {{ $exportDate }}</title>
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
        
        tr:hover {
            background-color: #e3f2fd;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .badge-stock {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .price {
            font-weight: bold;
            color: #28a745;
        }
        
        .cost {
            color: #dc3545;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Ensure proper page breaks */
        tbody tr {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MMP Auto-Meister</h1>
        <h2>Products Inventory Report</h2>
        <p>Generated on: {{ $exportDate }}</p>
    </div>
    
    <div class="summary">
        <strong>Total Products:</strong> {{ $totalProducts }} | 
        <strong>Active Products:</strong> {{ $products->where('status', 'active')->count() }} | 
        <strong>Inactive Products:</strong> {{ $products->where('status', 'inactive')->count() }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 8%;">SKU</th>
                <th style="width: 15%;">Product Name</th>
                <th style="width: 6%;">Brand</th>
                <th style="width: 8%;">Category</th>
                <th style="width: 6%;">Unit</th>
                <th style="width: 6%;">Last Cost</th>
                <th style="width: 6%;">Stock</th>
                <th style="width: 6%;">Normal Price</th>
                <th style="width: 6%;">Online Price</th>
                <th style="width: 6%;">Workshop Price</th>
                <th style="width: 8%;">OE Numbers</th>
                <th style="width: 8%;">Cross Ref</th>
                <th style="width: 6%;">Bin Location</th>
                <th style="width: 4%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                @php
                    $lastCost = $product->stockBatches->first() ? $product->stockBatches->first()->landed_unit_cost : 0;
                    $totalStock = $product->stockBatches->sum('qty_left');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $product->sku }}</strong></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->brand ? $product->brand->name : '-' }}</td>
                    <td>{{ $product->category ? $product->category->name : '-' }}</td>
                    <td class="text-center">{{ $product->unit }}</td>
                    <td class="text-right cost">R {{ number_format($lastCost, 2) }}</td>
                    <td class="text-right badge-stock">{{ number_format($totalStock, 2) }}</td>
                    <td class="text-right price">R {{ number_format($product->normal_price, 2) }}</td>
                    <td class="text-right price">R {{ number_format($product->online_price, 2) }}</td>
                    <td class="text-right price">R {{ number_format($product->workshop_price, 2) }}</td>
                    <td>
                        @if ($product->oeNumbers->count() > 0)
                            {{ $product->oeNumbers->take(2)->pluck('oe_number')->implode(', ') }}
                            @if ($product->oeNumbers->count() > 2)
                                <br><small>+{{ $product->oeNumbers->count() - 2 }} more</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($product->crossRefs->count() > 0)
                            {{ $product->crossRefs->take(2)->pluck('cross_ref_number')->implode(', ') }}
                            @if ($product->crossRefs->count() > 2)
                                <br><small>+{{ $product->crossRefs->count() - 2 }} more</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $product->bin_location ?: '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $product->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
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
