<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Illuminate\Console\Command;

class ActivateInvestments extends Command
{
    protected $signature = 'investments:activate {--all : Activate all inactive investments}';
    protected $description = 'Activate investments for testing';

    public function handle()
    {
        if ($this->option('all')) {
            $investments = Investment::where('active', false)->get();
            
            if ($investments->isEmpty()) {
                $this->info('No inactive investments found.');
                return;
            }
            
            $this->info('Activating ' . $investments->count() . ' investments...');
            
            foreach ($investments as $investment) {
                $investment->update([
                    'active' => true,
                    'started_at' => now()
                ]);
                
                $this->line("✓ Activated investment ID {$investment->id} for {$investment->user->email}");
            }
            
            $this->info('All investments activated successfully!');
        } else {
            $this->error('Use --all flag to activate all investments');
        }
    }
}
