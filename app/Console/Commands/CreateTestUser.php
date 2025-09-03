<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for local development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if test user already exists
        $existingUser = User::where('email', 'test@eni.com')->first();
        
        if ($existingUser) {
            $this->info('Test user already exists:');
            $this->line('Email: test@eni.com');
            $this->line('Password: password123');
            return;
        }

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@eni.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->info('Test user created successfully!');
        $this->line('Email: test@eni.com');
        $this->line('Password: password123');
        $this->line('User ID: ' . $user->id);
    }
}
