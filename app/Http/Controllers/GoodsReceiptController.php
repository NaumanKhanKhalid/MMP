<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockBatch;
use App\Models\StockLedger;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GoodsReceiptController extends Controller
{
    // AJAX: Return view modal HTML for a GRN
    public function viewModal($id)
    {
        $grn = GoodsReceipt::with([
            'supplier', 
            'purchaseOrder.supplier', 
            'items.product', 
            'user'
        ])->findOrFail($id);
        return view('goods_receipts.partials.view_modal', compact('grn'))->render();
    }

    // Print GRN
    public function print(GoodsReceipt $grn)
    {
        $grn->load(['supplier', 'purchaseOrder', 'items.product', 'user']);
        return view('goods_receipts.print', compact('grn'));
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
        
        $grns = GoodsReceipt::with(['supplier', 'user', 'purchaseOrder', 'items'])
            ->when($request->search, function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->where('grn_number', 'like', "%{$request->search}%")
                          ->orWhereHas('purchaseOrder', function($subQ) use ($request) {
                              $subQ->where('po_number', 'like', "%{$request->search}%");
                          });
                });
            })
            ->when($request->supplier_id, fn($q) => $q->where('supplier_id', $request->supplier_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn($q) => $q->whereDate('received_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('received_date', '<=', $request->date_to))
            ->latest()
            ->paginate(10);

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
        $validated = $request->validate([
            'grn_number' => 'nullable|unique:goods_receipts,grn_number',
            'received_date' => 'required|date',
            'supplier_id' => 'required_without:purchase_order_id|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'invoice_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'nullable|exists:purchase_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.ordered_qty' => 'required|numeric|min:0',
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.line_total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate GRN number if not provided
            $grnNumber = $validated['grn_number'] ?? GoodsReceipt::generateGRNNumber();

            // Get supplier_id from validated data or from PO
            $supplierId = $validated['supplier_id'] ?? null;
            if (!$supplierId && $validated['purchase_order_id']) {
                $po = \App\Models\PurchaseOrder::find($validated['purchase_order_id']);
                $supplierId = $po ? $po->supplier_id : null;
            }

            $grn = GoodsReceipt::create([
                'grn_number' => $grnNumber,
                'received_date' => $validated['received_date'],
                'supplier_id' => $supplierId,
                'purchase_order_id' => $validated['purchase_order_id'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'total_amount' => 0, // will update after loop
                'status' => 'pending', // Always create as pending
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            $total = 0;
            foreach ($validated['items'] as $itemData) {
                $product = \App\Models\Product::findOrFail($itemData['product_id']);
                
                // Create GRN item
                \App\Models\GoodsReceiptItem::create([
                    'goods_receipt_id' => $grn->id,
                    'purchase_order_item_id' => $itemData['purchase_order_item_id'] ?? null,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'ordered_qty' => $itemData['ordered_qty'],
                    'received_qty' => $itemData['received_qty'],
                    'unit_cost' => $itemData['unit_cost'],
                    'line_total' => $itemData['line_total'],
                    'batch_number' => GoodsReceiptItem::generateBatchNumber(),
                    'status' => 'pending',
                ]);

                $total += $itemData['line_total'];
            }
            
            $grn->update(['total_amount' => $total]);

            // Auto-post GRN if created from PO
            if ($grn->purchase_order_id) {
                // Post GRN automatically
                $this->postGRNInternal($grn);
                
                // Update PO status
                $po = \App\Models\PurchaseOrder::find($grn->purchase_order_id);
                if ($po) {
                    \App\Http\Controllers\PurchaseOrderController::updatePOStatusFromGRN($po);
                }
            }

            DB::commit();

            // Return JSON for AJAX requests
            if ($request->ajax()) {
                $message = $grn->purchase_order_id 
                    ? 'Stock received and posted successfully!' 
                    : 'GRN created successfully!';
                    
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'grn_id' => $grn->id,
                    'grn_number' => $grn->grn_number,
                ]);
            }

            return redirect()->route('goods-receipts.index')->with('success', 'GRN created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create GRN: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('goods-receipts.index')->with('error', 'Failed to create GRN: ' . $e->getMessage());
        }
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

    /**
     * Post/Complete a GRN (update stock, create batches)
     */
    public function post($id)
    {
        try {
            DB::beginTransaction();

            $grn = GoodsReceipt::with(['items.product', 'purchaseOrder.items'])->findOrFail($id);

            if (!$grn->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'GRN is already posted or cancelled.'
                ], 422);
            }

            // Post GRN using internal method
            $this->postGRNInternal($grn);

            // Update PO status if linked
            if ($grn->purchase_order_id) {
                $po = \App\Models\PurchaseOrder::find($grn->purchase_order_id);
                if ($po) {
                    \App\Http\Controllers\PurchaseOrderController::updatePOStatusFromGRN($po);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'GRN posted successfully! Stock updated.',
                'grn' => $grn
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to post GRN: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Internal method to post GRN (called from store or post)
     */
    private function postGRNInternal($grn)
    {
        // Load relationships if not already loaded
        if (!$grn->relationLoaded('items')) {
            $grn->load(['items.product']);
        }

        // Process each item
        foreach ($grn->items as $item) {
            // Create FIFO stock batch
            $batch = StockBatch::create([
                'product_id' => $item->product_id,
                'batch_number' => $item->batch_number ?? GoodsReceiptItem::generateBatchNumber(),
                'supplier_id' => $grn->supplier_id,
                'purchase_order_id' => $grn->purchase_order_id,
                'goods_receipt_id' => $grn->id,
                'cost_per_unit' => $item->unit_cost,
                'qty_received' => $item->received_qty,
                'qty_left' => $item->received_qty,
                'expiry_date' => $item->expiry_date,
                'received_date' => $grn->received_date,
            ]);

            // Update product stock
            $product = $item->product;
            $product->increment('on_hand', $item->received_qty);
            $product->decrement('on_order', $item->ordered_qty);
            $product->update(['last_cost' => $item->unit_cost]);

            // Create stock ledger entry
            $lineTotal = $item->received_qty * $item->unit_cost;
            StockLedger::create([
                'product_id' => $item->product_id,
                'document_type' => 'GRN',
                'document_id' => $grn->id,
                'qty' => $item->received_qty,
                'unit_cost' => $item->unit_cost,
                'total_cost' => $lineTotal,
                'notes' => 'GRN #' . $grn->grn_number . ' - Batch: ' . $batch->batch_number,
                'user_id' => auth()->id(),
            ]);

            // Update item status
            $item->update(['status' => 'received']);
        }

        // Update GRN status
        $grn->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Get PO items for GRN creation
     */
    public function getPOItems($poId)
    {
        try {
            $po = \App\Models\PurchaseOrder::with(['items.product', 'supplier'])->findOrFail($poId);

            $items = $po->items->map(function ($item) {
                // Calculate received quantity from GRN items (only from completed GRNs)
                $receivedQty = \App\Models\GoodsReceiptItem::where('purchase_order_item_id', $item->id)
                    ->whereHas('goodsReceipt', function($query) {
                        $query->where('status', 'completed');
                    })
                    ->sum('received_qty');
                $remainingQty = $item->quantity - $receivedQty;
                
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Unknown',
                    'product_sku' => $item->product->sku ?? 'N/A',
                    'quantity' => $item->quantity,
                    'received_qty' => $receivedQty,
                    'remaining_qty' => $remainingQty,
                    'unit_price' => $item->unit_price,
                    'can_receive' => $remainingQty > 0,
                ];
            });

            return response()->json([
                'success' => true,
                'po' => [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'supplier' => [
                        'id' => $po->supplier->id,
                        'name' => $po->supplier->name,
                    ],
                ],
                'items' => $items
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load PO items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get full PO details for display
     */
    public function getPODetails($poId)
    {
        try {
            $po = \App\Models\PurchaseOrder::with(['supplier', 'user', 'items.product'])->findOrFail($poId);

            return response()->json([
                'success' => true,
                'po' => [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'status' => $po->status,
                    'status_label' => ucfirst(str_replace('_', ' ', $po->status)),
                    'order_date' => optional($po->order_date)->format('d M Y') ?? 'N/A',
                    'expected_delivery_date' => optional($po->expected_delivery_date)->format('d M Y'),
                    'received_date' => optional($po->received_date)->format('d M Y'),
                    'supplier_name' => $po->supplier->name ?? 'N/A',
                    'supplier_email' => $po->supplier->email ?? '',
                    'supplier_phone' => $po->supplier->phone ?? '',
                    'created_by' => $po->user->name ?? 'N/A',
                    'notes' => $po->notes,
                    'subtotal' => $po->subtotal ?? 0,
                    'total_discount' => $po->total_discount ?? 0,
                    'shipping' => $po->shipping ?? 0,
                    'vat' => $po->vat ?? 0,
                    'grand_total' => $po->grand_total ?? 0,
                    'items' => $po->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->name ?? 'Unknown',
                            'product_sku' => $item->product->sku ?? 'N/A',
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total' => $item->total,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load PO details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get previous receipts for a PO
     */
    public function getPOReceipts($poId)
    {
        try {
            $receipts = GoodsReceipt::where('purchase_order_id', $poId)
                ->with(['items.product', 'user'])
                ->orderBy('received_date', 'desc')
                ->get()
                ->map(function ($receipt) {
                    /** @var \App\Models\GoodsReceipt $receipt */
                    return [
                        'id' => $receipt->id,
                        'grn_number' => $receipt->grn_number,
                        'received_date' => optional($receipt->received_date)->format('d M Y') ?? 'N/A',
                        'status' => $receipt->status,
                        'total_amount' => $receipt->total_amount ?? 0,
                        'user_name' => $receipt->user->name ?? 'N/A',
                        'items' => $receipt->items->map(function ($item) {
                            return [
                                'product_name' => $item->product_name,
                                'product_sku' => $item->product_sku,
                                'received_qty' => $item->received_qty,
                                'unit_cost' => $item->unit_cost,
                                'line_total' => $item->line_total,
                            ];
                        })
                    ];
                });

            return response()->json([
                'success' => true,
                'receipts' => $receipts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load previous receipts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export GRNs
     */
    public function export(Request $request)
    {
        $query = GoodsReceipt::with(['supplier', 'purchaseOrder', 'items']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('grn_number', 'like', "%{$search}%")
                  ->orWhereHas('purchaseOrder', function ($subQ) use ($search) {
                      $subQ->where('po_number', 'like', "%{$search}%");
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
            $query->whereDate('received_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('received_date', '<=', $request->date_to);
        }

        $grns = $query->latest()->get();

        $format = $request->get('format', 'csv');
        
        switch ($format) {
            case 'excel':
                return $this->exportExcel($grns);
            case 'pdf':
                return $this->exportPDF($grns);
            default:
                return $this->exportCSV($grns);
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($grns)
    {
        $filename = 'goods_receipts_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($grns) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'GRN Number', 'Supplier', 'PO Number', 'Received Date', 
                'Total Items', 'Total Amount', 'Status', 'Created By'
            ]);
            
            // CSV Data
            foreach ($grns as $grn) {
                fputcsv($file, [
                    $grn->grn_number,
                    $grn->supplier->name ?? 'N/A',
                    $grn->purchaseOrder->po_number ?? '-',
                    $grn->received_date->format('Y-m-d'),
                    $grn->items->count(),
                    $grn->total_amount ?? 0,
                    ucfirst($grn->status),
                    $grn->user->name ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel
     */
    private function exportExcel($grns)
    {
        // For now, redirect to CSV
        return $this->exportCSV($grns);
    }

    /**
     * Export to PDF
     */
    private function exportPDF($grns)
    {
        // For now, redirect to CSV
        return $this->exportCSV($grns);
    }

    /**
     * Search products for Direct GRN
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('search', '');
        
        if (empty($search)) {
            return response()->json(['success' => false, 'products' => []]);
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
                    'cost' => $product->cost_price ?? 0,
                ];
            });

        return response()->json(['success' => true, 'products' => $products]);
    }

}
