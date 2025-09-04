<?php

namespace App\Console\Commands;

use App\Models\DailyInterestLog;
use App\Models\Investment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTotalInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interest:update {--dry-run : Show what would be processed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and distribute daily interest for active investments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $today = Carbon::today();
        
        $this->info("Processing daily interest for: {$today->toDateString()}");
        
        // Get all active investments
        $activeInvestments = Investment::active()
            ->where('remaining_days', '>', 0)
            ->with(['user', 'investmentPackage'])
            ->get();

        if ($activeInvestments->isEmpty()) {
            $this->info('No active investments found.');
            return 0;
        }

        $totalProcessed = 0;
        $totalInterest = 0;

        DB::transaction(function () use ($activeInvestments, $today, $isDryRun, &$totalProcessed, &$totalInterest) {
            foreach ($activeInvestments as $investment) {
                // Check if interest already processed for today
                $existingLog = DailyInterestLog::where('investment_id', $investment->id)
                    ->where('interest_date', $today)
                    ->exists();

                if ($existingLog) {
                    continue;
                }

                $dailyInterest = $investment->calculateDailyInterest();
                $totalInterest += $dailyInterest;
                $totalProcessed++;

                if (!$isDryRun) {
                    // Create daily interest log
                    DailyInterestLog::create([
                        'investment_id' => $investment->id,
                        'interest_amount' => $dailyInterest,
                        'interest_date' => $today,
                    ]);

                    // Update investment totals
                    $investment->update([
                        'total_interest_earned' => $investment->total_interest_earned + $dailyInterest,
                        'remaining_days' => $investment->remaining_days - 1,
                        'active' => $investment->remaining_days - 1 > 0,
                        'ended_at' => $investment->remaining_days - 1 <= 0 ? now() : null,
                    ]);

                    // Create transaction record
                    Transaction::create([
                        'user_id' => $investment->user_id,
                        'type' => 'interest',
                        'amount' => $dailyInterest,
                        'reference' => "Daily interest for investment #{$investment->id}",
                        'status' => 'completed',
                        'description' => "Daily interest payment - {$today->toDateString()}",
                        'processed_at' => now(),
                    ]);

                    // Update user's account balance
                    $investment->user->increment('account_balance', $dailyInterest);
                }

                $this->line("Investment #{$investment->id} - User: {$investment->user->name} - Interest: $" . number_format($dailyInterest, 2));
            }
        });

        $this->info("Summary:");
        $this->info("Total investments processed: {$totalProcessed}");
        $this->info("Total interest distributed: $" . number_format($totalInterest, 2));
        
        if ($isDryRun) {
            $this->warn("DRY RUN - No changes were made to the database.");
        } else {
            $this->info("Interest distribution completed successfully!");
        }

        return 0;
    }
}
