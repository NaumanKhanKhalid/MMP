<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Filters', 'parent_id' => 1],
            ['name' => 'Gaskets', 'parent_id' => 1],
            ['name' => 'Headlights', 'parent_id' => 3],
        ]);
    }
}
