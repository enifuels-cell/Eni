<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Referral;
use App\Models\ReferralBonus;

echo "=== REFERRAL BONUS VERIFICATION ===\n\n";

// Check Test User's referral setup
$testUser = User::where('name', 'Test User')->first();

if (!$testUser) {
    echo "❌ Test User not found!\n";
    exit(1);
}

echo "=== Test User Info ===\n";
echo "Name: {$testUser->name}\n";
echo "Email: {$testUser->email}\n";
echo "Username: " . ($testUser->username ?? 'NONE') . "\n";

// Check if Test User was referred
$referralReceived = Referral::where('referee_id', $testUser->id)->first();
if ($referralReceived) {
    $referrer = User::find($referralReceived->referrer_id);
    echo "Referred By: {$referrer->name} (ID: {$referrer->id})\n";
} else {
    echo "Referred By: NONE\n";
}

// Check how many people Test User has referred
$referralsGiven = Referral::where('referrer_id', $testUser->id)->count();
echo "Users Referred: {$referralsGiven}\n";
echo "\n";

// Check for referral bonus transactions
echo "=== Referral Bonus Records ===\n";
$referralBonuses = ReferralBonus::with(['referral.referrer', 'referral.referee', 'investment'])
    ->get();

if ($referralBonuses->isEmpty()) {
    echo "❌ No referral bonus records found in the system.\n";
} else {
    echo "Found {$referralBonuses->count()} referral bonus record(s):\n\n";
    foreach ($referralBonuses as $bonus) {
        $amount = $bonus->amount instanceof \App\Support\Money ? $bonus->amount->toFloat() : (float)$bonus->amount;
        echo "ID: {$bonus->id}\n";
        echo "  Referrer: {$bonus->referral->referrer->name}\n";
        echo "  Referee: {$bonus->referral->referee->name}\n";
        echo "  Amount: \${$amount}\n";
        echo "  Investment ID: {$bonus->investment_id}\n";
        echo "  Created: {$bonus->created_at}\n\n";
    }
}

// Check if Test User has any investments that should have triggered bonuses
echo "=== Test User's Active Investments ===\n";
$activeInvestments = Investment::where('user_id', $testUser->id)
    ->where('active', true)
    ->get();

echo "Active Investments Count: {$activeInvestments->count()}\n";
$totalInvested = $activeInvestments->sum(fn($inv) => $inv->amount instanceof \App\Support\Money ? $inv->amount->toFloat() : (float)$inv->amount);
echo "Total Amount: \${$totalInvested}\n\n";

// Check all users with referrers to see potential bonus scenarios
echo "=== All Referral Relationships ===\n";
$allReferrals = Referral::with(['referrer', 'referee'])->get();

if ($allReferrals->isEmpty()) {
    echo "❌ No referral relationships exist.\n";
    echo "   Referral bonuses only work when users sign up with a referral link.\n\n";
} else {
    echo "Found {$allReferrals->count()} referral relationship(s):\n\n";
    foreach ($allReferrals as $ref) {
        echo "Referrer: {$ref->referrer->name} → Referee: {$ref->referee->name}\n";

        $refereeInvestments = Investment::where('user_id', $ref->referee_id)->where('active', true)->count();
        $bonusesEarned = ReferralBonus::where('referral_id', $ref->id)->count();

        echo "  Referee's Active Investments: {$refereeInvestments}\n";
        echo "  Bonuses Created: {$bonusesEarned}\n\n";
    }
}

// Final assessment
echo "=== ASSESSMENT ===\n";
if (!$referralReceived) {
    echo "⚠️  Test User was NOT referred by anyone.\n";
    echo "   To test referral bonuses:\n";
    echo "   1. Create a referral relationship in the database\n";
    echo "   2. Or register a new user using Test User's referral link\n";
    echo "   3. Then approve deposits for the referred user\n\n";
} else {
    if ($referralBonuses->isEmpty()) {
        echo "❌ Referral relationships exist but NO bonuses were created.\n";
        echo "   The investment activation may not be triggering referral bonus creation.\n";
        echo "   Need to check the investment activation logic.\n\n";
    } else {
        echo "✅ Referral bonus system appears to be working!\n\n";
    }
}

// Show total referral bonus for each user
echo "=== User Referral Bonus Totals ===\n";
$allUsers = User::where('role', 'user')->get();
$hasAnyBonuses = false;
foreach ($allUsers as $user) {
    $totalBonus = $user->totalReferralBonuses();
    if ($totalBonus > 0) {
        echo "{$user->name}: \${$totalBonus}\n";
        $hasAnyBonuses = true;
    }
}
if (!$hasAnyBonuses) {
    echo "No users have received referral bonuses yet.\n";
}
