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
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            
            // Link to invoice or supplier invoice
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('supplier_invoice_id')->nullable();
            
            // Allocation details
            $table->decimal('allocated_amount', 15, 2);
            $table->date('allocation_date');
            
            $table->timestamps();
            
            // Ensure payment is allocated to either invoice or supplier invoice, not both
            $table->index(['payment_id', 'invoice_id']);
            $table->index(['payment_id', 'supplier_invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
