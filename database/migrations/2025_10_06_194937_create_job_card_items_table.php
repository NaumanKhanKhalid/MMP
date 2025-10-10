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
        Schema::create('job_card_items', function (Blueprint $table) {
            $table->id();
            
            // Job Card Reference
            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            
            // Product Information
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_sku')->nullable();
            $table->string('product_name');
            $table->string('product_barcode')->nullable();
            
            // Quantity and Pricing
            $table->decimal('quantity_used', 10, 3)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            
            // Stock Management
            $table->boolean('reserved')->default(false)->comment('Is this item reserved from stock?');
            $table->boolean('consumed')->default(false)->comment('Has this item been consumed from stock?');
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index(['job_card_id', 'product_id']);
            $table->index(['reserved', 'consumed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_items');
    }
};