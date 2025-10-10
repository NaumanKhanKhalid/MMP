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

        $products = Product::orderBy('name')->get();

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
            'adjustment_qty' => 'required|numeric|not_in:0',
            'adjustment_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            $quantityBefore = $product->on_hand ?? 0;
            $adjustmentQty = $validated['adjustment_qty'];
            $quantityAfter = $quantityBefore + $adjustmentQty;

            // Validate that adjustment won't result in negative stock (unless product allows it)
            if ($quantityAfter < 0 && !$product->allow_negative) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adjustment would result in negative stock. Current stock: ' . $quantityBefore,
                ], 400);
            }

            // Create adjustment
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

            // Update product on_hand
            $product->on_hand = $quantityAfter;
            $product->save();

            // Get cost for ledger
            $unitCost = $this->getProductCost($product);

            // Create stock ledger entry
            StockLedger::create([
                'product_id' => $product->id,
                'document_type' => 'ADJUSTMENT',
                'document_id' => $adjustment->id,
                'qty' => $adjustmentQty,
                'unit_cost' => $unitCost,
                'total_cost' => $adjustmentQty * $unitCost,
                'user_id' => auth()->id(),
                'notes' => $validated['reason'],
            ]);

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

    // Helper method
    private function getProductCost(Product $product)
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

