<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FitmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fitments')->insert([
            ['make' => 'Toyota', 'model' => 'Corolla', 'year' => '2018'],
            ['make' => 'Honda', 'model' => 'Civic', 'year' => '2020'],
            ['make' => 'Suzuki', 'model' => 'Alto', 'year' => '2019'],
        ]);
    }
}
