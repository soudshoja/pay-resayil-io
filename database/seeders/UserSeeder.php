<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed test users.
     */
    public function run(): void
    {
        // Super Admin (no agency)
        User::create([
            'agency_id' => null,
            'username' => '+96500000000',
            'full_name' => 'Super Admin',
            'email' => '[email protected]',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);

        // City Tours - Admin
        User::create([
            'agency_id' => 1,
            'username' => '+96511111111',
            'full_name' => 'Ahmed Al-Rashid',
            'email' => '[email protected]',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'ar',
        ]);

        // City Tours - Accountant
        User::create([
            'agency_id' => 1,
            'username' => '+96522222222',
            'full_name' => 'Fatima Al-Said',
            'email' => '[email protected]',
            'password' => Hash::make('password123'),
            'role' => 'accountant',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'ar',
        ]);

        // City Tours - Agent 1
        User::create([
            'agency_id' => 1,
            'username' => '+96533333333',
            'full_name' => 'Mohammed Hassan',
            'email' => '[email protected]',
            'password' => Hash::make('password123'),
            'role' => 'agent',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);

        // City Tours - Agent 2
        User::create([
            'agency_id' => 1,
            'username' => '+96544444444',
            'full_name' => 'Sara Al-Mutawa',
            'email' => '[email protected]',
            'password' => Hash::make('password123'),
            'role' => 'agent',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'ar',
        ]);

        // Gulf Travel - Admin
        User::create([
            'agency_id' => 2,
            'username' => '+96555555555',
            'full_name' => 'Khalid Al-Fahad',
            'email' => '[email protected]',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone_verified_at' => now(),
            'is_active' => true,
            'preferred_locale' => 'en',
        ]);
    }
}
