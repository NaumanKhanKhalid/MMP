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
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->id();
            
            // Link to Goods Receipt
            $table->foreignId('goods_receipt_id')->constrained('goods_receipts')->onDelete('cascade');
            
            // Link to Purchase Order Item (if from PO)
            $table->foreignId('purchase_order_item_id')->nullable()->constrained('purchase_order_items')->onDelete('set null');
            
            // Product Details
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            
            // Quantities
            $table->integer('ordered_qty')->default(0);
            $table->integer('received_qty')->default(0);
            $table->integer('rejected_qty')->default(0);
            
            // Cost Details
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            
            // Batch Details (for FIFO)
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'received', 'rejected'])->default('pending');
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('goods_receipt_id');
            $table->index('product_id');
            $table->index('purchase_order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
