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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // MMP10000+
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name')->nullable(); // For cash sales
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            
            // Vehicle details (optional)
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_vin')->nullable();
            $table->string('vehicle_reg')->nullable();
            $table->string('vehicle_mileage')->nullable();
            
            // Invoice totals
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            
            // Payment details
            $table->enum('payment_status', ['draft', 'posted', 'paid', 'unpaid', 'partially_paid', 'cancelled'])->default('draft');
            $table->enum('payment_method', ['cash', 'card', 'eft', 'on_account'])->nullable();
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            
            // VAT settings
            $table->boolean('vat_enabled')->default(false);
            $table->decimal('vat_rate', 5, 2)->default(15.00);
            $table->boolean('vat_inclusive')->default(false);
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->string('reference')->nullable(); // Customer PO number
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created
            $table->foreignId('quote_id')->nullable()->constrained()->onDelete('set null'); // If converted from quote
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['invoice_number']);
            $table->index(['customer_id']);
            $table->index(['payment_status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};