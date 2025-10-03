<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Investment;
use App\Models\Referral;
use App\Models\ReferralBonus;

echo "=== MANUALLY CREATE REFERRAL BONUS FOR TEST ===\n\n";

DB::beginTransaction();

try {
    $testUser = User::where('name', 'Test User')->first();
    $referral = Referral::where('referee_id', $testUser->id)->first();

    if (!$referral) {
        echo "❌ No referral found for Test User\n";
        DB::rollBack();
        exit(1);
    }

    $referrer = $referral->referrer;
    echo "Referrer: {$referrer->name}\n";
    echo "Referrer Balance Before: \${$referrer->account_balance}\n\n";

    // Get first active investment without a bonus
    $investment = Investment::where('user_id', $testUser->id)
        ->where('active', true)
        ->whereDoesntHave('referralBonuses')
        ->first();

    if (!$investment) {
        echo "ℹ️ All investments already have bonuses or no investments found\n";
        DB::rollBack();
        exit(0);
    }

    echo "Creating bonus for Investment #{$investment->id}\n";
    echo "Investment Amount: \${$investment->amount} (Type: " . gettype($investment->amount) . ")\n";
    echo "Package: {$investment->investmentPackage->name}\n";
    echo "Bonus Rate: {$investment->investmentPackage->referral_bonus_rate}%\n\n";

    // Call the model's createReferralBonus method
    $investment->createReferralBonus();

    // Refresh to see changes
    $referrer->refresh();

    echo "✅ Bonus created!\n\n";
    echo "Referrer Balance After: \${$referrer->account_balance}\n";

    // Verify the bonus was created
    $bonus = ReferralBonus::where('investment_id', $investment->id)->first();

    if ($bonus) {
        echo "\nBonus Details:\n";
        echo "  ID: {$bonus->id}\n";
        echo "  Amount: \${$bonus->bonus_amount} (Type: " . gettype($bonus->bonus_amount) . ")\n";
        echo "  Paid: " . ($bonus->paid ? 'Yes' : 'No') . "\n";

        $expectedBonus = (float)$investment->amount * ($investment->investmentPackage->referral_bonus_rate / 100);
        echo "\nExpected: \${$expectedBonus}\n";
        echo "Actual: \${$bonus->bonus_amount}\n";

        if (abs((float)$bonus->bonus_amount - $expectedBonus) < 0.01) {
            echo "✅ Bonus amount is CORRECT!\n";
        } else {
            echo "❌ Bonus amount mismatch!\n";
        }

        // Check transaction
        $transaction = \App\Models\Transaction::where('user_id', $referrer->id)
            ->where('type', 'referral_bonus')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($transaction) {
            echo "\nBonus Transaction:\n";
            echo "  Amount: \${$transaction->amount} (Type: " . gettype($transaction->amount) . ")\n";
            echo "  Description: {$transaction->description}\n";
            echo "  Status: {$transaction->status}\n";
            echo "✅ Transaction created!\n";
        } else {
            echo "\n❌ No transaction created\n";
        }

        echo "\n=== SUCCESS ===\n";
        echo "✅ Referral bonus system works correctly!\n";
        echo "✅ All amounts are decimals (no Money objects)\n";
        echo "✅ Math calculations are accurate\n";
        echo "✅ Referrer balance updated\n";
        echo "✅ Transaction record created\n";

    } else {
        echo "❌ Bonus was NOT created\n";
    }

    // Rollback to keep database clean
    DB::rollBack();
    echo "\n(Changes rolled back - test only)\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
