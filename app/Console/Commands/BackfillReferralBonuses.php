<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillReferralBonuses extends Command
{
    protected $signature = 'referrals:backfill-bonuses';
    protected $description = 'Backfill referral bonuses for past investments that did not receive bonuses';

    public function handle()
    {
        $this->info('Starting referral bonus backfill...');

        // Find all active investments that don't have a referral bonus
        $investments = Investment::with(['user.referralReceived.referrer', 'investmentPackage'])
            ->active()
            ->get();

        $created = 0;
        $skipped = 0;

        foreach ($investments as $investment) {
            // Check if this investment already has a referral bonus
            $existingBonus = ReferralBonus::where('investment_id', $investment->id)->first();

            if ($existingBonus) {
                $skipped++;
                continue;
            }

            // Check if the investor was referred by someone
            $referral = $investment->user->referralReceived;

            if (!$referral) {
                $skipped++;
                continue;
            }

            // Calculate and create the bonus
            $package = $investment->investmentPackage;
            $investmentAmount = $investment->amount instanceof \App\Support\Money
                ? $investment->amount->toFloat()
                : (float) $investment->amount;

            $bonusAmount = $investmentAmount * ($package->referral_bonus_rate / 100);

            DB::transaction(function () use ($referral, $investment, $bonusAmount, $package) {
                // Create referral bonus record
                ReferralBonus::create([
                    'referral_id' => $referral->id,
                    'investment_id' => $investment->id,
                    'bonus_amount' => $bonusAmount,
                    'paid' => true,
                    'paid_at' => now()
                ]);

                // Credit referrer's account balance
                $referrer = $referral->referrer;
                if ($referrer) {
                    $referrer->increment('account_balance', $bonusAmount);

                    // Create transaction record
                    $referrer->transactions()->create([
                        'type' => 'referral_bonus',
                        'amount' => $bonusAmount,
                        'status' => 'completed',
                        'description' => 'Backfilled referral bonus from ' . $investment->user->name . ' - ' . $package->name . ' investment',
                        'reference' => 'BACKFILL-REF' . time() . rand(1000, 9999),
                        'processed_at' => now()
                    ]);
                }
            });

            $created++;
            $this->info("Created bonus for investment #{$investment->id} - Amount: $" . number_format($bonusAmount, 2));
        }

        $this->info("\nBackfill complete!");
        $this->info("Bonuses created: {$created}");
        $this->info("Investments skipped: {$skipped}");

        return 0;
    }
}
