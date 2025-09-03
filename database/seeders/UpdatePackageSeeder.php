<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvestmentPackage;

class UpdatePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update the packages to have names that match our images
        InvestmentPackage::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Energy Saver',
                'min_amount' => 200,
                'max_amount' => 4999,
                'daily_shares_rate' => 0.5,
                'effective_days' => 180, // 6 months
                'available_slots' => 1000,
                'referral_bonus_rate' => 5.0,
                'active' => true
            ]
        );

        InvestmentPackage::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Growth Power',
                'min_amount' => 900,
                'max_amount' => 24999,
                'daily_shares_rate' => 0.7,
                'effective_days' => 240, // 8 months
                'available_slots' => 500,
                'referral_bonus_rate' => 5.0,
                'active' => true
            ]
        );

        InvestmentPackage::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Capital Prime',
                'min_amount' => 7000,
                'max_amount' => 100000,
                'daily_shares_rate' => 0.9,
                'effective_days' => 365, // 12 months
                'available_slots' => 100,
                'referral_bonus_rate' => 5.0,
                'active' => true
            ]
        );
    }
}
