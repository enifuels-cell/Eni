<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ShowUserBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:show-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all user account balances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('role', '!=', 'admin')->get();
        
        $this->info('User Account Balances:');
        $this->line('');
        
        foreach ($users as $user) {
            $this->line("Email: {$user->email}");
            $this->line("Name: {$user->name}");
            $this->line("Account Balance: $" . number_format($user->account_balance, 2));
            $this->line("Total Invested: $" . number_format($user->totalInvestedAmount(), 2));
            $this->line('---');
        }
    }
}
