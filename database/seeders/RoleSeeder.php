<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed roles according to MMP Auto-Meister requirements
     * 
     * 1. Staff - Sales/Quotes/Returns only, no cost visibility
     * 2. Manager - Stock management, POs, GRNs, no cost visibility
     * 3. Owner - Full system access, costs, settings, reports
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'Owner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}