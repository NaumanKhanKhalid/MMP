<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarMakesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('car_makes')->insert([
            ['name' => 'Toyota', 'country' => 'Japan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Honda', 'country' => 'Japan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Suzuki', 'country' => 'Japan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ford', 'country' => 'USA', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'BMW', 'country' => 'Germany', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
