<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestInvestmentTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:investment-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test investment totals calculation with active/inactive filter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in database');
            return;
        }

        $this->info("Testing investment totals for user: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->line('');

        // Show all investments
        $investments = $user->investments;
        $this->info('All Investments:');
        foreach ($investments as $investment) {
            $status = $investment->active ? 'ACTIVE' : 'INACTIVE';
            $this->line("- ID " . $investment->id . ": $" . $investment->amount . " (" . $status . ")");
        }
        $this->line('');

        // Show only active investments
        $activeInvestments = $user->investments()->active()->get();
        $this->info('Active Investments Only:');
        foreach ($activeInvestments as $investment) {
            $this->line("- ID " . $investment->id . ": $" . $investment->amount . " (ACTIVE)");
        }
        $this->line('');

        // Show totals
        $totalInvested = $user->totalInvestedAmount();
        $totalInterest = $user->totalInterestEarned();
        
        $this->info("Total Invested (Active Only): $" . $totalInvested);
        $this->info("Total Interest Earned (Active Only): $" . $totalInterest);
        
        // Manual calculation for verification
        $manualTotal = $user->investments()->active()->sum('amount');
        $this->line('');
        $this->info("Manual calculation (active investments sum): $" . $manualTotal);
        
        if ($totalInvested == $manualTotal) {
            $this->info('✅ Fix is working correctly - totals match!');
        } else {
            $this->error('❌ Fix not working - totals do not match');
        }
    }
}
