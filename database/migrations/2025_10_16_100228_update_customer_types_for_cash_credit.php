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
        // Check if customer_type_new exists (from failed migration)
        $hasCustomerTypeNew = Schema::hasColumn('customers', 'customer_type_new');
        $hasCustomerType = Schema::hasColumn('customers', 'customer_type');
        $hasCustomerCategory = Schema::hasColumn('customers', 'customer_category');
        
        // Step 1: Add customer_category field if not exists
        if (!$hasCustomerCategory) {
            if ($hasCustomerType) {
                \DB::statement("ALTER TABLE customers ADD customer_category ENUM('individual', 'business') NOT NULL DEFAULT 'individual' COMMENT 'Customer category' AFTER customer_type");
                // Copy current customer_type to customer_category
                \DB::statement("UPDATE customers SET customer_category = customer_type WHERE customer_type IN ('individual', 'business')");
            } else {
                \DB::statement("ALTER TABLE customers ADD customer_category ENUM('individual', 'business') NOT NULL DEFAULT 'individual' COMMENT 'Customer category' AFTER customer_type_new");
            }
        }
        
        // Step 2: Handle customer_type column
        if ($hasCustomerTypeNew && !$hasCustomerType) {
            // Just rename customer_type_new to customer_type
            \DB::statement("ALTER TABLE customers CHANGE customer_type_new customer_type ENUM('cash', 'credit') NOT NULL DEFAULT 'cash' COMMENT 'Customer type: cash or credit'");
        } elseif ($hasCustomerType && !$hasCustomerTypeNew) {
            // Add temporary column, migrate data, drop old, rename new
            \DB::statement("ALTER TABLE customers ADD customer_type_new ENUM('cash', 'credit') NOT NULL DEFAULT 'cash' AFTER customer_type");
            \DB::statement("UPDATE customers SET customer_type_new = CASE WHEN terms = 'on_account' THEN 'credit' ELSE 'cash' END");
            \DB::statement("ALTER TABLE customers DROP COLUMN customer_type");
            \DB::statement("ALTER TABLE customers CHANGE customer_type_new customer_type ENUM('cash', 'credit') NOT NULL DEFAULT 'cash' COMMENT 'Customer type: cash or credit'");
        }
        
        // Step 3: Update terms field - first update values then change enum
        \DB::statement("UPDATE customers SET terms = 'credit' WHERE terms = 'on_account'");
        \DB::statement("ALTER TABLE customers MODIFY terms ENUM('cash', 'credit', 'mixed') NOT NULL DEFAULT 'cash' COMMENT 'Payment terms'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Revert customer_type enum
            $table->enum('customer_type', ['individual', 'business'])->default('individual')->change();
            
            // Remove customer_category field
            $table->dropColumn('customer_category');
            
            // Revert terms field
            $table->enum('terms', ['cash', 'on_account'])->default('cash')->change();
        });
    }
};