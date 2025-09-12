<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Referral System ===\n\n";

// Find or create test users
$referrer = \App\Models\User::where('email', 'test@example.com')->first();
$referee = \App\Models\User::where('email', 'dycinne@gmail.com')->first();

if (!$referrer || !$referee) {
    echo "❌ Test users not found\n";
    echo "Referrer (test@example.com): " . ($referrer ? "Found" : "Not found") . "\n";
    echo "Referee (dycinne@gmail.com): " . ($referee ? "Found" : "Not found") . "\n";
    exit;
}

echo "✅ Test users found\n";
echo "Referrer: {$referrer->name} ({$referrer->email})\n";
echo "Referee: {$referee->name} ({$referee->email})\n\n";

// Create a referral relationship if it doesn't exist
$existingReferral = \App\Models\Referral::where('referrer_id', $referrer->id)
    ->where('referee_id', $referee->id)
    ->first();

if (!$existingReferral) {
    $referral = \App\Models\Referral::create([
        'referrer_id' => $referrer->id,
        'referee_id' => $referee->id,
        'referral_code' => $referrer->id,
        'referred_at' => now(),
    ]);
    echo "✅ Created referral relationship\n";
} else {
    $referral = $existingReferral;
    echo "✅ Referral relationship already exists\n";
}

echo "Referral ID: {$referral->id}\n\n";

// Find an inactive investment for the referee
$investment = \App\Models\Investment::where('user_id', $referee->id)
    ->where('active', false)
    ->first();

if (!$investment) {
    echo "❌ No inactive investment found for referee\n";
    
    // Create a test investment
    $package = \App\Models\InvestmentPackage::first();
    if (!$package) {
        echo "❌ No investment packages found\n";
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
    echo "✅ Created test investment: #{$investment->id}\n";
} else {
    echo "✅ Found inactive investment: #{$investment->id}\n";
}

echo "Investment amount: $" . number_format($investment->amount, 2) . "\n";
echo "Package: {$investment->investmentPackage->name}\n";
echo "Referral bonus rate: {$investment->investmentPackage->referral_bonus_rate}%\n\n";

// Check referrer's balance before
$balanceBefore = $referrer->accountBalance();
echo "Referrer balance before: $" . number_format($balanceBefore, 2) . "\n";

// Activate the investment and create referral bonus
echo "Activating investment and creating referral bonus...\n";
$investment->update(['active' => true]);
$investment->createReferralBonus();

// Check referrer's balance after
$balanceAfter = $referrer->fresh()->accountBalance();
echo "Referrer balance after: $" . number_format($balanceAfter, 2) . "\n";

$bonusAmount = $investment->amount * ($investment->investmentPackage->referral_bonus_rate / 100);
echo "Expected bonus: $" . number_format($bonusAmount, 2) . "\n";
echo "Actual bonus received: $" . number_format($balanceAfter - $balanceBefore, 2) . "\n\n";

// Check if referral bonus was created
$referralBonus = \App\Models\ReferralBonus::where('referral_id', $referral->id)
    ->where('investment_id', $investment->id)
    ->first();

if ($referralBonus) {
    echo "✅ Referral bonus created:\n";
    echo "  - Bonus amount: $" . number_format($referralBonus->bonus_amount, 2) . "\n";
    echo "  - Paid: " . ($referralBonus->paid ? 'Yes' : 'No') . "\n";
    echo "  - Paid at: " . ($referralBonus->paid_at ? $referralBonus->paid_at->format('Y-m-d H:i:s') : 'Not paid') . "\n";
} else {
    echo "❌ No referral bonus was created\n";
}

// Check transaction record
$bonusTransaction = \App\Models\Transaction::where('user_id', $referrer->id)
    ->where('type', 'referral_bonus')
    ->where('description', 'like', '%investment #' . $investment->id . '%')
    ->first();

if ($bonusTransaction) {
    echo "✅ Referral bonus transaction created:\n";
    echo "  - Amount: $" . number_format($bonusTransaction->amount, 2) . "\n";
    echo "  - Status: {$bonusTransaction->status}\n";
    echo "  - Description: {$bonusTransaction->description}\n";
} else {
    echo "❌ No referral bonus transaction was created\n";
}

echo "\n=== Final Status ===\n";
echo "Total referrals: " . \App\Models\Referral::count() . "\n";
echo "Total referral bonuses: " . \App\Models\ReferralBonus::count() . "\n";
echo "Referrer total earned: $" . number_format($referrer->fresh()->totalReferralBonuses(), 2) . "\n";
