<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;

class CreateInactiveInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-inactive-investment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an inactive investment for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        $package = InvestmentPackage::first();
        
        if (!$user || !$package) {
            $this->error('User or package not found');
            return;
        }

        $investment = Investment::create([
            'user_id' => $user->id,
            'investment_package_id' => $package->id,
            'amount' => 500.00,
            'daily_shares_rate' => 1.50, // Required field
            'remaining_days' => 30, // Required field  
            'total_interest_earned' => 0.00, // Required field
            'active' => false, // Make this inactive
            'started_at' => now(),
        ]);

        $this->info("Created inactive investment with ID: " . $investment->id);
        $this->info("Amount: $" . $investment->amount);
        $this->info("Active: " . ($investment->active ? 'Yes' : 'No'));
    }
}
