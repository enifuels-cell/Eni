<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Investment;

class ActivateInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:activate-investment {investment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate a specific investment by ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $investmentId = $this->argument('investment_id');
        $investment = Investment::find($investmentId);
        
        if (!$investment) {
            $this->error("Investment with ID {$investmentId} not found");
            return;
        }

        $this->info("Investment before activation:");
        $this->line("- ID {$investment->id}: $" . $investment->amount . " (" . ($investment->active ? 'ACTIVE' : 'INACTIVE') . ")");
        $this->line("- User: " . $investment->user->name . " (" . $investment->user->email . ")");
        
        $investment->update([
            'active' => true,
            'started_at' => now()
        ]);

        $this->info("Investment activated successfully!");
        $this->line("- ID {$investment->id}: $" . $investment->amount . " (ACTIVE)");
    }
}
