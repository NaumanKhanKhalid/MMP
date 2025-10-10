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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Product details (snapshot at time of sale)
            $table->string('product_sku');
            $table->string('product_name');
            $table->string('product_barcode')->nullable();
            
            // Pricing
            $table->decimal('unit_price', 15, 2);
            $table->decimal('quantity', 10, 3);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('line_total', 15, 2);
            
            // Cost tracking for profit calculation
            $table->decimal('unit_cost', 15, 2)->default(0); // FIFO cost
            $table->decimal('line_cost', 15, 2)->default(0); // quantity * unit_cost
            $table->decimal('line_profit', 15, 2)->default(0); // line_total - line_cost
            
            // Stock tracking
            $table->foreignId('stock_batch_id')->nullable()->constrained()->onDelete('set null');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['invoice_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};