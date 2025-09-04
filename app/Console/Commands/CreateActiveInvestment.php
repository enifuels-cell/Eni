<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;

class CreateActiveInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-active-investment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an active investment for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        $package = InvestmentPackage::first();
        
        if (!$user || !$package) {
            $this->error('Need at least one user and one investment package');
            return Command::FAILURE;
        }

        $investment = $user->investments()->create([
            'investment_package_id' => $package->id,
            'amount' => 1000,
            'daily_shares_rate' => $package->daily_shares_rate,
            'remaining_days' => $package->effective_days,
            'total_interest_earned' => 0,
            'active' => true,
            'started_at' => now(),
        ]);

        $this->info("✅ Active investment created!");
        $this->info("Investment ID: {$investment->id}");
        $this->info("User: {$user->name}");
        $this->info("Amount: \${$investment->amount}");
        $this->info("Status: " . ($investment->active ? 'Active' : 'Inactive'));
        
        $this->info("Now the dashboard should show \$1000 as Total Invested");

        return Command::SUCCESS;
    }
}
