<?php

namespace App\Console\Commands;

use App\Models\InvestmentPackage;
use Illuminate\Console\Command;

class CleanupPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate investment packages and ensure correct configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up investment packages...');

        // First, delete all existing packages
        $deletedCount = InvestmentPackage::count();
        InvestmentPackage::truncate();
        $this->info("Deleted {$deletedCount} existing packages");

        // Create the correct packages
        $packages = [
            [
                'name' => 'Energy Saver',
                'min_amount' => 200.00,
                'max_amount' => 899.00,
                'daily_shares_rate' => 0.5, // 0.5% daily
                'effective_days' => 180, // 180 days maturity
                'available_slots' => 100,
                'referral_bonus_rate' => 5.0, // 5% referral bonus
                'active' => true,
                'image' => 'Energy.png',
            ],
            [
                'name' => 'Growth Power',
                'min_amount' => 900.00,
                'max_amount' => 6900.00,
                'daily_shares_rate' => 0.7, // 0.7% daily
                'effective_days' => 180, // 180 days maturity
                'available_slots' => 75,
                'referral_bonus_rate' => 7.0, // 7% referral bonus
                'active' => true,
                'image' => 'Growth.png',
            ],
            [
                'name' => 'Capital Prime',
                'min_amount' => 7000.00,
                'max_amount' => 1000000.00,
                'daily_shares_rate' => 0.9, // 0.9% daily
                'effective_days' => 180, // 180 days maturity
                'available_slots' => 50,
                'referral_bonus_rate' => 10.0, // 10% referral bonus
                'active' => true,
                'image' => 'Capital.png',
            ],
        ];

        foreach ($packages as $package) {
            $created = InvestmentPackage::create($package);
            $this->info("✓ Created: {$created->name} (ID: {$created->id})");
        }

        $this->info('Package cleanup completed successfully!');
        $this->newLine();

        // Show final state
        $this->info('Current packages:');
        InvestmentPackage::all()->each(function($p) {
            $this->info('  ' . $p->name . ': $' . $p->min_amount . ' - $' . $p->max_amount . ', ' . $p->daily_shares_rate . "% daily, " . $p->effective_days . ' days');
        });

        return 0;
    }
}
