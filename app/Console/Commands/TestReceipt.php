<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use Illuminate\Console\Command;

class TestReceipt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:receipt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test investment receipt functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing investment receipt functionality...');
        
        // Get first user for testing
        $user = User::first();
        if (!$user) {
            $this->error('No users found. Please create a user first.');
            return 1;
        }
        
        // Get first package for testing
        $package = InvestmentPackage::first();
        if (!$package) {
            $this->error('No investment packages found.');
            return 1;
        }
        
        $this->info("Creating test transaction for user: {$user->name}");
        $this->info("Using package: {$package->name}");
        
        // Create a test transaction
        $transaction = $user->transactions()->create([
            'type' => 'deposit',
            'amount' => $package->min_amount,
            'reference' => 'Test investment transaction',
            'status' => 'pending',
            'description' => 'Investment in ' . $package->name . ' package',
        ]);
        
        // Create associated investment
        $investment = $user->investments()->create([
            'investment_package_id' => $package->id,
            'amount' => $package->min_amount,
            'daily_shares_rate' => $package->daily_shares_rate,
            'remaining_days' => $package->effective_days,
            'total_interest_earned' => 0,
            'active' => false,
            'started_at' => now(),
            'ended_at' => null,
        ]);
        
        $this->info("✓ Test transaction created with ID: {$transaction->id}");
        $this->info("✓ Test investment created with ID: {$investment->id}");
        $this->info("Receipt URL: " . route('user.investment.receipt', $transaction->id));
        $this->info("You can now test the receipt page in your browser!");
        
        return 0;
    }
}
