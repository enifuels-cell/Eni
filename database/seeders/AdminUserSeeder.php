<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $adminEmail = 'admin@eni.com';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'ENI Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: ' . $adminEmail);
            $this->command->info('Password: admin123');
        } else {
            $this->command->info('Admin user already exists.');
        }

        // Create test client user
        $clientEmail = 'user@test.com';
        
        if (!User::where('email', $clientEmail)->exists()) {
            User::create([
                'name' => 'Test Client',
                'email' => $clientEmail,
                'password' => Hash::make('password123'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Test client user created successfully!');
            $this->command->info('Email: ' . $clientEmail);
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Test client user already exists.');
        }
    }
}
