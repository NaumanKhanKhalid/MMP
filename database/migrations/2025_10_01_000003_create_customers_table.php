<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_vin')->nullable();
            $table->string('vehicle_reg')->nullable();
            $table->string('vehicle_mileage')->nullable();
            $table->enum('terms', ['cash', 'on_account'])->default('cash');
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->enum('price_tier', ['normal', 'online', 'workshop'])->default('normal');
            $table->enum('statement_delivery', ['email', 'whatsapp', 'pdf'])->default('email');
            $table->decimal('balance', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
