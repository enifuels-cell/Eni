<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InvestmentPackage;

class ListPackages extends Command
{
    protected $signature = 'list:packages';
    protected $description = 'List available investment packages';

    public function handle()
    {
        $packages = InvestmentPackage::active()->get();
        
        $this->info('Available Investment Packages:');
        $this->line('');
        
        foreach ($packages as $package) {
            $this->line("ID: {$package->id}");
            $this->line("Name: {$package->name}");
            $this->line("Range: $" . number_format($package->min_amount) . " - $" . number_format($package->max_amount));
            $this->line("Daily Rate: {$package->daily_shares_rate}%");
            $this->line("Duration: {$package->effective_days} days");
            $this->line("---");
        }
    }
}
