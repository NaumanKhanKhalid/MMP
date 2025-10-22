<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\StockBatch;
use App\Models\StockLedger;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditNoteController extends Controller
{
    /**
     * Display a listing of credit notes
     */
    public function index()
    {
        $creditNotes = CreditNote::with(['customer', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('credit-notes.index', compact('creditNotes'));
    }

    /**
     * Show the form for creating a new credit note
     */
    public function create()
    {
        return view('credit-notes.create');
    }

    /**
     * Store a newly created credit note in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.invoice_item_id' => 'nullable|exists:invoice_items,id',
            'items.*.qty_returned' => 'required|integer|min:1',
            'items.*.stock_handling' => 'required|in:restock,write_off,no_stock',
            'reason_for_return' => 'required|string',
            'refund_method' => 'required|in:credit_note,bank_refund,cash_refund,card_refund',
            'handling_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Load invoice with customer
            $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($validated['invoice_id']);
            
            // Reload customer to ensure fresh data
            $invoice->load('customer');

            // Validate invoice can be returned
            if ($invoice->payment_status === 'draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot return draft invoices.'
                ], 422);
            }

            // Create credit note
            $creditNote = CreditNote::create([
                'invoice_id' => $invoice->id,
                'linked_invoice_number' => $invoice->invoice_number,
                'customer_id' => $invoice->customer_id,
                'customer_name' => $invoice->customer_name,
                'customer_email' => $invoice->customer_email,
                'customer_phone' => $invoice->customer_phone,
                'vehicle_make' => $invoice->vehicle_make,
                'vehicle_model' => $invoice->vehicle_model,
                'vehicle_reg' => $invoice->vehicle_reg,
                'vehicle_vin' => $invoice->vehicle_vin,
                'vehicle_mileage' => $invoice->vehicle_mileage,
                'reason_for_return' => $validated['reason_for_return'],
                'refund_method' => $validated['refund_method'],
                'handling_fee' => $validated['handling_fee'] ?? 0,
                'customer_type' => $invoice->customer->customer_type ?? 'cash',
                'apply_to_account' => ($invoice->customer->customer_type ?? 'cash') === 'account',
                'vat_enabled' => $invoice->vat_enabled,
                'vat_rate' => $invoice->vat_rate,
                'vat_inclusive' => $invoice->vat_inclusive,
                'status' => 'draft',
                'user_id' => auth()->id(),
            ]);

            // Process items
            $subtotal = 0;
            $vatAmount = 0;

            foreach ($validated['items'] as $itemData) {
                // Find invoice item by invoice_item_id if provided, otherwise by product_id
                if (isset($itemData['invoice_item_id'])) {
                    $invoiceItem = InvoiceItem::findOrFail($itemData['invoice_item_id']);
                } else {
                    $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)
                        ->where('product_id', $itemData['product_id'])
                        ->firstOrFail();
                }
                
                // Validate return quantity
                if ($itemData['qty_returned'] > $invoiceItem->quantity) {
                    throw new \Exception("Return quantity cannot exceed sold quantity for product: {$invoiceItem->product_name}");
                }

                // Calculate return values
                $returnUnitPrice = $invoiceItem->unit_price;
                $returnLineTotal = $returnUnitPrice * $itemData['qty_returned'];
                $returnVatAmount = $invoice->vat_enabled ? ($returnLineTotal * $invoice->vat_rate / 100) : 0;

                $subtotal += $returnLineTotal;
                $vatAmount += $returnVatAmount;

                // Create credit note item
                $creditNoteItem = CreditNoteItem::create([
                    'credit_note_id' => $creditNote->id,
                    'invoice_item_id' => $invoiceItem->id,
                    'product_id' => $invoiceItem->product_id,
                    'product_name' => $invoiceItem->product_name,
                    'product_sku' => $invoiceItem->product_sku ?? null,
                    'product_description' => $invoiceItem->product_description ?? null,
                    'qty_sold' => $invoiceItem->quantity,
                    'unit_price' => $invoiceItem->unit_price,
                    'discount' => $invoiceItem->discount ?? 0,
                    'discount_percentage' => $invoiceItem->discount_percentage ?? 0,
                    'line_total' => $invoiceItem->line_total,
                    'qty_returned' => $itemData['qty_returned'],
                    'return_unit_price' => $returnUnitPrice,
                    'return_discount' => 0,
                    'return_line_total' => $returnLineTotal,
                    'stock_handling' => $itemData['stock_handling'],
                    'batch_id' => $invoiceItem->batch_id ?? null,
                    'vat_amount' => $returnVatAmount,
                    'vat_rate' => $invoice->vat_rate,
                ]);

                // Handle stock based on stock_handling
                if ($itemData['stock_handling'] === 'restock' && $invoiceItem->batch_id) {
                    // Restock into original batch
                    $batch = StockBatch::find($invoiceItem->batch_id);
                    if ($batch) {
                        $batch->increment('qty_left', $itemData['qty_returned']);

                        // Log stock movement
                        StockLedger::create([
                            'product_id' => $invoiceItem->product_id,
                            'batch_id' => $batch->id,
                            'movement_type' => 'return',
                            'quantity' => $itemData['qty_returned'],
                            'reference_type' => 'credit_note',
                            'reference_id' => $creditNote->id,
                            'notes' => "Return from {$invoice->invoice_number}",
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }

            // Update credit note totals
            $creditNote->update([
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'grand_total' => $subtotal + $vatAmount - $creditNote->handling_fee,
            ]);

            // Auto-post credit note if account customer and apply_to_account is true
            if ($creditNote->customer_type === 'account' && $creditNote->apply_to_account) {
                // Update customer balance
                $customer = Customer::findOrFail($creditNote->customer_id);
                $oldBalance = $customer->balance;
                $customer->decrement('balance', $creditNote->grand_total);
                $newBalance = $customer->balance;
                
                Log::info("Customer balance updated: {$oldBalance} -> {$newBalance} (Credit: {$creditNote->grand_total})");

                // Create ledger entry
                CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_type' => 'credit_note',
                    'transaction_id' => $creditNote->id,
                    'transaction_date' => now(),
                    'description' => 'Credit Note #' . $creditNote->credit_note_number,
                    'debit' => 0,
                    'credit' => $creditNote->grand_total,
                    'balance' => $customer->balance,
                    'created_by' => auth()->id(),
                ]);

                // Update credit note status
                $creditNote->update([
                    'status' => 'posted',
                    'posted_by' => auth()->id(),
                    'posted_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Credit note created and posted successfully!',
                'credit_note_id' => $creditNote->id,
                'credit_note_number' => $creditNote->credit_note_number,
                'status' => $creditNote->status,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit Note Creation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create credit note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Post/Issue the credit note
     */
    public function post($id)
    {
        try {
            DB::beginTransaction();

            $creditNote = CreditNote::with(['customer', 'items'])->findOrFail($id);

            if (!$creditNote->canBePosted()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credit note cannot be posted.'
                ], 422);
            }

            // Update customer balance if account customer
            if ($creditNote->customer_type === 'account' && $creditNote->apply_to_account) {
                $customer = $creditNote->customer;
                $customer->decrement('balance', $creditNote->grand_total);

                // Create ledger entry
                CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_type' => 'credit_note',
                    'transaction_id' => $creditNote->id,
                    'transaction_date' => now(),
                    'description' => 'Credit Note #' . $creditNote->credit_note_number,
                    'debit' => 0,
                    'credit' => $creditNote->grand_total,
                    'balance' => $customer->balance,
                    'created_by' => auth()->id(),
                ]);
            }

            // Update credit note status
            $creditNote->update([
                'status' => 'posted',
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Credit note posted successfully!',
                'credit_note' => $creditNote,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit Note Post Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to post credit note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice details for return process
     */
    public function getInvoiceDetails(Request $request)
    {
        $invoiceNumber = $request->input('invoice_number');

        $invoice = Invoice::with(['customer', 'items.product'])
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.'
            ], 404);
        }

        // Validate invoice can be returned
        if ($invoice->payment_status === 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot return draft invoices.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'date' => $invoice->created_at->format('d/m/Y'),
                'customer' => [
                    'id' => $invoice->customer_id,
                    'name' => $invoice->customer_name,
                    'email' => $invoice->customer_email,
                    'phone' => $invoice->customer_phone,
                    'type' => $invoice->customer->customer_type ?? 'cash',
                ],
                'vehicle' => [
                    'make' => $invoice->vehicle_make,
                    'model' => $invoice->vehicle_model,
                    'reg' => $invoice->vehicle_reg,
                    'vin' => $invoice->vehicle_vin,
                    'mileage' => $invoice->vehicle_mileage,
                ],
                'items' => $invoice->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'product_sku' => $item->product_sku,
                        'qty_sold' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'discount' => $item->discount,
                        'line_total' => $item->line_total,
                        'batch_id' => $item->batch_id,
                    ];
                }),
                'vat_enabled' => $invoice->vat_enabled,
                'vat_rate' => $invoice->vat_rate,
            ]
        ]);
    }

    /**
     * Display the specified credit note
     */
    public function show($id)
    {
        $creditNote = CreditNote::with(['customer', 'invoice', 'items.product'])->findOrFail($id);
        return view('credit-notes.show', compact('creditNote'));
    }

    /**
     * Generate PDF for credit note
     */
    public function pdf($id)
    {
        $creditNote = CreditNote::with(['customer', 'invoice', 'items.product'])->findOrFail($id);
        
        // TODO: Implement PDF generation
        // For now, return view
        return view('credit-notes.pdf', compact('creditNote'));
    }

    /**
     * Void a credit note
     */
    public function void($id)
    {
        try {
            DB::beginTransaction();

            $creditNote = CreditNote::with(['customer', 'items'])->findOrFail($id);

            if (!$creditNote->canBeVoided()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credit note cannot be voided.'
                ], 422);
            }

            // Reverse customer balance if account customer
            if ($creditNote->customer_type === 'account' && $creditNote->apply_to_account) {
                $customer = $creditNote->customer;
                $customer->increment('balance', $creditNote->grand_total);

                // Create reverse ledger entry
                CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_type' => 'credit_note_void',
                    'transaction_id' => $creditNote->id,
                    'transaction_date' => now(),
                    'description' => 'Voided Credit Note #' . $creditNote->credit_note_number,
                    'debit' => $creditNote->grand_total,
                    'credit' => 0,
                    'balance' => $customer->balance,
                    'created_by' => auth()->id(),
                ]);
            }

            // Reverse stock if restocked
            foreach ($creditNote->items as $item) {
                if ($item->isRestock() && $item->batch_id) {
                    $batch = StockBatch::find($item->batch_id);
                    if ($batch) {
                        $batch->decrement('qty_left', $item->qty_returned);

                        // Log reverse stock movement
                        StockLedger::create([
                            'product_id' => $item->product_id,
                            'batch_id' => $batch->id,
                            'movement_type' => 'void_return',
                            'quantity' => -$item->qty_returned,
                            'reference_type' => 'credit_note',
                            'reference_id' => $creditNote->id,
                            'notes' => "Voided return from {$creditNote->linked_invoice_number}",
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }

            // Update credit note status
            $creditNote->update([
                'status' => 'voided',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Credit note voided successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit Note Void Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to void credit note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Email credit note
     */
    public function email($id)
    {
        // TODO: Implement email functionality
        return response()->json([
            'success' => false,
            'message' => 'Email functionality not yet implemented.'
        ]);
    }

    /**
     * WhatsApp credit note
     */
    public function whatsapp($id)
    {
        // TODO: Implement WhatsApp functionality
        return response()->json([
            'success' => false,
            'message' => 'WhatsApp functionality not yet implemented.'
        ]);
    }
}
