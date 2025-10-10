<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();
        $product = Product::first();
        if (!$customer || !$product) return;

        // Generate a unique quote_number (e.g., QT10001)
        $lastQuote = Quote::orderBy('id', 'desc')->first();
        $nextNumber = 10001;
        if ($lastQuote && preg_match('/QT(\d+)/', $lastQuote->quote_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $quoteNumber = 'QT' . $nextNumber;

        $quote = Quote::create([
            'quote_number' => $quoteNumber,
            'customer_id' => $customer->id,
            'status' => 'draft', // must match enum
            'valid_until' => Carbon::now()->addDays(10)->toDateString(),
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'vehicle_vin' => 'JTDBR32E720123456',
            'vehicle_reg' => 'ABC-123',
            'vehicle_mileage' => '45000',
            'notes' => 'Customer requested urgent delivery.',
        ]);

        QuoteItem::create([
            'quote_id' => $quote->id,
            'product_id' => $product->id,
            'description' => 'Front brake pads',
            'quantity' => 2,
            'unit_price' => 3500,
            'discount' => 0,
            'total' => 7000,
        ]);
    }
}
