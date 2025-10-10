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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY10000+
            $table->enum('payment_type', ['customer', 'supplier']); // Customer payment (debtor) or Supplier payment (creditor)
            
            // Payer information
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            
            // Payment details
            $table->enum('payment_method', ['cash', 'card', 'eft'])->default('cash');
            $table->string('reference')->nullable(); // Cheque number, EFT reference, etc.
            $table->date('payment_date');
            
            // Amounts
            $table->decimal('gross_amount', 15, 2); // Amount before fees
            $table->decimal('fee_amount', 15, 2)->default(0); // Bank/card fees
            $table->decimal('net_amount', 15, 2); // Amount after fees
            $table->decimal('allocated_amount', 15, 2)->default(0); // Amount allocated to invoices
            $table->decimal('unallocated_amount', 15, 2)->default(0); // Remaining balance
            
            // Notes and audit
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['draft', 'posted', 'voided'])->default('posted');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
