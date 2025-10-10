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
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->string('count_number')->unique(); // SC10000+
            $table->string('count_name'); // e.g., "Monthly Count - October 2025"
            $table->date('count_date');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'posted', 'cancelled'])->default('draft');
            
            // Filters applied
            $table->json('filters')->nullable(); // Store category, brand, location filters
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->string('bin_location')->nullable();
            
            // Summary
            $table->integer('total_products')->default(0);
            $table->integer('counted_products')->default(0);
            $table->integer('products_with_variance')->default(0);
            $table->decimal('total_variance_value', 15, 2)->default(0); // Cost impact
            
            // Audit
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created it
            $table->foreignId('posted_by')->nullable()->constrained('users')->onDelete('set null'); // Who posted it
            $table->timestamp('posted_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'count_date']);
        });

        Schema::create('stock_count_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_count_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Quantities
            $table->decimal('system_qty', 14, 4); // What system says
            $table->decimal('counted_qty', 14, 4)->nullable(); // What was counted
            $table->decimal('variance_qty', 14, 4)->default(0); // Difference
            
            // Cost impact
            $table->decimal('unit_cost', 18, 4)->default(0); // Current average cost
            $table->decimal('variance_value', 18, 4)->default(0); // variance_qty * unit_cost
            
            // Status
            $table->boolean('is_counted')->default(false);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['stock_count_id', 'product_id']);
        });

        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->unique(); // ADJ10000+
            $table->enum('adjustment_type', ['count', 'manual', 'damage', 'loss', 'found', 'correction'])->default('manual');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_count_id')->nullable()->constrained()->onDelete('set null'); // Link to count if from count
            
            $table->date('adjustment_date');
            $table->decimal('quantity_before', 14, 4);
            $table->decimal('adjustment_qty', 14, 4); // +ve = increase, -ve = decrease
            $table->decimal('quantity_after', 14, 4);
            
            $table->string('reason'); // Damage, Loss, Found, Count Variance, etc.
            $table->text('notes')->nullable();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['product_id', 'adjustment_date']);
            $table->index(['adjustment_type', 'adjustment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('stock_count_items');
        Schema::dropIfExists('stock_counts');
    }
};

