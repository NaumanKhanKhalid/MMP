<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert status to VARCHAR temporarily
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status VARCHAR(20) DEFAULT 'draft'");
        
        // Update any invalid existing values
        DB::table('purchase_orders')->whereNotIn('status', ['draft', 'approved', 'sent', 'partially_received', 'closed', 'completed', 'cancelled'])->update(['status' => 'draft']);
        
        // Convert back to ENUM with new values
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('draft', 'approved', 'sent', 'partially_received', 'closed', 'completed', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to original ENUM
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('draft', 'sent', 'partially_received', 'completed', 'cancelled') DEFAULT 'draft'");
    }
};
