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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Rename expected_date to expected_delivery_date if it exists
            if (Schema::hasColumn('purchase_orders', 'expected_date') && !Schema::hasColumn('purchase_orders', 'expected_delivery_date')) {
                $table->renameColumn('expected_date', 'expected_delivery_date');
            }
            
            // Rename total_amount to grand_total if it exists and grand_total doesn't
            if (Schema::hasColumn('purchase_orders', 'total_amount') && !Schema::hasColumn('purchase_orders', 'grand_total')) {
                $table->renameColumn('total_amount', 'grand_total');
            }
        });
        
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Add columns if they don't exist  
            if (!Schema::hasColumn('purchase_orders', 'received_date')) {
                $afterColumn = Schema::hasColumn('purchase_orders', 'expected_delivery_date') ? 'expected_delivery_date' : 'order_date';
                $table->date('received_date')->nullable()->after($afterColumn);
            }
            
            if (!Schema::hasColumn('purchase_orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('notes');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'payment_terms')) {
                $table->string('payment_terms')->nullable()->after('delivery_address');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('payment_terms');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'total_discount')) {
                $table->decimal('total_discount', 10, 2)->default(0)->after('subtotal');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'shipping')) {
                $table->decimal('shipping', 10, 2)->default(0)->after('total_discount');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'vat')) {
                $table->decimal('vat', 10, 2)->default(0)->after('shipping');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'vat_enabled')) {
                $table->boolean('vat_enabled')->default(false)->after('vat');
            }
            
            if (!Schema::hasColumn('purchase_orders', 'grand_total')) {
                $table->decimal('grand_total', 10, 2)->default(0)->after('vat_enabled');
            }
        });
        
        // Update purchase_order_items table
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Rename total_price to total if it exists
            if (Schema::hasColumn('purchase_order_items', 'total_price') && !Schema::hasColumn('purchase_order_items', 'total')) {
                $table->renameColumn('total_price', 'total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'received_date')) {
                $table->dropColumn('received_date');
            }
            if (Schema::hasColumn('purchase_orders', 'delivery_address')) {
                $table->dropColumn('delivery_address');
            }
            if (Schema::hasColumn('purchase_orders', 'payment_terms')) {
                $table->dropColumn('payment_terms');
            }
            if (Schema::hasColumn('purchase_orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('purchase_orders', 'total_discount')) {
                $table->dropColumn('total_discount');
            }
            if (Schema::hasColumn('purchase_orders', 'shipping')) {
                $table->dropColumn('shipping');
            }
            if (Schema::hasColumn('purchase_orders', 'vat')) {
                $table->dropColumn('vat');
            }
            if (Schema::hasColumn('purchase_orders', 'vat_enabled')) {
                $table->dropColumn('vat_enabled');
            }
        });
    }
};
