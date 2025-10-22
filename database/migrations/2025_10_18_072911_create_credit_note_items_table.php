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
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();
            
            // Link to Credit Note
            $table->foreignId('credit_note_id')->constrained('credit_notes')->onDelete('cascade');
            
            // Link to Original Invoice Item
            $table->foreignId('invoice_item_id')->nullable()->constrained('invoice_items')->onDelete('set null');
            
            // Product Details
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->text('product_description')->nullable();
            
            // Original Sale Details
            $table->integer('qty_sold')->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            
            // Return Details
            $table->integer('qty_returned')->default(0);
            $table->decimal('return_unit_price', 15, 2)->default(0);
            $table->decimal('return_discount', 15, 2)->default(0);
            $table->decimal('return_line_total', 15, 2)->default(0);
            
            // Stock Handling
            $table->enum('stock_handling', ['restock', 'write_off', 'no_stock'])->default('restock');
            $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->onDelete('set null');
            $table->boolean('restocked')->default(false);
            $table->timestamp('restocked_at')->nullable();
            
            // VAT
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('credit_note_id');
            $table->index('product_id');
            $table->index('invoice_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
    }
};
