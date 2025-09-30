<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Identification
            $table->string('sku', 50)->unique()->comment('Auto-generated: 0001, 0002...');
            $table->string('barcode_primary', 100)->unique()->comment('Auto-generated: MMP-0001');
            $table->string('barcode_alternate', 100)->nullable()->comment('Manual entry');

            // Basic Info
            $table->string('name');
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('description')->nullable();

            // Unit & Location
            $table->enum('unit', ['PCS', 'SET'])->default('PCS');
            $table->string('bin_location', 50)->nullable()->comment('Format: A-16');

            // Pricing (3 tiers)
            $table->decimal('price_normal', 15, 2)->default(0);
            $table->decimal('price_online', 15, 2)->default(0);
            $table->decimal('price_workshop', 15, 2)->default(0);

            // Stock Management
            $table->integer('reorder_level')->default(0);
            $table->boolean('allow_negative')->default(false);
            $table->boolean('special_order')->default(false);

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Notes
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('sku');
            $table->index('barcode_primary');
            $table->index('barcode_alternate');
            $table->index('name');
            $table->index('status');
        });


        // OE numbers
        Schema::create('product_oe_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('oe_number')->index();
            $table->timestamps();
        });

        // Cross references
        Schema::create('product_cross_refs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('cross_ref')->index();
            $table->timestamps();
        });

        Schema::create('product_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->decimal('purchase_price', 15, 4)->default(0);
            $table->string('currency', 10)->default('PKR');
            $table->integer('lead_time')->nullable(); // days
            $table->string('supplier_sku')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'supplier_id']);
        });

        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('batch_code')->nullable(); // supplier invoice/batch ref
            $table->decimal('qty_received', 14, 4)->default(0);
            $table->decimal('qty_left', 14, 4)->default(0);
            $table->decimal('landed_unit_cost', 18, 4)->default(0);
            $table->date('received_date')->nullable();
            $table->foreignId('grn_id')->nullable()->constrained('goods_receipts')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('document_type'); // GRN / PO / INVOICE / RETURN / ADJUSTMENT
            $table->unsignedBigInteger('document_id')->nullable();
            $table->decimal('qty', 14, 4); // + in, - out
            $table->decimal('unit_cost', 18, 4)->nullable();
            $table->decimal('total_cost', 20, 4)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('stock_ledger');
        Schema::dropIfExists('product_suppliers');
        Schema::dropIfExists('product_cross_refs');
        Schema::dropIfExists('product_oe_numbers');
    }
};
