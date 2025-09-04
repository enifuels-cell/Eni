<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class CreateTestDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test deposit for admin testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test deposit...');

        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'testuser@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create a test deposit transaction
        $deposit = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 500.00,
            'status' => 'pending',
            'reference' => 'TEST-' . time(),
            'description' => 'Test bank transfer deposit',
            'receipt_path' => 'receipts/test-receipt.jpg', // This would be a real file path
        ]);

        $this->info("✅ Test deposit created successfully!");
        $this->info("Deposit ID: {$deposit->id}");
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Amount: \${$deposit->amount}");
        $this->info("Reference: {$deposit->reference}");
        $this->info("Status: {$deposit->status}");
        $this->info("");
        $this->info("You can now test the admin pending deposits page at:");
        $this->info("http://127.0.0.1:8000/admin/pending-deposits");

        return Command::SUCCESS;
    }
}
