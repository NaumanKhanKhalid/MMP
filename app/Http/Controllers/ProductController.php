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
        $categories = Category::all();

        // Fitments ke liye required data
        $makes = CarMake::orderBy('name')->get();
        $models =CarModel::orderBy('name')->get(); // ModelCar use karen agar aapke Model ka naam conflict kare
        $engines = Engine::orderBy('code')->get();

        return view('products.index', compact(
            'products',
            'brands',
            'categories',
            'makes',
            'models',
            'engines',
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
        // 🔹 Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:10',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'suppliers' => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id',
        ]);

        // 🔹 Create Product
        $product = Product::create([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'brand_id' => $validated['brand_id'],
            'category_id' => $validated['category_id'],
            'unit' => $validated['unit'],
            'purchase_price' => $validated['purchase_price'],
            'sale_price' => $validated['sale_price'],
        ]);

        // 🔹 Save Product Suppliers (pivot table: product_suppliers)
        if (!empty($validated['suppliers'])) {
            foreach ($validated['suppliers'] as $supplierId) {
                ProductSupplier::create([
                    'product_id' => $product->id,
                    'supplier_id' => $supplierId,
                ]);
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'primary_supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_code' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:20',
            'images.*' => 'nullable|image|max:2048',
            'bin_location' => 'nullable|string|max:50',
            'reorder_level' => 'nullable|integer|min:0',
            'price_normal' => 'nullable|numeric|min:0',
            'price_online' => 'nullable|numeric|min:0',
            'price_workshop' => 'nullable|numeric|min:0',
            'allow_negative' => 'nullable|boolean',
            'special_order' => 'nullable|boolean',
            'oe_numbers' => 'nullable|string',
            'cross_refs' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $product) {
            $data = $request->only([
                'name',
                'description',
                'brand_id',
                'category_id',
                'primary_supplier_id',
                'supplier_code',
                'unit',
                'bin_location',
                'reorder_level',
                'price_normal',
                'price_online',
                'price_workshop',
                'notes'
            ]);
            $data['allow_negative'] = $request->has('allow_negative') ? boolval($request->allow_negative) : $product->allow_negative;
            $data['special_order'] = $request->has('special_order') ? boolval($request->special_order) : $product->special_order;

            // append new images up to 3
            $images = $product->images ?? [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $images[] = $file->store('products', 'public');
                    if (count($images) >= 3)
                        break;
                }
            }
            $data['images'] = $images ?: null;

            $product->update($data);

            // replace OE and cross refs (simple approach)
            $product->oeNumbers()->delete();
            if ($request->filled('oe_numbers')) {
                $lines = is_array($request->oe_numbers) ? $request->oe_numbers : preg_split("/\r\n|\n|\r/", $request->oe_numbers);
                foreach ($lines as $l) {
                    $l = trim($l);
                    if ($l !== '')
                        ProductOeNumber::create(['product_id' => $product->id, 'oe_number' => $l]);
                }
            }

            $product->crossRefs()->delete();
            if ($request->filled('cross_refs')) {
                $lines = is_array($request->cross_refs) ? $request->cross_refs : preg_split("/\r\n|\n|\r/", $request->cross_refs);
                foreach ($lines as $l) {
                    $l = trim($l);
                    if ($l !== '')
                        ProductCrossRef::create(['product_id' => $product->id, 'cross_ref' => $l]);
                }
            }

            // optionally add primary supplier pivot
            if ($request->filled('primary_supplier_id')) {
                $product->suppliers()->syncWithoutDetaching([
                    $request->primary_supplier_id => [
                        'purchase_price' => 0,
                        'currency' => 'ZAR',
                        'lead_time' => null,
                        'supplier_sku' => $request->supplier_code ?? null
                    ]
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product updated.');
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
