<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete(); // kis supplier se receive hua
            $table->unsignedBigInteger('purchase_order_id')->nullable();       // agar PO ke against receive hua

            // Basic fields
            $table->string('grn_number')->unique(); // e.g. GRN-0001
            $table->date('received_date');
            $table->string('invoice_number')->nullable(); // supplier invoice ref
            $table->decimal('total_amount', 18, 2)->default(0);

            // Status
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // kisne receive kiya
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
