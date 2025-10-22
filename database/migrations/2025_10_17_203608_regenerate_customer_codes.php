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
        // Temporarily remove unique constraint
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['customer_code']);
        });

        // Regenerate all customer codes sequentially
        $customers = DB::table('customers')->orderBy('id')->get();
        
        foreach ($customers as $index => $customer) {
            $newCode = 'CUST-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            DB::table('customers')
                ->where('id', $customer->id)
                ->update(['customer_code' => $newCode]);
        }

        // Re-add unique constraint
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('customer_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed
    }
};
