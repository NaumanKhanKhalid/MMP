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
        Schema::table('credit_notes', function (Blueprint $table) {
            // Add new columns for return process
            $table->date('credit_note_date')->after('credit_note_number');
            $table->string('linked_invoice_number')->after('return_id');
            
            // Customer details
            $table->string('customer_name')->after('customer_id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            
            // Vehicle details
            $table->string('vehicle_make')->nullable()->after('customer_phone');
            $table->string('vehicle_model')->nullable()->after('vehicle_make');
            $table->string('vehicle_reg')->nullable()->after('vehicle_model');
            $table->string('vehicle_vin')->nullable()->after('vehicle_reg');
            $table->string('vehicle_mileage')->nullable()->after('vehicle_vin');
            
            // Additional financial fields
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount_amount');
            $table->decimal('shipping', 15, 2)->default(0)->after('discount_percentage');
            $table->decimal('grand_total', 15, 2)->default(0)->after('total_amount');
            
            // VAT settings
            $table->boolean('vat_enabled')->default(false)->after('grand_total');
            $table->decimal('vat_rate', 5, 2)->default(0)->after('vat_enabled');
            $table->boolean('vat_inclusive')->default(false)->after('vat_rate');
            
            // Rename tax_amount to vat_amount for consistency
            $table->renameColumn('tax_amount', 'vat_amount');
            
            // Return details
            $table->text('reason_for_return')->nullable()->after('vat_inclusive');
            $table->enum('refund_method', ['credit_note', 'bank_refund', 'cash_refund', 'card_refund'])->default('credit_note')->after('reason_for_return');
            $table->decimal('handling_fee', 15, 2)->default(0)->after('refund_method');
            
            // Customer type handling
            $table->enum('customer_type', ['cash', 'account'])->default('cash')->after('handling_fee');
            $table->boolean('apply_to_account')->default(false)->after('customer_type');
            
            // Additional tracking
            $table->string('reference')->nullable()->after('notes');
            $table->foreignId('posted_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->timestamp('posted_at')->nullable()->after('issued_at');
            
            // Add soft deletes
            $table->softDeletes();
            
            // Add indexes
            $table->index('credit_note_number');
            $table->index('invoice_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('credit_note_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->dropColumn([
                'credit_note_date',
                'linked_invoice_number',
                'customer_name',
                'customer_email',
                'customer_phone',
                'vehicle_make',
                'vehicle_model',
                'vehicle_reg',
                'vehicle_vin',
                'vehicle_mileage',
                'discount_percentage',
                'shipping',
                'grand_total',
                'vat_enabled',
                'vat_rate',
                'vat_inclusive',
                'reason_for_return',
                'refund_method',
                'handling_fee',
                'customer_type',
                'apply_to_account',
                'reference',
                'posted_by',
                'posted_at',
                'deleted_at'
            ]);
            
            $table->renameColumn('vat_amount', 'tax_amount');
        });
    }
};
