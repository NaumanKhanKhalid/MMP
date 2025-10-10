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
        // Customer Ledger (Debtors)
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // invoice, credit_note, payment
            $table->unsignedBigInteger('transaction_id')->nullable(); // ID of invoice/credit_note/payment
            $table->string('document_number')->nullable(); // Invoice #, Payment #, etc.
            $table->date('transaction_date');
            
            // Amounts (positive = debit/owe us, negative = credit/we owe them)
            $table->decimal('debit', 15, 2)->default(0); // Invoices
            $table->decimal('credit', 15, 2)->default(0); // Payments, Credit Notes
            $table->decimal('balance', 15, 2)->default(0); // Running balance
            
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
            
            $table->index(['customer_id', 'transaction_date']);
        });

        // Supplier Ledger (Creditors)
        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // supplier_invoice, payment, debit_note
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('document_number')->nullable();
            $table->date('transaction_date');
            
            // Amounts (positive = credit/we owe them, negative = debit/they owe us)
            $table->decimal('debit', 15, 2)->default(0); // Payments we made
            $table->decimal('credit', 15, 2)->default(0); // Supplier invoices
            $table->decimal('balance', 15, 2)->default(0); // Running balance
            
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->timestamps();
            
            $table->index(['supplier_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ledgers');
        Schema::dropIfExists('supplier_ledgers');
    }
};
