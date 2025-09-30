<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockLedgerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stock_ledger')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'document_type' => 'GRN',
                'document_id' => 1,
                'qty' => 100,
                'unit_cost' => 1200,
                'total_cost' => 120000,
                'user_id' => 1,
                'notes' => 'Initial stock from GRN-0001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'product_id' => 2,
                'document_type' => 'GRN',
                'document_id' => 1,
                'qty' => 50,
                'unit_cost' => 1800,
                'total_cost' => 90000,
                'user_id' => 1,
                'notes' => 'Initial stock from GRN-0001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
