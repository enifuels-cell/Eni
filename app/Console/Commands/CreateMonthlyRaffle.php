<?php

namespace App\Console\Commands;

use App\Models\MonthlyRaffle;
use Illuminate\Console\Command;

class CreateMonthlyRaffle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raffle:create-monthly {--month= : Specific month to create raffle for} {--year= : Specific year to create raffle for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create monthly raffle for current month or specified month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?? now()->month;
        $year = $this->option('year') ?? now()->year;

        $this->info("Creating raffle for {$year}-{$month}");

        // Check if raffle already exists
        $existing = MonthlyRaffle::where('raffle_year', $year)
            ->where('raffle_month', $month)
            ->first();

        if ($existing) {
            $this->warn("Raffle already exists for {$year}-{$month}");
            return Command::SUCCESS;
        }

        // Create the raffle
        $raffle = MonthlyRaffle::create([
            'title' => 'Monthly iPhone Air Raffle - ' . now()->setYear($year)->setMonth($month)->format('F Y'),
            'description' => 'Win a brand new iPhone Air! Login daily to earn raffle tickets and increase your chances!',
            'raffle_year' => $year,
            'raffle_month' => $month,
            'status' => 'active',
        ]);

        $this->info("✅ Monthly raffle created successfully!");
        $this->info("📅 Raffle: {$raffle->title}");
        $this->info("🎯 Status: {$raffle->status}");

        return Command::SUCCESS;
    }
}
