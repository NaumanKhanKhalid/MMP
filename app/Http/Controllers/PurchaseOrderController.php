<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
   public function index(Request $request)
{
    $suppliers = Supplier::all();
    $products = Product::all();
    $orders = PurchaseOrder::with('supplier', 'user')->latest()->paginate(15);

    $purchaseOrders = PurchaseOrder::with(['supplier', 'items.product'])
        ->get()
        ->map(function ($po) {
            return [
                'id'        => $po->id,
                'po_number' => $po->po_number,
                'supplier'  => $po->supplier,
                'items'     => $po->items->map(function ($item) {
                    return [
                        "product_id"   => $item->product_id,
                        "product_name" => $item->product->name ?? '',
                        "quantity"     => $item->quantity,
                        "unit_price"   => $item->unit_price,
                    ];
                }),
            ];
        });

    return view('purchase_orders.index', compact('orders', 'suppliers', 'products', 'purchaseOrders'));
}


    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $po = new PurchaseOrder();
            $po->po_number = 'PO-' . strtoupper(uniqid());
            $po->supplier_id = $validated['supplier_id'];
            $po->order_date = $validated['order_date'];
            $po->expected_date = $validated['expected_date'] ?? null;
            $po->notes = $validated['notes'] ?? null;
            $po->status = 'draft';
            $po->user_id = auth()->id();
            $po->total_amount = 0; // will be updated below
            $po->save();

            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $po->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
                $total += $itemTotal;
            }
            $po->total_amount = $total;
            $po->save();
            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create PO: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product', 'user');
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $purchaseOrder->load('items.product');
        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,partially_received,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder->supplier_id = $validated['supplier_id'];
            $purchaseOrder->order_date = $validated['order_date'];
            $purchaseOrder->expected_date = $validated['expected_date'] ?? null;
            $purchaseOrder->notes = $validated['notes'] ?? null;
            $purchaseOrder->status = $validated['status'];
            $purchaseOrder->total_amount = 0; // will be updated below
            $purchaseOrder->save();

            // Remove old items
            $purchaseOrder->items()->delete();
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal,
                ]);
                $total += $itemTotal;
            }
            $purchaseOrder->total_amount = $total;
            $purchaseOrder->save();
            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update PO: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted');
    }

    // AJAX: Return view modal HTML for a PO
    public function viewModal($id)
    {
        $po = PurchaseOrder::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchase_orders.view_modal', compact('po'))->render();
    }

    // AJAX: Return edit modal HTML for a PO
    public function editModal($id)
    {
        $po = PurchaseOrder::with(['supplier', 'items.product'])->findOrFail($id);
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $products = \App\Models\Product::orderBy('name')->get();
        return view('purchase_orders.edit_modal', compact('po', 'suppliers', 'products'))->render();
    }

    // Change status action
    public function changeStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,partially_received,completed,cancelled',
            'cancellation_reason' => 'nullable|string',
        ]);
        $oldStatus = $purchaseOrder->status;
        $purchaseOrder->status = $validated['status'];

        // Lock PO if completed or cancelled
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $purchaseOrder->locked = true;
        }

        // On cancelled: log reason
        if ($validated['status'] === 'cancelled' && $oldStatus !== 'cancelled') {
            $purchaseOrder->cancellation_reason = $validated['cancellation_reason'] ?? null;
        }

        $purchaseOrder->save();
        return back()->with('success', 'PO status updated to ' . ucfirst($validated['status']));
    }

    // Call this method from your GRN creation/update logic
    public static function updatePOStatusFromGRN(PurchaseOrder $purchaseOrder)
    {
        // Calculate total received quantity for each item from all linked GRNs
        $poItems = $purchaseOrder->items;
        $allReceived = true;
        $anyReceived = false;
        foreach ($poItems as $item) {
            // Sum received qty from all GRN batches for this PO item
            $receivedQty = \App\Models\GoodsReceipt::where('purchase_order_id', $purchaseOrder->id)
                ->whereHas('batches', function($q) use ($item) {
                    $q->where('product_id', $item->product_id);
                })
                ->with(['batches' => function($q) use ($item) {
                    $q->where('product_id', $item->product_id);
                }])
                ->get()
                ->flatMap->batches
                ->where('product_id', $item->product_id)
                ->sum('qty_received');
            if ($receivedQty >= $item->quantity) {
                $anyReceived = true;
            } else if ($receivedQty > 0) {
                $anyReceived = true;
                $allReceived = false;
            } else {
                $allReceived = false;
            }
        }
        if ($allReceived && $poItems->count()) {
            $purchaseOrder->status = 'completed';
            $purchaseOrder->locked = true;
        } else if ($anyReceived) {
            $purchaseOrder->status = 'partially_received';
            $purchaseOrder->locked = false;
        } else {
            $purchaseOrder->status = 'sent';
            $purchaseOrder->locked = false;
        }
        $purchaseOrder->save();
    }
}
