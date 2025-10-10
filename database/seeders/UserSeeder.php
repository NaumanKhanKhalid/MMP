<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed initial users according to MMP Auto-Meister requirements
     * 
     * Roles:
     * 1 = Owner (Full access)
     * 2 = Manager (Stock, POs, no costs)
     * 3 = Staff (Sales only, max 10% discount)
     */
    public function run()
    {
        // Owner 1: Razinah Deedat
        User::create([
            'name' => 'Razinah Deedat',
            'email' => 'razinah.deedat@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Change on first login
            'role_id' => 1, // Owner
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 1, // Force change on first login
            'two_factor_attempts' => 0,
            'status' => 'active',
            'max_discount_allowed' => 100, // Owner can give any discount
            'first_login' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Owner 2: Moe Suleman
        User::create([
            'name' => 'Moe Suleman',
            'email' => 'moesuleman9@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Change on first login
            'role_id' => 1, // Owner
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 1, // Force change on first login
            'two_factor_attempts' => 0,
            'status' => 'active',
            'max_discount_allowed' => 100, // Owner can give any discount
            'first_login' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Manager
        User::create([
            'name' => 'Manager',
            'email' => 'manager@mmpautomeister.co.za',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Change on first login
            'role_id' => 2, // Manager
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 1, // Force change on first login
            'two_factor_attempts' => 0,
            'status' => 'active',
            'max_discount_allowed' => 25, // Manager can give up to 25% discount
            'first_login' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Staff
        User::create([
            'name' => 'Sales Staff',
            'email' => 'sales@mmpautomeister.co.za',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Change on first login
            'role_id' => 3, // Staff
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 1, // Force change on first login
            'two_factor_attempts' => 0,
            'status' => 'active',
            'max_discount_allowed' => 10, // Staff max 10% discount
            'first_login' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
