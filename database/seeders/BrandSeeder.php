<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            ['name' => 'Toyota'],
            ['name' => 'Honda'],
            ['name' => 'Suzuki'],
            ['name' => 'Bosch'],
        ]);
    }
}
