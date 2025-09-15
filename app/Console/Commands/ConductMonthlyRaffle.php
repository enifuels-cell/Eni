<?php

namespace App\Console\Commands;

use App\Models\MonthlyRaffle;
use Illuminate\Console\Command;

class ConductMonthlyRaffle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raffle:conduct-monthly {--month= : Specific month to conduct raffle for} {--year= : Specific year to conduct raffle for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conduct monthly raffle draw for the previous month or specified month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?? now()->subMonth()->month;
        $year = $this->option('year') ?? now()->subMonth()->year;

        $this->info("Conducting raffle for {$year}-{$month}");

        // Find the raffle for the specified month
        $raffle = MonthlyRaffle::where('raffle_year', $year)
            ->where('raffle_month', $month)
            ->where('status', 'active')
            ->first();

        if (!$raffle) {
            $this->warn("No active raffle found for {$year}-{$month}");
            return Command::FAILURE;
        }

        $this->info("Found raffle: {$raffle->title}");

        try {
            $winner = $raffle->conductDraw();

            $this->info("✅ Raffle conducted successfully!");
            $this->info("🏆 Winner: {$winner->name} ({$winner->email})");
            $this->info("🎫 Winner's tickets: {$winner->total_tickets}");

            // Log the results
            $this->newLine();
            $this->info("Raffle Details:");
            $this->info("- Total participants: " . $raffle->getEligibleUsers()->count());
            $this->info("- Total tickets distributed: " . $raffle->getEligibleUsers()->sum('total_tickets'));
            $this->info("- Draw method: Weighted random based on tickets");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Failed to conduct raffle: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
