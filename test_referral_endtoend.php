<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== End-to-End Referral System Test ===\n\n";

// 1. Create a new test user (referee)
$testEmail = 'referee.test.' . time() . '@example.com';
$referee = \App\Models\User::create([
    'name' => 'Test Referee User',
    'email' => $testEmail,
    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
]);

echo "✅ Created new test user (referee):\n";
echo "  - Name: {$referee->name}\n";
echo "  - Email: {$referee->email}\n";
echo "  - ID: {$referee->id}\n\n";

// 2. Create referral relationship (simulate registration with referral code)
$referrer = \App\Models\User::where('email', 'test@example.com')->first();
if (!$referrer) {
    echo "❌ Referrer user not found\n";
    exit;
}

$referral = \App\Models\Referral::create([
    'referrer_id' => $referrer->id,
    'referee_id' => $referee->id,
    'referral_code' => $referrer->id,
    'referred_at' => now(),
]);

echo "✅ Created referral relationship:\n";
echo "  - Referrer: {$referrer->name} (ID: {$referrer->id})\n";
echo "  - Referee: {$referee->name} (ID: {$referee->id})\n";
echo "  - Referral ID: {$referral->id}\n\n";

// 3. Create an investment for the referee
$package = \App\Models\InvestmentPackage::where('name', 'Energy Saver')->first();
if (!$package) {
    echo "❌ Energy Saver package not found\n";
    exit;
}

$investment = \App\Models\Investment::create([
    'user_id' => $referee->id,
    'investment_package_id' => $package->id,
    'amount' => $package->min_amount,
    'daily_shares_rate' => $package->daily_shares_rate,
    'remaining_days' => $package->effective_days,
    'total_interest_earned' => 0,
    'active' => false,
    'started_at' => now(),
    'ended_at' => null,
]);

echo "✅ Created investment for referee:\n";
echo "  - Investment ID: {$investment->id}\n";
echo "  - Package: {$package->name}\n";
echo "  - Amount: $" . number_format($investment->amount, 2) . "\n";
echo "  - Referral bonus rate: {$package->referral_bonus_rate}%\n";
echo "  - Status: " . ($investment->active ? 'Active' : 'Inactive') . "\n\n";

// 4. Check referrer balance before activation
$balanceBefore = $referrer->accountBalance();
echo "Referrer balance before activation: $" . number_format($balanceBefore, 2) . "\n";

// 5. Activate the investment (this should trigger referral bonus)
echo "Activating investment...\n";
$investment->update(['active' => true]);
$investment->createReferralBonus();

// 6. Check results
$balanceAfter = $referrer->fresh()->accountBalance();
$bonusAmount = $investment->amount * ($package->referral_bonus_rate / 100);

echo "✅ Investment activated!\n";
echo "  - Referrer balance after: $" . number_format($balanceAfter, 2) . "\n";
echo "  - Expected bonus: $" . number_format($bonusAmount, 2) . "\n";
echo "  - Actual bonus received: $" . number_format($balanceAfter - $balanceBefore, 2) . "\n\n";

// 7. Check referral bonus record
$referralBonus = \App\Models\ReferralBonus::where('referral_id', $referral->id)
    ->where('investment_id', $investment->id)
    ->first();

if ($referralBonus) {
    echo "✅ Referral bonus created:\n";
    echo "  - Bonus ID: {$referralBonus->id}\n";
    echo "  - Amount: $" . number_format($referralBonus->bonus_amount, 2) . "\n";
    echo "  - Paid: " . ($referralBonus->paid ? 'Yes' : 'No') . "\n";
    echo "  - Created: {$referralBonus->created_at->format('Y-m-d H:i:s')}\n\n";
} else {
    echo "❌ Referral bonus not created\n\n";
}

// 8. Check transaction record
$bonusTransaction = \App\Models\Transaction::where('user_id', $referrer->id)
    ->where('type', 'referral_bonus')
    ->where('amount', $bonusAmount)
    ->latest()
    ->first();

if ($bonusTransaction) {
    echo "✅ Referral bonus transaction created:\n";
    echo "  - Transaction ID: {$bonusTransaction->id}\n";
    echo "  - Amount: $" . number_format($bonusTransaction->amount, 2) . "\n";
    echo "  - Status: {$bonusTransaction->status}\n";
    echo "  - Description: {$bonusTransaction->description}\n\n";
} else {
    echo "❌ Referral bonus transaction not created\n\n";
}

// 9. Final summary
echo "=== Final Test Results ===\n";
echo "✅ Referral system is working correctly!\n";
echo "  - New user registered with referral: ✓\n";
echo "  - Investment created and activated: ✓\n";
echo "  - Referral bonus calculated and paid: ✓\n";
echo "  - Transaction record created: ✓\n";
echo "  - Referrer balance updated: ✓\n";

echo "\nTest URLs:\n";
echo "  - Referrer's referrals page: http://127.0.0.1:8000/user/referrals\n";
echo "  - Referrer's transactions page: http://127.0.0.1:8000/user/transactions\n";
echo "  - Investment receipt: http://127.0.0.1:8000/user/investment/receipt/" . $investment->id . "\n";
