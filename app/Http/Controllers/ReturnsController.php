<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReturn;
use App\Models\ReturnItem;
use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturnsController extends Controller
{
    /**
     * Display a listing of returns
     */
    public function index(Request $request)
    {
        $query = ProductReturn::with(['customer', 'user', 'invoice', 'creditNote']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by return type
        if ($request->filled('type')) {
            $query->where('return_type', $request->type);
        }

        // Filter by refund method
        if ($request->filled('refund_method')) {
            $query->where('refund_method', $request->refund_method);
        }

        // Search in return number, customer name, or invoice number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('invoice', function($q) use ($search) {
                      $q->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new return
     */
    public function create()
    {
        $invoices = Invoice::with(['customer', 'items.product'])
            ->where('payment_status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('returns.partials.create_modal', compact('invoices'))->render();
    }

    /**
     * Store a newly created return
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'return_type' => 'required|in:full,partial,exchange',
            'reason' => 'required|string|max:500',
            'refund_method' => 'required|in:cash,store_credit,exchange,bank_transfer',
            'stock_handling_type' => 'required|in:restock,writeoff,credit_only',
            'items' => 'required|array|min:1',
            'items.*.invoice_item_id' => 'required|exists:invoice_items,id',
            'items.*.quantity_returned' => 'required|integer|min:1',
            'items.*.return_reason' => 'required|string|max:255',
            'items.*.condition' => 'required|in:new,used,damaged,defective',
            'items.*.restock' => 'boolean',
        ]);
        
        // Validate quantities: cannot return more than sold
        foreach ($request->items as $itemData) {
            $invoiceItem = InvoiceItem::find($itemData['invoice_item_id']);
            if (!$invoiceItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid invoice item',
                ], 400);
            }
            
            // Check if trying to return more than original quantity
            if ($itemData['quantity_returned'] > $invoiceItem->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot return more than sold quantity for {$invoiceItem->product_name}. Sold: {$invoiceItem->quantity}, Attempting to return: {$itemData['quantity_returned']}",
                ], 400);
            }
            
            // Check if already returned
            $alreadyReturned = ReturnItem::whereHas('productReturn', function($q) use ($invoiceItem) {
                $q->where('invoice_id', $invoiceItem->invoice_id)
                  ->whereIn('status', ['pending', 'approved', 'completed']);
            })->where('invoice_item_id', $itemData['invoice_item_id'])
              ->sum('quantity_returned');
              
            if (($alreadyReturned + $itemData['quantity_returned']) > $invoiceItem->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Total returns exceed sold quantity for {$invoiceItem->product_name}. Sold: {$invoiceItem->quantity}, Already returned: {$alreadyReturned}",
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($request->invoice_id);
            
            // Create return with stock handling type
            $return = ProductReturn::create([
                'invoice_id' => $request->invoice_id,
                'customer_id' => $invoice->customer_id,
                'user_id' => auth()->id(),
                'return_type' => $request->return_type,
                'reason' => $request->reason,
                'refund_method' => $request->refund_method,
                'stock_handling_type' => $request->stock_handling_type,
                'restock_items' => $request->stock_handling_type === 'restock',
                'status' => 'pending',
            ]);

            $totalAmount = 0;

            // Create return items
            foreach ($request->items as $item) {
                $invoiceItem = InvoiceItem::findOrFail($item['invoice_item_id']);
                
                // Validate quantity
                if ($item['quantity_returned'] > $invoiceItem->quantity) {
                    throw new \Exception("Return quantity cannot exceed original quantity for item: {$invoiceItem->product_name}");
                }

                $lineTotal = $item['quantity_returned'] * $invoiceItem->unit_price;
                $totalAmount += $lineTotal;

                ReturnItem::create([
                    'return_id' => $return->id,
                    'invoice_item_id' => $item['invoice_item_id'],
                    'product_id' => $invoiceItem->product_id,
                    'product_sku' => $invoiceItem->product_sku,
                    'product_name' => $invoiceItem->product_name,
                    'product_barcode' => $invoiceItem->product_barcode,
                    'quantity_returned' => $item['quantity_returned'],
                    'unit_price' => $invoiceItem->unit_price,
                    'line_total' => $lineTotal,
                    'return_reason' => $item['return_reason'],
                    'condition' => $item['condition'],
                    'restock' => $item['restock'] ?? true,
                ]);
            }

            // Update return totals
            $return->update([
                'total_amount' => $totalAmount,
                'refund_amount' => $totalAmount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return created successfully',
                'return_id' => $return->id,
                'return_number' => $return->return_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating return: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified return
     */
    public function show(ProductReturn $return)
    {
        $return->load(['customer', 'user', 'invoice', 'items.product', 'creditNote']);
        
        return view('returns.partials.view_modal', compact('return'))->render();
    }

    /**
     * Show the form for editing the specified return
     */
    public function edit(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending returns can be edited',
            ], 400);
        }

        $return->load(['customer', 'user', 'invoice', 'items.product']);
        $invoices = Invoice::with(['customer', 'items.product'])
            ->where('payment_status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('returns.partials.edit_modal', compact('return', 'invoices'))->render();
    }

    /**
     * Update the specified return
     */
    public function update(Request $request, ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending returns can be updated',
            ], 400);
        }

        $request->validate([
            'return_type' => 'required|in:full,partial,exchange',
            'reason' => 'required|string|max:500',
            'refund_method' => 'required|in:cash,store_credit,exchange,bank_transfer',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:return_items,id',
            'items.*.quantity_returned' => 'required|integer|min:1',
            'items.*.return_reason' => 'required|string|max:255',
            'items.*.condition' => 'required|in:new,used,damaged,defective',
            'items.*.restock' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;

            // Update return items
            foreach ($request->items as $item) {
                $returnItem = ReturnItem::findOrFail($item['id']);
                
                $lineTotal = $item['quantity_returned'] * $returnItem->unit_price;
                $totalAmount += $lineTotal;

                $returnItem->update([
                    'quantity_returned' => $item['quantity_returned'],
                    'line_total' => $lineTotal,
                    'return_reason' => $item['return_reason'],
                    'condition' => $item['condition'],
                    'restock' => $item['restock'] ?? true,
                ]);
            }

            // Update return
            $return->update([
                'return_type' => $request->return_type,
                'reason' => $request->reason,
                'refund_method' => $request->refund_method,
                'total_amount' => $totalAmount,
                'refund_amount' => $totalAmount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return updated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating return: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve a return
     */
    public function approve(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending returns can be approved',
            ], 400);
        }

        $return->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Return approved successfully',
        ]);
    }

    /**
     * Reject a return
     */
    public function reject(Request $request, ProductReturn $return)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        if ($return->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending returns can be rejected',
            ], 400);
        }

        $return->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'processed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Return rejected successfully',
        ]);
    }

    /**
     * Complete a return (process refund and restock)
     */
    public function complete(ProductReturn $return)
    {
        if ($return->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved returns can be completed',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Handle stock based on stock_handling_type
            foreach ($return->items as $item) {
                $product = Product::find($item->product_id);
                
                if ($return->stock_handling_type === 'restock') {
                    // RESTOCK: Return to inventory (FIFO - restore to original batches)
                    // Find the invoice item to get the original batch
                    $invoiceItem = InvoiceItem::find($item->invoice_item_id);
                    
                    if ($invoiceItem && $invoiceItem->stock_batch_id) {
                        // Restore to original batch (FIFO)
                        $stockBatch = \App\Models\StockBatch::find($invoiceItem->stock_batch_id);
                        if ($stockBatch) {
                            $stockBatch->increment('qty_left', $item->quantity_returned);
                        }
                    } else {
                        // If no batch info, create a new batch
                        \App\Models\StockBatch::create([
                            'product_id' => $item->product_id,
                            'qty_received' => $item->quantity_returned,
                            'qty_left' => $item->quantity_returned,
                            'landed_unit_cost' => $item->unit_price,
                            'received_date' => now(),
                            'document_type' => 'return',
                            'document_id' => $return->id,
                        ]);
                    }
                    
                    // Update product stock
                    $product->increment('on_hand', $item->quantity_returned);
                    
                    // Create stock ledger entry
                    StockLedger::create([
                        'product_id' => $item->product_id,
                        'document_type' => 'return',
                        'document_id' => $return->id,
                        'qty' => $item->quantity_returned,
                        'unit_cost' => $item->unit_price,
                        'total_cost' => $item->line_total,
                        'user_id' => auth()->id(),
                        'notes' => 'Return restocked (FIFO) - ' . $item->return_reason,
                    ]);
                    
                } elseif ($return->stock_handling_type === 'writeoff') {
                    // WRITE-OFF: Damaged/defective - no stock adjustment, just log
                    StockLedger::create([
                        'product_id' => $item->product_id,
                        'document_type' => 'return_writeoff',
                        'document_id' => $return->id,
                        'qty' => 0, // No stock change
                        'unit_cost' => $item->unit_price,
                        'total_cost' => 0,
                        'user_id' => auth()->id(),
                        'notes' => 'Return write-off (damaged/defective) - ' . $item->return_reason,
                    ]);
                    
                } elseif ($return->stock_handling_type === 'credit_only') {
                    // CREDIT ONLY: No stock movement at all, just log
                    StockLedger::create([
                        'product_id' => $item->product_id,
                        'document_type' => 'return_credit_only',
                        'document_id' => $return->id,
                        'qty' => 0, // No stock change
                        'unit_cost' => $item->unit_price,
                        'total_cost' => 0,
                        'user_id' => auth()->id(),
                        'notes' => 'Return credit only (no stock adjustment) - ' . $item->return_reason,
                    ]);
                }
            }

            // Create credit note
            $creditNote = CreditNote::create([
                'return_id' => $return->id,
                'invoice_id' => $return->invoice_id,
                'customer_id' => $return->customer_id,
                'user_id' => auth()->id(),
                'subtotal' => $return->total_amount,
                'total_amount' => $return->total_amount,
                'status' => 'issued',
                'issued_at' => now(),
            ]);

            // Create customer ledger entry (credit - reduce their debt)
            if ($return->customer_id) {
                \App\Models\CustomerLedger::createEntry(
                    $return->customer_id,
                    'credit_note',
                    $creditNote->id,
                    $creditNote->credit_note_number,
                    now(),
                    0, // debit
                    $return->total_amount, // credit (reduce amount owed)
                    'Credit note issued for return'
                );

                // Update invoice balance
                if ($return->invoice) {
                    $return->invoice->balance_due -= $return->total_amount;
                    if ($return->invoice->balance_due < 0) {
                        $return->invoice->balance_due = 0;
                    }
                    $return->invoice->save();
                }

                // Update customer balance
                $return->customer->updateBalance();
            }

            // Update return status
            $return->update([
                'status' => 'completed',
                'returned_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return completed successfully',
                'credit_note_id' => $creditNote->id,
                'credit_note_number' => $creditNote->credit_note_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error completing return: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get invoice items for return
     */
    public function getInvoiceItems(Invoice $invoice)
    {
        // Load customer and items with products
        $invoice->load(['customer', 'items.product']);
        
        return response()->json([
            'success' => true,
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'created_at' => $invoice->created_at->toISOString(),
                'grand_total' => $invoice->grand_total,
                'payment_status' => $invoice->payment_status,
                'customer' => $invoice->customer ? [
                    'id' => $invoice->customer->id,
                    'name' => $invoice->customer->name,
                ] : null,
                'items' => $invoice->items,
            ],
            'items' => $invoice->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_sku' => $item->product_sku,
                    'product_name' => $item->product_name,
                    'product_barcode' => $item->product_barcode ?? '',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->line_total,
                    'product' => $item->product,
                ];
            }),
        ]);
    }

    /**
     * Generate credit note PDF
     */
    public function generateCreditNotePDF(CreditNote $creditNote)
    {
        $creditNote->load(['customer', 'productReturn.items.product', 'user']);
        
        $pdf = Pdf::loadView('returns.credit-note-pdf', compact('creditNote'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Credit-Note-{$creditNote->credit_note_number}.pdf");
    }

    /**
     * Delete a return
     */
    public function destroy(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending returns can be deleted',
            ], 400);
        }

        $return->delete();

        return response()->json([
            'success' => true,
            'message' => 'Return deleted successfully',
        ]);
    }
}