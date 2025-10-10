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
        Schema::create('job_card_labour', function (Blueprint $table) {
            $table->id();
            
            // Job Card Reference
            $table->foreignId('job_card_id')->constrained()->onDelete('cascade');
            
            // Labour Details
            $table->string('labour_description');
            $table->text('detailed_description')->nullable();
            
            // Time and Rate
            $table->decimal('hours_worked', 8, 2)->default(0);
            $table->decimal('hourly_rate', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            
            // Labour Type
            $table->enum('labour_type', ['diagnostic', 'repair', 'maintenance', 'installation', 'other'])->default('repair');
            
            // Technician
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('technician_name')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes
            $table->index(['job_card_id', 'status']);
            $table->index(['technician_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_labour');
    }
};