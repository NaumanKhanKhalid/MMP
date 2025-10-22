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
        // Step 1: Convert customer_type to VARCHAR temporarily
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_type')->change();
        });

        // Step 2: Update data - move individual/business to customer_category
        DB::statement("UPDATE customers SET customer_category = customer_type WHERE customer_type IN ('individual', 'business')");
        
        // Step 3: Set all customer_type to 'cash' by default
        DB::statement("UPDATE customers SET customer_type = 'cash'");

        // Step 4: Convert customer_type back to ENUM with new values
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('customer_type', ['cash', 'credit'])->default('cash')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Convert customer_type to VARCHAR temporarily
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_type')->change();
        });

        // Step 2: Move customer_category back to customer_type
        DB::statement("UPDATE customers SET customer_type = customer_category WHERE customer_category IN ('individual', 'business')");

        // Step 3: Convert customer_type back to ENUM with old values
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('customer_type', ['individual', 'business'])->default('individual')->change();
        });
    }
};
