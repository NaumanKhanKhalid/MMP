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
        Schema::table('returns', function (Blueprint $table) {
            // Add stock handling type column after refund_method
            $table->enum('stock_handling_type', ['restock', 'writeoff', 'credit_only'])
                  ->default('restock')
                  ->after('refund_method')
                  ->comment('restock=return to inventory (FIFO batches), writeoff=damaged/defective, credit_only=no stock adjustment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn('stock_handling_type');
        });
    }
};
