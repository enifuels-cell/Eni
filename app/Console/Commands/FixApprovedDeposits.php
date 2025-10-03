<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Investment;
use Carbon\Carbon;

class FixApprovedDeposits extends Command
{
    protected $signature = 'deposits:fix-approved';
    protected $description = 'Fix approved deposits that did not activate their corresponding investments';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC: Checking all approved deposits ===');

        // Get ALL approved deposit transactions
        $allApprovedDeposits = Transaction::where('type', 'deposit')
            ->where('status', 'approved')
            ->with('user')
            ->get();

        $this->info("Found {$allApprovedDeposits->count()} total approved deposits\n");

        foreach ($allApprovedDeposits as $txn) {
            $this->info("Transaction ID: {$txn->id}");
            $this->info("  User: {$txn->user->name} (ID: {$txn->user_id})");
            $this->info("  Amount: \${$txn->amount}");
            $this->info("  Description: {$txn->description}");
            $this->info("  Payment Method: {$txn->payment_method}");
            $this->info("  Created: {$txn->created_at}");
            $this->info("");
        }

        // Now check investments for each user
        $this->info("\n=== CHECKING INVESTMENTS ===\n");

        $userIds = $allApprovedDeposits->pluck('user_id')->unique();
        $users = \App\Models\User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $this->info("User: {$user->name} (ID: {$user->id})");

            $investments = $user->investments()->with('investmentPackage')->get();
            $this->info("  Total investments: {$investments->count()}");

            foreach ($investments as $inv) {
                $this->info("    - Investment ID: {$inv->id}");
                $this->info("      Package: {$inv->investmentPackage->name}");
                $this->info("      Amount: \${$inv->amount}");
                $this->info("      Active: " . ($inv->active ? 'YES' : 'NO'));
                $this->info("      Created: {$inv->created_at}");
            }

            $this->info("  User Stats:");
            $this->info("    Total Invested: \${$user->totalInvestedAmount()}");
            $this->info("    Account Balance: \${$user->accountBalance()}");
            $this->info("    Active Investments: " . $user->investments()->where('active', true)->count());
            $this->info("");
        }

        // Now try to fix inactive investments
        $this->info("\n=== ATTEMPTING TO FIX INACTIVE INVESTMENTS ===\n");
        $fixed = 0;

        $approvedDeposits = Transaction::where('type', 'deposit')
            ->where('status', 'approved')
            ->get();

        foreach ($approvedDeposits as $transaction) {
            // Get transaction amount
            $transactionAmountValue = (float)$transaction->amount;

            // Find matching inactive investments within 1 hour
            $investments = $transaction->user->investments()
                ->where('active', false)
                ->whereBetween('created_at', [
                    $transaction->created_at->copy()->subHour(),
                    $transaction->created_at->copy()->addHour()
                ])
                ->get()
                ->filter(function($investment) use ($transactionAmountValue) {
                    $investmentAmount = (float)$investment->amount;
                    return abs($investmentAmount - $transactionAmountValue) < 0.01;
                });

            if ($investments->isNotEmpty()) {
                foreach ($investments as $investment) {
                    $this->line("  Activating investment #{$investment->id} for user {$transaction->user->name} (${$transactionAmountValue})");

                    $investment->update([
                        'active' => true,
                        'started_at' => $transaction->processed_at ?? $transaction->created_at
                    ]);

                    // Deduct available slots from the package
                    $package = $investment->investmentPackage;
                    if ($package && $package->available_slots > 0) {
                        \App\Models\InvestmentPackage::where('id', $package->id)
                            ->where('available_slots', '>', 0)
                            ->decrement('available_slots');
                    }

                    // Process referral bonus if not already processed
                    $referral = $transaction->user->referralReceived;
                    if ($referral && $package) {
                        // Check if bonus already exists
                        $existingBonus = \App\Models\ReferralBonus::where('referral_id', $referral->id)
                            ->where('investment_id', $investment->id)
                            ->first();

                        if (!$existingBonus) {
                            $investmentAmountValue = (float)$investment->amount;
                            $bonusRate = $package->referral_bonus_rate / 100;
                            $bonusAmount = $investmentAmountValue * $bonusRate;

                            \App\Models\ReferralBonus::create([
                                'referral_id' => $referral->id,
                                'investment_id' => $investment->id,
                                'bonus_amount' => $bonusAmount,
                                'paid' => true,
                                'paid_at' => now()
                            ]);

                            $referrer = $referral->referrer;
                            if ($referrer) {
                                $referrer->increment('account_balance', $bonusAmount);

                                $referrer->transactions()->create([
                                    'type' => 'referral_bonus',
                                    'amount' => $bonusAmount,
                                    'status' => 'completed',
                                    'description' => 'Referral bonus from ' . $transaction->user->name . ' - ' . $package->name . ' investment',
                                    'reference' => 'REF' . time() . rand(1000, 9999),
                                    'processed_at' => now()
                                ]);

                                $this->line("    + Created referral bonus of ${$bonusAmount} for referrer {$referrer->name}");
                            }
                        }
                    }

                    $fixed++;
                }
            }
        }

        $this->info("\n✅ Fixed {$fixed} investments");
        return 0;
    }
}
