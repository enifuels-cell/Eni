<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Console\Command;

class ActivateInvestments extends Command
{
    protected $signature = 'investments:activate {--all : Activate all inactive investments} {--user-id= : Activate for specific user} {--transaction-id= : Activate for specific transaction}';
    protected $description = 'Activate investments and create referral bonuses';

    public function handle()
    {
        $userId = $this->option('user-id');
        $transactionId = $this->option('transaction-id');

        $query = Investment::where('active', false);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($transactionId) {
            // Find investment related to transaction
            $transaction = Transaction::find($transactionId);
            if ($transaction && $transaction->type === 'deposit') {
                // Update transaction to completed if pending
                if ($transaction->status === 'pending') {
                    $transaction->update(['status' => 'completed', 'processed_at' => now()]);
                    $this->info("Transaction #{$transactionId} marked as completed");
                }
                
                // Find related investment
                $investment = Investment::where('user_id', $transaction->user_id)
                    ->where('amount', $transaction->amount)
                    ->where('active', false)
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
                if ($investment) {
                    $query = Investment::where('id', $investment->id);
                } else {
                    $this->error("No matching investment found for transaction #{$transactionId}");
                    return 1;
                }
            } else {
                $this->error("Transaction #{$transactionId} not found");
                return 1;
            }
        }

        if (!$this->option('all') && !$userId && !$transactionId) {
            $this->error('Use --all flag to activate all investments, or specify --user-id or --transaction-id');
            return 1;
        }

        $investments = $query->with(['user', 'investmentPackage'])->get();

        if ($investments->isEmpty()) {
            $this->info('No inactive investments found to activate.');
            return 0;
        }

        $this->info("Found {$investments->count()} inactive investment(s):");
        foreach ($investments as $investment) {
            $this->line("- Investment #{$investment->id}: {$investment->user->name} - \${$investment->amount} ({$investment->investmentPackage->name})");
        }

        if (!$this->confirm('Activate these investments?')) {
            $this->info('Cancelled.');
            return 0;
        }

        $activatedCount = 0;
        $bonusesCreated = 0;

        foreach ($investments as $investment) {
            try {
                // Activate the investment
                $investment->update([
                    'active' => true,
                    'started_at' => now()
                ]);
                
                // Create referral bonus if applicable
                $investment->createReferralBonus();
                
                $activatedCount++;
                
                // Check if referral bonus was created
                $referralBonus = $investment->referralBonuses()->latest()->first();
                if ($referralBonus) {
                    $bonusesCreated++;
                    $this->info("✓ Investment #{$investment->id} activated with referral bonus: \${$referralBonus->bonus_amount}");
                } else {
                    $this->info("✓ Investment #{$investment->id} activated (no referral)");
                }
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to activate investment #{$investment->id}: {$e->getMessage()}");
            }
        }

        $this->info("\nSummary:");
        $this->info("✓ Investments activated: {$activatedCount}");
        $this->info("✓ Referral bonuses created: {$bonusesCreated}");

        return 0;
    }
}
