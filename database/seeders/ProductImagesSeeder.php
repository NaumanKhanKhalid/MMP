<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('product_images')->insert([
            ['product_id' => 1, 'path' => 'products/images/product1_img1.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 1, 'path' => 'products/images/product1_img2.jpg', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
