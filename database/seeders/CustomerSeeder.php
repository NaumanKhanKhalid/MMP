<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ali Khan',
                'email' => 'ali.khan@example.com',
                'phone' => '03001234567',
                'address' => '123 Main Street, Karachi',
                'vehicle_make' => 'Toyota',
                'vehicle_model' => 'Corolla',
                'vehicle_vin' => 'JTDBR32E720123456',
                'vehicle_reg' => 'ABC-123',
                'vehicle_mileage' => '45000',
                'terms' => 'cash',
                'credit_limit' => 0,
                'price_tier' => 'normal',
                'statement_delivery' => 'email',
                'balance' => 0,
            ],
            [
                'name' => 'Sara Ahmed',
                'email' => 'sara.ahmed@example.com',
                'phone' => '03119876543',
                'address' => '456 Market Road, Lahore',
                'vehicle_make' => 'Honda',
                'vehicle_model' => 'Civic',
                'vehicle_vin' => '2HGES267X1H123456',
                'vehicle_reg' => 'XYZ-789',
                'vehicle_mileage' => '32000',
                'terms' => 'on_account',
                'credit_limit' => 50000,
                'price_tier' => 'online',
                'statement_delivery' => 'whatsapp',
                'balance' => 12000,
            ],
        ];
        foreach ($customers as $data) {
            Customer::create($data);
        }
    }
}
