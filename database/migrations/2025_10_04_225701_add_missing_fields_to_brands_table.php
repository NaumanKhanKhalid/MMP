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
        Schema::table('brands', function (Blueprint $table) {
            // Add new fields
            $table->string('code')->nullable()->after('name');
            $table->string('banner')->nullable()->after('logo');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->integer('sort_order')->default(0)->after('is_featured');
            $table->string('website')->nullable()->after('description');
            $table->string('contact_email')->nullable()->after('website');
            $table->string('phone')->nullable()->after('contact_email');
            $table->string('country')->nullable()->after('phone');
            $table->text('address')->nullable()->after('country');
            $table->string('meta_title')->nullable()->after('address');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });

        // Update existing brands with proper timestamps if they are null
        DB::statement('UPDATE brands SET created_at = NOW() WHERE created_at IS NULL');
        DB::statement('UPDATE brands SET updated_at = NOW() WHERE updated_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'banner',
                'is_featured',
                'sort_order',
                'website',
                'contact_email',
                'phone',
                'country',
                'address',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }
};