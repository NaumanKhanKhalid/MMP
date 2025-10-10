<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Engine Parts', 'slug' => 'engine-parts', 'status' => 'active'],
            ['name' => 'Body Parts', 'slug' => 'body-parts', 'status' => 'active'],
            ['name' => 'Electrical', 'slug' => 'electrical', 'status' => 'active'],
            ['name' => 'Brakes', 'slug' => 'brakes', 'status' => 'active'],
            ['name' => 'Suspension', 'slug' => 'suspension', 'status' => 'active'],
            ['name' => 'Filters', 'slug' => 'filters', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'status' => $category['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
