<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 200; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // use Hash!
                'role_id' => rand(1, 3), // assuming 1=admin, 2=staff, 3=manager
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
}
