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
        // Update the status enum to include 'accepted' and 'declined'
        DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft', 'sent', 'accepted', 'declined', 'converted', 'expired', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE quotes MODIFY COLUMN status ENUM('draft', 'sent', 'converted', 'expired', 'cancelled') DEFAULT 'draft'");
    }
};