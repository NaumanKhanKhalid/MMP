<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::first();
        $user = User::first();
        if (!$supplier || !$user) return;

        PurchaseOrder::create([
            'supplier_id' => $supplier->id,
            'po_number' => 'PO-0001',
            'order_date' => Carbon::now()->subDays(10)->toDateString(),
            'expected_date' => Carbon::now()->addDays(5)->toDateString(),
            'status' => 'sent',
            'total_amount' => 150000,
            'notes' => 'Urgent order for new stock',
            'user_id' => $user->id,
        ]);
    }
}
