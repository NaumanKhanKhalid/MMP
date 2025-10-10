<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('product_supplier')->insertOrIgnore([
            [
                'product_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 900.00,
                'currency' => 'PKR',
                'lead_time' => 5,
                'supplier_sku' => 'SUP-P0001',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 1,
                'supplier_id' => 2,
                'purchase_price' => 950.00,
                'currency' => 'PKR',
                'lead_time' => 7,
                'supplier_sku' => 'SUP-P0002',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
