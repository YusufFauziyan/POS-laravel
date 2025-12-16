<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pos.test',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        // Create Cashier user
        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@pos.test',
            'password' => Hash::make('password'),
            'role' => UserRole::CASHIER,
            'is_active' => true,
        ]);

        // Create Owner user
        User::create([
            'name' => 'Owner User',
            'email' => 'owner@pos.test',
            'password' => Hash::make('password'),
            'role' => UserRole::OWNER,
            'is_active' => true,
        ]);
    }
}
