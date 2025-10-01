<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->boolean('locked')->default(false)->after('status');
            $table->string('cancellation_reason')->nullable()->after('notes');
        });
    }
    public function down() {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['locked', 'cancellation_reason']);
        });
    }
};
