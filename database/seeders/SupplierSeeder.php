<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'ABC Auto Parts',
                'phone' => '03001234567',
                'email' => 'abc@supplier.com',
                'address' => '123 Main Street, City A',
                'lead_time' => 5,  // days
                'balance' => 1000.00,
                'status' => 'active',
            ],
            [
                'name' => 'XYZ Traders',
                'phone' => '03119876543',
                'email' => 'xyz@supplier.com',
                'address' => '456 Market Road, City B',
                'lead_time' => 7,
                'balance' => 1500.00,
                'status' => 'active',
            ],
        ]);
    }
}
