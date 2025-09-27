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
    Schema::create('model_engines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_model_id')->constrained('car_models')->cascadeOnDelete();
        $table->foreignId('engine_id')->constrained('engines')->cascadeOnDelete();
        $table->string('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_engines');
    }
};
