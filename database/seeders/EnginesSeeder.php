<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnginesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('engines')->insert([
            ['code' => '1NZ-FE', 'displacement' => '1500cc', 'fuel_type' => 'Petrol', 'cylinder' => 4, 'power' => '109hp', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'R18A', 'displacement' => '1800cc', 'fuel_type' => 'Petrol', 'cylinder' => 4, 'power' => '140hp', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'K12B', 'displacement' => '1200cc', 'fuel_type' => 'Petrol', 'cylinder' => 4, 'power' => '82hp', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
