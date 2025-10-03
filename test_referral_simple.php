<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\Investment;
use App\Models\ReferralBonus;
use App\Models\Transaction;

echo "=== TESTING REFERRAL BONUS WITH EXISTING INVESTMENT ===\n\n";

// Find Test User
$testUser = User::where('name', 'Test User')->first();

if (!$testUser) {
    echo "❌ Test User not found!\n";
    exit(1);
}

// Create a referrer for Test User
echo "Step 1: Creating a referrer...\n";
$referrer = User::where('email', 'referrer@test.com')->first();

if (!$referrer) {
    $referrer = User::create([
        'name' => 'Referrer User',
        'email' => 'referrer@test.com',
        'username' => 'referreruser',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
        'role' => 'user',
        'account_balance' => 0,
    ]);
    echo "  ✅ Created referrer: {$referrer->name}\n";
} else {
    echo "  ✅ Found referrer: {$referrer->name}\n";
}

// Create referral relationship
echo "\nStep 2: Creating referral relationship (Test User was referred by Referrer User)...\n";
$existingReferral = Referral::where('referee_id', $testUser->id)->first();

if ($existingReferral) {
    echo "  ⚠️  Deleting existing referral relationship...\n";
    ReferralBonus::where('referral_id', $existingReferral->id)->delete();
    $existingReferral->delete();
}

$referral = Referral::create([
    'referrer_id' => $referrer->id,
    'referee_id' => $testUser->id,
    'referral_code' => $referrer->username ?: 'REF' . $referrer->id,
    'referred_at' => now(),
]);
echo "  ✅ Created referral: {$referrer->name} → {$testUser->name}\n";

// Check referrer's balance before
$referrerBalanceBefore = is_object($referrer->account_balance) ? $referrer->account_balance->toFloat() : $referrer->account_balance;
echo "\nStep 3: Referrer balance BEFORE: \${$referrerBalanceBefore}\n";

// Get one of Test User's active investments
echo "\nStep 4: Finding an investment to trigger bonus for...\n";
$investment = Investment::where('user_id', $testUser->id)
    ->where('active', true)
    ->first();

if (!$investment) {
    echo "  ❌ No active investments found for Test User!\n";
    exit(1);
}

$invAmount = $investment->amount instanceof \App\Support\Money ? $investment->amount->toFloat() : (float)$investment->amount;
echo "  ✅ Found investment ID: {$investment->id} for \${$invAmount}\n";

// Check if bonus already exists
$existingBonus = ReferralBonus::where('investment_id', $investment->id)->first();
if ($existingBonus) {
    echo "  ⚠️  Bonus already exists for this investment, deleting it...\n";
    $existingBonus->delete();
}

// Trigger referral bonus creation
echo "\nStep 5: Creating referral bonus...\n";
$investment->createReferralBonus();

// Check if bonus was created
$bonus = ReferralBonus::where('investment_id', $investment->id)->first();

if ($bonus) {
    $bonusAmount = $bonus->bonus_amount instanceof \App\Support\Money ? $bonus->bonus_amount->toFloat() : (float)$bonus->bonus_amount;
    echo "  ✅ Referral bonus created!\n";
    echo "     Bonus ID: {$bonus->id}\n";
    echo "     Amount: \${$bonusAmount}\n";
    echo "     Paid: " . ($bonus->paid ? 'Yes' : 'No') . "\n";
} else {
    echo "  ❌ No referral bonus was created!\n";
}

// Check referrer's balance after
$referrer->refresh();
$referrerBalanceAfter = is_object($referrer->account_balance) ? $referrer->account_balance->toFloat() : $referrer->account_balance;
echo "\nStep 6: Referrer balance AFTER: \${$referrerBalanceAfter}\n";
echo "  Change: +\$" . ($referrerBalanceAfter - $referrerBalanceBefore) . "\n";

// Check transaction
$bonusTransaction = Transaction::where('user_id', $referrer->id)
    ->where('type', 'referral_bonus')
    ->latest()
    ->first();

echo "\nStep 7: Checking transaction record...\n";
if ($bonusTransaction) {
    $transAmount = $bonusTransaction->amount instanceof \App\Support\Money ? $bonusTransaction->amount->toFloat() : (float)$bonusTransaction->amount;
    echo "  ✅ Transaction created!\n";
    echo "     Amount: \${$transAmount}\n";
    echo "     Status: {$bonusTransaction->status}\n";
    echo "     Description: {$bonusTransaction->description}\n";
} else {
    echo "  ❌ No transaction found!\n";
}

// Final summary
echo "\n=== SUMMARY ===\n";
if ($bonus && $bonusTransaction && $referrerBalanceAfter > $referrerBalanceBefore) {
    echo "✅ REFERRAL BONUS SYSTEM IS WORKING!\n\n";
    echo "When a user who was referred makes an investment:\n";
    echo "1. ReferralBonus record is created ✅\n";
    echo "2. Referrer's account_balance is increased ✅\n";
    echo "3. Transaction record is created ✅\n\n";
    echo "The referrer receives {$investment->investmentPackage->referral_bonus_rate}% of the investment amount.\n";
} else {
    echo "❌ REFERRAL BONUS SYSTEM HAS ISSUES!\n";
}

echo "\nNote: This only created 1 bonus for 1 investment as a test.\n";
echo "To create bonuses for all Test User's investments, run:\n";
echo "php artisan tinker\n";
echo "Then execute:\n";
echo "\$testUser = App\\Models\\User::where('name', 'Test User')->first();\n";
echo "\$testUser->investments()->active()->each(fn(\$inv) => \$inv->createReferralBonus());\n";
