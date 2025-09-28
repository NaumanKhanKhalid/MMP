<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductFitmentsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('product_fitments')->insert([
            ['product_id' => 1, 'make_id' => 1, 'model_id' => 1, 'engine_id' => 1, 'year_start' => 2018, 'year_end' => 2022, 'market' => 'Pakistan', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 1, 'make_id' => 2, 'model_id' => 3, 'engine_id' => 2, 'year_start' => 2017, 'year_end' => 2021, 'market' => 'Pakistan', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
