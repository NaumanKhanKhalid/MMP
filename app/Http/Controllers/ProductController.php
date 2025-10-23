<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Engine;
use App\Models\Product;
use App\Models\ProductOeNumber;
use App\Models\StockBatch;
use App\Models\StockLedger;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
                    ->orWhere('supplier_code', 'like', "%{$search}%")
                    ->orWhere('bin_location', 'like', "%{$search}%")
                    ->orWhere('barcode_primary', 'like', "%{$search}%")
                    ->orWhere('barcode_alternate', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('brand', function($bq) use ($search) {
                        $bq->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('oeNumbers', function($oq) use ($search) {
                        $oq->where('oe_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('crossRefs', function($crq) use ($search) {
                        $crq->where('cross_ref', 'like', "%{$search}%");
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

        $products = $query->orderByDesc('id')->paginate(10);

        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->get();
        $subCategories = Category::whereNotNull('parent_id')->get();
        $makes = CarMake::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get();
        $engines = Engine::orderBy('code')->get();
        $suppliers = Supplier::get();

        // Handle AJAX requests for filtering/pagination
        if ($request->ajax() && $request->get('ajax') == '2') {
            \Log::info('AJAX Products Request', [
                'page' => $request->get('page'),
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage()
            ]);
            
            return response()->json([
                'success' => true,
                'table' => view('products.partials.table', compact('products', 'brands', 'categories', 'subCategories', 'makes', 'models', 'engines', 'suppliers'))->render(),
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
            'car_model_id' => 'nullable|exists:car_models,id',
            'engine_id' => 'nullable|exists:engines,id',
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
                'car_model_id' => $validated['car_model_id'] ?? null,
                'engine_id' => $validated['engine_id'] ?? null,
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
            'oe_number' => 'nullable|string|max:255',
            'supplier_code' => 'nullable|string|max:255',
            'brand_code' => 'nullable|string|max:255',
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
                'supplier_code' => $request->supplier_code,
                'price_normal' => $request->price_normal,
                'price_online' => $request->price_normal,
                'price_workshop' => $request->price_normal,
                'unit' => 'PCS',
                'allow_negative' => true,
                'special_order' => true,
                'status' => 'active',
            ]);

            // Add OE Number if provided
            if ($request->filled('oe_number')) {
                ProductOeNumber::create([
                    'product_id' => $product->id,
                    'oe_number' => $request->oe_number,
                ]);
            }

            // Add Brand Code to notes if provided (since there's no brand_code field in products table)
            if ($request->filled('brand_code')) {
                $product->update([
                    'notes' => 'Brand Code: ' . $request->brand_code
                ]);
            }

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

            if ($format === 'pdf') {
                $filename = 'products_'.date('Y-m-d_H-i-s').'.pdf';
                return $this->exportToPdf($products, $filename);
            } elseif ($format === 'csv') {
                $filename = 'products_'.date('Y-m-d_H-i-s').'.csv';
                return $this->exportToCsv($products, $filename);
            } elseif ($format === 'excel') {
                $filename = 'products_'.date('Y-m-d_H-i-s').'.xlsx';
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
     * Import products from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('import_file');
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                return $this->importFromCsv($file);
            } else {
                return $this->importFromExcel($file);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Import from CSV
     */
    private function importFromCsv($file)
    {
        $csvData = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($csvData);
        
        $imported = 0;
        $errors = [];
        $warnings = [];
        
        // Validate required columns
        $requiredColumns = ['name', 'price_normal'];
        $missingColumns = array_diff($requiredColumns, $header);
        
        if (!empty($missingColumns)) {
            return redirect()->back()->withErrors([
                'error' => 'Missing required columns: ' . implode(', ', $missingColumns)
            ]);
        }
        
        foreach ($csvData as $index => $row) {
            try {
                if (count($row) !== count($header)) {
                    $errors[] = "Row " . ($index + 2) . ": Column count mismatch";
                    continue;
                }
                
                $data = array_combine($header, $row);
                
                // Validate required fields
                if (empty($data['name'])) {
                    $errors[] = "Row " . ($index + 2) . ": Product name is required";
                    continue;
                }
                
                // Generate SKU if not provided
                if (empty($data['sku'])) {
                    $data['sku'] = 'MMP' . str_pad(Product::max('id') + $imported + 1, 4, '0', STR_PAD_LEFT);
                }
                
                // Check for duplicate SKU
                if (Product::where('sku', $data['sku'])->exists()) {
                    $warnings[] = "Row " . ($index + 2) . ": SKU '{$data['sku']}' already exists, skipping";
                    continue;
                }
                
                // Create product with enhanced data
                $product = Product::create([
                    'name' => trim($data['name']),
                    'sku' => trim($data['sku']),
                    'supplier_code' => trim($data['supplier_code'] ?? ''),
                    'price_normal' => (float)($data['price_normal'] ?? 0),
                    'price_online' => (float)($data['price_online'] ?? $data['price_normal'] ?? 0),
                    'price_workshop' => (float)($data['price_workshop'] ?? $data['price_normal'] ?? 0),
                    'unit' => trim($data['unit'] ?? 'PCS'),
                    'status' => in_array($data['status'] ?? 'active', ['active', 'inactive']) ? $data['status'] : 'active',
                    'bin_location' => trim($data['bin_location'] ?? ''),
                    'reorder_level' => (int)($data['reorder_level'] ?? 0),
                    'notes' => trim($data['notes'] ?? ''),
                    'allow_negative_sale' => true, // Default for imported products
                    'special_order_only' => false,
                ]);
                
                // Create brand code if provided
                if (!empty($data['brand_code'])) {
                    $product->notes = ($product->notes ? $product->notes . "\n" : '') . "Brand Code: " . trim($data['brand_code']);
                }
                
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        $message = "Successfully imported {$imported} products";
        if (!empty($warnings)) {
            $message .= " with " . count($warnings) . " warnings";
        }
        if (!empty($errors)) {
            $message .= " and " . count($errors) . " errors";
        }
        
        return redirect()->back()->with([
            'success' => $message,
            'warnings' => $warnings,
            'errors' => $errors
        ]);
    }

    /**
     * Import from Excel
     */
    private function importFromExcel($file)
    {
        // For now, convert Excel to CSV and process
        // In a real implementation, you'd use PhpSpreadsheet
        return $this->importFromCsv($file);
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
     * Export products to Excel (Proper .xlsx format using PhpSpreadsheet)
     */
    private function exportToExcel($products, $filename)
    {
        // Change filename to .xlsx for proper Excel format
        $filename = str_replace('.csv', '.xlsx', $filename);
        
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set sheet title
            $sheet->setTitle('Products Export');
            
            // Headers
            $headers = [
                'A1' => 'SKU',
                'B1' => 'Product Name',
                'C1' => 'Brand',
                'D1' => 'Category',
                'E1' => 'Subcategory',
                'F1' => 'Supplier',
                'G1' => 'Supplier Code',
                'H1' => 'Unit',
                'I1' => 'Last Cost (R)',
                'J1' => 'Total Stock',
                'K1' => 'Normal Price (R)',
                'L1' => 'Online Price (R)',
                'M1' => 'Workshop Price (R)',
                'N1' => 'OE Numbers',
                'O1' => 'Cross References',
                'P1' => 'Bin Location',
                'Q1' => 'Reorder Level',
                'R1' => 'Status',
                'S1' => 'Allow Negative Sale',
                'T1' => 'Special Order Only',
                'U1' => 'Created By',
                'V1' => 'Created At',
            ];
            
            // Set headers
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $headerRange = 'A1:V1';
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Add data
            $row = 2;
            foreach ($products as $product) {
                $lastCost = $product->stockBatches->first() ? $product->stockBatches->first()->landed_unit_cost : 0;
                $totalStock = $product->stockBatches->sum('qty_left');
                
                $sheet->setCellValue('A' . $row, $product->sku);
                $sheet->setCellValue('B' . $row, $product->name);
                $sheet->setCellValue('C' . $row, $product->brand ? $product->brand->name : 'No Brand');
                $sheet->setCellValue('D' . $row, $product->category ? $product->category->name : 'Uncategorized');
                $sheet->setCellValue('E' . $row, $product->subcategory ? $product->subcategory->name : '-');
                $sheet->setCellValue('F' . $row, $product->supplier ? $product->supplier->name : 'No Supplier');
                $sheet->setCellValue('G' . $row, $product->supplier_code ?: '-');
                $sheet->setCellValue('H' . $row, $product->unit);
                $sheet->setCellValue('I' . $row, number_format($lastCost, 2));
                $sheet->setCellValue('J' . $row, number_format($totalStock, 4));
                $sheet->setCellValue('K' . $row, number_format($product->normal_price, 2));
                $sheet->setCellValue('L' . $row, number_format($product->online_price, 2));
                $sheet->setCellValue('M' . $row, number_format($product->workshop_price, 2));
                $sheet->setCellValue('N' . $row, $product->oeNumbers->pluck('oe_number')->implode(', '));
                $sheet->setCellValue('O' . $row, $product->crossRefs->pluck('cross_ref_number')->implode(', '));
                $sheet->setCellValue('P' . $row, $product->bin_location ?: '-');
                $sheet->setCellValue('Q' . $row, $product->reorder_level);
                $sheet->setCellValue('R' . $row, ucfirst($product->status));
                $sheet->setCellValue('S' . $row, $product->allow_negative_sale ? 'Yes' : 'No');
                $sheet->setCellValue('T' . $row, $product->special_order_only ? 'Yes' : 'No');
                $sheet->setCellValue('U' . $row, $product->creator ? $product->creator->name : '-');
                $sheet->setCellValue('V' . $row, $product->created_at->format('Y-m-d H:i:s'));
                
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'V') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Add borders
            $dataRange = 'A1:V' . ($row - 1);
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            // Create writer and save to temporary file
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'products_export_') . '.xlsx';
            $writer->save($tempFile);
            
            // Return file download
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Excel export failed: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
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
