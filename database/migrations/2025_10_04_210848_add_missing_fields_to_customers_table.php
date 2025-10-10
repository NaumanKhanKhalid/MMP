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
        Schema::table('customers', function (Blueprint $table) {
            // Unique customer identifier
            $table->string('customer_code')->nullable()->after('name')->comment('Unique customer code');
            
            // Tax information
            $table->string('tax_number')->nullable()->after('customer_code')->comment('VAT/Tax registration number');
            
            // Personal information
            $table->date('date_of_birth')->nullable()->after('tax_number')->comment('Date of birth for personal customers');
            
            // Business information
            $table->string('company_name')->nullable()->after('date_of_birth')->comment('Company name for business customers');
            
            // Contact person
            $table->string('contact_person')->nullable()->after('company_name')->comment('Primary contact person');
            
            // Customer type (individual/business)
            $table->enum('customer_type', ['individual', 'business'])->default('individual')->after('contact_person')->comment('Type of customer');
            
            // Additional fields
            $table->string('city')->nullable()->after('address')->comment('City');
            $table->string('postal_code')->nullable()->after('city')->comment('Postal code');
            $table->string('country')->default('South Africa')->after('postal_code')->comment('Country');
            
            // Customer status
            $table->enum('customer_status', ['active', 'inactive', 'suspended'])->default('active')->after('customer_type')->comment('Customer status');
            
            // Marketing preferences
            $table->boolean('marketing_consent')->default(false)->after('customer_status')->comment('Marketing communication consent');
            $table->boolean('sms_consent')->default(false)->after('marketing_consent')->comment('SMS communication consent');
        });

        // Populate customer_code for existing records
        $customers = \App\Models\Customer::all();
        foreach ($customers as $index => $customer) {
            $customer->customer_code = 'CUST-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $customer->save();
        }

        // Now make customer_code unique
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'customer_code',
                'tax_number',
                'date_of_birth',
                'company_name',
                'contact_person',
                'customer_type',
                'city',
                'postal_code',
                'country',
                'customer_status',
                'marketing_consent',
                'sms_consent'
            ]);
        });
    }
};
