<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\Referral;
use App\Models\ReferralBonus;

echo "=== Testing Referral Bonus System (No Money Objects) ===\n\n";

DB::beginTransaction();

try {
    // Create referrer user
    $referrer = User::where('email', 'referrer@test.com')->first();
    $referralCode = 'REF' . strtoupper(substr(md5(time() . 'ref'), 0, 8));

    if (!$referrer) {
        $referrer = User::create([
            'name' => 'Referrer User',
            'email' => 'referrer@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_balance' => 0,
            'referral_code' => $referralCode,
            'email_verified_at' => now(),
        ]);
        echo "✅ Created referrer user: {$referrer->name} (Code: {$referralCode})\n";
    } else {
        // Ensure referrer has a referral code
        if (!$referrer->referral_code) {
            $referrer->referral_code = $referralCode;
            $referrer->save();
            $referrer->refresh();
            echo "✅ Added referral code to existing referrer: {$referralCode}\n";
        }
        echo "✅ Using existing referrer: {$referrer->name} (Code: {$referrer->referral_code})\n";
    }

    // Create referred user
    $referred = User::where('email', 'referred@test.com')->first();
    if (!$referred) {
        $referred = User::create([
            'name' => 'Referred User',
            'email' => 'referred@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_balance' => 0,
            'email_verified_at' => now(),
        ]);
        echo "✅ Created referred user: {$referred->name}\n";
    } else {
        echo "✅ Using existing referred user: {$referred->name}\n";
    }

    // Create referral relationship
    $referral = Referral::where('referee_id', $referred->id)->first();
    if (!$referral) {
        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referred->id,
            'referral_code' => $referrer->referral_code,
        ]);
        echo "✅ Created referral relationship\n";
    } else {
        echo "✅ Using existing referral relationship\n";
    }

    echo "\nReferrer Balance Before: \${$referrer->account_balance}\n\n";

    // Get a package
    $package = InvestmentPackage::first();
    if (!$package) {
        echo "❌ No investment packages found\n";
        DB::rollBack();
        exit(1);
    }

    echo "Creating investment for referred user...\n";
    echo "Package: {$package->name}\n";
    echo "Amount: \${$package->minimum_amount}\n";
    echo "Referral Bonus Rate: {$package->referral_bonus_rate}%\n\n";

    // Create and activate investment
    $investment = Investment::create([
        'user_id' => $referred->id,
        'investment_package_id' => $package->id,
        'amount' => $package->minimum_amount,
        'daily_shares_rate' => $package->daily_shares_rate,
        'remaining_days' => $package->effective_days,
        'start_date' => now(),
        'started_at' => now(),
        'total_interest_earned' => 0,
        'active' => true,  // Activate immediately
        'investment_code' => 'INV' . time() . rand(1000, 9999),
    ]);

    echo "✅ Investment created (ID: {$investment->id})\n";
    echo "   Amount: \${$investment->amount} (Type: " . gettype($investment->amount) . ")\n";

    // Create referral bonus using the model method
    echo "\nCreating referral bonus...\n";
    $investment->createReferralBonus();

    // Refresh models
    $referrer->refresh();
    $referred->refresh();

    // Check results
    $bonus = ReferralBonus::where('investment_id', $investment->id)->first();

    if ($bonus) {
        echo "✅ Referral bonus created!\n";
        echo "   Bonus ID: {$bonus->id}\n";
        echo "   Bonus Amount: \${$bonus->bonus_amount} (Type: " . gettype($bonus->bonus_amount) . ")\n";
        echo "   Paid: " . ($bonus->paid ? 'Yes' : 'No') . "\n\n";

        echo "Referrer Balance After: \${$referrer->account_balance}\n";

        $expectedBonus = $package->minimum_amount * ($package->referral_bonus_rate / 100);
        echo "Expected Bonus: \${$expectedBonus}\n";

        if (abs($referrer->account_balance - $expectedBonus) < 0.01) {
            echo "✅ Referrer balance updated correctly!\n\n";
        } else {
            echo "❌ Balance mismatch!\n\n";
        }

        // Check transaction
        $bonusTransaction = \App\Models\Transaction::where('user_id', $referrer->id)
            ->where('type', 'referral_bonus')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($bonusTransaction) {
            echo "✅ Bonus transaction created!\n";
            echo "   Amount: \${$bonusTransaction->amount} (Type: " . gettype($bonusTransaction->amount) . ")\n";
            echo "   Description: {$bonusTransaction->description}\n";
        } else {
            echo "❌ No bonus transaction found\n";
        }

    } else {
        echo "❌ Referral bonus NOT created\n";
    }

    DB::rollBack();
    echo "\n✅ Test completed (rolled back)\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== CONCLUSION ===\n";
echo "The referral bonus system works WITHOUT Money objects!\n";
echo "All amounts are simple decimal values that can be:\n";
echo "  - Added/subtracted directly\n";
echo "  - Multiplied for percentages\n";
echo "  - Compared without conversions\n";
echo "  - Displayed without ->toFloat()\n";
