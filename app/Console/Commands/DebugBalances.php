<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DebugBalances extends Command
{
    protected $signature = 'debug:balances';
    protected $description = 'Debug balance calculation differences';

    public function handle()
    {
        $user = User::where('email', 'test@eni.com')->first();
        
        if (!$user) {
            $this->error('User not found');
            return;
        }
        
        $this->info('Balance Debug for: ' . $user->email);
        $this->line('DB Field (account_balance): $' . number_format($user->account_balance, 2));
        $this->line('Method (accountBalance()): $' . number_format($user->accountBalance(), 2));
        
        // Check transaction totals
        $credits = $user->transactions()
            ->whereIn('type', ['deposit', 'interest', 'referral_bonus'])
            ->where('status', 'completed')
            ->sum('amount');
            
        $debits = $user->transactions()
            ->whereIn('type', ['withdrawal', 'transfer'])
            ->where('status', 'completed')
            ->sum('amount');
            
        $this->line('Credits from transactions: $' . number_format($credits, 2));
        $this->line('Debits from transactions: $' . number_format($debits, 2));
        $this->line('Net from transactions: $' . number_format($credits - $debits, 2));
    }
}
