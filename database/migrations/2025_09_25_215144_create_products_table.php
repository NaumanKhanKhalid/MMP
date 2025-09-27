<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();         // 0001
            $table->string('barcode')->unique();     // MMP-0001
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            // primary supplier (optional quick selection) — product can have many suppliers via pivot
            $table->foreignId('primary_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('supplier_code')->nullable();
            $table->string('unit')->default('PCS');
            $table->json('images')->nullable();          // array of up to 3 storage paths
            $table->string('bin_location')->nullable();  // A-16
            $table->integer('reorder_level')->default(0);

            // Pricing tiers
            $table->decimal('price_normal', 15, 2)->default(0);
            $table->decimal('price_online', 15, 2)->default(0);
            $table->decimal('price_workshop', 15, 2)->default(0);

            // Flags
            $table->boolean('allow_negative')->default(true);
            $table->boolean('special_order')->default(true);

            $table->text('notes')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');

            $table->timestamps();
        });

        // OE numbers
        Schema::create('product_oe_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('oe_number')->index();
            $table->timestamps();
        });

        // Cross refs
        Schema::create('product_cross_refs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('cross_ref')->index();
            $table->timestamps();
        });

        // product_supplier pivot (product can be bought from many suppliers)
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->decimal('purchase_price', 15, 4)->default(0);
            $table->string('currency', 10)->default('ZAR'); // change default to your currency
            $table->integer('lead_time')->nullable(); // days
            $table->string('supplier_sku')->nullable();
            $table->timestamps();

            $table->unique(['product_id','supplier_id']);
        });

        // stock batches (FIFO)
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('batch_code')->nullable(); // optional supplier batch/invoice ref
            $table->decimal('qty_received', 14, 4)->default(0);
            $table->decimal('qty_left', 14, 4)->default(0);
            $table->decimal('landed_unit_cost', 18, 4)->default(0);
            $table->date('received_date')->nullable();
            $table->foreignId('grn_id')->nullable()->constrained('goods_receipts')->nullOnDelete();
            $table->timestamps();
        });

        // stock ledger (audit trail)
        Schema::create('stock_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('document_type'); // GRN/PO/INVOICE/RETURN/ADJUSTMENT
            $table->unsignedBigInteger('document_id')->nullable();
            $table->decimal('qty', 14, 4);     // + for in, - for out
            $table->decimal('unit_cost', 18, 4)->nullable();
            $table->decimal('total_cost', 20, 4)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('product_suppliers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('stock_ledger');
        Schema::dropIfExists('stock_batches');
        Schema::dropIfExists('product_supplier');
        Schema::dropIfExists('product_cross_refs');
        Schema::dropIfExists('product_oe_numbers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_suppliers');
    }
};

