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
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            
            // Job Card Number (WS10000+)
            $table->string('job_card_number', 50)->unique()->comment('Auto-generated: WS10000, WS10001...');
            
            // Customer Information
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name')->nullable()->comment('For walk-in customers');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            
            // Vehicle Information
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_vin')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('vehicle_mileage')->nullable();
            $table->string('engine_code')->nullable();
            $table->year('vehicle_year')->nullable();
            
            // Job Details
            $table->text('job_description')->nullable();
            $table->text('notes')->nullable();
            $table->text('customer_complaint')->nullable();
            
            // Status Management
            $table->enum('status', ['pending', 'booked', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Pricing
            $table->decimal('parts_total', 15, 2)->default(0);
            $table->decimal('labour_total', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            
            // Linked Invoice (when converted to final invoice)
            $table->foreignId('final_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};