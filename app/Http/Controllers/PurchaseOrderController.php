<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders with filters
     */
   public function index(Request $request)
{
        $query = PurchaseOrder::with(['supplier', 'user', 'items.product']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $purchaseOrders = $query->latest()->paginate(10);
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchase_orders.index', compact('purchaseOrders', 'suppliers'));
    }

    /**
     * Show the form for creating a new purchase order
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'received_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:draft,approved',
            'notes' => 'nullable|string|max:1000',
            'delivery_address' => 'nullable|string|max:500',
            'payment_terms' => 'nullable|string|max:500',
            'subtotal' => 'required|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'vat_enabled' => 'nullable|boolean',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate PO number
            $poNumber = 'PO-' . date('Y') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'received_date' => $validated['received_date'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'subtotal' => $validated['subtotal'],
                'total_discount' => $validated['total_discount'] ?? 0,
                'shipping' => $validated['shipping'] ?? 0,
                'vat' => $validated['vat'] ?? 0,
                'vat_enabled' => $validated['vat_enabled'] ?? false,
                'grand_total' => $validated['grand_total'],
            ]);

            // Create purchase order items
            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase Order created successfully!',
                    'redirect' => route('purchase-orders.index')
                ]);
            }

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create Purchase Order: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create Purchase Order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase order
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $purchaseOrder->load(['supplier', 'user', 'items.product']);
        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified purchase order
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Check if PO can be edited based on status
        $currentStatus = $purchaseOrder->status;
        
        if (!in_array($currentStatus, ['draft', 'approved'])) {
            return response()->json([
                'success' => false,
                'message' => 'This Purchase Order cannot be edited. Only Draft and Approved POs can be edited.'
            ], 403);
        }

        // Different validation rules based on status
        if ($currentStatus === 'approved') {
            // Limited edit for approved POs - only minor fields
            $validated = $request->validate([
                'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
                'notes' => 'nullable|string|max:1000',
            ]);
            
            DB::beginTransaction();
            try {
                $purchaseOrder->update([
                    'expected_delivery_date' => $validated['expected_delivery_date'] ?? $purchaseOrder->expected_delivery_date,
                    'notes' => $validated['notes'] ?? $purchaseOrder->notes,
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase Order updated successfully (Limited Edit Mode)',
                    'purchaseOrder' => $purchaseOrder->load(['supplier', 'user', 'items.product'])
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Purchase Order: ' . $e->getMessage()
                ], 500);
            }
        }
        
        // Full edit for draft POs
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'received_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:draft,approved,cancelled',
            'notes' => 'nullable|string|max:1000',
            'delivery_address' => 'nullable|string|max:500',
            'payment_terms' => 'nullable|string|max:500',
            'subtotal' => 'required|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'vat_enabled' => 'nullable|boolean',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'received_date' => $validated['received_date'] ?? null,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'delivery_address' => $validated['delivery_address'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'subtotal' => $validated['subtotal'],
                'total_discount' => $validated['total_discount'] ?? 0,
                'shipping' => $validated['shipping'] ?? 0,
                'vat' => $validated['vat'] ?? 0,
                'vat_enabled' => $validated['vat_enabled'] ?? false,
                'grand_total' => $validated['grand_total'],
            ]);

            // Delete existing items and create new ones
            $purchaseOrder->items()->delete();
            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase Order updated successfully!',
                    'redirect' => route('purchase-orders.index')
                ]);
            }

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Purchase Order: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update Purchase Order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified purchase order
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
        $purchaseOrder->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Purchase Order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Return view modal HTML for a purchase order
     */
    public function viewModal($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        return view('purchase_orders.partials.view_modal', compact('purchaseOrder'))->render();
    }

    /**
     * AJAX: Return edit modal HTML for a purchase order
     */
    public function editModal($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $vatSettings = Setting::getGroup('vat');
        
        return view('purchase_orders.partials.edit_modal', compact('purchaseOrder', 'suppliers', 'products', 'vatSettings'))->render();
    }

    /**
     * AJAX: Return create modal HTML
     */
    public function createModal()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $vatSettings = Setting::getGroup('vat');
        
        return view('purchase_orders.partials.create_modal', compact('suppliers', 'products', 'vatSettings'))->render();
    }

    /**
     * Print purchase order
     */
    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);
        return view('purchase_orders.print', compact('purchaseOrder'));
    }

    /**
     * Export purchase orders
     */
    public function export(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user', 'items.product']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $purchaseOrders = $query->latest()->get();

        $format = $request->get('format', 'csv');
        
        switch ($format) {
            case 'excel':
                return $this->exportExcel($purchaseOrders);
            case 'pdf':
                return $this->exportPDF($purchaseOrders);
            default:
                return $this->exportCSV($purchaseOrders);
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($purchaseOrders)
    {
        $filename = 'purchase_orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($purchaseOrders) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'PO Number', 'Supplier', 'Order Date', 'Expected Delivery', 
                'Status', 'Subtotal', 'Total Discount', 'Shipping', 'VAT', 'Grand Total', 'Created By'
            ]);

            foreach ($purchaseOrders as $po) {
                fputcsv($file, [
                    $po->po_number,
                    $po->supplier->name ?? '',
                    $po->order_date->format('d/m/Y'),
                    $po->expected_delivery_date ? $po->expected_delivery_date->format('d/m/Y') : '',
                    ucfirst($po->status),
                    number_format($po->subtotal, 2),
                    number_format($po->total_discount, 2),
                    number_format($po->shipping, 2),
                    number_format($po->vat, 2),
                    number_format($po->grand_total, 2),
                    $po->user->name ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel
     */
    private function exportExcel($purchaseOrders)
    {
        // This would require Laravel Excel package
        // For now, return CSV with Excel extension
        return $this->exportCSV($purchaseOrders);
    }

    /**
     * Export to PDF
     */
    private function exportPDF($purchaseOrders)
    {
        // This would require a PDF library like dompdf or tcpdf
        // For now, return a simple HTML view
        return view('purchase_orders.export_pdf', compact('purchaseOrders'));
    }

    /**
     * Search products for purchase order
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        
        if (empty($search)) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%")
            ->orWhere('supplier_code', 'like', "%{$search}%")
            ->with(['category', 'brand'])
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'supplier_code' => $product->supplier_code,
                    'category' => $product->category->name ?? '',
                    'brand' => $product->brand->name ?? '',
                    'cost_price' => $product->cost_price,
                    'selling_price' => $product->selling_price,
                ];
            });

        return response()->json($products);
    }

    /**
     * Approve a purchase order
     */
    public function approve($id)
    {
        try {
            $po = PurchaseOrder::findOrFail($id);

            if (!$po->isDraft()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only draft POs can be approved.'
                ], 422);
            }

            $po->update([
                'status' => 'approved',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase order approved successfully!',
                'po' => $po
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve PO: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close a purchase order (auto-called when all items received)
     */
    public function close($id)
    {
        try {
            $po = PurchaseOrder::with('items')->findOrFail($id);

            if ($po->isClosed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PO is already closed.'
                ], 422);
            }

            // Check if all items are fully received
            if (!$po->allItemsReceived()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot close PO. Not all items have been received.'
                ], 422);
            }

            $po->update([
                'status' => 'closed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase order closed successfully!',
                'po' => $po
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to close PO: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update PO status based on received quantities (called from GRN posting)
     */
    public static function updatePOStatusFromGRN($po)
    {
        // Check if all items are fully received by checking GRN items
        $allReceived = true;
        $someReceived = false;
        
        foreach ($po->items as $poItem) {
            $receivedQty = \App\Models\GoodsReceiptItem::where('purchase_order_item_id', $poItem->id)
                ->sum('received_qty');
            
            if ($receivedQty > 0) {
                $someReceived = true;
            }
            
            if ($receivedQty < $poItem->quantity) {
                $allReceived = false;
            }
        }
        
        // Update PO status
        if ($allReceived && $someReceived) {
            $po->update(['status' => 'closed']);
        } elseif ($someReceived) {
            $po->update(['status' => 'partially_received']);
        }
    }
}