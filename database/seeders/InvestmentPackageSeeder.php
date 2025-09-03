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
                'name' => 'Starter Package',
                'min_amount' => 100.00,
                'max_amount' => 999.99,
                'daily_shares_rate' => 1.5, // 1.5% daily
                'effective_days' => 30,
                'available_slots' => 100,
                'referral_bonus_rate' => 5.0, // 5% referral bonus
                'active' => true,
            ],
            [
                'name' => 'Professional Package',
                'min_amount' => 1000.00,
                'max_amount' => 4999.99,
                'daily_shares_rate' => 2.0, // 2% daily
                'effective_days' => 45,
                'available_slots' => 75,
                'referral_bonus_rate' => 7.5, // 7.5% referral bonus
                'active' => true,
            ],
            [
                'name' => 'Premium Package',
                'min_amount' => 5000.00,
                'max_amount' => 9999.99,
                'daily_shares_rate' => 2.5, // 2.5% daily
                'effective_days' => 60,
                'available_slots' => 50,
                'referral_bonus_rate' => 10.0, // 10% referral bonus
                'active' => true,
            ],
            [
                'name' => 'Elite Package',
                'min_amount' => 10000.00,
                'max_amount' => 24999.99,
                'daily_shares_rate' => 3.0, // 3% daily
                'effective_days' => 90,
                'available_slots' => 25,
                'referral_bonus_rate' => 12.5, // 12.5% referral bonus
                'active' => true,
            ],
            [
                'name' => 'VIP Package',
                'min_amount' => 25000.00,
                'max_amount' => 50000.00,
                'daily_shares_rate' => 3.5, // 3.5% daily
                'effective_days' => 120,
                'available_slots' => 10,
                'referral_bonus_rate' => 15.0, // 15% referral bonus
                'active' => true,
            ],
        ];

        foreach ($packages as $package) {
            InvestmentPackage::create($package);
        }

        $this->command->info('Investment packages seeded successfully!');
    }
}
