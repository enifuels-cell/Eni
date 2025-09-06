<?php

namespace Database\Seeders;

use App\Models\InvestmentPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update available slots for each package
        $packageSlots = [
            'Capital Package' => 50,
            'Energy Package' => 75,
            'Growth Package' => 100,
        ];

        foreach ($packageSlots as $packageName => $slots) {
            InvestmentPackage::where('name', $packageName)->update([
                'available_slots' => $slots
            ]);
            
            $this->command->info("Updated {$packageName} with {$slots} available slots.");
        }

        $this->command->info('Package slots updated successfully!');
    }
}
