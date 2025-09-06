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
                'name' => 'Capital Package',
                'min_amount' => 7000.00,
                'max_amount' => 50000.00,
                'daily_shares_rate' => 0.9, // 0.9% daily
                'effective_days' => 365, // 12 months
                'available_slots' => 50,
                'referral_bonus_rate' => 5.0, // 5% referral bonus
                'active' => true,
                'image' => 'Capital.png',
            ],
            [
                'name' => 'Energy Package',
                'min_amount' => 5000.00,
                'max_amount' => 30000.00,
                'daily_shares_rate' => 1.2, // 1.2% daily
                'effective_days' => 270, // 9 months
                'available_slots' => 75,
                'referral_bonus_rate' => 7.0, // 7% referral bonus
                'active' => true,
                'image' => 'Energy.png',
            ],
            [
                'name' => 'Growth Package',
                'min_amount' => 3000.00,
                'max_amount' => 20000.00,
                'daily_shares_rate' => 1.5, // 1.5% daily
                'effective_days' => 180, // 6 months
                'available_slots' => 100,
                'referral_bonus_rate' => 10.0, // 10% referral bonus
                'active' => true,
                'image' => 'Growth.png',
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
