<?php

namespace App\Console\Commands;

use App\Models\InvestmentPackage;
use Illuminate\Console\Command;

class UpdatePackageSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:update-slots {slots=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update available slots for all investment packages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slots = (int) $this->argument('slots');
        
        $this->info("Updating all investment packages to have {$slots} available slots...");
        
        $packages = InvestmentPackage::all();
        
        if ($packages->isEmpty()) {
            $this->error('No investment packages found in the database.');
            return 1;
        }
        
        $this->info("Found {$packages->count()} package(s):");
        $this->newLine();
        
        // Show current state
        $this->table(
            ['ID', 'Package Name', 'Current Slots'],
            $packages->map(fn($p) => [
                $p->id,
                $p->name,
                $p->available_slots ?? 'Unlimited'
            ])
        );
        
        $this->newLine();
        
        // Update all packages
        $updated = InvestmentPackage::query()->update(['available_slots' => $slots]);
        
        $this->info("✓ Successfully updated {$updated} package(s) to {$slots} slots.");
        $this->newLine();
        
        // Show updated state
        $packages = InvestmentPackage::all();
        $this->info('Updated packages:');
        $this->table(
            ['ID', 'Package Name', 'New Slots'],
            $packages->map(fn($p) => [
                $p->id,
                $p->name,
                $p->available_slots ?? 'Unlimited'
            ])
        );
        
        return 0;
    }
}
