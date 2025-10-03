<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Referral;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\ReferralBonus;
use App\Models\Transaction;

echo "=== TESTING REFERRAL BONUS SYSTEM ===\n\n";

// Step 1: Create or find a referrer user
echo "Step 1: Setting up referrer...\n";
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
    echo "  ✅ Created new referrer: {$referrer->name}\n";
} else {
    echo "  ✅ Found existing referrer: {$referrer->name}\n";
}

// Step 2: Create or find a referee user
echo "\nStep 2: Setting up referee...\n";
$referee = User::where('email', 'referee@test.com')->first();

if (!$referee) {
    $referee = User::create([
        'name' => 'Referee User',
        'email' => 'referee@test.com',
        'username' => 'refereeuser',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
        'role' => 'user',
        'account_balance' => 0,
    ]);
    echo "  ✅ Created new referee: {$referee->name}\n";
} else {
    echo "  ✅ Found existing referee: {$referee->name}\n";
}

// Step 3: Create referral relationship if it doesn't exist
echo "\nStep 3: Creating referral relationship...\n";
$existingReferral = Referral::where('referee_id', $referee->id)->first();

if (!$existingReferral) {
    $referral = Referral::create([
        'referrer_id' => $referrer->id,
        'referee_id' => $referee->id,
        'referral_code' => $referrer->username ?: 'REF' . $referrer->id,
        'referred_at' => now(),
    ]);
    echo "  ✅ Created referral: {$referrer->name} → {$referee->name}\n";
} else {
    $referral = $existingReferral;
    echo "  ✅ Referral already exists: {$referrer->name} → {$referee->name}\n";
}

// Step 4: Create an investment for the referee
echo "\nStep 4: Creating investment for referee...\n";
$package = InvestmentPackage::first();

if (!$package) {
    echo "  ❌ No investment packages found!\n";
    exit(1);
}

echo "  Using package: {$package->name}\n";
echo "  Referral bonus rate: {$package->referral_bonus_rate}%\n";

$investment = Investment::create([
    'user_id' => $referee->id,
    'investment_package_id' => $package->id,
    'amount' => 500,
    'active' => false, // Start inactive
    'started_at' => null,
]);

echo "  ✅ Created investment ID: {$investment->id} for \$500\n";

// Step 5: Check balances before activation
echo "\nStep 5: Balances BEFORE activation...\n";
$referrerBalanceBefore = is_object($referrer->account_balance) ? $referrer->account_balance->toFloat() : $referrer->account_balance;
echo "  Referrer balance: \${$referrerBalanceBefore}\n";

// Step 6: Activate the investment (should trigger referral bonus)
echo "\nStep 6: Activating investment...\n";
$investment->update([
    'active' => true,
    'started_at' => now()
]);

echo "  ✅ Investment activated\n";

// Step 7: Trigger referral bonus creation
echo "\nStep 7: Creating referral bonus...\n";
$investment->createReferralBonus();
echo "  ✅ Referral bonus creation attempted\n";

// Step 8: Check if bonus was created
echo "\nStep 8: Checking results...\n";
$bonus = ReferralBonus::where('investment_id', $investment->id)->first();

if ($bonus) {
    $bonusAmount = $bonus->bonus_amount instanceof \App\Support\Money ? $bonus->bonus_amount->toFloat() : (float)$bonus->bonus_amount;
    echo "  ✅ Referral bonus created!\n";
    echo "     Bonus ID: {$bonus->id}\n";
    echo "     Amount: \${$bonusAmount}\n";
    echo "     Paid: " . ($bonus->paid ? 'Yes' : 'No') . "\n";
} else {
    echo "  ❌ No referral bonus was created!\n";
    echo "     Check the logs for errors.\n";
}

// Step 9: Check referrer's updated balance
echo "\nStep 9: Balances AFTER activation...\n";
$referrer->refresh();
$referrerBalanceAfter = is_object($referrer->account_balance) ? $referrer->account_balance->toFloat() : $referrer->account_balance;
echo "  Referrer balance: \${$referrerBalanceAfter}\n";
echo "  Change: \$" . ($referrerBalanceAfter - $referrerBalanceBefore) . "\n";

// Step 10: Check transaction record
echo "\nStep 10: Checking transaction record...\n";
$bonusTransaction = Transaction::where('user_id', $referrer->id)
    ->where('type', 'referral_bonus')
    ->latest()
    ->first();

if ($bonusTransaction) {
    $transAmount = $bonusTransaction->amount instanceof \App\Support\Money ? $bonusTransaction->amount->toFloat() : (float)$bonusTransaction->amount;
    echo "  ✅ Transaction created!\n";
    echo "     Transaction ID: {$bonusTransaction->id}\n";
    echo "     Amount: \${$transAmount}\n";
    echo "     Status: {$bonusTransaction->status}\n";
    echo "     Description: {$bonusTransaction->description}\n";
} else {
    echo "  ❌ No transaction record found!\n";
}

// Final summary
echo "\n=== SUMMARY ===\n";
if ($bonus && $bonusTransaction && $referrerBalanceAfter > $referrerBalanceBefore) {
    echo "✅ Referral bonus system is WORKING!\n";
    echo "   - Bonus record created in database\n";
    echo "   - Referrer's balance increased\n";
    echo "   - Transaction record created\n\n";
    echo "Expected bonus: \$" . (500 * $package->referral_bonus_rate / 100) . "\n";
    echo "Actual bonus: \${$bonusAmount}\n";
} else {
    echo "❌ Referral bonus system has issues:\n";
    if (!$bonus) echo "   - No bonus record created\n";
    if (!$bonusTransaction) echo "   - No transaction record created\n";
    if ($referrerBalanceAfter <= $referrerBalanceBefore) echo "   - Referrer balance not updated\n";
}
