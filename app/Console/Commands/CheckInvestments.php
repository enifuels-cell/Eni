<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Illuminate\Console\Command;

class CheckInvestments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investments:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of all investments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Investment Status Report');
        $this->info('========================');
        
        $investments = Investment::with(['user', 'investmentPackage'])->get();
        
        if ($investments->isEmpty()) {
            $this->info('No investments found.');
            return;
        }
        
        $this->table(
            ['ID', 'User', 'Package', 'Amount', 'Rate', 'Active', 'Days Left', 'Started'],
            $investments->map(function ($investment) {
                return [
                    $investment->id,
                    $investment->user->email ?? 'N/A',
                    $investment->investmentPackage->name ?? 'N/A',
                    '$' . number_format($investment->amount, 2),
                    $investment->daily_shares_rate . '%',
                    $investment->active ? 'Yes' : 'No',
                    $investment->remaining_days,
                    $investment->started_at ? $investment->started_at->format('Y-m-d H:i') : 'Not started'
                ];
            })
        );
        
        $this->info('Summary:');
        $this->info('Total investments: ' . $investments->count());
        $this->info('Active investments: ' . $investments->where('active', true)->count());
        $this->info('Investments with days > 0: ' . $investments->where('remaining_days', '>', 0)->count());
    }
}
