<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\Product;
use App\Models\StockBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'user', 'items', 'quote']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('quote', function($quoteQuery) use ($search) {
                      $quoteQuery->where('quote_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->get('status'));
        }

        if ($request->filled('customer')) {
            $query->where('customer_id', $request->get('customer'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $invoices = $query->latest()->paginate(20);
        $customers = Customer::orderBy('name')->get();
        
        return view('invoices.index', compact('invoices', 'customers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::with(['brand', 'category'])->orderBy('name')->get();
        return view('invoices.partials.create_modal', compact('customers', 'products'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_id,null|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,eft,on_account',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_vin' => $request->vehicle_vin,
                'vehicle_reg' => $request->vehicle_reg,
                'vehicle_mileage' => $request->vehicle_mileage,
                'payment_method' => $request->payment_method,
                'vat_enabled' => $request->boolean('vat_enabled'),
                'vat_rate' => $request->vat_rate ?? 15.00,
                'vat_inclusive' => $request->boolean('vat_inclusive'),
                'notes' => $request->notes,
                'reference' => $request->reference,
                'user_id' => Auth::id(),
                'payment_status' => 'draft',
            ]);

            $subtotal = 0;
            $totalDiscount = 0;

            // Create invoice items
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                
                // Calculate line total
                $lineSubtotal = $quantity * $unitPrice;
                $discountAmount = $itemData['discount_amount'] ?? 0;
                $discountPercentage = $itemData['discount_percentage'] ?? 0;
                
                if ($discountPercentage > 0) {
                    $discountAmount = $lineSubtotal * ($discountPercentage / 100);
                }
                
                $lineTotal = $lineSubtotal - $discountAmount;
                
                // Get FIFO cost
                $fifoCost = $this->getFifoCost($product, $quantity);
                
                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_sku' => $product->sku,
                    'product_name' => $product->name,
                    'product_barcode' => $product->barcode_primary,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount_amount' => $discountAmount,
                    'discount_percentage' => $discountPercentage,
                    'line_total' => $lineTotal,
                    'unit_cost' => $fifoCost,
                    'line_cost' => $quantity * $fifoCost,
                    'line_profit' => $lineTotal - ($quantity * $fifoCost),
                    'notes' => $itemData['notes'] ?? null,
                ]);

                $subtotal += $lineTotal;
                $totalDiscount += $discountAmount;
            }

            // Update invoice totals
            $shipping = $request->shipping ?? 0;
            $vatAmount = 0;
            $grandTotal = $subtotal + $shipping;

            if ($invoice->vat_enabled) {
                if ($invoice->vat_inclusive) {
                    // VAT is included in prices
                    $vatAmount = $grandTotal - ($grandTotal / (1 + $invoice->vat_rate / 100));
                    // $grandTotal remains the same (VAT inclusive)
                } else {
                    // VAT to be added
                    $vatAmount = $grandTotal * ($invoice->vat_rate / 100);
                    $grandTotal += $vatAmount;
                }
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'shipping' => $shipping,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'balance_due' => $grandTotal,
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully!',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.product', 'quote']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if (!$invoice->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft invoices can be edited!'
            ], 403);
        }

        $customers = Customer::orderBy('name')->get();
        $products = Product::with(['brand', 'category'])->orderBy('name')->get();
        
        return view('invoices.partials.edit_modal', compact('invoice', 'customers', 'products'))->render();
    }

    public function update(Request $request, Invoice $invoice)
    {
        if (!$invoice->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft invoices can be edited!'
            ], 403);
        }

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_id,null|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,eft,on_account',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Update invoice
            $invoice->update([
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'vehicle_vin' => $request->vehicle_vin,
                'vehicle_reg' => $request->vehicle_reg,
                'vehicle_mileage' => $request->vehicle_mileage,
                'payment_method' => $request->payment_method,
                'vat_enabled' => $request->boolean('vat_enabled'),
                'vat_rate' => $request->vat_rate ?? 15.00,
                'vat_inclusive' => $request->boolean('vat_inclusive'),
                'notes' => $request->notes,
                'reference' => $request->reference,
            ]);

            // Delete existing items
            $invoice->items()->delete();

            $subtotal = 0;
            $totalDiscount = 0;

            // Create new invoice items
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $itemData['unit_price'];
                
                // Calculate line total
                $lineSubtotal = $quantity * $unitPrice;
                $discountAmount = $itemData['discount_amount'] ?? 0;
                $discountPercentage = $itemData['discount_percentage'] ?? 0;
                
                if ($discountPercentage > 0) {
                    $discountAmount = $lineSubtotal * ($discountPercentage / 100);
                }
                
                $lineTotal = $lineSubtotal - $discountAmount;
                
                // Get FIFO cost
                $fifoCost = $this->getFifoCost($product, $quantity);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_sku' => $product->sku,
                    'product_name' => $product->name,
                    'product_barcode' => $product->barcode_primary,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount_amount' => $discountAmount,
                    'discount_percentage' => $discountPercentage,
                    'line_total' => $lineTotal,
                    'unit_cost' => $fifoCost,
                    'line_cost' => $quantity * $fifoCost,
                    'line_profit' => $lineTotal - ($quantity * $fifoCost),
                    'notes' => $itemData['notes'] ?? null,
                ]);

                $subtotal += $lineTotal;
                $totalDiscount += $discountAmount;
            }

            // Update invoice totals
            $shipping = $request->shipping ?? 0;
            $vatAmount = 0;
            $grandTotal = $subtotal + $shipping;

            if ($invoice->vat_enabled) {
                if ($invoice->vat_inclusive) {
                    $vatAmount = $grandTotal - ($grandTotal / (1 + $invoice->vat_rate / 100));
                } else {
                    $vatAmount = $grandTotal * ($invoice->vat_rate / 100);
                    $grandTotal += $vatAmount;
                }
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'shipping' => $shipping,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
                'balance_due' => $grandTotal,
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Invoice $invoice)
    {
        if (!$invoice->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft invoices can be deleted!'
            ], 403);
        }

        $invoice->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully!'
        ]);
    }


    public function editModal($id)
    {
        $invoice = Invoice::with(['items.product'])->findOrFail($id);
        
        if (!$invoice->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft invoices can be edited!'
            ], 403);
        }

        $customers = Customer::orderBy('name')->get();
        $products = Product::with(['brand', 'category'])->orderBy('name')->get();
        
        return view('invoices.partials.edit_modal', compact('invoice', 'customers', 'products'))->render();
    }

    public function print($id)
    {
        $invoice = Invoice::with(['customer', 'user', 'items.product'])->findOrFail($id);
        return view('invoices.print', compact('invoice'));
    }

    // public function export(Request $request)
    // {
    //     $query = Invoice::with(['customer', 'user', 'quote']);

    //     // Apply same filters as index method
    //     if ($request->filled('search')) {
    //         $search = $request->get('search');
    //         $query->where(function($q) use ($search) {
    //             $q->where('invoice_number', 'like', "%{$search}%")
    //               ->orWhere('customer_name', 'like', "%{$search}%")
    //               ->orWhere('customer_phone', 'like', "%{$search}%")
    //               ->orWhereHas('customer', function($customerQuery) use ($search) {
    //                   $customerQuery->where('name', 'like', "%{$search}%")
    //                                ->orWhere('phone', 'like', "%{$search}%");
    //               })
    //               ->orWhereHas('quote', function($quoteQuery) use ($search) {
    //                   $quoteQuery->where('quote_number', 'like', "%{$search}%");
    //               });
    //         });
    //     }

    //     if ($request->filled('status')) {
    //         $query->where('payment_status', $request->get('status'));
    //     }

    //     if ($request->filled('customer')) {
    //         $query->where('customer_id', $request->get('customer'));
    //     }

    //     if ($request->filled('date_from')) {
    //         $query->whereDate('created_at', '>=', $request->get('date_from'));
    //     }

    //     if ($request->filled('date_to')) {
    //         $query->whereDate('created_at', '<=', $request->get('date_to'));
    //     }

    //     $invoices = $query->latest()->get();
    //     $format = $request->get('format', 'pdf');

    //     if ($format === 'excel') {
    //         return view('invoices.export_excel', compact('invoices'));
    //     }

    //     // Default to PDF
    //     return view('invoices.export_pdf', compact('invoices'));
    // }

    public function post(Invoice $invoice)
    {
        if (!$invoice->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft invoices can be posted!'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Update stock using FIFO
            foreach ($invoice->items as $item) {
                $this->consumeStock($item->product, $item->quantity);
            }

            // Update invoice status
            $invoice->update(['payment_status' => 'posted']);

            // Create customer ledger entry (debit - they owe us)
            if ($invoice->customer_id) {
                CustomerLedger::createEntry(
                    $invoice->customer_id,
                    'invoice',
                    $invoice->id,
                    $invoice->invoice_number,
                    now(),
                    $invoice->grand_total, // debit (amount they owe)
                    0, // credit
                    'Invoice posted'
                );

                // Update customer balance
                $invoice->customer->updateBalance();
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice posted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error posting invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function getFifoCost($product, $quantity)
    {
        $batches = StockBatch::where('product_id', $product->id)
            ->where('qty_left', '>', 0)
            ->orderBy('received_date', 'asc')
            ->get();

        if ($batches->isEmpty()) {
            return 0; // No stock available
        }

        $remainingQty = $quantity;
        $totalCost = 0;

        foreach ($batches as $batch) {
            if ($remainingQty <= 0) break;
            
            $qtyToUse = min($remainingQty, $batch->qty_left);
            $totalCost += $qtyToUse * $batch->landed_unit_cost;
            $remainingQty -= $qtyToUse;
        }

        return $remainingQty > 0 ? 0 : ($totalCost / $quantity);
    }

    private function consumeStock($product, $quantity)
    {
        $batches = StockBatch::where('product_id', $product->id)
            ->where('qty_left', '>', 0)
            ->orderBy('received_date', 'asc')
            ->get();

        $remainingQty = $quantity;

        foreach ($batches as $batch) {
            if ($remainingQty <= 0) break;
            
            $qtyToUse = min($remainingQty, $batch->qty_left);
            $batch->decrement('qty_left', $qtyToUse);
            $remainingQty -= $qtyToUse;
        }

        // If negative stock is allowed, create a negative batch
        if ($remainingQty > 0 && $product->allow_negative) {
            StockBatch::create([
                'product_id' => $product->id,
                'qty_received' => 0,
                'qty_left' => -$remainingQty,
                'landed_unit_cost' => 0, // Will be updated when stock arrives
                'received_date' => now(),
            ]);
        }
    }

    // View Invoice Modal
    public function viewModal($id)
    {
        $invoice = Invoice::with(['customer', 'items.product', 'quote', 'user'])->findOrFail($id);
        return view('invoices.partials.view_modal', compact('invoice'))->render();
    }

    // Export Invoices
    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $invoices = Invoice::with(['customer', 'items'])->get();

        if ($format === 'csv') {
            return $this->exportCSV($invoices);
        } elseif ($format === 'excel') {
            return $this->exportExcel($invoices);
        } else {
            return $this->exportPDF($invoices);
        }
    }

    private function exportCSV($invoices)
    {
        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice Number', 'Customer', 'Items Count', 'Grand Total', 'Paid', 'Balance', 'Status', 'Date']);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->customer->name ?? $invoice->customer_name ?? 'Cash Sale',
                    $invoice->items->count(),
                    number_format($invoice->grand_total ?? 0, 2),
                    number_format($invoice->amount_paid ?? 0, 2),
                    number_format($invoice->balance_due ?? 0, 2),
                    ucfirst($invoice->payment_status),
                    $invoice->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($invoices)
    {
        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.html';
        return response()->view('invoices.export_excel', compact('invoices'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportPDF($invoices)
    {
        $pdf = \PDF::loadView('invoices.export_pdf', compact('invoices'));
        return $pdf->download('invoices_' . now()->format('Y-m-d_His') . '.pdf');
    }
}