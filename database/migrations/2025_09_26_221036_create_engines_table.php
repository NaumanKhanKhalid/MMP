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
    Schema::create('engines', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // 1NZ-FE, R18A
        $table->string('displacement')->nullable(); // 1500cc
        $table->string('fuel_type')->nullable(); // Petrol, Diesel, Hybrid
        $table->integer('cylinder')->nullable(); // 4
        $table->string('power')->nullable(); // 120hp
            $table->enum('status', ['active', 'inactive'])->default('active');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engines');
    }
};
