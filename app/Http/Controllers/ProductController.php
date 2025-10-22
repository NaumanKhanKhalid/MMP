<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Engine;
use App\Models\Product;
use App\Models\StockBatch;
use App\Models\StockLedger;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'brand',
            'category',
            'subcategory',
            'supplier',
            'stockBatches',
            'oeNumbers',
            'crossRefs',
            'images',
            'creator',
            'fitments.make',
            'fitments.model',
            'fitments.engine',
        ])
            ->withSum('stockBatches as on_hand_sum', 'qty_left')
            ->withSum('stockBatches as reserved', 'reserved_qty')
            ->withSum('stockLedger as actual_stock_sum', 'qty'); // Include negative stock from ledger

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
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by Stock Status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'negative':
                    // Check actual stock from ledger (includes negative sales without batches)
                    $query->havingRaw('actual_stock_sum < 0 OR on_hand_sum < 0');
                    break;
                case 'out':
                    $query->havingRaw('on_hand_sum = 0 AND actual_stock_sum >= 0');
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

        // Search by multiple fields
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    // ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('supplier_code', 'like', "%{$search}%")
                    // ->orWhere('brand_code', 'like', "%{$search}%")
                    ->orWhere('bin_location', 'like', "%{$search}%")
                    ->orWhere('barcode_primary', 'like', "%{$search}%")
                    // ->orWhere('barcode_alternate', 'like', "%{$search}%")
                    ->orWhereHas('oeNumbers', function($oq) use ($search) {
                        $oq->where('oe_number', 'like', "%{$search}%");
                    });
            });
        }

        // Handle AJAX requests for barcode modal
        if ($request->ajax() && $request->get('ajax') == '1') {
            try {
                // Create a simple query for AJAX to avoid complex relationships
                $ajaxQuery = Product::with(['brand', 'category']);

                // Apply filters for barcode modal
                if ($request->filled('search')) {
                    $search = $request->search;
                    $ajaxQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('barcode_primary', 'like', "%{$search}%");
                    });
                }

                if ($request->filled('category')) {
                    $ajaxQuery->where('category_id', $request->category);
                }

                if ($request->filled('status')) {
                    $ajaxQuery->where('status', $request->status);
                }

                $products = $ajaxQuery->withSum('stockBatches as on_hand_sum', 'qty_left')
                    ->withSum('stockBatches as reserved', 'reserved_qty')
                    ->orderBy('name')->limit(50)->get();
                
                return response()->json([
                    'success' => true,
                    'products' => $products->map(function($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'barcode_primary' => $product->barcode_primary,
                            'price_normal' => $product->price_normal,
                            'brand_name' => $product->brand->name ?? 'No Brand',
                            'status' => $product->status,
                            'category_id' => $product->category_id,
                            'on_hand' => $product->on_hand_sum ?? 0,
                            'reserved' => $product->reserved ?? 0,
                            'available' => ($product->on_hand_sum ?? 0) - ($product->reserved ?? 0),
                        ];
                    })  
                ]);
            } catch (\Exception $e) {
                \Log::error('AJAX Products Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading products: ' . $e->getMessage(),
                    'products' => []
                ], 500);
            }
        }

        $products = $query->orderBy('name')->paginate(15);

        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->get();
        $subCategories = Category::whereNotNull('parent_id')->get();
        $makes = CarMake::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        $engines = Engine::orderBy('code')->get();
        $suppliers = Supplier::get();

        // Handle AJAX requests for filtering/pagination
        if ($request->ajax() && $request->get('ajax') == '2') {
            return response()->json([
                'success' => true,
                'table' => view('products.partials.table', compact('products', 'suppliers'))->render(),
                'pagination' => view('products.partials.pagination', compact('products'))->render()
            ]);
        }

        return view('products.index', compact(
            'products',
            'brands',
            'categories',
            'subCategories',
            'makes',
            'models',
            'engines',
            'suppliers',
            'brands'
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
            'supplier_code' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'supplier_ids' => 'nullable|exists:suppliers,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode_primary' => 'nullable|string|max:100|unique:products,barcode_primary',
            'barcode_alternate' => 'nullable|string|max:100',
            'unit' => 'nullable|in:PCS,SET',
            'bin_location' => 'nullable|string|max:50',
            'price_normal' => 'nullable|numeric|min:0',
            'price_online' => 'nullable|numeric|min:0',
            'price_workshop' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'target_stock_level' => 'nullable|integer|min:0',
            'allow_negative' => 'boolean',
            'special_order' => 'boolean',
            'status' => 'required|in:active,inactive',
            'oe_numbers' => 'nullable|string',
            'cross_refs' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fitments' => 'nullable|array',
            'fitments.*.make_id' => 'nullable|exists:car_makes,id',
            'fitments.*.model_id' => 'nullable|exists:car_models,id',
            'fitments.*.engine_id' => 'nullable|exists:engines,id',
            'fitments.*.year_start' => 'nullable|integer|min:1900|max:2100',
            'fitments.*.year_end' => 'nullable|integer|min:1900|max:2100',
            'initial_qty' => 'nullable|integer|min:0',
            'initial_cost' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $validated['name'],
                'supplier_id' => $validated['supplier_ids'] ?? null,
                'supplier_code' => $validated['supplier_code'], // Note: migration has typo 'suplier_code'
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
                'reorder_level' => $validated['reorder_point'] ?? 0,
                'allow_negative' => $request->boolean('allow_negative'),
                'special_order' => $request->boolean('special_order'),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // OE Numbers (Tagify format: JSON array)
            if (! empty($validated['oe_numbers'])) {
                $oeData = json_decode($validated['oe_numbers'], true);
                if (is_array($oeData)) {
                    foreach ($oeData as $item) {
                        if (! empty($item['value'])) {
                            $product->oeNumbers()->create(['oe_number' => trim($item['value'])]);
                        }
                    }
                }
            }

            // Cross References (Tagify format: JSON array)
            if (! empty($validated['cross_refs'])) {
                $crossData = json_decode($validated['cross_refs'], true);
                if (is_array($crossData)) {
                    foreach ($crossData as $item) {
                        if (! empty($item['value'])) {
                            $product->crossRefs()->create(['cross_ref' => trim($item['value'])]);
                        }
                    }
                }
            }

            // Opening Stock Batch
            if ($request->filled('initial_qty') && $request->initial_qty > 0) {
                StockBatch::create([
                    'product_id' => $product->id,
                    'qty_received' => $request->initial_qty,
                    'qty_left' => $request->initial_qty,
                    'landed_unit_cost' => $request->initial_cost ?? 0,
                    'received_date' => now(),
                    'document_type' => 'OPENING_STOCK',
                    'document_id' => null,
                ]);

                // Record in stock ledger
                StockLedger::create([
                    'product_id' => $product->id,
                    'document_type' => 'OPENING_STOCK',
                    'document_id' => null,
                    'qty' => $request->initial_qty,
                    'unit_cost' => $request->initial_cost ?? 0,
                    'total_cost' => ($request->initial_qty * ($request->initial_cost ?? 0)),
                    'user_id' => auth()->id(),
                    'notes' => 'Opening stock on product creation',
                ]);
            }

            // Fitments
            if ($request->has('fitments')) {
                foreach ($request->fitments as $fit) {
                    if (! empty($fit['make_id']) && ! empty($fit['model_id'])) {
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
                    // Store in storage/app/public/products
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
            'supplier_code' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'supplier_ids' => 'nullable|exists:suppliers,id',
            'barcode_alternate' => 'nullable|string|max:100',
            'unit' => 'nullable|in:PCS,SET',
            'bin_location' => 'nullable|string|max:50',
            'price_normal' => 'nullable|numeric|min:0',
            'price_online' => 'nullable|numeric|min:0',
            'price_workshop' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'allow_negative' => 'boolean',
            'special_order' => 'boolean',
            'status' => 'required|in:active,inactive',
            'oe_numbers' => 'nullable|string',
            'cross_refs' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fitments' => 'nullable|array',
            'fitments.*.make_id' => 'nullable|exists:car_makes,id',
            'fitments.*.model_id' => 'nullable|exists:car_models,id',
            'fitments.*.engine_id' => 'nullable|exists:engines,id',
            'fitments.*.year_start' => 'nullable|integer|min:1900|max:2100',
            'fitments.*.year_end' => 'nullable|integer|min:1900|max:2100',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => $validated['name'],
                'supplier_id' => $validated['supplier_ids'] ?? null,
                'supplier_code' => $validated['supplier_code'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'barcode_alternate' => $validated['barcode_alternate'] ?? null,
                'unit' => $validated['unit'] ?? 'PCS',
                'bin_location' => $validated['bin_location'] ?? null,
                'price_normal' => $validated['price_normal'] ?? 0,
                'price_online' => $validated['price_online'] ?? 0,
                'price_workshop' => $validated['price_workshop'] ?? 0,
                'reorder_level' => $validated['reorder_point'] ?? 0,
                'allow_negative' => $request->boolean('allow_negative'),
                'special_order' => $request->boolean('special_order'),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // OE Numbers (Tagify format: JSON array)
            $product->oeNumbers()->delete();
            if (! empty($validated['oe_numbers'])) {
                $oeData = json_decode($validated['oe_numbers'], true);
                if (is_array($oeData)) {
                    foreach ($oeData as $item) {
                        if (! empty($item['value'])) {
                            $product->oeNumbers()->create(['oe_number' => trim($item['value'])]);
                        }
                    }
                }
            }

            // Cross References (Tagify format: JSON array)
            $product->crossRefs()->delete();
            if (! empty($validated['cross_refs'])) {
                $crossData = json_decode($validated['cross_refs'], true);
                if (is_array($crossData)) {
                    foreach ($crossData as $item) {
                        if (! empty($item['value'])) {
                            $product->crossRefs()->create(['cross_ref' => trim($item['value'])]);
                        }
                    }
                }
            }

            // Fitments
            $product->fitments()->delete();
            if ($request->has('fitments')) {
                foreach ($request->fitments as $fit) {
                    if (! empty($fit['make_id']) && ! empty($fit['model_id'])) {
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
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Get first available brand and category as defaults
            $defaultBrand = Brand::first();
            $defaultCategory = Category::whereNull('parent_id')->first();

            if (! $defaultBrand || ! $defaultCategory) {
                return back()->withErrors(['error' => 'Please create at least one Brand and Category first.']);
            }

            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $defaultBrand->id,
                'category_id' => $defaultCategory->id,
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
                $unitCost = $request->filled('unit_cost') ? $request->unit_cost : 0;
                
                StockBatch::create([
                    'product_id' => $product->id,
                    'qty_received' => $request->qty,
                    'qty_left' => $request->qty,
                    'landed_unit_cost' => $unitCost,
                    'received_date' => now(),
                ]);

                StockLedger::create([
                    'product_id' => $product->id,
                    'document_type' => 'QUICK_ADD',
                    'document_id' => null,
                    'qty' => $request->qty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $request->qty * $unitCost,
                    'user_id' => auth()->id(),
                    'notes' => 'Quick Add - Initial Stock',
                ]);
            }

            DB::commit();

            // Check if AJAX request (from job cards, POS, etc)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully',
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode_primary' => $product->barcode_primary,
                        'price_normal' => $product->price_normal,
                        'price_online' => $product->price_online,
                        'price_workshop' => $product->price_workshop,
                        'on_hand' => $request->qty ?? 0,
                    ],
                ]);
            }

            return redirect()->route('products.index')->with('success', 'Quick product added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            
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
            if ($remaining <= 0) {
                break;
            }

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

    /**
     * Export products to PDF, CSV or Excel
     */
    public function export(Request $request)
    {
        // Debug: Log that the method is being called
        \Log::info('Export method called with format: ' . $request->get('format', 'csv'));
        
        try {
            $format = $request->get('format', 'csv');

            // Get all products with relationships
            $products = Product::with(['brand', 'category', 'subcategory', 'supplier', 'stockBatches', 'oeNumbers', 'crossRefs', 'images', 'creator'])
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = 'products_'.date('Y-m-d_H-i-s').'.'.$format;

            if ($format === 'pdf') {
                return $this->exportToPdf($products, $filename);
            } elseif ($format === 'csv') {
                return $this->exportToCsv($products, $filename);
            } elseif ($format === 'excel') {
                return $this->exportToExcel($products, $filename);
            }

            return response()->json(['error' => 'Invalid export format: '.$format], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Export failed: '.$e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    /**
     * Export products to PDF
     */
    private function exportToPdf($products, $filename)
    {
        try {
            $data = [
                'products' => $products,
                'exportDate' => now()->format('Y-m-d H:i:s'),
                'totalProducts' => $products->count(),
            ];

            $pdf = Pdf::loadView('products.exports.pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Fallback: return a simple text response for debugging
            return response()->json([
                'error' => 'PDF generation failed: '.$e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    /**
     * Export products to CSV
     */
    private function exportToCsv($products, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($products) {
            // Add BOM for UTF-8
            echo "\xEF\xBB\xBF";

            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'SKU', 'Product Name', 'Brand', 'Category', 'Subcategory', 'Supplier', 'Supplier Code',
                'Unit', 'Last Cost (R)', 'Total Stock', 'Normal Price (R)', 'Online Price (R)', 'Workshop Price (R)',
                'OE Numbers', 'Cross References', 'Bin Location', 'Reorder Level', 'Status',
                'Allow Negative Sale', 'Special Order Only', 'Created By', 'Created At',
            ]);

            // CSV Data
            foreach ($products as $product) {
                $lastCost = $product->stockBatches->first() ? $product->stockBatches->first()->landed_unit_cost : 0;
                $totalStock = $product->stockBatches->sum('qty_left');

                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->brand ? $product->brand->name : '',
                    $product->category ? $product->category->name : '',
                    $product->subcategory ? $product->subcategory->name : '',
                    $product->supplier ? $product->supplier->name : '',
                    $product->supplier_code ?: '',
                    $product->unit,
                    number_format($lastCost, 2),
                    number_format($totalStock, 4),
                    number_format($product->normal_price, 2),
                    number_format($product->online_price, 2),
                    number_format($product->workshop_price, 2),
                    $product->oeNumbers->pluck('oe_number')->implode(', '),
                    $product->crossRefs->pluck('cross_ref_number')->implode(', '),
                    $product->bin_location ?: '',
                    $product->reorder_level,
                    ucfirst($product->status),
                    $product->allow_negative_sale ? 'Yes' : 'No',
                    $product->special_order_only ? 'Yes' : 'No',
                    $product->creator ? $product->creator->name : '',
                    $product->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export products to Excel (Simple HTML table that Excel can open)
     */
    private function exportToExcel($products, $filename)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Products Export - '.date('Y-m-d H:i:s').'</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #007bff; text-align: center; }
                h2 { color: #333; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #007bff; color: white; font-weight: bold; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                tr:hover { background-color: #e3f2fd; }
                .summary { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .price { color: #28a745; font-weight: bold; }
                .cost { color: #dc3545; }
                .badge { padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
                .badge-active { background-color: #d4edda; color: #155724; }
                .badge-inactive { background-color: #f8d7da; color: #721c24; }
            </style>
        </head>
        <body>
            <h1>MMP Auto-Meister</h1>
            <h2>Products Inventory Report</h2>
            <div class="summary">
                <strong>Export Date:</strong> '.date('Y-m-d H:i:s').'<br>
                <strong>Total Products:</strong> '.$products->count().' | 
                <strong>Active Products:</strong> '.$products->where('status', 'active')->count().' | 
                <strong>Inactive Products:</strong> '.$products->where('status', 'inactive')->count().'
            </div>
            <table>
                <thead>
                    <tr>
                        <th>SKU</th><th>Product Name</th><th>Brand</th><th>Category</th><th>Subcategory</th>
                        <th>Supplier</th><th>Supplier Code</th><th>Unit</th><th>Last Cost (R)</th><th>Total Stock</th>
                        <th>Normal Price (R)</th><th>Online Price (R)</th><th>Workshop Price (R)</th><th>OE Numbers</th>
                        <th>Cross References</th><th>Bin Location</th><th>Reorder Level</th><th>Status</th>
                        <th>Allow Negative Sale</th><th>Special Order Only</th><th>Created By</th><th>Created At</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($products as $product) {
            $lastCost = $product->stockBatches->first() ? $product->stockBatches->first()->landed_unit_cost : 0;
            $totalStock = $product->stockBatches->sum('qty_left');

            $html .= '<tr>
                <td><strong>'.$product->sku.'</strong></td>
                <td>'.htmlspecialchars($product->name).'</td>
                <td>'.($product->brand ? htmlspecialchars($product->brand->name) : '-').'</td>
                <td>'.($product->category ? htmlspecialchars($product->category->name) : '-').'</td>
                <td>'.($product->subcategory ? htmlspecialchars($product->subcategory->name) : '-').'</td>
                <td>'.($product->supplier ? htmlspecialchars($product->supplier->name) : '-').'</td>
                <td>'.htmlspecialchars($product->supplier_code ?: '-').'</td>
                <td>'.$product->unit.'</td>
                <td class="cost">R '.number_format($lastCost, 2).'</td>
                <td>'.number_format($totalStock, 4).'</td>
                <td class="price">R '.number_format($product->normal_price, 2).'</td>
                <td class="price">R '.number_format($product->online_price, 2).'</td>
                <td class="price">R '.number_format($product->workshop_price, 2).'</td>
                <td>'.htmlspecialchars($product->oeNumbers->pluck('oe_number')->implode(', ')).'</td>
                <td>'.htmlspecialchars($product->crossRefs->pluck('cross_ref_number')->implode(', ')).'</td>
                <td>'.htmlspecialchars($product->bin_location ?: '-').'</td>
                <td>'.$product->reorder_level.'</td>
                <td><span class="badge '.($product->status === 'active' ? 'badge-active' : 'badge-inactive').'">'.ucfirst($product->status).'</span></td>
                <td>'.($product->allow_negative_sale ? 'Yes' : 'No').'</td>
                <td>'.($product->special_order_only ? 'Yes' : 'No').'</td>
                <td>'.($product->creator ? htmlspecialchars($product->creator->name) : '-').'</td>
                <td>'.$product->created_at->format('Y-m-d H:i:s').'</td>
            </tr>';
        }

        $html .= '</tbody></table>
            <div style="margin-top: 30px; text-align: center; color: #666; font-size: 12px;">
                <p>© '.date('Y').' MMP Auto-Meister. All rights reserved.</p>
            </div>
        </body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Test PDF generation
     */
    public function testPdf()
    {
        try {
            // Simple test data
            $data = [
                'products' => collect([]),
                'exportDate' => now()->format('Y-m-d H:i:s'),
                'totalProducts' => 0,
            ];

            $pdf = Pdf::loadView('products.exports.pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('test_products.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF test failed: '.$e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
}
