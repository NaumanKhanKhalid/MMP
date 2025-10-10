<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            ['name' => 'Toyota', 'slug' => 'toyota', 'status' => 'active'],
            ['name' => 'Honda', 'slug' => 'honda', 'status' => 'active'],
            ['name' => 'Suzuki', 'slug' => 'suzuki', 'status' => 'active'],
            ['name' => 'Bosch', 'slug' => 'bosch', 'status' => 'active'],
        ]);
    }
}
