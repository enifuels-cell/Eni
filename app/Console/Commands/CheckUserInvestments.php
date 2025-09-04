<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserInvestments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:check-user {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check specific user investments and totals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->line("Account Balance: $" . $user->account_balance);
        $this->line("Total Invested (Active): $" . $user->totalInvestedAmount());
        $this->line("Total Interest Earned (Active): $" . $user->totalInterestEarned());
        $this->line('');

        $this->info('All Investments:');
        foreach ($user->investments as $investment) {
            $status = $investment->active ? 'ACTIVE' : 'INACTIVE';
            $this->line("- ID {$investment->id}: $" . $investment->amount . " ({$status})");
        }
    }
}
