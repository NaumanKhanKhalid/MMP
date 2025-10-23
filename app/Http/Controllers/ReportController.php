<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockLedger;
use App\Models\CustomerLedger;
use App\Models\SupplierLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $customerId = $request->get('customer_id');

        $query = Invoice::with(['customer', 'items.product', 'user'])
            ->whereIn('payment_status', ['posted', 'paid'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalSales = $invoices->sum('grand_total');
        $totalCost = $invoices->sum(function ($invoice) {
            return $invoice->items->sum('line_cost');
        });
        $totalProfit = $invoices->sum('total_profit');
        $averageMargin = $totalSales > 0 ? ($totalProfit / $totalSales) * 100 : 0;

        $customers = Customer::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.sales-pdf', compact('invoices', 'startDate', 'endDate', 'totalSales', 'totalCost', 'totalProfit', 'averageMargin'));
            return $pdf->download('sales-report-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportSalesCSV($invoices, $startDate, $endDate);
        }

        return view('reports.sales', compact('invoices', 'startDate', 'endDate', 'totalSales', 'totalCost', 'totalProfit', 'averageMargin', 'customers'));
    }

    /**
     * Debtors Ageing Report
     */
    public function debtorsAgeing(Request $request)
    {
        $asAt = $request->get('as_at', now()->format('Y-m-d'));
        
        $customers = Customer::with(['invoices' => function ($query) use ($asAt) {
            $query->whereIn('payment_status', ['posted', 'paid'])
                  ->where('balance_due', '>', 0)
                  ->where('created_at', '<=', $asAt);
        }])->get();

        $ageingData = [];
        
        foreach ($customers as $customer) {
            if ($customer->invoices->isEmpty()) {
                continue;
            }

            $current = 0;
            $days30 = 0;
            $days60 = 0;
            $days90 = 0;
            $over90 = 0;

            foreach ($customer->invoices as $invoice) {
                $daysOld = now()->diffInDays($invoice->created_at);
                $balance = $invoice->balance_due;

                if ($daysOld <= 30) {
                    $current += $balance;
                } elseif ($daysOld <= 60) {
                    $days30 += $balance;
                } elseif ($daysOld <= 90) {
                    $days60 += $balance;
                } elseif ($daysOld <= 120) {
                    $days90 += $balance;
                } else {
                    $over90 += $balance;
                }
            }

            $total = $current + $days30 + $days60 + $days90 + $over90;

            if ($total > 0) {
                $ageingData[] = [
                    'customer' => $customer,
                    'current' => $current,
                    'days30' => $days30,
                    'days60' => $days60,
                    'days90' => $days90,
                    'over90' => $over90,
                    'total' => $total,
                ];
            }
        }

        // Sort by total descending
        usort($ageingData, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Calculate column totals
        $totals = [
            'current' => array_sum(array_column($ageingData, 'current')),
            'days30' => array_sum(array_column($ageingData, 'days30')),
            'days60' => array_sum(array_column($ageingData, 'days60')),
            'days90' => array_sum(array_column($ageingData, 'days90')),
            'over90' => array_sum(array_column($ageingData, 'over90')),
            'total' => array_sum(array_column($ageingData, 'total')),
        ];

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.debtors-ageing-pdf', compact('ageingData', 'totals', 'asAt'));
            return $pdf->download('debtors-ageing-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportDebtorsAgeingCSV($ageingData, $totals, $asAt);
        }

        return view('reports.debtors-ageing', compact('ageingData', 'totals', 'asAt'));
    }

    /**
     * Creditors Ageing Report
     */
    public function creditorsAgeing(Request $request)
    {
        $asAt = $request->get('as_at', now()->format('Y-m-d'));
        
        $suppliers = Supplier::with(['supplierInvoices' => function ($query) use ($asAt) {
            $query->whereIn('status', ['posted', 'paid'])
                  ->where('balance_due', '>', 0)
                  ->where('invoice_date', '<=', $asAt);
        }])->get();

        $ageingData = [];
        
        foreach ($suppliers as $supplier) {
            if ($supplier->supplierInvoices->isEmpty()) {
                continue;
            }

            $current = 0;
            $days30 = 0;
            $days60 = 0;
            $days90 = 0;
            $over90 = 0;

            foreach ($supplier->supplierInvoices as $invoice) {
                $daysOld = now()->diffInDays($invoice->invoice_date);
                $balance = $invoice->balance_due;

                if ($daysOld <= 30) {
                    $current += $balance;
                } elseif ($daysOld <= 60) {
                    $days30 += $balance;
                } elseif ($daysOld <= 90) {
                    $days60 += $balance;
                } elseif ($daysOld <= 120) {
                    $days90 += $balance;
                } else {
                    $over90 += $balance;
                }
            }

            $total = $current + $days30 + $days60 + $days90 + $over90;

            if ($total > 0) {
                $ageingData[] = [
                    'supplier' => $supplier,
                    'current' => $current,
                    'days30' => $days30,
                    'days60' => $days60,
                    'days90' => $days90,
                    'over90' => $over90,
                    'total' => $total,
                ];
            }
        }

        // Sort by total descending
        usort($ageingData, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Calculate column totals
        $totals = [
            'current' => array_sum(array_column($ageingData, 'current')),
            'days30' => array_sum(array_column($ageingData, 'days30')),
            'days60' => array_sum(array_column($ageingData, 'days60')),
            'days90' => array_sum(array_column($ageingData, 'days90')),
            'over90' => array_sum(array_column($ageingData, 'over90')),
            'total' => array_sum(array_column($ageingData, 'total')),
        ];

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.creditors-ageing-pdf', compact('ageingData', 'totals', 'asAt'));
            return $pdf->download('creditors-ageing-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportCreditorsAgeingCSV($ageingData, $totals, $asAt);
        }

        return view('reports.creditors-ageing', compact('ageingData', 'totals', 'asAt'));
    }

    /**
     * Negative Stock Report
     */
    public function negativeStock(Request $request)
    {
        $products = Product::with(['category', 'brand'])
            ->where('on_hand', '<', 0)
            ->orderBy('on_hand', 'asc')
            ->get();

        // Calculate value impact
        foreach ($products as $product) {
            $product->value_impact = $product->on_hand * $this->getAverageCost($product);
        }

        $totalNegativeValue = $products->sum('value_impact');

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.negative-stock-pdf', compact('products', 'totalNegativeValue'));
            return $pdf->download('negative-stock-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportNegativeStockCSV($products);
        }

        return view('reports.negative-stock', compact('products', 'totalNegativeValue'));
    }

    /**
     * Inventory Valuation Report
     */
    public function inventoryValuation(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        $query = Product::with(['category', 'brand', 'stockBatches'])
            ->where('on_hand', '>', 0);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();

        // Calculate values
        foreach ($products as $product) {
            $product->avg_cost = $this->getAverageCost($product);
            $product->total_value = $product->on_hand * $product->avg_cost;
        }

        // Group by category
        $categorySummary = $products->groupBy('category_id')->map(function ($items, $categoryId) {
            $category = $items->first()->category;
            return [
                'category' => $category ? $category->name : 'Uncategorized',
                'product_count' => $items->count(),
                'total_qty' => $items->sum('on_hand'),
                'total_value' => $items->sum('total_value'),
            ];
        })->values();

        $totalValue = $products->sum('total_value');
        $totalQty = $products->sum('on_hand');

        $categories = \App\Models\Category::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.inventory-valuation-pdf', compact('products', 'categorySummary', 'totalValue', 'totalQty'));
            return $pdf->download('inventory-valuation-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportInventoryValuationCSV($products, $categorySummary);
        }

        return view('reports.inventory-valuation', compact('products', 'categorySummary', 'totalValue', 'totalQty', 'categories'));
    }

    /**
     * Stock Movement Report
     */
    public function stockMovement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $productId = $request->get('product_id');

        $query = StockLedger::with(['product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $movements = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $totalIn = $movements->where('qty', '>', 0)->sum('qty');
        $totalOut = abs($movements->where('qty', '<', 0)->sum('qty'));
        $netMovement = $totalIn - $totalOut;

        $products = Product::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.stock-movement-pdf', compact('movements', 'startDate', 'endDate', 'totalIn', 'totalOut', 'netMovement'));
            return $pdf->download('stock-movement-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportStockMovementCSV($movements, $startDate, $endDate);
        }

        return view('reports.stock-movement', compact('movements', 'startDate', 'endDate', 'totalIn', 'totalOut', 'netMovement', 'products'));
    }

    /**
     * Sales by Category Report
     */
    public function salesByCategory(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $salesByCategory = DB::table('invoices')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('invoices.payment_status', ['posted', 'paid'])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(DISTINCT invoices.id) as invoice_count'),
                DB::raw('SUM(invoice_items.qty) as total_qty'),
                DB::raw('SUM(invoice_items.line_total) as total_sales'),
                DB::raw('SUM(invoice_items.line_cost) as total_cost'),
                DB::raw('SUM(invoice_items.line_total - invoice_items.line_cost) as total_profit'),
                DB::raw('AVG((invoice_items.line_total - invoice_items.line_cost) / invoice_items.line_total * 100) as avg_margin')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        $totalSales = $salesByCategory->sum('total_sales');
        $totalProfit = $salesByCategory->sum('total_profit');
        $overallMargin = $totalSales > 0 ? ($totalProfit / $totalSales) * 100 : 0;

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.sales-by-category-pdf', compact('salesByCategory', 'startDate', 'endDate', 'totalSales', 'totalProfit', 'overallMargin'));
            return $pdf->download('sales-by-category-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportSalesByCategoryCSV($salesByCategory, $startDate, $endDate);
        }

        return view('reports.sales-by-category', compact('salesByCategory', 'startDate', 'endDate', 'totalSales', 'totalProfit', 'overallMargin'));
    }

    /**
     * Timed Sales Report
     */
    public function timedSales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $interval = $request->get('interval', 'hour'); // hour, day, week, month

        $query = Invoice::whereIn('payment_status', ['posted', 'paid'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        switch ($interval) {
            case 'hour':
                $salesData = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('HOUR(created_at) as time_period'),
                    DB::raw('COUNT(*) as invoice_count'),
                    DB::raw('SUM(grand_total) as total_sales'),
                    DB::raw('SUM(total_profit) as total_profit')
                )->groupBy('date', 'time_period')->orderBy('date')->orderBy('time_period')->get();
                break;
            case 'day':
                $salesData = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as invoice_count'),
                    DB::raw('SUM(grand_total) as total_sales'),
                    DB::raw('SUM(total_profit) as total_profit')
                )->groupBy('date')->orderBy('date')->get();
                break;
            case 'week':
                $salesData = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('WEEK(created_at) as week'),
                    DB::raw('COUNT(*) as invoice_count'),
                    DB::raw('SUM(grand_total) as total_sales'),
                    DB::raw('SUM(total_profit) as total_profit')
                )->groupBy('year', 'week')->orderBy('year')->orderBy('week')->get();
                break;
            case 'month':
                $salesData = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as invoice_count'),
                    DB::raw('SUM(grand_total) as total_sales'),
                    DB::raw('SUM(total_profit) as total_profit')
                )->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();
                break;
        }

        $totalSales = $salesData->sum('total_sales');
        $totalProfit = $salesData->sum('total_profit');
        $totalInvoices = $salesData->sum('invoice_count');

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.timed-sales-pdf', compact('salesData', 'startDate', 'endDate', 'interval', 'totalSales', 'totalProfit', 'totalInvoices'));
            return $pdf->download('timed-sales-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportTimedSalesCSV($salesData, $startDate, $endDate, $interval);
        }

        return view('reports.timed-sales', compact('salesData', 'startDate', 'endDate', 'interval', 'totalSales', 'totalProfit', 'totalInvoices'));
    }

    /**
     * Discount Matrix Report
     */
    public function discountMatrix(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $discountMatrix = DB::table('invoices')
            ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->whereIn('invoices.payment_status', ['posted', 'paid'])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('CASE 
                    WHEN invoice_items.discount_percentage = 0 THEN "0%"
                    WHEN invoice_items.discount_percentage <= 5 THEN "1-5%"
                    WHEN invoice_items.discount_percentage <= 10 THEN "6-10%"
                    WHEN invoice_items.discount_percentage <= 15 THEN "11-15%"
                    WHEN invoice_items.discount_percentage <= 20 THEN "16-20%"
                    ELSE "20%+"
                END as discount_range'),
                DB::raw('COUNT(*) as item_count'),
                DB::raw('SUM(invoice_items.qty) as total_qty'),
                DB::raw('SUM(invoice_items.line_total) as total_sales'),
                DB::raw('SUM(invoice_items.line_cost) as total_cost'),
                DB::raw('SUM(invoice_items.line_total - invoice_items.line_cost) as total_profit'),
                DB::raw('AVG(invoice_items.discount_percentage) as avg_discount')
            )
            ->groupBy('discount_range')
            ->orderBy('avg_discount')
            ->get();

        $totalSales = $discountMatrix->sum('total_sales');
        $totalProfit = $discountMatrix->sum('total_profit');
        $totalItems = $discountMatrix->sum('item_count');

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.discount-matrix-pdf', compact('discountMatrix', 'startDate', 'endDate', 'totalSales', 'totalProfit', 'totalItems'));
            return $pdf->download('discount-matrix-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportDiscountMatrixCSV($discountMatrix, $startDate, $endDate);
        }

        return view('reports.discount-matrix', compact('discountMatrix', 'startDate', 'endDate', 'totalSales', 'totalProfit', 'totalItems'));
    }

    /**
     * Replenishment Report
     */
    public function replenishment(Request $request)
    {
        $categoryId = $request->get('category_id');
        $supplierId = $request->get('supplier_id');

        $query = Product::with(['category', 'supplier', 'stockBatches'])
            ->where('status', 'active');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $products = $query->get();

        // Calculate replenishment data
        foreach ($products as $product) {
            $totalStock = $product->stockBatches->sum('qty_left');
            $product->total_stock = $totalStock;
            $product->needs_replenishment = $totalStock <= $product->reorder_level;
            $product->stock_status = $totalStock <= 0 ? 'Out of Stock' : 
                                   ($totalStock <= $product->reorder_level ? 'Low Stock' : 'In Stock');
        }

        $lowStockProducts = $products->where('needs_replenishment', true);
        $outOfStockProducts = $products->where('total_stock', '<=', 0);

        $categories = \App\Models\Category::orderBy('name')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.replenishment-pdf', compact('products', 'lowStockProducts', 'outOfStockProducts'));
            return $pdf->download('replenishment-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportReplenishmentCSV($products, $lowStockProducts, $outOfStockProducts);
        }

        return view('reports.replenishment', compact('products', 'lowStockProducts', 'outOfStockProducts', 'categories', 'suppliers'));
    }

    /**
     * New Items Report
     */
    public function newItems(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $categoryId = $request->get('category_id');

        $query = Product::with(['category', 'brand', 'supplier', 'creator'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $newProducts = $query->orderBy('created_at', 'desc')->get();

        // Group by creation date
        $productsByDate = $newProducts->groupBy(function ($product) {
            return $product->created_at->format('Y-m-d');
        });

        $categories = \App\Models\Category::orderBy('name')->get();

        if ($request->get('export') === 'pdf') {
            $pdf = PDF::loadView('reports.new-items-pdf', compact('newProducts', 'productsByDate', 'startDate', 'endDate'));
            return $pdf->download('new-items-' . date('Y-m-d') . '.pdf');
        }

        if ($request->get('export') === 'csv') {
            return $this->exportNewItemsCSV($newProducts, $startDate, $endDate);
        }

        return view('reports.new-items', compact('newProducts', 'productsByDate', 'startDate', 'endDate', 'categories'));
    }

    // Helper methods
    private function getAverageCost(Product $product)
    {
        $batches = $product->stockBatches()->where('qty_left', '>', 0)->get();

        if ($batches->isEmpty()) {
            return 0;
        }

        $totalCost = 0;
        $totalQty = 0;

        foreach ($batches as $batch) {
            $totalCost += $batch->qty_left * $batch->landed_unit_cost;
            $totalQty += $batch->qty_left;
        }

        return $totalQty > 0 ? ($totalCost / $totalQty) : 0;
    }

    // CSV Export Methods
    private function exportSalesCSV($invoices, $startDate, $endDate)
    {
        $filename = 'sales-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice #', 'Date', 'Customer', 'Subtotal', 'VAT', 'Total', 'Cost', 'Profit', 'Margin %', 'Status']);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->created_at->format('Y-m-d'),
                    $invoice->customer ? $invoice->customer->name : 'Cash Sale',
                    $invoice->subtotal,
                    $invoice->vat_amount,
                    $invoice->grand_total,
                    $invoice->items->sum('line_cost'),
                    $invoice->total_profit,
                    $invoice->gross_profit_percentage,
                    $invoice->payment_status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportDebtorsAgeingCSV($ageingData, $totals, $asAt)
    {
        $filename = 'debtors-ageing-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ageingData, $totals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer', 'Current', '30 Days', '60 Days', '90 Days', 'Over 90', 'Total']);

            foreach ($ageingData as $data) {
                fputcsv($file, [
                    $data['customer']->name,
                    $data['current'],
                    $data['days30'],
                    $data['days60'],
                    $data['days90'],
                    $data['over90'],
                    $data['total'],
                ]);
            }

            // Totals row
            fputcsv($file, [
                'TOTAL',
                $totals['current'],
                $totals['days30'],
                $totals['days60'],
                $totals['days90'],
                $totals['over90'],
                $totals['total'],
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCreditorsAgeingCSV($ageingData, $totals, $asAt)
    {
        $filename = 'creditors-ageing-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ageingData, $totals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Supplier', 'Current', '30 Days', '60 Days', '90 Days', 'Over 90', 'Total']);

            foreach ($ageingData as $data) {
                fputcsv($file, [
                    $data['supplier']->name,
                    $data['current'],
                    $data['days30'],
                    $data['days60'],
                    $data['days90'],
                    $data['over90'],
                    $data['total'],
                ]);
            }

            // Totals row
            fputcsv($file, [
                'TOTAL',
                $totals['current'],
                $totals['days30'],
                $totals['days60'],
                $totals['days90'],
                $totals['over90'],
                $totals['total'],
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportNegativeStockCSV($products)
    {
        $filename = 'negative-stock-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SKU', 'Product', 'Category', 'Brand', 'On Hand', 'Avg Cost', 'Value Impact']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category ? $product->category->name : '-',
                    $product->brand ? $product->brand->name : '-',
                    $product->on_hand,
                    $product->avg_cost ?? 0,
                    $product->value_impact,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventoryValuationCSV($products, $categorySummary)
    {
        $filename = 'inventory-valuation-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SKU', 'Product', 'Category', 'On Hand', 'Avg Cost', 'Total Value']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category ? $product->category->name : '-',
                    $product->on_hand,
                    $product->avg_cost,
                    $product->total_value,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportStockMovementCSV($movements, $startDate, $endDate)
    {
        $filename = 'stock-movement-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Product', 'Type', 'Document #', 'Qty In', 'Qty Out', 'Cost', 'User']);

            foreach ($movements as $movement) {
                fputcsv($file, [
                    $movement->created_at->format('Y-m-d H:i'),
                    $movement->product->name,
                    $movement->document_type,
                    $movement->document_id,
                    $movement->qty > 0 ? $movement->qty : '',
                    $movement->qty < 0 ? abs($movement->qty) : '',
                    $movement->unit_cost,
                    $movement->user ? $movement->user->name : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

