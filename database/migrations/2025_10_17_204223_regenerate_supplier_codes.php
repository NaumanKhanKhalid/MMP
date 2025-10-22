<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Regenerate all supplier codes sequentially
        $suppliers = DB::table('suppliers')->orderBy('id')->get();
        
        foreach ($suppliers as $index => $supplier) {
            $newCode = 'SUP-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            DB::table('suppliers')
                ->where('id', $supplier->id)
                ->update(['supplier_code' => $newCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed
    }
};
