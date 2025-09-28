<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductCrossRefsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('product_cross_refs')->insert([
            ['product_id' => 1, 'cross_ref' => 'CR12345', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 1, 'cross_ref' => 'CR67890', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
