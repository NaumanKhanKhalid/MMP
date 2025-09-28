<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_fitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('make_id')->constrained('car_makes')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('car_models')->cascadeOnDelete();
            $table->foreignId('engine_id')->nullable()->constrained('engines')->cascadeOnDelete();
            $table->year('year_start');
            $table->year('year_end');
            $table->string('market')->nullable(); // e.g. Pakistan, Japan
            $table->timestamps();

            // Indexes for fast searching
            $table->index(['make_id', 'model_id', 'engine_id']);

            // Prevent duplicate fitments
            $table->unique(['product_id', 'make_id', 'model_id', 'engine_id', 'year_start', 'year_end', 'market'], 'product_fitments_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_fitments');
    }
};
