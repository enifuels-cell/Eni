<?php

namespace App\Services;

use App\Events\InvestmentCreated;
use App\Events\ReferralBonusGranted;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InvestmentService
{
    /**
     * Orchestrate creating an investment with all side effects.
     *
     * @param User $user
     * @param InvestmentPackage $package (locked by caller or service)
     * @param float $amount
     * @param string|null $referralCode
     * @return Investment
     * @throws ValidationException
     */
    public function createInvestment(User $user, InvestmentPackage $package, float $amount, ?string $referralCode = null): Investment
    {
        // Validate amount inside service as defense in depth
        if ($amount < $package->min_amount || $amount > $package->max_amount) {
            throw ValidationException::withMessages([
                'amount' => "Amount must be between $" . number_format($package->min_amount, 2) . " and $" . number_format($package->max_amount, 2)
            ]);
        }

        // Check user available balance
        $availableBalance = $user->accountBalance();
        if ($availableBalance < $amount) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient available balance. You have $' . number_format($availableBalance, 2) . ' available for investment.'
            ]);
        }

        return DB::transaction(function () use ($user, $package, $amount, $referralCode) {
            // Lock package row for update if slots limited to prevent race
            if (!is_null($package->available_slots)) {
                $package = InvestmentPackage::where('id', $package->id)->lockForUpdate()->first();
                if ($package->available_slots <= 0) {
                    throw ValidationException::withMessages([
                        'package' => 'This package is currently full.'
                    ]);
                }
            }

            // Create investment
            $investment = Investment::create([
                'user_id' => $user->id,
                'investment_package_id' => $package->id,
                'amount' => $amount,
                'daily_shares_rate' => $package->daily_shares_rate,
                'remaining_days' => $package->effective_days,
                'total_interest_earned' => 0,
                'active' => true,
                'started_at' => now(),
                'ended_at' => null,
            ]);

            // Deduct funds from user balance
            $user->decrement('account_balance', $amount);

            // Ledger transaction (outflow)
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'other',
                'amount' => -$amount,
                'reference' => "Investment #" . $investment->id,
                'status' => 'completed',
                'description' => "Investment in " . $package->name,
                'processed_at' => now(),
            ]);

            // Referral bonus logic
            if ($referralCode) {
                $referral = Referral::where('referral_code', $referralCode)->first();
                if ($referral && $referral->referee_id === $user->id) {
                    $bonusAmount = $amount * ($package->referral_bonus_rate / 100);

                    $bonus = ReferralBonus::create([
                        'referral_id' => $referral->id,
                        'investment_id' => $investment->id,
                        'bonus_amount' => $bonusAmount,
                        'paid' => true,
                        'paid_at' => now(),
                    ]);

                    Transaction::create([
                        'user_id' => $referral->referrer_id,
                        'type' => 'referral_bonus',
                        'amount' => $bonusAmount,
                        'reference' => "Referral bonus for investment #" . $investment->id,
                        'status' => 'completed',
                        'description' => "Referral bonus from " . $user->name,
                        'processed_at' => now(),
                    ]);

                    event(new ReferralBonusGranted($bonus));
                }
            }

            // Decrement slots atomically (prevent overbooking)
            if (!is_null($package->available_slots)) {
                $affected = InvestmentPackage::where('id', $package->id)
                    ->where('available_slots', '>', 0)
                    ->decrement('available_slots');
                if ($affected === 0) {
                    throw ValidationException::withMessages([
                        'package' => 'This package just became full. Please choose another package.'
                    ]);
                }
            }

            event(new InvestmentCreated($investment));

            Log::channel('investment')->info('Investment created', [
                'investment_id' => $investment->id,
                'investment_code' => $investment->investment_code,
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $amount
            ]);

            return $investment;
        });
    }
}
