<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockBatch;
use App\Models\StockLedger;
use App\Models\GoodsReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GoodsReceiptController extends Controller
{
    // AJAX: Return view modal HTML for a GRN
    public function viewModal($id)
    {
        $purchaseOrders = \App\Models\PurchaseOrder::with('items.product')->get();

        $grn = GoodsReceipt::with(['supplier', 'batches.product'])->findOrFail($id);
        return view('goods_receipts.partials.view_modal', compact('grn', 'purchaseOrders'))->render();
    }

    // AJAX: Return edit modal HTML for a GRN
    public function editModal($id)
    {
        $grn = GoodsReceipt::with(['supplier', 'batches.product'])->findOrFail($id);
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $products = \App\Models\Product::orderBy('name')->get();
        return view('goods_receipts.partials.edit_modal', compact('grn', 'suppliers', 'products'))->render();
    }
    public function index(Request $request)
    {
        $purchaseOrders = \App\Models\PurchaseOrder::with('items.product')->get();
        $grns = GoodsReceipt::with(['supplier', 'user'])
            ->when($request->search, fn($q) => $q->where('grn_number', 'like', "%{$request->search}%"))
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('received_date', $request->date))
            ->latest()
            ->paginate(15);

        $products = Product::orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('goods_receipts.index', compact('grns', 'suppliers', 'products', 'purchaseOrders'));
    }


    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('goods_receipts._create_modal', compact('suppliers', 'products'));
    }

    // public function store(Request $request)
    // {
    //     DB::transaction(function () use ($request) {
    //         $grn = GoodsReceipt::create([
    //             'supplier_id' => $request->supplier_id,
    //             'grn_number' => 'GRN-' . time(),
    //             'grn_date' => $request->grn_date,
    //             'invoice_number' => $request->invoice_number,
    //             'total_amount' => 0,
    //             'user_id' => auth()->id(),
    //         ]);

    //         $total = 0;
    //         foreach ($request->products as $item) {
    //             $batch = StockBatch::create([
    //                 'product_id' => $item['product_id'],
    //                 'batch_code' => $request->invoice_number,
    //                 'qty_received' => $item['qty'],
    //                 'qty_left' => $item['qty'],
    //                 'landed_unit_cost' => $item['cost'],
    //                 'received_date' => $request->grn_date,
    //                 'grn_id' => $grn->id,
    //             ]);

    //             StockLedger::create([
    //                 'product_id' => $item['product_id'],
    //                 'document_type' => 'GRN',
    //                 'document_id' => $grn->id,
    //                 'qty' => $item['qty'],
    //                 'unit_cost' => $item['cost'],
    //                 'total_cost' => $item['qty'] * $item['cost'],
    //                 'user_id' => auth()->id(),
    //                 'notes' => 'Received via GRN',
    //             ]);

    //             $total += $item['qty'] * $item['cost'];
    //         }

    //         $grn->update(['total_amount' => $total]);
    //     });

    //     return redirect()->route('grn.index')->with('success', 'GRN created successfully');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'grn_number' => 'required|unique:goods_receipts,grn_number',
            'received_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $grn = GoodsReceipt::create([
                'grn_number' => $request->grn_number,
                'received_date' => $request->received_date,
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'total_amount' => 0, // will update after loop
                'status' => $request->status,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $batch = StockBatch::create([
                    'product_id' => $item['product_id'],
                    'batch_code' => $request->invoice_number,
                    'qty_received' => $item['quantity'],
                    'qty_left' => $item['quantity'],
                    'landed_unit_cost' => $item['purchase_price'],
                    'received_date' => $request->received_date,
                    'grn_id' => $grn->id,
                ]);

                StockLedger::create([
                    'product_id' => $item['product_id'],
                    'document_type' => 'GRN',
                    'document_id' => $grn->id,
                    'qty' => $item['quantity'],
                    'unit_cost' => $item['purchase_price'],
                    'total_cost' => $item['quantity'] * $item['purchase_price'],
                    'user_id' => auth()->id(),
                    'notes' => 'Received via GRN',
                ]);

                $total += $item['quantity'] * $item['purchase_price'];
            }
            $grn->update(['total_amount' => $total]);

            // Update PO status if this GRN is linked to a PO
            if ($request->purchase_order_id) {
                $po = \App\Models\PurchaseOrder::find($request->purchase_order_id);
                if ($po) {
                    \App\Http\Controllers\PurchaseOrderController::updatePOStatusFromGRN($po);
                }
            }
        });

        return redirect()->route('goods-receipts.index')->with('success', 'GRN created successfully!');
    }

    // public function show(GoodsReceipt $grn)
    // {

    //     $grn->load('supplier', 'batches.product');
    //     return view('grn.show', compact('grn'));
    // }


    public function update(Request $request, GoodsReceipt $goods_receipt)
    {
        \Log::info('GRN Update Debug', [
            'grn_id' => $goods_receipt->id,
            'submitted_grn_number' => $request->grn_number,
        ]);
        $request->validate([
            'grn_number' => 'required|unique:goods_receipts,grn_number,' . $goods_receipt->id,
            'received_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $goods_receipt) {
            // Update GRN header
            $goods_receipt->update([
                'grn_number' => $request->grn_number,
                'received_date' => $request->received_date,
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'total_amount' => 0, // will update after loop
                'status' => $request->status,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            // Delete old batches and ledgers for this GRN
            \App\Models\StockBatch::where('grn_id', $goods_receipt->id)->delete();
            \App\Models\StockLedger::where('document_type', 'GRN')->where('document_id', $goods_receipt->id)->delete();

            $total = 0;
            foreach ($request->items as $item) {
                $batch = \App\Models\StockBatch::create([
                    'product_id' => $item['product_id'],
                    'batch_code' => $request->invoice_number,
                    'qty_received' => $item['quantity'],
                    'qty_left' => $item['quantity'],
                    'landed_unit_cost' => $item['purchase_price'],
                    'received_date' => $request->received_date,
                    'grn_id' => $goods_receipt->id,
                ]);

                \App\Models\StockLedger::create([
                    'product_id' => $item['product_id'],
                    'document_type' => 'GRN',
                    'document_id' => $goods_receipt->id,
                    'qty' => $item['quantity'],
                    'unit_cost' => $item['purchase_price'],
                    'total_cost' => $item['quantity'] * $item['purchase_price'],
                    'user_id' => auth()->id(),
                    'notes' => 'Received via GRN',
                ]);

                $total += $item['quantity'] * $item['purchase_price'];
            }

            $goods_receipt->update(['total_amount' => $total]);

            // Update PO status if this GRN is linked to a PO
            if ($request->purchase_order_id) {
                $po = \App\Models\PurchaseOrder::find($request->purchase_order_id);
                if ($po) {
                    \App\Http\Controllers\PurchaseOrderController::updatePOStatusFromGRN($po);
                }
            }
        });

        return redirect()->route('goods-receipts.index')->with('success', 'GRN updated successfully!');
    }

    public function destroy(GoodsReceipt $grn)
    {
        // dd($grn); // Removed debug statement
        try {
            DB::transaction(function () use ($grn) {
                StockBatch::where('grn_id', $grn->id)->delete();
                StockLedger::where('document_type', 'GRN')->where('document_id', $grn->id)->delete();
                $grn->delete();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
        // Check if still exists
        $stillExists = GoodsReceipt::find($grn->id);
        if ($stillExists) {
            return back()->with('error', 'Delete failed: GRN still exists. There may be a foreign key constraint or soft delete.');
        }
        return back()->with('success', 'GRN deleted');
    }

    public function show($id)
    {
        $grn = GoodsReceipt::with(['supplier', 'items.product'])->findOrFail($id);
        return response()->json($grn);
    }

    public function edit($id)
    {
        $grn = GoodsReceipt::with('supplier')->findOrFail($id);
        return response()->json($grn);
    }

}
