<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductOENumbersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('product_oe_numbers')->insert([
            ['product_id' => 1, 'oe_number' => 'OE12345', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 1, 'oe_number' => 'OE67890', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
    