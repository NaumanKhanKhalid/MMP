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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('max_discount_allowed', 5, 2)->default(10.00)->after('status');
            $table->boolean('first_login')->default(true)->after('force_password_change');
            $table->timestamp('last_login_at')->nullable()->after('first_login');
            $table->integer('login_attempts')->default(0)->after('last_login_at');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
            $table->string('phone')->nullable()->after('email');
            $table->text('notes')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'max_discount_allowed',
                'first_login',
                'last_login_at',
                'login_attempts',
                'locked_until',
                'phone',
                'notes',
            ]);
        });
    }
};
