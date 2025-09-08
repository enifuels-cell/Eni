<?php

namespace Database\Seeders;

use App\Models\InvestmentPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSetupSeeder extends Seeder
{
    /**
     * Run the database seeds - specifically for production environment.
     */
    public function run(): void
    {
        $this->command->info('Setting up production data...');
        
        // Ensure investment packages exist
        $this->seedInvestmentPackages();
        
        $this->command->info('Production setup completed successfully!');
    }
    
    private function seedInvestmentPackages(): void
    {
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
            $created = InvestmentPackage::updateOrCreate(
                ['name' => $package['name']], // Find by name
                $package // Update or create with this data
            );
            
            $this->command->info("✓ Package: {$created->name} (ID: {$created->id}) - Active: " . ($created->active ? 'Yes' : 'No'));
        }
        
        $totalPackages = InvestmentPackage::count();
        $activePackages = InvestmentPackage::where('active', true)->count();
        $availablePackages = InvestmentPackage::available()->count();
        
        $this->command->info("Summary: {$totalPackages} total, {$activePackages} active, {$availablePackages} available packages");
    }
}
