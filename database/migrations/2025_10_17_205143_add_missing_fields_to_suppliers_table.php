<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Add supplier type
            $table->enum('supplier_type', ['company', 'individual'])->default('company')->after('supplier_code')->comment('Type of supplier');
            
            // Add payment terms
            $table->string('payment_terms')->default('30 days')->after('lead_time')->comment('Payment terms');
            
            // Add tax number
            $table->string('tax_number')->nullable()->after('payment_terms')->comment('Tax/VAT number');
            
            // Add bank details
            $table->text('bank_details')->nullable()->after('tax_number')->comment('Bank account details');
            
            // Add contact person
            $table->string('contact_person')->nullable()->after('bank_details')->comment('Primary contact person');
            
            // Add notes
            $table->text('notes')->nullable()->after('contact_person')->comment('Additional notes');
            
            // Add credit limit
            $table->decimal('credit_limit', 15, 2)->default(0)->after('supplier_type')->comment('Credit limit');
        });

        // Populate default values for existing records
        $suppliers = DB::table('suppliers')->get();
        foreach ($suppliers as $supplier) {
            DB::table('suppliers')
                ->where('id', $supplier->id)
                ->update([
                    'supplier_type' => 'company',
                    'payment_terms' => '30 days'
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'supplier_code',
                'supplier_type',
                'payment_terms',
                'tax_number',
                'bank_details',
                'contact_person',
                'notes',
                'credit_limit'
            ]);
        });
    }
};
