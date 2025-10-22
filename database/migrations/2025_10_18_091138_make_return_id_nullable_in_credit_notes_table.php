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
            // Drop the foreign key constraint first
            $table->dropForeign(['return_id']);
            
            // Make return_id nullable
            $table->unsignedBigInteger('return_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            // Make return_id not nullable
            $table->unsignedBigInteger('return_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade');
        });
    }
};
