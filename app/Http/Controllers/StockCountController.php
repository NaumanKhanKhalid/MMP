<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockCount;
use App\Models\StockCountItem;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockCountController extends Controller
{
    /**
     * Display a listing of stock counts
     */
    public function index()
    {
        $counts = StockCount::with(['user', 'category', 'brand'])
            ->orderBy('count_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('stock-counts.index', compact('counts'));
    }

    /**
     * Show form for creating new stock count
     */
    public function create(Request $request)
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // If it's an AJAX request (from modal), return only the modal content
        if ($request->ajax() || $request->wantsJson()) {
            return view('stock-counts._create_modal', compact('categories', 'brands'));
        }

        // Otherwise return the full page
        return view('stock-counts.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created stock count
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'count_name' => 'required|string|max:255',
            'count_date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'bin_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create stock count
            $stockCount = StockCount::create([
                'count_name' => $validated['count_name'],
                'count_date' => $validated['count_date'],
                'category_id' => $validated['category_id'] ?? null,
                'brand_id' => $validated['brand_id'] ?? null,
                'bin_location' => $validated['bin_location'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
                'status' => 'draft',
                'filters' => [
                    'category_id' => $validated['category_id'] ?? null,
                    'brand_id' => $validated['brand_id'] ?? null,
                    'bin_location' => $validated['bin_location'] ?? null,
                ],
            ]);

            // Get products based on filters
            $productsQuery = Product::query();

            if (!empty($validated['category_id'])) {
                $productsQuery->where('category_id', $validated['category_id']);
            }

            if (!empty($validated['brand_id'])) {
                $productsQuery->where('brand_id', $validated['brand_id']);
            }

            if (!empty($validated['bin_location'])) {
                $productsQuery->where('bin_location', 'LIKE', '%' . $validated['bin_location'] . '%');
            }

            $products = $productsQuery->get();

            // Create count items
            foreach ($products as $product) {
                // Get current system quantity from batches (proper way)
                $systemQty = $product->stockBatches()->sum('qty_left');

                // Get average cost (weighted average from batches or last cost)
                $unitCost = $this->getAverageCost($product);

                StockCountItem::create([
                    'stock_count_id' => $stockCount->id,
                    'product_id' => $product->id,
                    'system_qty' => $systemQty,
                    'unit_cost' => $unitCost,
                    'is_counted' => false,
                ]);
            }

            // Update total products
            $stockCount->update(['total_products' => $products->count()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock count created successfully!',
                'count_id' => $stockCount->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating stock count: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show counting screen
     */
    public function count($id)
    {
        $stockCount = StockCount::with([
            'items.product.brand',
            'items.product.category',
            'items.product.oeNumbers',
        ])->findOrFail($id);

        if (!$stockCount->canEdit()) {
            return redirect()->route('stock-counts.index')
                ->with('error', 'This stock count cannot be edited.');
        }

        return view('stock-counts.count', compact('stockCount'));
    }

    /**
     * Update counted quantity for an item
     */
    public function updateItem(Request $request, $countId, $itemId)
    {
        $validated = $request->validate([
            'counted_qty' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $item = StockCountItem::where('stock_count_id', $countId)
            ->where('id', $itemId)
            ->firstOrFail();

        // Update counted quantity
        $item->counted_qty = $validated['counted_qty'];
        $item->notes = $validated['notes'] ?? null;
        $item->calculateVariance();
        $item->save();

        // Update stock count summary
        $this->updateCountSummary($countId);

        return response()->json([
            'success' => true,
            'item' => $item->load('product'),
        ]);
    }

    /**
     * Get stock count statistics for real-time updates
     */
    public function getStats($id)
    {
        $stockCount = StockCount::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'total_products' => $stockCount->total_products,
            'counted_products' => $stockCount->counted_products,
            'products_with_variance' => $stockCount->products_with_variance,
            'total_variance_value' => $stockCount->total_variance_value,
            'progress_percentage' => $stockCount->progress_percentage,
        ]);
    }

    /**
     * Mark stock count as in progress
     */
    public function startCounting($id)
    {
        $stockCount = StockCount::findOrFail($id);

        if (!$stockCount->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft counts can be started.',
            ], 400);
        }

        $stockCount->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Stock count started!',
        ]);
    }

    /**
     * Complete counting (ready for review)
     */
    public function completeCounting($id)
    {
        $stockCount = StockCount::findOrFail($id);

        if (!$stockCount->isInProgress()) {
            return response()->json([
                'success' => false,
                'message' => 'Count must be in progress to complete.',
            ], 400);
        }

        // Check if all items are counted
        $uncountedItems = $stockCount->items()->where('is_counted', false)->count();

        if ($uncountedItems > 0) {
            return response()->json([
                'success' => false,
                'message' => "There are {$uncountedItems} items not yet counted.",
            ], 400);
        }

        $stockCount->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Stock count completed! Ready to post.',
        ]);
    }

    /**
     * Post stock count adjustments
     */
    public function post($id)
    {
        $stockCount = StockCount::with('items.product')->findOrFail($id);

        if (!$stockCount->canPost()) {
            return response()->json([
                'success' => false,
                'message' => 'Only completed counts can be posted.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $adjustmentsCreated = 0;

            // Create adjustments for items with variance
            foreach ($stockCount->items as $item) {
                if ($item->hasVariance()) {
                    $product = $item->product;
                    $varianceQty = $item->variance_qty;

                    // Get cost for adjustment
                    $unitCost = $item->unit_cost > 0 ? $item->unit_cost : ($this->getAverageCost($product) ?? 0);

                    // Handle batch creation/update based on variance type
                    if ($varianceQty > 0) {
                        // VARIANCE POSITIVE: Create new batch (found more stock than system)
                        \App\Models\StockBatch::create([
                            'product_id' => $product->id,
                            'batch_code' => 'COUNT-' . $stockCount->count_number . '-' . $product->sku,
                            'qty_received' => $varianceQty,
                            'qty_left' => $varianceQty,
                            'landed_unit_cost' => $unitCost,
                            'received_date' => $stockCount->count_date,
                            'document_type' => 'stock_count',
                            'document_id' => $stockCount->id,
                        ]);
                    } else {
                        // VARIANCE NEGATIVE: Reduce from latest batches (less stock than system)
                        $qtyToReduce = abs($varianceQty);
                        $batches = $product->stockBatches()
                            ->where('qty_left', '>', 0)
                            ->orderBy('received_date', 'desc')
                            ->orderBy('id', 'desc')
                            ->get();

                        foreach ($batches as $batch) {
                            if ($qtyToReduce <= 0) break;

                            if ($batch->qty_left >= $qtyToReduce) {
                                $batch->qty_left -= $qtyToReduce;
                                $batch->save();
                                $qtyToReduce = 0;
                            } else {
                                $qtyToReduce -= $batch->qty_left;
                                $batch->qty_left = 0;
                                $batch->save();
                            }
                        }
                    }

                    // Create stock adjustment record
                    $adjustment = StockAdjustment::create([
                        'adjustment_type' => 'count',
                        'product_id' => $product->id,
                        'stock_count_id' => $stockCount->id,
                        'adjustment_date' => $stockCount->count_date,
                        'quantity_before' => $item->system_qty,
                        'adjustment_qty' => $varianceQty,
                        'quantity_after' => $item->counted_qty,
                        'reason' => 'Stock count variance - ' . $stockCount->count_number,
                        'notes' => $item->notes,
                        'user_id' => auth()->id(),
                    ]);

                    // Create stock ledger entry
                    StockLedger::create([
                        'product_id' => $product->id,
                        'document_type' => 'STOCK_COUNT',
                        'document_id' => $stockCount->id,
                        'qty' => $varianceQty,
                        'unit_cost' => $unitCost,
                        'total_cost' => $item->variance_value,
                        'user_id' => auth()->id(),
                        'notes' => 'Stock count adjustment - ' . $stockCount->count_number,
                    ]);

                    // Update product's on_hand from batches sum
                    $product->on_hand = $product->stockBatches()->sum('qty_left');
                    $product->save();

                    $adjustmentsCreated++;
                }
            }

            // Update stock count status
            $stockCount->update([
                'status' => 'posted',
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$adjustmentsCreated} stock adjustments posted successfully!",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error posting stock count: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show variance report
     */
    public function varianceReport($id)
    {
        $stockCount = StockCount::with(['items' => function ($query) {
            $query->where('variance_qty', '!=', 0)
                ->with('product')
                ->orderBy('variance_value', 'desc');
        }])->findOrFail($id);

        return view('stock-counts.variance-report', compact('stockCount'));
    }

    /**
     * Cancel stock count
     */
    public function cancel($id)
    {
        $stockCount = StockCount::findOrFail($id);

        if ($stockCount->isPosted()) {
            return response()->json([
                'success' => false,
                'message' => 'Posted counts cannot be cancelled.',
            ], 400);
        }

        $stockCount->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Stock count cancelled.',
        ]);
    }

    /**
     * Delete stock count
     */
    public function destroy($id)
    {
        $stockCount = StockCount::findOrFail($id);

        if ($stockCount->isPosted()) {
            return response()->json([
                'success' => false,
                'message' => 'Posted counts cannot be deleted.',
            ], 400);
        }

        $stockCount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock count deleted successfully.',
        ]);
    }

    // Helper methods
    private function getAverageCost(Product $product)
    {
        // Get weighted average cost from batches
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

    private function updateCountSummary($countId)
    {
        $stockCount = StockCount::findOrFail($countId);
        $items = $stockCount->items;

        $countedProducts = $items->where('is_counted', true)->count();
        $productsWithVariance = $items->where('variance_qty', '!=', 0)->count();
        $totalVarianceValue = $items->sum('variance_value');

        $stockCount->update([
            'counted_products' => $countedProducts,
            'products_with_variance' => $productsWithVariance,
            'total_variance_value' => $totalVarianceValue,
        ]);
    }
}

