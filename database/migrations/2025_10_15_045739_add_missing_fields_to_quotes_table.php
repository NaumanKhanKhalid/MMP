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
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'vehicle_engine')) {
                $table->string('vehicle_engine')->nullable()->after('vehicle_model');
            }
            if (!Schema::hasColumn('quotes', 'vehicle_year')) {
                $table->integer('vehicle_year')->nullable()->after('vehicle_engine');
            }
            if (!Schema::hasColumn('quotes', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('quotes', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('notes');
            }
            if (!Schema::hasColumn('quotes', 'total_discount')) {
                $table->decimal('total_discount', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('quotes', 'shipping')) {
                $table->decimal('shipping', 12, 2)->default(0)->after('total_discount');
            }
            if (!Schema::hasColumn('quotes', 'vat')) {
                $table->decimal('vat', 12, 2)->default(0)->after('shipping');
            }
            if (!Schema::hasColumn('quotes', 'grand_total')) {
                $table->decimal('grand_total', 12, 2)->default(0)->after('vat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_engine',
                'vehicle_year',
                'user_id',
                'subtotal',
                'total_discount',
                'shipping',
                'vat',
                'grand_total'
            ]);
        });
    }
};
