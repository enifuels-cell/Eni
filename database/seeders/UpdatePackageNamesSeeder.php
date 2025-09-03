<?php

namespace Database\Seeders;

use App\Models\InvestmentPackage;
use Illuminate\Database\Seeder;

class UpdatePackageNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing packages to match our image names
        $packages = [
            [
                'old_name' => 'Starter Package',
                'new_name' => 'Capital Package',
                'min_amount' => 200.00,
                'max_amount' => 5000.00,
                'daily_shares_rate' => 0.5,
                'effective_days' => 180, // 6 months
            ],
            [
                'old_name' => 'Professional Package',
                'new_name' => 'Energy Package',
                'min_amount' => 900.00,
                'max_amount' => 15000.00,
                'daily_shares_rate' => 0.7,
                'effective_days' => 240, // 8 months
            ],
            [
                'old_name' => 'Premium Package',
                'new_name' => 'Growth Package',
                'min_amount' => 7000.00,
                'max_amount' => 50000.00,
                'daily_shares_rate' => 0.9,
                'effective_days' => 365, // 12 months
            ],
        ];

        foreach ($packages as $packageData) {
            $package = InvestmentPackage::where('name', $packageData['old_name'])->first();
            if ($package) {
                $package->update([
                    'name' => $packageData['new_name'],
                    'min_amount' => $packageData['min_amount'],
                    'max_amount' => $packageData['max_amount'],
                    'daily_shares_rate' => $packageData['daily_shares_rate'],
                    'effective_days' => $packageData['effective_days'],
                ]);
                $this->command->info("Updated {$packageData['old_name']} to {$packageData['new_name']}");
            }
        }

        // Deactivate other packages
        InvestmentPackage::whereNotIn('name', ['Capital Package', 'Energy Package', 'Growth Package'])
            ->update(['active' => false]);

        $this->command->info('Package names updated successfully!');
    }
}
