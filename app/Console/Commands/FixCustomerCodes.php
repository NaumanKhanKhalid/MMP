<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class FixCustomerCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:fix-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix customer codes that are in wrong format and regenerate them properly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing customer codes...');
        
        // Find customers with wrong format codes (not matching CUST-XXXX pattern)
        $wrongFormatCustomers = Customer::whereNotNull('customer_code')
            ->where(function($query) {
                $query->whereRaw('customer_code NOT REGEXP ?', ['^CUST-[0-9]{4}$'])
                      ->orWhereNull('customer_code');
            })
            ->get();
        
        if ($wrongFormatCustomers->isEmpty()) {
            $this->info('No customers with wrong format found!');
            return 0;
        }
        
        $this->info("Found {$wrongFormatCustomers->count()} customers with wrong format codes.");
        
        $fixed = 0;
        $failed = 0;
        
        foreach ($wrongFormatCustomers as $customer) {
            $oldCode = $customer->customer_code;
            
            try {
                // Generate new code
                $newCode = Customer::generateCustomerCode();
                $customer->customer_code = $newCode;
                $customer->save();
                
                $this->line("✓ Fixed: {$oldCode} → {$newCode} (ID: {$customer->id}, Name: {$customer->name})");
                $fixed++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to fix customer ID {$customer->id}: {$e->getMessage()}");
                $failed++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Fixed: {$fixed}");
        if ($failed > 0) {
            $this->error("- Failed: {$failed}");
        }
        
        return 0;
    }
}
