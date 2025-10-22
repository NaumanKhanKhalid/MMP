<?php

namespace App\Http\Controllers;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Engine;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {

        $query = Quote::query()->with(['customer', 'items']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'LIKE', "%{$search}%")
                    ->orWhere('vehicle_make', 'LIKE', "%{$search}%")
                    ->orWhere('vehicle_model', 'LIKE', "%{$search}%")
                    ->orWhere('vehicle_reg', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Customer filter
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $quotes = $query->latest()->paginate(20);
        $customers = Customer::orderBy('name')->get();

        return view('quotes.index', compact('quotes', 'customers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $makes = CarMake::where('status', 'active')->orderBy('name')->get();
        $models = CarModel::where('status', 'active')->orderBy('name')->get();
        $engines = Engine::where('status', 'active')->orderBy('code')->get();

        // Get VAT settings from database
        $vatSettings = [
            'enabled' => Setting::vatEnabled(),
            'rate' => Setting::vatRate(),
            'inclusive' => Setting::vatInclusive(),
        ];

        return view('quotes.create', compact('customers', 'products', 'makes', 'models', 'engines', 'vatSettings'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();

        $data = $request->validate([
            'customer_id' => 'nullable|integer',
                'vehicle_make_id' => 'nullable|integer',
                'vehicle_model_id' => 'nullable|integer',
                'vehicle_engine_id' => 'nullable|integer',
                'vehicle_year' => 'nullable|integer',
            'vehicle_vin' => 'nullable|string',
            'vehicle_reg' => 'nullable|string',
                'vehicle_mileage' => 'nullable|integer',
            'valid_until' => 'nullable|date',
                'status' => 'nullable|string|in:draft,sent,accepted,declined,converted,expired,cancelled',
            'notes' => 'nullable|string',
                'subtotal' => 'nullable|numeric|min:0',
                'total_discount' => 'nullable|numeric|min:0',
                'shipping' => 'nullable|numeric|min:0',
                'vat_enabled' => 'nullable|in:on,off,1,0,true,false',
                'vat_amount' => 'nullable|numeric|min:0',
                'grand_total' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0',
            ]);

            // Generate quote number
            $data['quote_number'] = 'QT'.(10000 + (Quote::max('id') ?? 0) + 1);
            $data['user_id'] = auth()->id();
            $data['status'] = $data['status'] ?? 'draft';

            // Handle VAT - Convert checkbox value to boolean
            $vatEnabled = in_array($request->vat_enabled, ['on', '1', 1, true, 'true'], true);
            $data['vat'] = $vatEnabled ? ($request->vat_amount ?? 0) : 0;
            unset($data['vat_enabled'], $data['vat_amount'], $data['items']);

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

            \DB::commit();

            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quote created successfully!',
                    'quote_id' => $quote->id,
                    'quote_number' => $quote->quote_number,
                ]);
            }

            return redirect()->route('quotes.index')->with('success', 'Quote created successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Quote Creation Error: '.$e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create quote: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create quote: '.$e->getMessage());
        }
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
        $quote = Quote::with(['customer', 'vehicleMake', 'vehicleModel', 'vehicleEngine', 'items.product'])->findOrFail($id);

        return view('quotes.partials.view_modal', compact('quote'))->render();
    }

    // Modal for editing a quote
    public function editModal($id)
    {
        $quote = Quote::with(['customer', 'vehicleMake', 'vehicleModel', 'vehicleEngine', 'items.product.stockBatches'])->findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $makes = CarMake::where('status', 'active')->orderBy('name')->get();
        $models = CarModel::where('status', 'active')->orderBy('name')->get();
        $engines = Engine::where('status', 'active')->orderBy('code')->get();

        // Get VAT settings from database
        $vatSettings = [
            'enabled' => Setting::vatEnabled(),
            'rate' => Setting::vatRate(),
            'inclusive' => Setting::vatInclusive(),
        ];

        return view('quotes.partials.edit_modal', compact('quote', 'customers', 'products', 'makes', 'models', 'engines', 'vatSettings'))->render();
    }

    public function update(Request $request, Quote $quote)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|integer',
            'vehicle_make_id' => 'nullable|integer',
            'vehicle_model_id' => 'nullable|integer',
            'vehicle_engine_id' => 'nullable|integer',
            'vehicle_year' => 'nullable|integer',
            'vehicle_vin' => 'nullable|string',
            'vehicle_reg' => 'nullable|string',
            'vehicle_mileage' => 'nullable|integer',
            'valid_until' => 'nullable|date',
            'status' => 'nullable|string|in:draft,sent,accepted,declined,converted,expired,cancelled',
            'notes' => 'nullable|string',
            'total_discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'vat_enabled' => 'nullable|in:on,off,1,0,true,false',
            'vat_amount' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
        ]);

        // Handle VAT - convert checkbox value to boolean
        $vatEnabled = in_array($request->vat_enabled, ['on', '1', 'true', 1, true]);
        $data['vat'] = $vatEnabled ? ($request->vat_amount ?? 0) : 0;
        unset($data['vat_enabled'], $data['vat_amount']);

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

        return response()->json(['success' => true, 'message' => 'Quote updated successfully!']);
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Quote deleted!');
    }

    /**
     * ═══════════════════════════════════════════════════════════════════════════
     * CONVERT QUOTE TO INVOICE - COMPLETE DOCUMENTATION
     * ═══════════════════════════════════════════════════════════════════════════
     * 
     * This method converts a Quote to an Invoice with full FIFO stock consumption.
     * 
     * BUSINESS LOGIC:
     * ─────────────────────────────────────────────────────────────────────────
     * 1. Quotes do NOT move stock (can be created with negative stock)
     * 2. Converting to Invoice DOES move stock (FIFO consumption)
     * 3. Stock consumption behavior is controlled by setting: allow_out_of_stock_sale
     * 
     * STOCK CONSUMPTION RULES:
     * ─────────────────────────────────────────────────────────────────────────
     * Setting ON (allow_out_of_stock_sale = true):
     *   ✅ Conversion proceeds even if stock is insufficient
     *   ⚠️  Warning shown to user about stock shortage
     *   📉 Product stock can go negative
     *   💰 Last known cost used for negative quantity
     * 
     * Setting OFF (allow_out_of_stock_sale = false):
     *   ❌ Conversion BLOCKED if any item has insufficient stock
     *   📋 Detailed error message showing each shortage
     *   ℹ️  User must add stock before converting
     * 
     * FIFO EXAMPLE:
     * ─────────────────────────────────────────────────────────────────────────
     * Quote Item: Product A = 5 units
     * Available Stock Batches:
     *   - Batch 1: 3 units @ R10 each (received: 2025-01-01)
     *   - Batch 2: 4 units @ R12 each (received: 2025-01-05)
     * 
     * Consumption:
     *   Step 1: Consume 3 units from Batch 1 = R30
     *   Step 2: Consume 2 units from Batch 2 = R24
     *   Total Cost: R54
     *   Average FIFO Cost: R54 ÷ 5 = R10.80 per unit
     * 
     * Result:
     *   - Batch 1: qty_left = 0 (depleted)
     *   - Batch 2: qty_left = 2 (2 units remaining)
     *   - Invoice Item: unit_cost = R10.80, line_cost = R54
     *   - Stock Ledger: -5 units, -R54 cost
     * 
     * NEGATIVE STOCK SCENARIO:
     * ─────────────────────────────────────────────────────────────────────────
     * Quote Item: Product B = 5 units
     * Available Stock: 3 units @ R50 (only 1 batch)
     * Shortage: 2 units
     * 
     * If allow_out_of_stock_sale = true:
     *   Step 1: Consume 3 units from batch = R150
     *   Step 2: Virtual 2 units @ R50 (last known cost) = R100
     *   Total Cost: R250
     *   Average Cost: R250 ÷ 5 = R50 per unit
     *   Product on_hand: 3 - 5 = -2 (negative stock)
     *   
     * Future Reconciliation:
     *   When new GRN received, negative stock gets fulfilled first
     * 
     * NO BATCH SCENARIO:
     * ─────────────────────────────────────────────────────────────────────────
     * Quote Item: Product C = 5 units
     * Available Stock: 0 units (no batches exist)
     * 
     * If allow_out_of_stock_sale = true:
     *   Cost Calculation: 5 units @ R0 (no cost history)
     *   Product on_hand: 0 - 5 = -5
     *   Invoice Item: unit_cost = R0, profit = full selling price
     * 
     * DATABASE CHANGES:
     * ─────────────────────────────────────────────────────────────────────────
     * 1. invoices → New invoice record created
     * 2. invoice_items → Line items with FIFO costs
     * 3. stock_batches → qty_left reduced for consumed batches
     * 4. stock_ledgers → Audit trail with negative qty (stock out)
     * 5. quotes → Status updated to 'accepted', linked to invoice
     * 
     * ═══════════════════════════════════════════════════════════════════════════
     */
    public function convertToInvoice(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            $quote = Quote::with(['items.product.stockBatches', 'customer', 'user'])->findOrFail($id);
            
            // ✅ Step 1: Basic Validation
            if (!$quote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quotation not found.'
                ], 404);
            }
            
            // Check if quotation has items
            if ($quote->items->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot convert: Quotation has no items.'
                ], 400);
            }
            
            // Check if customer is assigned
            if (!$quote->customer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot convert: No customer assigned to this quotation.'
                ], 400);
            }

            // Validation: Check if quote is already converted
            if ($quote->status === 'converted' || $quote->status === 'accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This quote has already been converted to an invoice.',
                ], 400);
            }

            // Check stock availability and identify short items
            $stockIssues = [];
            foreach ($quote->items as $item) {
                if ($item->product) {
                    $availableStock = $item->product->stockBatches->sum('qty_left');
                    if ($availableStock < $item->quantity) {
                        $stockIssues[] = [
                            'product' => $item->product->name,
                            'sku' => $item->product->sku,
                            'required' => $item->quantity,
                            'available' => $availableStock,
                            'short' => $item->quantity - $availableStock,
                        ];
                    }
                }
            }

            // Check setting: Allow out-of-stock sale
            $allowOutOfStockSale = Setting::allowOutOfStockSale();

            // If stock issues exist and setting is OFF, block conversion
            if (count($stockIssues) > 0 && !$allowOutOfStockSale) {
                \DB::rollBack();
                
                $errorMessage = 'Insufficient stock. Cannot convert quote to invoice:<br><br>';
                foreach ($stockIssues as $issue) {
                    $errorMessage .= '• <strong>'.$issue['product'].'</strong> (SKU: '.$issue['sku'].')<br>';
                    $errorMessage .= '&nbsp;&nbsp;Required: '.$issue['required'].' units | Available: '.$issue['available'].' units | Short: <span class="text-danger fw-bold">'.$issue['short'].' units</span><br><br>';
                }
                $errorMessage .= '<small class="text-muted">Note: Out-of-stock sales are currently disabled in settings.</small>';

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'stock_issues' => $stockIssues,
                ], 400);
            }

            // Warning for stock issues (if setting is ON, allow conversion with warning)
            $stockWarning = null;
            if (count($stockIssues) > 0) {
                $stockWarning = 'Some items have insufficient stock: ';
                foreach ($stockIssues as $issue) {
                    $stockWarning .= $issue['product'].' (short: '.$issue['short'].' units), ';
                }
                $stockWarning = rtrim($stockWarning, ', ');
            }

            // Create Invoice
            $invoice = new \App\Models\Invoice;
            $invoice->invoice_number = \App\Models\Invoice::generateInvoiceNumber();
        $invoice->customer_id = $quote->customer_id;
            $invoice->customer_name = $quote->customer->name ?? 'Cash Sale';
            $invoice->customer_email = $quote->customer->email ?? null;
            $invoice->customer_phone = $quote->customer->phone ?? null;
            $invoice->customer_address = $quote->customer->address ?? null;
            $invoice->vehicle_make = $quote->vehicle_make;
            $invoice->vehicle_model = $quote->vehicle_model;
            $invoice->vehicle_vin = $quote->vehicle_vin;
            $invoice->vehicle_reg = $quote->vehicle_reg;
            $invoice->vehicle_mileage = $quote->vehicle_mileage;
        $invoice->subtotal = $quote->items->sum('total');
            $invoice->discount_amount = $quote->total_discount ?? 0;
        $invoice->shipping = $quote->shipping ?? 0;
            
            // Preserve quote's VAT settings exactly
            $invoice->vat_enabled = $quote->vat_enabled ?? false;
            $invoice->vat_rate = $quote->vat_rate ?? Setting::vatRate();
            $invoice->vat_inclusive = $quote->vat_inclusive ?? Setting::vatInclusive();
            $invoice->vat_amount = $quote->vat ?? 0;
            
            // Calculate grand total exactly as it was in the quote
            if ($quote->grand_total) {
                // Use the quote's grand total directly (already calculated correctly)
                $invoice->grand_total = (float) $quote->grand_total;
            } else {
                // Fallback calculation if grand_total is not set
                $subtotalAfterDiscount = $invoice->subtotal - $invoice->discount_amount;
                $invoice->grand_total = (float) ($subtotalAfterDiscount + $invoice->shipping + $invoice->vat_amount);
            }
            
            // Handle payment information
            $paymentMethod = $request->get('payment_method', 'on_account');
            $amountPaid = (float) ($request->get('amount_paid', 0));
            $paymentReference = $request->get('payment_reference', null);
            
            // Validate payment method based on customer type
            $customer = $quote->customer;
            if ($customer) {
                // Cash customers cannot use credit payment methods
                if ($customer->isCashCustomer() && $paymentMethod === 'on_account') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cash customers cannot make credit purchases. Please select Cash, Card, or EFT payment method.'
                    ], 400);
                }
                
                // Credit customers - check credit limit
                if ($customer->isCreditCustomer() && $paymentMethod === 'on_account') {
                    if (!$customer->canMakeCreditPurchase((float) $invoice->grand_total)) {
                        $availableCredit = (float) $customer->credit_limit - abs((float) $customer->balance);
                        return response()->json([
                            'success' => false,
                            'message' => "Credit limit exceeded. Available credit: R " . number_format($availableCredit, 2) . ". Required: R " . number_format((float) $invoice->grand_total, 2)
                        ], 400);
                    }
                }
            }
            
            // Validate payment amount
            if ($amountPaid > $invoice->grand_total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount paid cannot exceed invoice total.'
                ], 400);
            }
            
            // Determine payment status based on payment method and amount
            if ($paymentMethod === 'on_account') {
                // Credit customer
                if ($amountPaid == 0) {
                    $paymentStatus = 'unpaid';
                } elseif ($amountPaid > 0 && $amountPaid < $invoice->grand_total) {
                    $paymentStatus = 'partially_paid';
                } else {
                    $paymentStatus = 'paid';
                }
            } else {
                // Cash/Card/EFT - must pay full amount
                if ($amountPaid >= $invoice->grand_total) {
                    $paymentStatus = 'paid';
                } elseif ($amountPaid > 0 && $amountPaid < $invoice->grand_total) {
                    $paymentStatus = 'partially_paid';
                } else {
                    $paymentStatus = 'unpaid';
                }
            }
            
            $invoice->payment_method = $paymentMethod;
            $invoice->payment_status = $paymentStatus;
            $invoice->amount_paid = (float) $amountPaid;
            $invoice->balance_due = (float) ($invoice->grand_total - $amountPaid);
            $invoice->payment_reference = $paymentReference;
            $invoice->notes = $quote->notes;
            $invoice->user_id = auth()->id();
            $invoice->quote_id = $quote->id;
        $invoice->save();

            // Process each quote item with FIFO stock consumption
            foreach ($quote->items as $item) {
                $product = $item->product;
                $qtyNeeded = $item->quantity;
                $totalCost = 0;
                $consumedBatches = [];

                // ═══════════════════════════════════════════════════════════════
                // FIFO STOCK CONSUMPTION LOGIC
                // ═══════════════════════════════════════════════════════════════
                // Example: Quote needs 5 units
                // Batch 1: 3 units @ R10 (received 2025-01-01) - Oldest
                // Batch 2: 4 units @ R12 (received 2025-01-05) - Newer
                // 
                // Step 1: Consume 3 units from Batch 1 (cost = R30)
                // Step 2: Consume 2 units from Batch 2 (cost = R24)
                // Total Cost = R54, Avg FIFO Cost = R54/5 = R10.80 per unit
                // ═══════════════════════════════════════════════════════════════

                // Get available stock batches (FIFO order: oldest first)
                $batches = $product->stockBatches()
                    ->where('qty_left', '>', 0)
                    ->orderBy('received_date', 'asc')  // Oldest first (FIFO)
                    ->orderBy('id', 'asc')             // Tie-breaker for same date
                    ->get();

                // Consume stock using FIFO (First-In, First-Out)
                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) {
                        break; // All qty consumed
                    }

                    // Take minimum of qty needed and qty available in batch
                    $qtyToConsume = min($qtyNeeded, $batch->qty_left);
                    $batchCost = $qtyToConsume * $batch->landed_unit_cost;
                    $totalCost += $batchCost;

                    // Track consumed batches for audit
                    $consumedBatches[] = [
                        'batch_id' => $batch->id,
                        'received_date' => $batch->received_date,
                        'qty' => $qtyToConsume,
                        'unit_cost' => $batch->landed_unit_cost,
                        'cost' => $batchCost,
                    ];

                    // Update batch qty_left (reduce stock)
                    $batch->qty_left -= $qtyToConsume;
                    $batch->save();

                    // Reduce qty needed
                    $qtyNeeded -= $qtyToConsume;
                }

                // ═══════════════════════════════════════════════════════════════
                // NEGATIVE STOCK HANDLING (if setting allows)
                // ═══════════════════════════════════════════════════════════════
                // If still qty needed after consuming all batches:
                // - Use last known cost from last batch
                // - If no batches exist at all, use R0 cost
                // - This creates "virtual stock" that will be reconciled later
                // ═══════════════════════════════════════════════════════════════
                if ($qtyNeeded > 0) {
                    // Use last known cost or default to 0 if no batches
                    $lastCost = $batches->last() ? $batches->last()->landed_unit_cost : 0;
                    $totalCost += $qtyNeeded * $lastCost;
                    
                    // Note: Product's on_hand will become negative
                    // Future GRN will reconcile this negative stock
                }

                // Calculate weighted average FIFO cost per unit
                $fifoCost = $item->quantity > 0 ? $totalCost / $item->quantity : 0;

                // Create Invoice Item
                $invoiceItem = new \App\Models\InvoiceItem;
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item->product_id;
                $invoiceItem->product_sku = $product->sku;
                $invoiceItem->product_name = $product->name;
                $invoiceItem->product_barcode = $product->barcode_primary;
                $invoiceItem->unit_price = $item->unit_price;
                $invoiceItem->quantity = $item->quantity;
                $invoiceItem->discount_amount = $item->discount;
                $invoiceItem->line_total = $item->total;
                $invoiceItem->unit_cost = (float) $fifoCost;
                $invoiceItem->line_cost = (float) $totalCost;
                $invoiceItem->line_profit = (float) ($item->total - $totalCost);
                $invoiceItem->notes = $item->description ?? null;
                $invoiceItem->save();

                // Create Stock Ledger Entry
                \App\Models\StockLedger::create([
                    'product_id' => $product->id,
                    'document_type' => 'INVOICE',
                    'document_id' => $invoice->id,
                    'qty' => -$item->quantity, // Negative for consumption
                    'unit_cost' => $fifoCost,
                    'total_cost' => -$totalCost, // Negative for stock out
                    'user_id' => auth()->id(),
                    'notes' => "Stock consumed for Invoice #{$invoice->invoice_number} (from Quote #{$quote->quote_number})",
                ]);
            }

            // 🔄 Step 6: Post Actions - Update quote status
            $quote->status = 'accepted'; // Quotation marked as converted/accepted
        $quote->converted_invoice_id = $invoice->id;
        $quote->save();

            // 📒 Create Customer Ledger Entries
            if ($customer) {
                $currentBalance = $customer->balance;
                
                // Entry 1: Invoice (Debit - Customer owes)
                $currentBalance += $invoice->grand_total;
                \App\Models\CustomerLedger::create([
                    'customer_id' => $customer->id,
                    'transaction_type' => 'invoice',
                    'transaction_id' => $invoice->id,
                    'transaction_date' => now(),
                    'description' => "Invoice #{$invoice->invoice_number} - Sale",
                    'debit' => $invoice->grand_total,      // Customer owes this amount
                    'credit' => 0,
                    'balance' => $currentBalance,          // New balance after invoice
                ]);
                
                // Entry 2: Payment (Credit - Customer paid)
                if ($amountPaid > 0) {
                    $currentBalance -= $amountPaid;
                    
                    // Create payment record
                    $payment = \App\Models\Payment::create([
                        'payment_number' => \App\Models\Payment::generatePaymentNumber(),
                        'payment_type' => 'customer',  // 'customer' payment (not 'receipt')
                        'customer_id' => $customer->id,
                        'payment_date' => now(),
                        'payment_method' => $paymentMethod,
                        'gross_amount' => (float) $amountPaid,
                        'fee_amount' => 0.00,
                        'net_amount' => (float) $amountPaid,
                        'allocated_amount' => (float) $amountPaid,
                        'unallocated_amount' => 0.00,
                        'reference' => $paymentReference,
                        'notes' => "Payment for Invoice #{$invoice->invoice_number}",
                        'user_id' => auth()->id(),
                    ]);
                    
                    // Link payment to invoice (allocation)
                    \App\Models\PaymentAllocation::create([
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoice->id,
                        'allocated_amount' => (float) $amountPaid,
                        'allocation_date' => now(),
                    ]);
                    
                    // Create ledger entry for payment
                    \App\Models\CustomerLedger::create([
                        'customer_id' => $customer->id,
                        'transaction_type' => 'payment',
                        'transaction_id' => $payment->id,
                        'transaction_date' => now(),
                        'description' => "Payment #{$payment->payment_number} - " . strtoupper($paymentMethod),
                        'debit' => 0,
                        'credit' => $amountPaid,           // Customer paid this amount
                        'balance' => $currentBalance,      // New balance after payment
                    ]);
                }
                
                // Update customer's final balance
                $customer->balance = $currentBalance;
                $customer->save();
            }

            \DB::commit();

            // ✅ Final Result - Success response
            return response()->json([
                'success' => true,
                'message' => 'Quote converted to invoice successfully!'.($stockWarning ? ' '.$stockWarning : ''),
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'payment_status' => $paymentStatus,
                'amount_paid' => $amountPaid,
                'balance_due' => $invoice->balance_due,
                'redirect' => route('invoices.index'),
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Quote to Invoice Conversion Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to convert quote: '.$e->getMessage(),
            ], 500);
        }
    }

    // Get customer info for payment options
    public function getCustomerInfo($id)
    {
        try {
            $quote = Quote::with('customer')->findOrFail($id);
            
            if (!$quote->customer) {
                return response()->json([
                    'customer_type' => null,
                    'credit_limit' => 0,
                    'balance' => 0
                ]);
            }
            
            return response()->json([
                'customer_type' => $quote->customer->customer_type,
                'credit_limit' => $quote->customer->credit_limit,
                'balance' => $quote->customer->balance,
                'customer_name' => $quote->customer->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'customer_type' => null,
                'credit_limit' => 0,
                'balance' => 0
            ]);
        }
    }

    // Duplicate Quote
    public function duplicate($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        $newQuote = $quote->replicate();
        $newQuote->quote_number = 'QT'.(10000 + (Quote::max('id') ?? 0) + 1);
        $newQuote->status = 'draft';
        $newQuote->created_at = now();
        $newQuote->updated_at = now();
        $newQuote->save();

        foreach ($quote->items as $item) {
            $newItem = $item->replicate();
            $newItem->quote_id = $newQuote->id;
            $newItem->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Quote duplicated successfully!',
            'new_quote_number' => $newQuote->quote_number,
        ]);
    }

    // Print Quotation
    public function print(Quote $quote)
    {
        $quote->load(['customer', 'user', 'vehicleMake', 'vehicleModel', 'vehicleEngine', 'items.product']);

        return view('quotes.print', compact('quote'));
    }

    // Export Quotations
    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $quotes = Quote::with(['customer', 'items'])->get();

        if ($format === 'csv') {
            return $this->exportCSV($quotes);
        } elseif ($format === 'excel') {
            return $this->exportExcel($quotes);
        } else {
            return $this->exportPDF($quotes);
        }
    }

    private function exportCSV($quotes)
    {
        $filename = 'quotes_'.now()->format('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($quotes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Quote Number', 'Customer', 'Items Count', 'Grand Total', 'Status', 'Valid Until', 'Created']);

            foreach ($quotes as $quote) {
                fputcsv($file, [
                    $quote->quote_number,
                    $quote->customer->name ?? 'Cash Sale',
                    $quote->items->count(),
                    number_format($quote->grand_total ?? 0, 2),
                    ucfirst($quote->status),
                    $quote->valid_until ? \Carbon\Carbon::parse($quote->valid_until)->format('Y-m-d') : '-',
                    $quote->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($quotes)
    {
        $html = view('quotes.export_excel', compact('quotes'))->render();
        $filename = 'quotes_'.now()->format('Y-m-d_His').'.xls';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    private function exportPDF($quotes)
    {
        $html = view('quotes.export_pdf', compact('quotes'))->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        return $pdf->download('quotes_'.now()->format('Y-m-d_His').'.pdf');
    }

    /**
     * Search products for quote
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['products' => []]);
        }

        $products = Product::with(['brand', 'category', 'stockBatches', 'oeNumbers', 'crossRefs', 'fitments.make', 'fitments.model', 'fitments.engine'])
            ->where(function ($q) use ($query) {
                // Search by SKU
                $q->where('sku', 'like', "%{$query}%")
                  // Search by name
                    ->orWhere('name', 'like', "%{$query}%")
                  // Search by barcode
                    ->orWhere('barcode_primary', 'like', "%{$query}%")
                    ->orWhere('barcode_alternate', 'like', "%{$query}%")
                  // Search by supplier code
                    ->orWhere('supplier_code', 'like', "%{$query}%")
                  // Search by bin location
                    ->orWhere('bin_location', 'like', "%{$query}%");
            })
            // Search by OE numbers
            ->orWhereHas('oeNumbers', function ($q) use ($query) {
                $q->where('oe_number', 'like', "%{$query}%");
            })
            // Search by cross references
            ->orWhereHas('crossRefs', function ($q) use ($query) {
                $q->where('cross_ref', 'like', "%{$query}%");
            })
            // Search by vehicle fitment (through relationships)
            ->orWhereHas('fitments.make', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('fitments.model', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('fitments.engine', function ($q) use ($query) {
                $q->where('code', 'like', "%{$query}%");
            })
            ->where('status', 'active')
            ->limit(10)
            ->get();

        // Calculate stock and FIFO cost for each product
        $products->each(function ($product) {
            $product->current_stock = $product->stockBatches->sum('qty_left');
            $product->last_cost = $product->stockBatches->sortByDesc('received_date')->first()?->landed_unit_cost ?? 0;

            // Calculate FIFO cost (weighted average of available stock)
            $totalCost = 0;
            $totalQty = 0;
            foreach ($product->stockBatches->where('qty_left', '>', 0)->sortBy('received_date') as $batch) {
                $totalCost += ($batch->landed_unit_cost * $batch->qty_left);
                $totalQty += $batch->qty_left;
            }
            $product->fifo_cost = $totalQty > 0 ? ($totalCost / $totalQty) : 0;
        });

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'brand' => $product->brand?->name,
                    'category' => $product->category?->name,
                    'unit' => $product->unit,
                    'current_stock' => $product->current_stock,
                    'last_cost' => $product->last_cost,
                    'fifo_cost' => $product->fifo_cost,
                    'price_normal' => $product->price_normal,
                    'price_online' => $product->price_online,
                    'price_workshop' => $product->price_workshop,
                    'bin_location' => $product->bin_location,
                    'allow_negative' => $product->allow_negative,
                    'oe_numbers' => $product->oeNumbers->pluck('oe_number')->take(3),
                    'cross_refs' => $product->crossRefs->pluck('cross_ref')->take(3),
                    'fitments' => $product->fitments->take(2)->map(function ($fitment) {
                        return ($fitment->make?->name ?? '').' '.($fitment->model?->name ?? '').' ('.($fitment->year_start ?? '').'-'.($fitment->year_end ?? '').')';
                    }),
                    'image_url' => $product->primary_image_url,
                ];
            }),
        ]);
    }
}