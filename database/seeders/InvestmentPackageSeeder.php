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
                'name' => 'Energy Package',
                'min_amount' => 200.00,
                'max_amount' => 899.00,
                'daily_shares_rate' => 0.5, // 0.5% daily
                'effective_days' => 270, // 9 months
                'available_slots' => 100,
                'referral_bonus_rate' => 5.0, // 5% referral bonus
                'active' => true,
                'image' => 'Energy.png',
            ],
            [
                'name' => 'Growth Package',
                'min_amount' => 900.00,
                'max_amount' => 6999.00,
                'daily_shares_rate' => 0.7, // 0.7% daily
                'effective_days' => 180, // 6 months
                'available_slots' => 75,
                'referral_bonus_rate' => 7.0, // 7% referral bonus
                'active' => true,
                'image' => 'Growth.png',
            ],
            [
                'name' => 'Capital Package',
                'min_amount' => 7000.00,
                'max_amount' => 1000000.00,
                'daily_shares_rate' => 0.9, // 0.9% daily
                'effective_days' => 365, // 12 months
                'available_slots' => 50,
                'referral_bonus_rate' => 10.0, // 10% referral bonus
                'active' => true,
                'image' => 'Capital.png',
            ],
        ];

        foreach ($packages as $package) {
            InvestmentPackage::updateOrCreate(
                ['name' => $package['name']], // Find by name
                $package // Update or create with this data
            );
        }

        $this->command->info('Investment packages seeded successfully!');
    }
}
