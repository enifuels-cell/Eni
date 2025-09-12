<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Referral System ===\n\n";

// Check total referrals
$referrals = \App\Models\Referral::with(['referrer', 'referee', 'referralBonuses'])->get();
echo "Total referrals in database: " . $referrals->count() . "\n\n";

if ($referrals->count() > 0) {
    echo "Referral details:\n";
    foreach($referrals as $referral) {
        echo "- Referrer: " . $referral->referrer->name . " ({$referral->referrer->email})\n";
        echo "  Referee: " . $referral->referee->name . " ({$referral->referee->email})\n";
        echo "  Code: " . $referral->referral_code . "\n";
        echo "  Bonuses: " . $referral->referralBonuses->count() . "\n";
        echo "  Total earned: $" . number_format($referral->referralBonuses->sum('bonus_amount'), 2) . "\n\n";
    }
} else {
    echo "No referrals found in the database.\n\n";
}

// Check users with their referral info
echo "=== Users and their referral status ===\n";
$users = \App\Models\User::with(['referralsGiven.referee', 'referralReceived.referrer'])->get();

foreach($users as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    echo "- Referrals given: " . $user->referralsGiven->count() . "\n";
    if ($user->referralsGiven->count() > 0) {
        foreach($user->referralsGiven as $ref) {
            echo "  -> {$ref->referee->name} ({$ref->referee->email})\n";
        }
    }
    echo "- Referred by: " . ($user->referralReceived ? $user->referralReceived->referrer->name : 'Direct signup') . "\n";
    echo "\n";
}

// Check referral bonuses
echo "=== Referral Bonuses ===\n";
$bonuses = \App\Models\ReferralBonus::with(['referral.referrer', 'referral.referee', 'investment'])->get();
echo "Total bonuses: " . $bonuses->count() . "\n";

foreach($bonuses as $bonus) {
    echo "- Referrer: " . $bonus->referral->referrer->name . "\n";
    echo "  Amount: $" . number_format($bonus->bonus_amount, 2) . "\n";
    echo "  Paid: " . ($bonus->paid ? 'Yes' : 'No') . "\n";
    echo "  Investment: $" . number_format($bonus->investment->amount, 2) . "\n\n";
}
