<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            ['name' => 'Oil Filters', 'slug' => 'oil-filters', 'parent_id' => 1, 'status' => 'active'],
            ['name' => 'Air Filters', 'slug' => 'air-filters', 'parent_id' => 1, 'status' => 'active'],
            ['name' => 'Fuel Filters', 'slug' => 'fuel-filters', 'parent_id' => 1, 'status' => 'active'],
            ['name' => 'Bumpers', 'slug' => 'bumpers', 'parent_id' => 2, 'status' => 'active'],
            ['name' => 'Doors', 'slug' => 'doors', 'parent_id' => 2, 'status' => 'active'],
            ['name' => 'Headlights', 'slug' => 'headlights', 'parent_id' => 3, 'status' => 'active'],
            ['name' => 'Tail Lights', 'slug' => 'tail-lights', 'parent_id' => 3, 'status' => 'active'],
            ['name' => 'Brake Pads', 'slug' => 'brake-pads', 'parent_id' => 4, 'status' => 'active'],
            ['name' => 'Brake Discs', 'slug' => 'brake-discs', 'parent_id' => 4, 'status' => 'active'],
        ];

        foreach ($subcategories as $subcategory) {
            DB::table('categories')->insert([
                'name' => $subcategory['name'],
                'slug' => $subcategory['slug'],
                'parent_id' => $subcategory['parent_id'],
                'status' => $subcategory['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
