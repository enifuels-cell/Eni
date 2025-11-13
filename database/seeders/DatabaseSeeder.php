<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 1. Regular Test User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => now(), // Mark as verified
                // Note: Assuming password is not required for regular users if you use PIN or social login
            ]
        );

        // 2. Admin User for Local Testing
        User::firstOrCreate(
            ['email' => 'admin@app.com'], // The unique key to find or create the user
            [
                'name' => 'Administrator',
                'email' => 'admin@app.com',
                'password' => Hash::make('password'), // **ADMIN PASSWORD**
                'role' => 'admin',                     // **Crucial for admin access**
                'email_verified_at' => now(),
            ]
        );

        $this->call([FaqSeeder::class]);
    }
}
