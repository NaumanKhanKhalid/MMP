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
        Schema::table('products', function (Blueprint $table) {
            // Add stock quantity tracking columns after reorder_level
            $table->decimal('on_hand', 15, 3)->default(0)->after('reorder_level')
                  ->comment('Current physical stock quantity');
            $table->decimal('reserved', 15, 3)->default(0)->after('on_hand')
                  ->comment('Reserved for job cards/quotes');
            $table->decimal('on_order', 15, 3)->default(0)->after('reserved')
                  ->comment('Incoming stock from purchase orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['on_hand', 'reserved', 'on_order']);
        });
    }
};
