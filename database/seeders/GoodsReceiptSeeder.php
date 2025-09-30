<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoodsReceiptSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('goods_receipts')->insert([
            [
                'id' => 1,
                'supplier_id' => 1,
                'purchase_order_id' => null,
                'grn_number' => 'GRN-0001',
                'received_date' => Carbon::now(),
                'invoice_number' => 'INV-1001',
                'total_amount' => 5000,
                'status' => 'completed',
                'notes' => 'First GRN for Toyota Supplier',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
