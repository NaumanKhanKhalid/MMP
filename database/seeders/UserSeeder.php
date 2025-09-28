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
     * Run the database seeds.
     */
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminpassword'),
            'role_id' => 1, // assuming 1 = admin
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 0,
            'two_factor_attempts' => 0,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('staffpassword'),
            'role_id' => 3, // assuming 2 = staff
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 0,
            'two_factor_attempts' => 0,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('managerpassword'),
            'role_id' => 3, // assuming 3 = manager
            'two_factor_enabled' => 0,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'force_password_change' => 0,
            'two_factor_attempts' => 0,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
