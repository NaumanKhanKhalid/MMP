<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\StockLedger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date filter (default to current month)
        $filterType = $request->get('filter', 'month');
        
        switch ($filterType) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->get('start_date', now()->startOfMonth());
                $endDate = $request->get('end_date', now()->endOfMonth());
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
        }

        // Calculate metrics
        $metrics = $this->calculateMetrics($startDate, $endDate);
        
        // Get sales chart data (last 30 days)
        $chartData = $this->getSalesChartData();
        
        // Get low stock alerts
        $lowStockProducts = $this->getLowStockProducts();
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity();

        return view('dashboard', compact(
            'metrics',
            'chartData',
            'lowStockProducts',
            'recentActivity',
            'filterType',
            'startDate',
            'endDate'
        ));
    }

    private function calculateMetrics($startDate, $endDate)
    {
        // Revenue (Total Sales)
        $invoices = Invoice::whereIn('payment_status', ['posted', 'paid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $revenue = $invoices->sum('grand_total');
        
        // Cost (sum of all line costs)
        $totalCost = $invoices->sum(function ($invoice) {
            return $invoice->items->sum('line_cost');
        });
        
        // Gross Profit = Revenue - Cost
        $grossProfit = $revenue - $totalCost;
        
        // Net Profit (Gross Profit - Fees from payments in period)
        $paymentFees = \App\Models\Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('fee_amount');
        $netProfit = $grossProfit - $paymentFees;
        
        // Inventory Value (all products with stock)
        $inventoryValue = Product::with('stockBatches')->get()->sum(function ($product) {
            if ($product->on_hand > 0) {
                return $product->on_hand * $this->getAverageCost($product);
            }
            return 0;
        });
        
        // Debtors Balance (total owed by customers)
        $debtorsBalance = Customer::sum('balance');
        
        // Creditors Balance (total we owe suppliers)
        $creditorsBalance = Supplier::sum('balance');

        // Previous period comparison
        $prevStartDate = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
        $prevEndDate = $startDate->copy()->subDay();
        
        $prevRevenue = Invoice::whereIn('payment_status', ['posted', 'paid'])
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->sum('grand_total');
        
        $revenueChange = $prevRevenue > 0 ? (($revenue - $prevRevenue) / $prevRevenue) * 100 : 0;

        return [
            'revenue' => $revenue,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'inventory_value' => $inventoryValue,
            'debtors_balance' => $debtorsBalance,
            'creditors_balance' => $creditorsBalance,
            'revenue_change' => $revenueChange,
            'invoice_count' => $invoices->count(),
        ];
    }

    private function getSalesChartData()
    {
        // Get last 30 days of sales
        $salesData = [];
        $labels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $daySales = Invoice::whereIn('payment_status', ['posted', 'paid'])
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('grand_total');
            
            $salesData[] = $daySales;
            $labels[] = $date->format('d M');
        }

        return [
            'labels' => $labels,
            'data' => $salesData,
        ];
    }

    private function getLowStockProducts()
    {
        return Product::with(['category', 'brand', 'stockBatches'])
            ->where('reorder_level', '>', 0)
            ->get()
            ->filter(function ($product) {
                return $product->on_hand <= $product->reorder_level;
            })
            ->sortBy('on_hand')
            ->take(10);
    }

    private function getRecentActivity()
    {
        return StockLedger::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

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
}
