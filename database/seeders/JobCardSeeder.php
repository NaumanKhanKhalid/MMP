<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobCard;
use App\Models\JobCardItem;
use App\Models\JobCardLabour;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class JobCardSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "⚠️  Please run CustomerSeeder and ProductSeeder first\n";
            return;
        }

        DB::beginTransaction();

        try {
            $jobDescriptions = [
                'Engine oil and filter change',
                'Brake pads replacement',
                'Full vehicle service - 60,000km',
                'Air conditioning service and regas',
                'Wheel alignment and balancing',
                'Suspension check and repair',
                'Battery replacement',
                'Alternator repair',
                'Clutch replacement',
                'Timing belt replacement',
            ];

            $labourTypes = ['diagnostic', 'repair', 'maintenance', 'installation', 'other'];
            $statuses = ['pending', 'booked', 'in_progress', 'completed'];

            // Create 15 job cards
            for ($i = 1; $i <= 15; $i++) {
                $customer = $customers->random();
                $status = $statuses[array_rand($statuses)];
                
                $jobCard = JobCard::create([
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'customer_email' => $customer->email,
                    'vehicle_make' => $customer->vehicle_make ?? $this->randomMake(),
                    'vehicle_model' => $customer->vehicle_model ?? $this->randomModel(),
                    'vehicle_registration' => $this->randomReg(),
                    'vehicle_vin' => 'VIN' . strtoupper(substr(md5(rand()), 0, 17)),
                    'vehicle_mileage' => rand(10000, 200000) . ' km',
                    'engine_code' => $this->randomEngine(),
                    'vehicle_year' => rand(2010, 2024),
                    'job_description' => $jobDescriptions[array_rand($jobDescriptions)],
                    'customer_complaint' => $this->randomComplaint(),
                    'notes' => $i % 3 == 0 ? 'Check for additional issues' : null,
                    'status' => $status,
                    'booked_at' => in_array($status, ['booked', 'in_progress', 'completed']) ? now()->subDays(rand(1, 5)) : null,
                    'started_at' => in_array($status, ['in_progress', 'completed']) ? now()->subDays(rand(1, 3)) : null,
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 2)) : null,
                    'created_by' => 1,
                ]);

                // Add 1-3 parts
                $partsCount = rand(1, 3);
                $partsTotal = 0;

                for ($j = 0; $j < $partsCount; $j++) {
                    $product = $products->random();
                    $qty = rand(1, 3);
                    $price = $product->price_workshop;
                    $lineTotal = $qty * $price;

                    JobCardItem::create([
                        'job_card_id' => $jobCard->id,
                        'product_id' => $product->id,
                        'product_sku' => $product->sku,
                        'product_name' => $product->name,
                        'product_barcode' => $product->barcode_primary,
                        'quantity_used' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                        'created_by' => 1,
                    ]);

                    $partsTotal += $lineTotal;

                    // Reserve parts if booked or in progress
                    if (in_array($status, ['booked', 'in_progress'])) {
                        $product->increment('reserved', $qty);
                    }
                }

                // Add 1-2 labour entries
                $labourCount = rand(1, 2);
                $labourTotal = 0;

                for ($k = 0; $k < $labourCount; $k++) {
                    $hours = [0.5, 1, 1.5, 2, 2.5, 3][array_rand([0.5, 1, 1.5, 2, 2.5, 3])];
                    $rate = rand(250, 400);
                    $total = $hours * $rate;

                    JobCardLabour::create([
                        'job_card_id' => $jobCard->id,
                        'labour_description' => $this->randomLabourDesc(),
                        'hours_worked' => $hours,
                        'hourly_rate' => $rate,
                        'total_amount' => $total,
                        'labour_type' => $labourTypes[array_rand($labourTypes)],
                        'technician_id' => null,
                        'status' => 'pending',
                        'created_by' => 1,
                    ]);

                    $labourTotal += $total;
                }

                // Update totals
                $jobCard->update([
                    'parts_total' => $partsTotal,
                    'labour_total' => $labourTotal,
                    'grand_total' => $partsTotal + $labourTotal,
                ]);
            }

            DB::commit();
            echo "✅ Created 15 job cards with parts and labour\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }

    private function randomMake()
    {
        return collect(['Toyota', 'Honda', 'BMW', 'Mercedes', 'Ford', 'Volkswagen', 'Nissan', 'Mazda'])->random();
    }

    private function randomModel()
    {
        return collect(['Corolla', 'Civic', '320i', 'C-Class', 'Focus', 'Golf', 'Altima', 'Mazda3'])->random();
    }

    private function randomReg()
    {
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        $numbers = rand(100, 999);
        $province = collect(['GP', 'WC', 'KZN', 'EC', 'FS'])->random();
        return $letters . $numbers . $province;
    }

    private function randomEngine()
    {
        return collect(['N20B20', 'M54B30', '4G63', '2JZ-GTE', 'B18C', 'VQ35DE'])->random();
    }

    private function randomComplaint()
    {
        return collect([
            'Engine warning light on',
            'Strange noise from brakes',
            'Oil leak under vehicle',
            'Air conditioning not cooling',
            'Rough idling',
            'Battery keeps dying',
            'Steering vibration at high speed',
            'Clutch slipping',
        ])->random();
    }

    private function randomLabourDesc()
    {
        return collect([
            'Oil and filter change',
            'Brake pad replacement',
            'Diagnostic scan',
            'Wheel alignment',
            'Suspension inspection',
            'AC regas',
            'Battery replacement',
            'General service',
        ])->random();
    }
}


