<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::latest()->paginate(20);
        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('quotes.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|integer',
            'vehicle_make' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_vin' => 'nullable|string',
            'vehicle_reg' => 'nullable|string',
            'vehicle_mileage' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $data['quote_number'] = 'QT' . (10000 + (Quote::max('id') ?? 0) + 1);
        $quote = Quote::create($data);
        // Handle items
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $quote->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        }
        return redirect()->route('quotes.index')->with('success', 'Quote created!');
    }

    public function show(Quote $quote)
    {
        $quote->load('items');
        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $quote->load('items');
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('quotes.edit', compact('quote', 'customers', 'products'));
    }

    // Modal for viewing a quote
    public function viewModal($id)
    {
        $quote = Quote::with(['customer', 'items.product'])->findOrFail($id);
        return view('quotes.partials.view_modal', compact('quote'))->render();
    }

    // Modal for editing a quote
    public function editModal($id)
    {
        $quote = Quote::with(['customer', 'items.product'])->findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('quotes.partials.edit_modal', compact('quote', 'customers', 'products'))->render();
    }

    public function update(Request $request, Quote $quote)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|integer',
            'vehicle_make' => 'nullable|string',
            'vehicle_model' => 'nullable|string',
            'vehicle_vin' => 'nullable|string',
            'vehicle_reg' => 'nullable|string',
            'vehicle_mileage' => 'nullable|string',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $quote->update($data);
        // Update items (simple: delete and recreate)
        $quote->items()->delete();
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $quote->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total'],
                ]);
            }
        }
        return redirect()->route('quotes.index')->with('success', 'Quote updated!');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('quotes.index')->with('success', 'Quote deleted!');
    }

    // Convert Quote to Invoice
    public function convertToInvoice($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        // Create Invoice (basic fields, expand as needed)
        $invoice = new \App\Models\Invoice();
        $invoice->customer_id = $quote->customer_id;
        $invoice->status = 'draft';
        $invoice->invoice_number = 'INV' . (10000 + (\App\Models\Invoice::max('id') ?? 0) + 1);
        $invoice->quote_id = $quote->id;
        $invoice->subtotal = $quote->items->sum('total');
        $invoice->total_discount = $quote->total_discount ?? 0;
        $invoice->shipping = $quote->shipping ?? 0;
        $invoice->vat = $quote->vat ?? 0;
        $invoice->grand_total = $quote->grand_total ?? $quote->items->sum('total');
        $invoice->save();
        // Optionally copy items (if InvoiceItem model exists)
        // ...
        // Link back to quote
        $quote->converted_invoice_id = $invoice->id;
        $quote->status = 'converted';
        $quote->save();
        return response()->json(['success' => true, 'invoice_id' => $invoice->id, 'redirect' => route('invoices.show', $invoice->id)]);
    }

    // Duplicate Quote
    public function duplicate($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        $newQuote = $quote->replicate();
        $newQuote->quote_number = 'QT' . (10000 + (Quote::max('id') ?? 0) + 1);
        $newQuote->status = 'draft';
        $newQuote->created_at = now();
        $newQuote->updated_at = now();
        $newQuote->save();
        foreach ($quote->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $newQuote->id;
            $newItem->save();
        }
        return redirect()->route('quotes.index')->with('success', 'Quote duplicated successfully!');
    }

    // Print Quotation
    public function print(Quote $quote)
    {
        $quote->load(['customer', 'items.product']);
        return view('quotes.print', compact('quote'));
    }


}
