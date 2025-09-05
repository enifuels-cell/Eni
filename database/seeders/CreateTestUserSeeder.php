<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => \Hash::make('password'),
                'email_verified_at' => now(),
                'account_balance' => 1000.00,
                'role' => 'user',
            ]
        );
        
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => \Hash::make('password'),
                'email_verified_at' => now(),
                'account_balance' => 0.00,
                'role' => 'admin',
            ]
        );
    }
}
