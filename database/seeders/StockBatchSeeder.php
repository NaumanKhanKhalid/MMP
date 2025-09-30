<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockBatchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stock_batches')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'batch_code' => 'BATCH-001',
                'qty_received' => 100,
                'qty_left' => 100,
                'landed_unit_cost' => 1200,
                'received_date' => Carbon::now(),
                'grn_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'batch_code' => 'BATCH-002',
                'qty_received' => 50,
                'qty_left' => 50,
                'landed_unit_cost' => 1800,
                'received_date' => Carbon::now(),
                'grn_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
