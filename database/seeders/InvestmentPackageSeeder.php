<?php

namespace Database\Seeders;

use App\Models\InvestmentPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Energy Saver',
                'min_amount' => 200.00,
                'max_amount' => 899.99,
                'daily_shares_rate' => 0.5, // 0.5% daily
                'effective_days' => 180,
                'available_slots' => 100,
                'referral_bonus_rate' => 5.0, // 5% commission
                'active' => true,
                'image' => 'Energy.png',
            ],
            [
                'name' => 'Growth Power',
                'min_amount' => 900.00,
                'max_amount' => 6999.99,
                'daily_shares_rate' => 0.7, // 0.7% daily
                'effective_days' => 180,
                'available_slots' => 50,
                'referral_bonus_rate' => 5.0, // 5% commission (default)
                'active' => true,
                'image' => 'Growth.png',
            ],
            [
                'name' => 'Capital Prime',
                'min_amount' => 7000.00,
                'max_amount' => 50000.00,
                'daily_shares_rate' => 0.9, // 0.9% daily
                'effective_days' => 180,
                'available_slots' => 25,
                'referral_bonus_rate' => 5.0, // 5% commission (default)
                'active' => true,
                'image' => 'Capital.png',
            ],
        ];

        foreach ($packages as $package) {
            InvestmentPackage::create($package);
        }

        $this->command->info('Investment packages seeded successfully!');
    }
}
