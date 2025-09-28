<?php
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
use App\Models\ProductFitment;
use App\Models\ProductCrossRef;
use App\Models\ProductOeNumber;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        // eager load brand, category, supplier, stock batches
        $products = Product::with('brand', 'category', 'primarySupplier')
            ->withSum('stockBatches as on_hand_sum', 'qty_left')
            ->orderBy('name')
            ->paginate(15);

        $brands = Brand::all();
        $categories = Category::where('parent_id', null)->get();
        $subCategories = Category::where('parent_id', '!=', null)->get();

        // Fitments ke liye required data
        $makes = CarMake::orderBy('name')->get();
        $models = CarModel::orderBy('name')->get(); // ModelCar use karen agar aapke Model ka naam conflict kare
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
            'suppliers',
        ));
    }


    public function show(Product $product)
    {
        // detail including batches and ledger (paginate ledger)
        $product->load('oeNumbers', 'crossRefs', 'suppliers');
        $batches = $product->stockBatches()->orderBy('received_date', 'desc')->get();
        $ledger = $product->stockLedger()->orderByDesc('created_at')->paginate(20);

        return view('products.show', compact('product', 'batches', 'ledger'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'brand_id' => 'nullable|exists:brands,id',
    //         'category_id' => 'nullable|exists:categories,id',
    //         'primary_supplier_id' => 'nullable|exists:suppliers,id',
    //         'supplier_code' => 'nullable|string|max:255',
    //         'unit' => 'nullable|string|max:20',
    //         'images.*' => 'nullable|image|max:2048',
    //         'bin_location' => 'nullable|string|max:50',
    //         'reorder_level' => 'nullable|integer|min:0',
    //         'price_normal' => 'nullable|numeric|min:0',
    //         'price_online' => 'nullable|numeric|min:0',
    //         'price_workshop' => 'nullable|numeric|min:0',
    //         'allow_negative' => 'nullable|boolean',
    //         'special_order' => 'nullable|boolean',
    //         'oe_numbers' => 'nullable|string',
    //         'cross_refs' => 'nullable|string',
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         $sku = Product::generateSku();
    //         $barcode = Product::generateBarcode($sku);

    //         $data = $request->only([
    //             'name',
    //             'description',
    //             'brand_id',
    //             'category_id',
    //             'primary_supplier_id',
    //             'supplier_code',
    //             'unit',
    //             'bin_location',
    //             'reorder_level',
    //             'price_normal',
    //             'price_online',
    //             'price_workshop',
    //             'notes'
    //         ]);
    //         $data['sku'] = $sku;
    //         $data['barcode'] = $barcode;
    //         $data['allow_negative'] = $request->has('allow_negative') ? boolval($request->allow_negative) : true;
    //         $data['special_order'] = $request->has('special_order') ? boolval($request->special_order) : true;

    //         // images
    //         $images = [];
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $file) {
    //                 $images[] = $file->store('products', 'public');
    //                 if (count($images) >= 3)
    //                     break;
    //             }
    //         }
    //         $data['images'] = $images ?: null;

    //         $product = Product::create($data);

    //         // parse OE numbers - allow newline separated text or array
    //         if ($request->filled('oe_numbers')) {
    //             $lines = is_array($request->oe_numbers) ? $request->oe_numbers : preg_split("/\r\n|\n|\r/", $request->oe_numbers);
    //             foreach ($lines as $l) {
    //                 $l = trim($l);
    //                 if ($l !== '')
    //                     ProductOeNumber::create(['product_id' => $product->id, 'oe_number' => $l]);
    //             }
    //         }

    //         if ($request->filled('cross_refs')) {
    //             $lines = is_array($request->cross_refs) ? $request->cross_refs : preg_split("/\r\n|\n|\r/", $request->cross_refs);
    //             foreach ($lines as $l) {
    //                 $l = trim($l);
    //                 if ($l !== '')
    //                     ProductCrossRef::create(['product_id' => $product->id, 'cross_ref' => $l]);
    //             }
    //         }

    //         // Optionally attach primary supplier to pivot if provided
    //         if ($request->filled('primary_supplier_id')) {
    //             $supplier = Supplier::find($request->primary_supplier_id);
    //             if ($supplier) {
    //                 $product->suppliers()->syncWithoutDetaching([
    //                     $supplier->id => [
    //                         'purchase_price' => 0,
    //                         'currency' => 'ZAR',
    //                         'lead_time' => null,
    //                         'supplier_sku' => $request->supplier_code ?? null
    //                     ]
    //                 ]);
    //             }
    //         }
    //     });

    //     return redirect()->route('products.index')->with('success', 'Product created.');
    // }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'supplier_ids' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'supplier_ids.*' => 'exists:suppliers,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'unit' => 'nullable|string|max:20',
            'bin_location' => 'nullable|string|max:50',
            'price_normal' => 'nullable|numeric',
            'price_online' => 'nullable|numeric',
            'price_workshop' => 'nullable|numeric',
            'reorder_level' => 'nullable|integer',
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
            // ✅ Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'],
                'sku' => $validated['sku'] ?? strtoupper(Str::random(8)),
                'barcode' => $validated['barcode'] ?? strtoupper(Str::random(12)),
                'unit' => $validated['unit'] ?? 'PCS',
                'bin_location' => $validated['bin_location'] ?? null,
                'price_normal' => $validated['price_normal'] ?? 0,
                'price_online' => $validated['price_online'] ?? 0,
                'price_workshop' => $validated['price_workshop'] ?? 0,
                'reorder_level' => $validated['reorder_level'] ?? 0,
                'allow_negative' => $request->allow_negative ?? 0,
                'special_order' => $request->special_order ?? 0,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,

                'notes' => $validated['notes'] ?? null,
            ]);

            // OE Numbers (assuming comma-separated string input)
            if (!empty($validated['oe_numbers'])) {
                $oeNumbers = explode(',', $validated['oe_numbers']);
                foreach ($oeNumbers as $oe) {
                    $product->oeNumbers()->create(['oe_number' => trim($oe)]);
                }
            }

            // Cross References (assuming comma-separated string input)
            if (!empty($validated['cross_refs'])) {
                $crossRefs = explode(',', $validated['cross_refs']);
                foreach ($crossRefs as $crossRef) {
                    $product->crossRefs()->create(['cross_ref' => trim($crossRef)]);
                }
            }


            // ✅ Attach Multiple Suppliers
            if (!empty($validated['supplier_ids'])) {
                $product->suppliers()->sync($validated['supplier_ids']);
            }

            // ✅ Save Fitments
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

            // ✅ Upload Images (max 3)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    $product->images()->create([
                        'path' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'supplier_ids' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:categories,id',
            'supplier_ids.*' => 'exists:suppliers,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'unit' => 'nullable|string|max:20',
            'bin_location' => 'nullable|string|max:50',
            'price_normal' => 'nullable|numeric',
            'price_online' => 'nullable|numeric',
            'price_workshop' => 'nullable|numeric',
            'reorder_level' => 'nullable|integer',
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
            // Update product basic info
            $product->update([
                'name' => $validated['name'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'],
                'sku' => $validated['sku'] ?? $product->sku,
                'barcode' => $validated['barcode'] ?? $product->barcode,
                'unit' => $validated['unit'] ?? 'PCS',
                'bin_location' => $validated['bin_location'] ?? null,
                'price_normal' => $validated['price_normal'] ?? 0,
                'price_online' => $validated['price_online'] ?? 0,
                'price_workshop' => $validated['price_workshop'] ?? 0,
                'reorder_level' => $validated['reorder_level'] ?? 0,
                'allow_negative' => $request->allow_negative ?? 0,
                'special_order' => $request->special_order ?? 0,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Sync suppliers (many-to-many)
            if (!empty($validated['supplier_ids'])) {
                $product->suppliers()->sync($validated['supplier_ids']);
            } else {
                $product->suppliers()->sync([]);  // detach all if none selected
            }

            // OE Numbers update:
            // Delete old OE numbers and add new ones
            $product->oeNumbers()->delete();
            if (!empty($validated['oe_numbers'])) {
                $oeNumbers = explode(',', $validated['oe_numbers']);
                foreach ($oeNumbers as $oe) {
                    $product->oeNumbers()->create(['oe_number' => trim($oe)]);
                }
            }

            // Cross References update:
            $product->crossRefs()->delete();
            if (!empty($validated['cross_refs'])) {
                $crossRefs = explode(',', $validated['cross_refs']);
                foreach ($crossRefs as $crossRef) {
                    $product->crossRefs()->create(['cross_ref' => trim($crossRef)]);
                }
            }

            // Fitments update:
            // Delete existing fitments and add new ones from request
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

            // Images update:
            // Optionally handle deletion of old images before adding new ones
            if ($request->hasFile('images')) {
                // Delete old images if you want (optional)
                // foreach ($product->images as $image) {
                //     Storage::disk('public')->delete($image->path);
                //     $image->delete();
                // }

                // Add new images (append)
                foreach ($request->file('images') as $img) {
                    $path = $img->store('products', 'public');
                    $product->images()->create([
                        'path' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function destroy(Product $product)
    {
        // delete images
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $img)
                Storage::disk('public')->delete($img);
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
        ]);

        $sku = Product::generateSku();
        $barcode = Product::generateBarcode($sku);

        Product::create([
            'sku' => $sku,
            'barcode' => $barcode,
            'name' => $request->name,
            'price_normal' => $request->price_normal,
            'price_online' => $request->price_normal,
            'price_workshop' => $request->price_normal,
            'unit' => 'PCS',
            'allow_negative' => true,
            'special_order' => true,
            'status' => 'active'
        ]);

        return redirect()->route('products.index')->with('success', 'Quick product added.');
    }

    /**
     * FIFO consumer — call from Invoice posting.
     * Consumes qty from oldest batches first and records ledger entries.
     * If not enough stock left, creates negative ledger using last known cost (or 0).
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
            $batch->qty_left = $batch->qty_left - $take;
            $batch->save();

            StockLedger::create([
                'product_id' => $productId,
                'document_type' => $documentType,
                'document_id' => $documentId,
                'qty' => -1 * $take,
                'unit_cost' => $batch->landed_unit_cost,
                'total_cost' => -1 * $take * $batch->landed_unit_cost,
                'user_id' => auth()->id(),
                'notes' => 'FIFO consumption'
            ]);

            $remaining -= $take;
        }

        if ($remaining > 0) {
            $lastCost = StockBatch::where('product_id', $productId)
                ->where('landed_unit_cost', '>', 0)
                ->orderByDesc('received_date')->value('landed_unit_cost') ?? 0;

            StockLedger::create([
                'product_id' => $productId,
                'document_type' => $documentType,
                'document_id' => $documentId,
                'qty' => -1 * $remaining,
                'unit_cost' => $lastCost,
                'total_cost' => -1 * $remaining * $lastCost,
                'user_id' => auth()->id(),
                'notes' => 'Negative sale - adjust when GRN lands'
            ]);
        }
    }
}
