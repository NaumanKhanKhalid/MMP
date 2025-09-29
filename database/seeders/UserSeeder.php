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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mmp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
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

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@mmp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
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

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@mmp.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
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
