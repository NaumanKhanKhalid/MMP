<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReturn;
use App\Models\ReturnItem;
use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class ReturnSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = Invoice::where('payment_status', 'posted')->with('items')->get();

        if ($invoices->isEmpty()) {
            echo "⚠️  Please run InvoiceSeeder first\n";
            return;
        }

        DB::beginTransaction();

        try {
            $returnReasons = [
                'Defective product',
                'Wrong item ordered',
                'Customer changed mind',
                'Damaged on arrival',
                'Not compatible with vehicle',
                'Better price found elsewhere',
            ];

            $statuses = ['pending', 'approved', 'completed'];
            $returnTypes = ['partial', 'full'];
            $refundMethods = ['cash', 'store_credit', 'bank_transfer'];
            $stockHandlingTypes = ['restock', 'writeoff', 'credit_only'];

            // Create 10 returns
            for ($i = 0; $i < 10; $i++) {
                $invoice = $invoices->random();
                if ($invoice->items->isEmpty()) continue;

                $status = $statuses[array_rand($statuses)];
                $returnType = $returnTypes[array_rand($returnTypes)];
                
                $return = ProductReturn::create([
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'user_id' => 1,
                    'return_type' => $returnType,
                    'reason' => $returnReasons[array_rand($returnReasons)],
                    'refund_method' => $refundMethods[array_rand($refundMethods)],
                    'stock_handling_type' => $stockHandlingTypes[array_rand($stockHandlingTypes)],
                    'restock_items' => true,
                    'status' => $status,
                    'returned_at' => $status === 'completed' ? now()->subDays(rand(0, 5)) : null,
                    'created_at' => now()->subDays(rand(1, 15)),
                ]);

                // Add return items (1-2 items)
                $itemsToReturn = $returnType === 'full' ? $invoice->items : $invoice->items->take(rand(1, 2));
                $totalAmount = 0;

                foreach ($itemsToReturn as $invoiceItem) {
                    $qtyReturned = $returnType === 'full' ? $invoiceItem->quantity : rand(1, $invoiceItem->quantity);
                    $lineTotal = $qtyReturned * $invoiceItem->unit_price;

                    ReturnItem::create([
                        'return_id' => $return->id,
                        'invoice_item_id' => $invoiceItem->id,
                        'product_id' => $invoiceItem->product_id,
                        'product_sku' => $invoiceItem->product_sku,
                        'product_name' => $invoiceItem->product_name,
                        'product_barcode' => $invoiceItem->product_barcode,
                        'quantity_returned' => $qtyReturned,
                        'unit_price' => $invoiceItem->unit_price,
                        'line_total' => $lineTotal,
                        'return_reason' => $returnReasons[array_rand($returnReasons)],
                        'condition' => collect(['new', 'used', 'damaged', 'defective'])->random(),
                        'restock' => true,
                    ]);

                    $totalAmount += $lineTotal;
                }

                $return->update(['total_amount' => $totalAmount]);

                // Create credit note if completed
                if ($status === 'completed') {
                    CreditNote::create([
                        'return_id' => $return->id,
                        'invoice_id' => $invoice->id,
                        'customer_id' => $invoice->customer_id,
                        'user_id' => 1,
                        'subtotal' => $totalAmount,
                        'total_amount' => $totalAmount,
                        'status' => 'issued',
                        'issued_at' => now()->subDays(rand(0, 5)),
                    ]);
                }
            }

            DB::commit();
            echo "✅ Created 10 returns with credit notes\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

