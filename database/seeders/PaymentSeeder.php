<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = Invoice::where('payment_status', 'posted')
            ->where('balance_due', '>', 0)
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            echo "⚠️  No invoices with balance due found\n";
            return;
        }

        DB::beginTransaction();

        try {
            // Create 15 payments
            for ($i = 0; $i < 15; $i++) {
                $invoice = $invoices->random();
                if (!$invoice->customer_id) continue;

                $paymentType = collect(['cash', 'card', 'eft'])->random();
                $amount = min($invoice->balance_due, rand(100, 5000));
                
                // Calculate fees
                $fee = 0;
                if ($paymentType === 'card') {
                    $fee = $amount * 0.0275; // 2.75% card fee
                } elseif ($paymentType === 'cash') {
                    $fee = ($amount / 100) * 2.50; // R2.50 per R100
                }

                $netAmount = $amount - $fee;

                $payment = Payment::create([
                    'customer_id' => $invoice->customer_id,
                    'payment_type' => 'customer',
                    'payment_method' => $paymentType,
                    'gross_amount' => $amount,
                    'fee_amount' => $fee,
                    'net_amount' => $netAmount,
                    'allocated_amount' => $amount,
                    'unallocated_amount' => 0,
                    'payment_date' => now()->subDays(rand(0, 10)),
                    'reference' => 'PAY' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'notes' => 'Payment for ' . $invoice->invoice_number,
                    'user_id' => 1,
                    'status' => 'posted',
                ]);

                // Allocate to invoice
                PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'allocated_amount' => $amount,
                    'allocation_date' => now()->subDays(rand(0, 10)),
                ]);

                // Update invoice
                $invoice->increment('amount_paid', $amount);
                $invoice->decrement('balance_due', $amount);
            }

            DB::commit();
            echo "✅ Created 15 payments with allocations\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

