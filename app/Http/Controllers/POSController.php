<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockBatch;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class POSController extends Controller
{
    /**
     * Display POS interface
     */
    public function index()
    {
        return view('pos.index');
    }

    /**
     * Get products for POS
     */
    public function getProducts()
    {
        $products = Product::with(['brand', 'category'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode_primary' => $product->barcode_primary,
                    'price_normal' => $product->price_normal,
                    'price_online' => $product->price_online,
                    'price_workshop' => $product->price_workshop,
                    'on_hand' => $product->on_hand,
                    'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category->name ?? 'Uncategorized',
                    'brand_name' => $product->brand->name ?? 'No Brand',
                ];
            });

        return response()->json($products);
    }

    /**
     * Get customers for POS
     */
    public function getCustomers()
    {
        $customers = Customer::select('id', 'name', 'email', 'phone')
            ->orderBy('name')
            ->get();

        return response()->json($customers);
    }

    /**
     * Get categories for POS
     */
    public function getCategories()
    {
        $categories = Category::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    /**
     * Process sale
     */
    public function processSale(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|numeric|min:0.001',
            'cart.*.price' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|in:cash,card,eft,account',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
        ]);

        DB::beginTransaction();
        
        try {
            $cart = $request->cart;
            $customerId = $request->customer_id;
            $paymentMethod = $request->payment_method;
            $discountAmount = $request->discount_amount ?? 0;
            $discountType = $request->discount_type ?? 'fixed';

            // Calculate totals
            $subtotal = 0;
            $items = [];

            foreach ($cart as $cartItem) {
                $product = Product::findOrFail($cartItem['id']);
                
                // Check stock availability
                if ($product->on_hand < $cartItem['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $lineTotal = $cartItem['quantity'] * $cartItem['price'];
                $subtotal += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                    'line_total' => $lineTotal,
                ];
            }

            // Apply discount
            $discount = 0;
            if ($discountAmount > 0) {
                if ($discountType === 'percentage') {
                    $discount = $subtotal * ($discountAmount / 100);
                } else {
                    $discount = $discountAmount;
                }
            }

            $grandTotal = $subtotal - $discount;

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'customer_id' => $customerId,
                'customer_name' => $customerId ? Customer::find($customerId)->name : 'Walk-in Customer',
                'customer_email' => $customerId ? Customer::find($customerId)->email : null,
                'customer_phone' => $customerId ? Customer::find($customerId)->phone : null,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'discount_percentage' => $discountType === 'percentage' ? $discountAmount : 0,
                'vat_amount' => 0, // VAT calculation can be added later
                'grand_total' => $grandTotal,
                'payment_status' => $paymentMethod === 'account' ? 'posted' : 'paid',
                'payment_method' => $paymentMethod,
                'amount_paid' => $paymentMethod === 'account' ? 0 : $grandTotal,
                'balance_due' => $paymentMethod === 'account' ? $grandTotal : 0,
                'vat_enabled' => false,
                'vat_rate' => 0,
                'vat_inclusive' => false,
                'notes' => 'POS Sale',
                'reference' => 'POS-' . now()->format('YmdHis'),
                'user_id' => auth()->id(),
            ]);

            // Create invoice items and update stock
            foreach ($items as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                // Calculate FIFO cost
                $fifoCost = $this->calculateFIFOCost($product, $quantity);

                // Create invoice item
                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_barcode' => $product->barcode_primary,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'line_total' => $quantity * $price,
                    'unit_cost' => $fifoCost,
                    'line_cost' => $quantity * $fifoCost,
                    'line_profit' => ($quantity * $price) - ($quantity * $fifoCost),
                ]);

                // Update stock using FIFO
                $this->updateStockFIFO($product, $quantity, $invoiceItem->id);
            }

            // Update invoice totals
            $invoice->update([
                'subtotal' => $invoice->items->sum('line_total'),
                'grand_total' => $invoice->subtotal - $invoice->discount_amount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'pdf_url' => route('pos.invoice-pdf', $invoice),
                'message' => 'Sale completed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Calculate FIFO cost for a product
     */
    private function calculateFIFOCost($product, $quantity)
    {
        $batches = StockBatch::where('product_id', $product->id)
            ->where('qty_left', '>', 0)
            ->orderBy('created_at')
            ->get();

        $totalCost = 0;
        $remainingQuantity = $quantity;

        foreach ($batches as $batch) {
            if ($remainingQuantity <= 0) break;

            $usedQuantity = min($remainingQuantity, $batch->qty_left);
            $totalCost += $usedQuantity * $batch->unit_cost;
            $remainingQuantity -= $usedQuantity;
        }

        return $remainingQuantity > 0 ? 0 : ($totalCost / $quantity);
    }

    /**
     * Update stock using FIFO method
     */
    private function updateStockFIFO($product, $quantity, $invoiceItemId)
    {
        $batches = StockBatch::where('product_id', $product->id)
            ->where('qty_left', '>', 0)
            ->orderBy('created_at')
            ->get();

        $remainingQuantity = $quantity;

        foreach ($batches as $batch) {
            if ($remainingQuantity <= 0) break;

            $usedQuantity = min($remainingQuantity, $batch->qty_left);
            
            // Update batch quantity
            $batch->qty_left -= $usedQuantity;
            $batch->save();

            // Create stock ledger entry
            StockLedger::create([
                'product_id' => $product->id,
                'document_type' => 'sale',
                'document_id' => $invoiceItemId,
                'qty' => -$usedQuantity,
                'unit_cost' => $batch->unit_cost,
                'total_cost' => -($usedQuantity * $batch->unit_cost),
                'user_id' => auth()->id(),
                'notes' => 'POS Sale - FIFO',
            ]);

            $remainingQuantity -= $usedQuantity;
        }

        // Handle negative stock if needed
        if ($remainingQuantity > 0 && $product->allow_negative) {
            StockLedger::create([
                'product_id' => $product->id,
                'document_type' => 'sale',
                'document_id' => $invoiceItemId,
                'qty' => -$remainingQuantity,
                'unit_cost' => 0,
                'total_cost' => 0,
                'user_id' => auth()->id(),
                'notes' => 'POS Sale - Negative Stock',
            ]);
        }
    }

    /**
     * Search products
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['brand', 'category'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode_primary', 'like', "%{$query}%")
                  ->orWhere('barcode_alternate', 'like', "%{$query}%");
            })
            ->where('status', 'active')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode_primary' => $product->barcode_primary,
                    'price_normal' => $product->price_normal,
                    'on_hand' => $product->on_hand,
                    'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                    'category_name' => $product->category->name ?? 'Uncategorized',
                    'brand_name' => $product->brand->name ?? 'No Brand',
                ];
            });

        return response()->json($products);
    }


    /**
     * Get product by barcode
     */
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        
        $product = Product::with(['brand', 'category'])
            ->where(function ($q) use ($barcode) {
                $q->where('barcode_primary', $barcode)
                  ->orWhere('barcode_alternate', $barcode);
            })
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode_primary' => $product->barcode_primary,
                'price_normal' => $product->price_normal,
                'price_online' => $product->price_online,
                'price_workshop' => $product->price_workshop,
                'on_hand' => $product->on_hand,
                'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                'category_name' => $product->category->name ?? 'Uncategorized',
                'brand_name' => $product->brand->name ?? 'No Brand',
            ]
        ]);
    }

    /**
     * Generate PDF invoice
     */
    public function generateInvoicePDF(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'user']);
        
        $pdf = Pdf::loadView('pos.invoice-pdf', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * Generate picking list for delivery
     */
    public function generatePickingList(Invoice $invoice)
    {
        $invoice->load(['items.product', 'customer']);
        
        $pdf = Pdf::loadView('pos.picking-list', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Picking-List-{$invoice->invoice_number}.pdf");
    }

    /**
     * Send invoice via WhatsApp/Email
     */
    public function sendInvoice(Request $request, Invoice $invoice)
    {
        $request->validate([
            'method' => 'required|in:whatsapp,email',
            'contact' => 'required|string'
        ]);

        $invoice->load(['customer', 'items.product', 'user']);
        
        if ($request->method === 'whatsapp') {
            // Generate WhatsApp message
            $message = $this->generateWhatsAppMessage($invoice);
            $whatsappUrl = "https://wa.me/{$request->contact}?text=" . urlencode($message);
            
            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
                'message' => 'WhatsApp message prepared'
            ]);
        } else {
            // For email, you would integrate with your email service
            // This is a placeholder for email functionality
            return response()->json([
                'success' => true,
                'message' => 'Email functionality will be implemented with your email service'
            ]);
        }
    }

    /**
     * Generate WhatsApp message
     */
    private function generateWhatsAppMessage(Invoice $invoice)
    {
        $message = "🏪 *MMP Auto-Meister*\n\n";
        $message .= "📋 *Invoice: {$invoice->invoice_number}*\n";
        $message .= "📅 Date: " . $invoice->created_at->format('d/m/Y H:i') . "\n\n";
        
        if ($invoice->customer) {
            $message .= "👤 Customer: {$invoice->customer->name}\n";
            if ($invoice->customer->phone) {
                $message .= "📞 Phone: {$invoice->customer->phone}\n";
            }
        }
        
        $message .= "\n📦 *Items:*\n";
        foreach ($invoice->items as $item) {
            $message .= "• {$item->product_name} (Qty: {$item->quantity}) - $" . number_format($item->line_total, 2) . "\n";
        }
        
        $message .= "\n💰 *Total: $" . number_format($invoice->grand_total, 2) . "*\n";
        $message .= "💳 Payment: " . ucfirst($invoice->payment_method) . "\n\n";
        $message .= "Thank you for your business! 🚗";
        
        return $message;
    }
}
