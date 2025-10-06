<?php

namespace App\Console\Commands;

use App\Models\InvestmentPackage;
use Illuminate\Console\Command;

class CheckPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check investment packages configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Current Investment Packages:');
        $this->info('===========================');

        $packages = InvestmentPackage::all();

        foreach ($packages as $package) {
            $this->info("Package: {$package->name}");
            $this->info('  Amount Range: $' . $package->min_amount . ' - $' . $package->max_amount);
            $this->info("  Daily Interest: {$package->daily_shares_rate}%");
            $this->info("  Maturity: {$package->effective_days} days");
            $this->info("  Active: " . ($package->active ? 'Yes' : 'No'));
            $this->info('---');
        }

        return 0;
    }
}
