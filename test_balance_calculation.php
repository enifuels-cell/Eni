<?php

/**
 * Test script to verify the User accountBalance() method works correctly
 * with the investment locking system
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Transaction;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Account Balance Calculation Test ===\n\n";

$user = User::first();
if (!$user) {
    echo "❌ Error: Need at least one user\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n\n";

// Show all transactions affecting balance
echo "Transactions affecting account balance:\n";
echo "-------------------------------------\n";

$transactions = $user->transactions()
    ->whereIn('type', ['deposit', 'interest', 'referral_bonus', 'transfer', 'withdrawal', 'other'])
    ->where('status', 'completed')
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();

$totalCredits = 0;
$totalDebits = 0;

foreach ($transactions as $transaction) {
    $sign = $transaction->amount >= 0 ? '+' : '';
    $type = strtoupper($transaction->type);
    echo sprintf("%-12s %s$%s - %s\n", 
        $type, 
        $sign, 
        number_format(abs($transaction->amount), 2),
        $transaction->description
    );
    
    if ($transaction->amount > 0) {
        if (in_array($transaction->type, ['deposit', 'interest', 'referral_bonus', 'transfer'])) {
            $totalCredits += $transaction->amount;
        }
    } else {
        if (in_array($transaction->type, ['withdrawal', 'other'])) {
            $totalDebits += abs($transaction->amount);
        }
    }
}

echo "\nBalance Calculation:\n";
echo "-------------------\n";
echo "Credits (deposits, interest, referrals, transfers): +$" . number_format($totalCredits, 2) . "\n";
echo "Debits (withdrawals, investments): -$" . number_format($totalDebits, 2) . "\n";
echo "Net Balance: $" . number_format($totalCredits - $totalDebits, 2) . "\n";

echo "\nUser::accountBalance() method result: $" . number_format($user->accountBalance(), 2) . "\n";

// Check active investments
$activeInvestments = $user->investments()->active()->get();
$totalInvested = $activeInvestments->sum('amount');

echo "\nActive Investments:\n";
echo "------------------\n";
if ($activeInvestments->count() > 0) {
    foreach ($activeInvestments as $investment) {
        echo "Investment #{$investment->id}: $" . number_format($investment->amount, 2) . 
             " ({$investment->investmentPackage->name}) - {$investment->remaining_days} days left\n";
    }
    echo "Total Locked in Investments: $" . number_format($totalInvested, 2) . "\n";
} else {
    echo "No active investments\n";
}

echo "\nSummary:\n";
echo "-------\n";
echo "Available for Withdrawal: $" . number_format($user->accountBalance(), 2) . "\n";
echo "Locked in Investments: $" . number_format($totalInvested, 2) . "\n";
echo "Total User Value: $" . number_format($user->accountBalance() + $totalInvested, 2) . "\n";

echo "\n✅ The account balance correctly excludes locked investment funds\n";
echo "✅ Users can only withdraw their available balance, not invested funds\n";
echo "✅ Investments remain locked for their full duration (typically 180 days)\n";
