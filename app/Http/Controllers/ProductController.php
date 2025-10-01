<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockBatch;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('brand', 'category', 'subcategory')
            ->withSum('stockBatches as on_hand_sum', 'qty_left');

        // Filter by Brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('suppliers', function ($q) use ($request) {
                $q->where('suppliers.id', $request->supplier_id);
            });
        }

        // Filter by Stock Status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'negative':
                    $query->havingRaw('on_hand_sum < 0');
                    break;
                case 'out':
                    $query->havingRaw('on_hand_sum = 0');
                    break;
                case 'low':
                    $query->whereRaw('(SELECT SUM(qty_left) FROM stock_batches WHERE stock_batches.product_id = products.id) <= products.reorder_level')
                        ->havingRaw('on_hand_sum > 0');
                    break;
                case 'in':
                    $query->havingRaw('on_hand_sum > 0');
                    break;
            }
        }

        // Search by name, SKU, or barcode
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode_primary', 'like', "%{$search}%")
                    ->orWhere('barcode_alternate', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(15);

        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->get();
        $subCategories = Category::whereNotNull('parent_id')->get();
        $makes = CarMake::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        $engines = Engine::orderBy('code')->get();
        $suppliers = Supplier::get();



        return view('products.index', compact(
            'products',
            'brands',
            'categories',
            'subCategories',
            'makes',
            'models',
            'engines',
            'suppliers'
        ));
    }

    public function show(Product $product)
    {
        $product->load('brand', 'category', 'subcategory', 'suppliers', 'oeNumbers', 'crossRefs', 'fitments', 'images');
        $batches = $product->stockBatches()->orderBy('received_date', 'desc')->get();
        $ledger = $product->stockLedger()->orderByDesc('created_at')->paginate(20);

        return view('products.show', compact('product', 'batches', 'ledger'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode_primary' => 'nullable|string|max:100|unique:products,barcode_primary',
            'barcode_alternate' => 'nullable|string|max:100',
            'unit' => 'nullable|in:PCS,SET',
            'bin_location' => ['nullable'],
            'price_normal' => 'nullable|numeric|min:0',
            'price_online' => 'nullable|numeric|min:0',
            'price_workshop' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'allow_negative' => 'boolean',
            'special_order' => 'boolean',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'oe_numbers' => 'nullable|string',
            'cross_refs' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $validated['name'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'sku' => $validated['sku'] ?? null, // Auto-generated via boot
                'barcode_primary' => $validated['barcode_primary'] ?? null,
                'barcode_alternate' => $validated['barcode_alternate'] ?? null,
                'unit' => $validated['unit'] ?? 'PCS',
                'bin_location' => $validated['bin_location'] ?? null,
                'price_normal' => $validated['price_normal'] ?? 0,
                'price_online' => $validated['price_online'] ?? 0,
                'price_workshop' => $validated['price_workshop'] ?? 0,
                'reorder_level' => $validated['reorder_level'] ?? 0,
                'allow_negative' => $request->boolean('allow_negative'),
                'special_order' => $request->boolean('special_order'),
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // OE Numbers
            if (!empty($validated['oe_numbers'])) {
                foreach (explode(',', $validated['oe_numbers']) as $oe) {
                    $product->oeNumbers()->create(['oe_number' => trim($oe)]);
                }
            }

            // Cross References
            if (!empty($validated['cross_refs'])) {
                foreach (explode(',', $validated['cross_refs']) as $ref) {
                    $product->crossRefs()->create(['cross_ref' => trim($ref)]);
                }
            }

            // Suppliers
            if (!empty($validated['supplier_ids'])) {
                $product->suppliers()->sync($validated['supplier_ids']);
            }

            // Fitments
            if ($request->has('fitments')) {
                foreach ($request->fitments as $fit) {
                    if (!empty($fit['make_id']) && !empty($fit['model_id'])) {
                        $product->fitments()->create([
                            'make_id' => $fit['make_id'],
                            'model_id' => $fit['model_id'],
                            'engine_id' => $fit['engine_id'] ?? null,
                            'year_start' => $fit['year_start'] ?? null,
                            'year_end' => $fit['year_end'] ?? null,
                        ]);
                    }
                }
            }

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    $product->images()->create(['path' => $path]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'barcode_primary' => 'nullable|string|max:100|unique:products,barcode_primary,' . $product->id,
            'barcode_alternate' => 'nullable|string|max:100',
            'unit' => 'nullable|in:PCS,SET',
            'bin_location' => ['nullable', 'string', 'max:50', 'regex:/^[A-Z]-\d+$/'],
            'price_normal' => 'nullable|numeric|min:0',
            'price_online' => 'nullable|numeric|min:0',
            'price_workshop' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'allow_negative' => 'boolean',
            'special_order' => 'boolean',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'oe_numbers' => 'nullable|string',
            'cross_refs' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => $validated['name'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'barcode_alternate' => $validated['barcode_alternate'] ?? null,
                'unit' => $validated['unit'] ?? 'PCS',
                'bin_location' => $validated['bin_location'] ?? null,
                'price_normal' => $validated['price_normal'] ?? 0,
                'price_online' => $validated['price_online'] ?? 0,
                'price_workshop' => $validated['price_workshop'] ?? 0,
                'reorder_level' => $validated['reorder_level'] ?? 0,
                'allow_negative' => $request->boolean('allow_negative'),
                'special_order' => $request->boolean('special_order'),
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Suppliers
            $product->suppliers()->sync($validated['supplier_ids'] ?? []);

            // OE Numbers
            $product->oeNumbers()->delete();
            if (!empty($validated['oe_numbers'])) {
                foreach (explode(',', $validated['oe_numbers']) as $oe) {
                    $product->oeNumbers()->create(['oe_number' => trim($oe)]);
                }
            }

            // Cross References
            $product->crossRefs()->delete();
            if (!empty($validated['cross_refs'])) {
                foreach (explode(',', $validated['cross_refs']) as $ref) {
                    $product->crossRefs()->create(['cross_ref' => trim($ref)]);
                }
            }

            // Fitments
            $product->fitments()->delete();
            if ($request->has('fitments')) {
                foreach ($request->fitments as $fit) {
                    if (!empty($fit['make_id']) && !empty($fit['model_id'])) {
                        $product->fitments()->create([
                            'make_id' => $fit['make_id'],
                            'model_id' => $fit['model_id'],
                            'engine_id' => $fit['engine_id'] ?? null,
                            'year_start' => $fit['year_start'] ?? null,
                            'year_end' => $fit['year_end'] ?? null,
                        ]);
                    }
                }
            }

            // Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    $product->images()->create(['path' => $path]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    public function toggleStatus(Product $product)
    {
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        return redirect()->back()->with('success', 'Product status updated.');
    }

    public function quickAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_normal' => 'required|numeric|min:0',
            'qty' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $request->name,
                'price_normal' => $request->price_normal,
                'price_online' => $request->price_normal,
                'price_workshop' => $request->price_normal,
                'unit' => 'PCS',
                'allow_negative' => true,
                'special_order' => true,
                'status' => 'active',
            ]);

            // If qty provided, create stock batch
            if ($request->filled('qty') && $request->qty > 0) {
                StockBatch::create([
                    'product_id' => $product->id,
                    'qty_received' => $request->qty,
                    'qty_left' => $request->qty,
                    'landed_unit_cost' => 0,
                    'received_date' => now(),
                ]);

                StockLedger::create([
                    'product_id' => $product->id,
                    'document_type' => 'QUICK_ADD',
                    'document_id' => null,
                    'qty' => $request->qty,
                    'unit_cost' => 0,
                    'total_cost' => 0,
                    'user_id' => auth()->id(),
                    'notes' => 'Quick Add - Initial Stock',
                ]);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Quick product added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * FIFO Stock Consumption
     */
    public function consumeStockFIFO($productId, $qtyNeeded, $documentType = 'INVOICE', $documentId = null)
    {
        $remaining = $qtyNeeded;
        $batches = StockBatch::where('product_id', $productId)
            ->where('qty_left', '>', 0)
            ->orderBy('received_date', 'asc')
            ->get();

        foreach ($batches as $batch) {
            if ($remaining <= 0)
                break;

            $take = min($batch->qty_left, $remaining);
            $batch->qty_left -= $take;
            $batch->save();

            StockLedger::create([
                'product_id' => $productId,
                'document_type' => $documentType,
                'document_id' => $documentId,
                'qty' => -$take,
                'unit_cost' => $batch->landed_unit_cost,
                'total_cost' => -($take * $batch->landed_unit_cost),
                'user_id' => auth()->id(),
                'notes' => 'FIFO consumption',
            ]);

            $remaining -= $take;
        }

        // Handle negative stock
        if ($remaining > 0) {
            $lastCost = StockBatch::where('product_id', $productId)
                ->where('landed_unit_cost', '>', 0)
                ->orderByDesc('received_date')
                ->value('landed_unit_cost') ?? 0;

            StockLedger::create([
                'product_id' => $productId,
                'document_type' => $documentType,
                'document_id' => $documentId,
                'qty' => -$remaining,
                'unit_cost' => $lastCost,
                'total_cost' => -($remaining * $lastCost),
                'user_id' => auth()->id(),
                'notes' => 'Negative sale - adjust when GRN lands',
            ]);
        }
    }
}