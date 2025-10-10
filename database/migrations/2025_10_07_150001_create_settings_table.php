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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting key (e.g., 'company_name', 'vat_enabled')
            $table->text('value')->nullable(); // Setting value (stored as JSON for complex values)
            $table->string('type')->default('string'); // string, boolean, integer, decimal, json, image
            $table->string('group')->nullable(); // company, vat, pos, fees, numbering, email, security
            $table->string('label')->nullable(); // Human-readable label
            $table->text('description')->nullable(); // Description for admins
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

