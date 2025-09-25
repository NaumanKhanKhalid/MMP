<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Owner', 'Manager', 'Staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Default Owner account
        User::firstOrCreate(
            ['email' => 'owner@mmp.com'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('password'),
                'role_id' => Role::where('name', 'Owner')->first()->id
            ]
        );
    }
}
