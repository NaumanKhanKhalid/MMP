<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockLedgerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('stock_ledger')->insert([
            [
                'product_id' => 1,
                'document_type' => 'GRN',
                'document_id' => 1,
                'qty' => 100,
                'unit_cost' => 900.00,
                'total_cost' => 90000.00,
                'user_id' => 1,
                'notes' => 'Initial Stock',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'product_id' => 1,
                'document_type' => 'PO',
                'document_id' => 2,
                'qty' => 50,
                'unit_cost' => 950.00,
                'total_cost' => 47500.00,
                'user_id' => 1,
                'notes' => 'Purchase Order Stock',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
