<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarModelsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('car_models')->insert([
            ['car_make_id' => 1, 'name' => 'Corolla', 'generation' => '12th Gen', 'body_type' => 'Sedan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['car_make_id' => 1, 'name' => 'Camry', 'generation' => '8th Gen', 'body_type' => 'Sedan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['car_make_id' => 2, 'name' => 'Civic', 'generation' => '10th Gen', 'body_type' => 'Sedan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['car_make_id' => 2, 'name' => 'City', 'generation' => '5th Gen', 'body_type' => 'Sedan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['car_make_id' => 3, 'name' => 'Swift', 'generation' => '3rd Gen', 'body_type' => 'Hatchback', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
