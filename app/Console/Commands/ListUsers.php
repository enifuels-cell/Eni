<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:list-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users with their investments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('investments')->get();
        
        $this->info('All Users:');
        foreach ($users as $user) {
            $this->line("- ID {$user->id}: {$user->name} ({$user->email})");
            $this->line("  Role: {$user->role}");
            $this->line("  Total Invested: $" . $user->totalInvestedAmount());
            $this->line("  Account Balance: $" . $user->account_balance);
            
            if ($user->investments->count() > 0) {
                $this->line("  Investments:");
                foreach ($user->investments as $investment) {
                    $status = $investment->active ? 'ACTIVE' : 'INACTIVE';
                    $this->line("    - ID {$investment->id}: $" . $investment->amount . " ({$status})");
                }
            } else {
                $this->line("  No investments");
            }
            $this->line('');
        }
    }
}
