<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Referral Bonus Transactions ===\n\n";

$transactions = \App\Models\Transaction::where('type', 'referral_bonus')->with('user')->get();
echo "Total referral bonus transactions: " . $transactions->count() . "\n\n";

foreach($transactions as $transaction) {
    echo "Transaction ID: {$transaction->id}\n";
    echo "User: {$transaction->user->name} ({$transaction->user->email})\n";
    echo "Amount: $" . number_format($transaction->amount, 2) . "\n";
    echo "Status: {$transaction->status}\n";
    echo "Description: {$transaction->description}\n";
    echo "Created: {$transaction->created_at->format('Y-m-d H:i:s')}\n";
    echo str_repeat("-", 50) . "\n";
}

echo "\n=== Referral Summary ===\n";
$referrals = \App\Models\Referral::with(['referrer', 'referee', 'referralBonuses'])->get();

foreach($referrals as $referral) {
    echo "Referrer: {$referral->referrer->name}\n";
    echo "Referee: {$referral->referee->name}\n";
    echo "Total bonuses: " . $referral->referralBonuses->count() . "\n";
    echo "Total earned: $" . number_format($referral->referralBonuses->sum('bonus_amount'), 2) . "\n\n";
}
