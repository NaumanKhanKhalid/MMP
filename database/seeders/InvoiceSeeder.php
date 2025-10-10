<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockBatch;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "⚠️  Please run CustomerSeeder and ProductSeeder first\n";
            return;
        }

        DB::beginTransaction();

        try {
            // Create 20 invoices with various statuses
            for ($i = 1; $i <= 20; $i++) {
                $customer = $customers->random();
                $isPosted = $i > 5; // First 5 are draft, rest are posted
                
                $invoice = Invoice::create([
                    'customer_id' => rand(0, 3) === 0 ? null : $customer->id, // 25% walk-in
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'customer_email' => $customer->email,
                    'customer_address' => $customer->address,
                    'vehicle_make' => $customer->vehicle_make ?? $this->randomMake(),
                    'vehicle_model' => $customer->vehicle_model,
                    'vehicle_reg' => $customer->vehicle_reg,
                    'vehicle_mileage' => rand(10000, 200000),
                    'payment_method' => $this->randomPaymentMethod(),
                    'vat_enabled' => false,
                    'vat_rate' => 0,
                    'payment_status' => $isPosted ? 'posted' : 'draft',
                    'user_id' => 1,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Add 2-5 items per invoice
                $itemCount = rand(2, 5);
                $subtotal = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);
                    $unitPrice = $product->price_normal;
                    $lineTotal = $quantity * $unitPrice;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'product_sku' => $product->sku,
                        'product_name' => $product->name,
                        'product_barcode' => $product->barcode_primary,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_amount' => 0,
                        'discount_percentage' => 0,
                        'line_total' => $lineTotal,
                        'unit_cost' => 0,
                        'line_cost' => 0,
                        'line_profit' => $lineTotal,
                    ]);

                    $subtotal += $lineTotal;

                    // Consume stock if posted
                    if ($isPosted) {
                        $product->decrement('on_hand', $quantity);
                    }
                }

                // Update invoice totals
                $invoice->update([
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'shipping' => 0,
                    'vat_amount' => 0,
                    'grand_total' => $subtotal,
                    'amount_paid' => $isPosted ? ($i % 3 == 0 ? $subtotal : rand(0, $subtotal)) : 0, // Some fully paid, some partial
                    'balance_due' => $isPosted ? ($i % 3 == 0 ? 0 : rand(0, $subtotal)) : $subtotal,
                ]);
            }

            DB::commit();
            echo "✅ Created 20 invoices with items\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }

    private function randomPaymentMethod()
    {
        return collect(['cash', 'card', 'eft', 'on_account'])->random();
    }

    private function randomMake()
    {
        return collect(['Toyota', 'Honda', 'BMW', 'Mercedes', 'Ford', 'Volkswagen'])->random();
    }
}

