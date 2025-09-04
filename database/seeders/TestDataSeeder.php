<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $testUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'account_balance' => 1500.00
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'account_balance' => 2000.00
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'account_balance' => 750.00
            ]
        ];

        foreach ($testUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                $user = User::create($userData);
                
                // Create some sample transactions for each user
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'amount' => 500.00,
                    'status' => 'pending',
                    'description' => 'Initial deposit'
                ]);
                
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'withdrawal',
                    'amount' => 150.00,
                    'status' => 'pending',
                    'description' => 'Withdrawal request'
                ]);

                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'transfer',
                    'amount' => 200.00,
                    'status' => 'pending',
                    'description' => 'Transfer fund request'
                ]);
                
                $this->command->info("Created test user: {$user->email}");
            }
        }

        $this->command->info('Test data seeding completed!');
    }
}
