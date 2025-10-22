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
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('make_id')->nullable()->constrained('car_makes')->onDelete('set null');
            $table->foreignId('model_id')->nullable()->constrained('car_models')->onDelete('set null');
            $table->foreignId('engine_id')->nullable()->constrained('engines')->onDelete('set null');
            $table->string('registration_number')->nullable();
            $table->string('vin_number')->nullable();
            $table->string('year')->nullable();
            $table->string('color')->nullable();
            $table->string('mileage')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['customer_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_vehicles');
    }
};
