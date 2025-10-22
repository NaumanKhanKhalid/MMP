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
        // Update payment_status enum to include 'partial'
        \DB::statement("ALTER TABLE invoices MODIFY payment_status ENUM('draft', 'partial', 'paid', 'posted', 'cancelled') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        \DB::statement("ALTER TABLE invoices MODIFY payment_status ENUM('draft', 'posted', 'paid', 'cancelled') NOT NULL DEFAULT 'draft'");
    }
};