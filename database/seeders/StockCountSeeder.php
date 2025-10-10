<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockCount;
use App\Models\StockCountItem;
use App\Models\StockAdjustment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockCountSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('status', 'active')->get();

        if ($products->isEmpty()) {
            echo "⚠️  Please run ProductSeeder first\n";
            return;
        }

        DB::beginTransaction();

        try {
            // Create 5 stock counts
            for ($i = 1; $i <= 5; $i++) {
                $countDate = now()->subDays(rand(1, 30));
                $status = $i <= 2 ? 'posted' : ($i == 3 ? 'in_progress' : 'draft');
                $countNames = [
                    'Monthly Count - October 2025',
                    'Cycle Count - Fast Movers',
                    'Annual Stock Take',
                    'Spot Check - Workshop',
                    'Partial Count - Category A',
                ];
                
                $stockCount = StockCount::create([
                    'count_name' => $countNames[$i - 1],
                    'count_date' => $countDate,
                    'status' => $status,
                    'notes' => $i % 2 == 0 ? 'Routine stock verification' : 'Scheduled count',
                    'user_id' => 1,
                    'posted_by' => $status === 'posted' ? 1 : null,
                    'posted_at' => $status === 'posted' ? $countDate->addHours(3) : null,
                    'created_at' => $countDate,
                ]);

                // Count 10-20 random products (or all if less than 10)
                $countQty = min($products->count(), rand(10, 20));
                $productsToCount = $products->random($countQty);
                $totalVariance = 0;

                foreach ($productsToCount as $product) {
                    $systemQty = $product->on_hand ?? 0;
                    // Introduce small variances (±5)
                    $variance = rand(-5, 5);
                    $countedQty = max(0, $systemQty + $variance);
                    $varianceQty = $countedQty - $systemQty;

                    StockCountItem::create([
                        'stock_count_id' => $stockCount->id,
                        'product_id' => $product->id,
                        'system_qty' => $systemQty,
                        'counted_qty' => $countedQty,
                        'variance_qty' => $varianceQty,
                        'unit_cost' => $product->cost_price ?? 0,
                        'variance_value' => $varianceQty * ($product->cost_price ?? 0),
                        'is_counted' => true,
                    ]);

                    $totalVariance += abs($varianceQty);

                    // Create adjustment if completed and variance exists
                    if ($status === 'completed' && $varianceQty != 0) {
                        StockAdjustment::create([
                            'product_id' => $product->id,
                            'adjustment_type' => $varianceQty > 0 ? 'increase' : 'decrease',
                            'quantity' => abs($varianceQty),
                            'reason' => 'Stock count variance - ' . $stockCount->id,
                            'reference_number' => 'SC-' . str_pad($stockCount->id, 5, '0', STR_PAD_LEFT),
                            'notes' => 'Adjustment from stock count',
                            'created_by' => 1,
                            'created_at' => $countDate->addHours(3),
                        ]);

                        // Update product on_hand
                        $product->increment('on_hand', $varianceQty);
                    }
                }

                $stockCount->update([
                    'total_products' => $productsToCount->count(),
                    'counted_products' => $productsToCount->count(),
                    'products_with_variance' => $productsToCount->count(),
                    'total_variance_value' => $totalVariance,
                ]);
            }

            DB::commit();
            echo "✅ Created 5 stock counts with items and adjustments\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

