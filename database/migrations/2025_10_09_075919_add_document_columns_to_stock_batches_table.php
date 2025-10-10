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
        Schema::table('stock_batches', function (Blueprint $table) {
            // Add document tracking columns
            $table->string('document_type')->nullable()->after('received_date')
                  ->comment('Type of document: grn, return, adjustment, etc.');
            $table->unsignedBigInteger('document_id')->nullable()->after('document_type')
                  ->comment('ID of the source document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_batches', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'document_id']);
        });
    }
};
