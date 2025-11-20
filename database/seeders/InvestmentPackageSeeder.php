<?php

namespace Database\Seeders;

use App\Models\InvestmentPackage;
use Illuminate\Database\Seeder;

class InvestmentPackageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding investment packages...');

        $packages = [
            [
                'name' => 'Energy Saver',
                'min_amount' => 200.00,
                'max_amount' => 899.00,
                'daily_shares_rate' => 0.5,
                'effective_days' => 180,
                'available_slots' => 500,
                'referral_bonus_rate' => 5.0,
                'active' => true,
                'image' => 'Energy.png',
            ],
            [
                'name' => 'Growth Power',
                'min_amount' => 900.00,
                'max_amount' => 6900.00,
                'daily_shares_rate' => 0.7,
                'effective_days' => 180,
                'available_slots' => 500,
                'referral_bonus_rate' => 7.0,
                'active' => true,
                'image' => 'Growth.png',
            ],
            [
                'name' => 'Capital Prime',
                'min_amount' => 7000.00,
                'max_amount' => 1000000.00,
                'daily_shares_rate' => 0.9,
                'effective_days' => 180,
                'available_slots' => 500,
                'referral_bonus_rate' => 10.0,
                'active' => true,
                'image' => 'Capital.png',
            ],
        ];

        foreach ($packages as $package) {
            InvestmentPackage::updateOrInsert(
                ['name' => $package['name']],
                $package
            );
            $this->command->info("✓ Seeded: {$package['name']}");
        }

        $this->command->info('All investment packages seeded successfully!');
    }
}
