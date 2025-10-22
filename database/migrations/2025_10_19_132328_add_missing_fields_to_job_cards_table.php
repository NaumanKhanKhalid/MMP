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
        Schema::table('job_cards', function (Blueprint $table) {
            // Add assigned technician field
            $table->foreignId('assigned_technician_id')->nullable()->after('customer_email')->constrained('users')->nullOnDelete();
            
            // Add expected completion date
            $table->date('expected_completion_date')->nullable()->after('customer_complaint');
            
            // Add delivered status to enum
            $table->enum('status', ['pending', 'booked', 'in_progress', 'completed', 'delivered', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->dropForeign(['assigned_technician_id']);
            $table->dropColumn('assigned_technician_id');
            $table->dropColumn('expected_completion_date');
            
            // Revert status enum
            $table->enum('status', ['pending', 'booked', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }
};
