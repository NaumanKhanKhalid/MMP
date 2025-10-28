<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of stock adjustments
     */
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['product', 'user', 'stockCount']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('adjustment_type', $request->type);
        }

        // Filter by product
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('adjustment_number', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        $adjustments = $query->orderBy('adjustment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Load products with all necessary relationships for comprehensive search
        $products = Product::with(['brand', 'category', 'oeNumbers'])
            ->orderBy('name')
            ->get();

        return view('stock-adjustments.index', compact('adjustments', 'products'));
    }

    /**
     * Show form for creating new adjustment
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-adjustments.create', compact('products'));
    }

    /**
     * Store a newly created adjustment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:manual,damage,loss,found,correction',
            'adjustment_qty' => 'required|string|regex:/^[+-]?\d+(\.\d+)?$/',
            'adjustment_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Parse the quantity string to float
        $adjustmentQty = floatval($validated['adjustment_qty']);
        
        // Validate that quantity is not zero
        if ($adjustmentQty == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Adjustment quantity cannot be zero',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            
            // Calculate current stock from batches
            $quantityBefore = $product->stockBatches()->sum('qty_left');
            $quantityAfter = $quantityBefore + $adjustmentQty;

            // Validate that adjustment won't result in negative stock (unless product allows it)
            if ($quantityAfter < 0 && !$product->allow_negative) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adjustment would result in negative stock. Current stock: ' . $quantityBefore,
                ], 400);
            }

            // Get cost for adjustment
            $unitCost = $this->getProductCost($product);

            // Handle batch creation/update based on adjustment type
            if ($adjustmentQty > 0) {
                // CASE 1: Increase (+) - Create new batch
                \App\Models\StockBatch::create([
                    'product_id' => $product->id,
                    'batch_code' => 'ADJ-' . date('YmdHis'),
                    'qty_received' => $adjustmentQty,
                    'qty_left' => $adjustmentQty,
                    'landed_unit_cost' => $unitCost > 0 ? $unitCost : ($product->cost_price ?? 0),
                    'received_date' => $validated['adjustment_date'],
                    'document_type' => 'adjustment',
                    'document_id' => null, // Will update after creating adjustment
                ]);
            } else {
                // CASE 2: Decrease (-) - Reduce from latest batches
                $qtyToReduce = abs($adjustmentQty);
                $batches = $product->stockBatches()
                    ->where('qty_left', '>', 0)
                    ->orderBy('received_date', 'desc') // Latest first
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qtyToReduce <= 0) break;

                    if ($batch->qty_left >= $qtyToReduce) {
                        // This batch has enough
                        $batch->qty_left -= $qtyToReduce;
                        $batch->save();
                        $qtyToReduce = 0;
                    } else {
                        // Use entire batch and continue
                        $qtyToReduce -= $batch->qty_left;
                        $batch->qty_left = 0;
                        $batch->save();
                    }
                }

                if ($qtyToReduce > 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough stock in batches to reduce. Remaining: ' . $qtyToReduce,
                    ], 400);
                }
            }

            // Create adjustment record
            $adjustment = StockAdjustment::create([
                'adjustment_type' => $validated['adjustment_type'],
                'product_id' => $product->id,
                'adjustment_date' => $validated['adjustment_date'],
                'quantity_before' => $quantityBefore,
                'adjustment_qty' => $adjustmentQty,
                'quantity_after' => $quantityAfter,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            // Update batch with adjustment ID if it was an increase
            if ($adjustmentQty > 0) {
                \App\Models\StockBatch::where('product_id', $product->id)
                    ->where('document_type', 'adjustment')
                    ->whereNull('document_id')
                    ->orderBy('id', 'desc')
                    ->first()
                    ->update(['document_id' => $adjustment->id]);
            }

            // Create stock ledger entry
            StockLedger::create([
                'product_id' => $product->id,
                'document_type' => 'ADJUSTMENT',
                'document_id' => $adjustment->id,
                'qty' => $adjustmentQty,
                'unit_cost' => $unitCost > 0 ? $unitCost : ($product->cost_price ?? 0),
                'total_cost' => $adjustmentQty * ($unitCost > 0 ? $unitCost : ($product->cost_price ?? 0)),
                'user_id' => auth()->id(),
                'notes' => $validated['reason'],
            ]);

            // Update product's on_hand from batches sum
            $product->on_hand = $product->stockBatches()->sum('qty_left');
            $product->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjustment created successfully!',
                'adjustment_id' => $adjustment->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating adjustment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get product current stock for AJAX
     */
    public function getProductStock($productId)
    {
        $product = Product::findOrFail($productId);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'on_hand' => $product->on_hand ?? 0,
                'allow_negative' => $product->allow_negative,
            ],
        ]);
    }

    // Helper method to get product cost
    private function getProductCost(Product $product)
    {
        // Try to get average cost from active batches (FIFO)
        try {
            $batches = $product->stockBatches()->where('qty_left', '>', 0)->get();

            if ($batches->isNotEmpty()) {
                $totalCost = 0;
                $totalQty = 0;

                foreach ($batches as $batch) {
                    $totalCost += $batch->qty_left * $batch->landed_unit_cost;
                    $totalQty += $batch->qty_left;
                }

                if ($totalQty > 0) {
                    return round($totalCost / $totalQty, 4);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Error calculating batch cost: ' . $e->getMessage());
        }

        // Fallback to product's cost price or last cost
        return $product->cost_price ?? $product->last_cost ?? 0;
    }
}

