<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Convert enum to varchar temporarily
        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status VARCHAR(20) DEFAULT 'draft'");
        
        // Step 2: Update any invalid values to 'posted'
        DB::table('invoices')
            ->whereNotIn('payment_status', ['draft', 'posted', 'paid', 'unpaid', 'partially_paid', 'cancelled'])
            ->update(['payment_status' => 'posted']);
        
        // Step 3: Convert back to enum with new values
        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status ENUM('draft', 'posted', 'paid', 'unpaid', 'partially_paid', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Convert enum to varchar temporarily
        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status VARCHAR(20) DEFAULT 'draft'");
        
        // Step 2: Update 'unpaid' and 'partially_paid' back to 'posted'
        DB::table('invoices')
            ->whereIn('payment_status', ['unpaid', 'partially_paid'])
            ->update(['payment_status' => 'posted']);
        
        // Step 3: Convert back to original enum values
        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status ENUM('draft', 'posted', 'paid', 'cancelled') DEFAULT 'draft'");
    }
};
