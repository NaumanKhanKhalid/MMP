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
        Schema::table('quotes', function (Blueprint $table) {
            // Change vehicle fields to store foreign key IDs instead of names
            $table->unsignedBigInteger('vehicle_make_id')->nullable()->after('customer_id');
            $table->unsignedBigInteger('vehicle_model_id')->nullable()->after('vehicle_make_id');
            $table->unsignedBigInteger('vehicle_engine_id')->nullable()->after('vehicle_model_id');
            
            // Add foreign key constraints
            $table->foreign('vehicle_make_id')->references('id')->on('car_makes')->onDelete('set null');
            $table->foreign('vehicle_model_id')->references('id')->on('car_models')->onDelete('set null');
            $table->foreign('vehicle_engine_id')->references('id')->on('engines')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['vehicle_make_id']);
            $table->dropForeign(['vehicle_model_id']);
            $table->dropForeign(['vehicle_engine_id']);
            
            // Drop the new columns
            $table->dropColumn(['vehicle_make_id', 'vehicle_model_id', 'vehicle_engine_id']);
        });
    }
};
