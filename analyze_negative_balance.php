<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the current user (assuming you're the first user)
$user = User::first();

echo "=== Account Analysis for {$user->name} ===\n\n";

echo "Raw account_balance field: \${$user->account_balance}\n";
echo "Calculated balance: \$" . number_format($user->accountBalance(), 2) . "\n";
echo "Total invested: \$" . number_format($user->totalInvestedAmount(), 2) . "\n\n";

echo "=== All Transactions (chronological) ===\n";
$transactions = $user->transactions()->orderBy('created_at', 'asc')->get();

$runningBalance = 0;
foreach($transactions as $trans) {
    $runningBalance += $trans->amount;
    echo "{$trans->created_at->format('Y-m-d H:i')} | {$trans->type} | \${$trans->amount} | {$trans->status} | {$trans->description} | Running: \${$runningBalance}\n";
}

echo "\n=== Active Investments ===\n";
$investments = $user->investments()->active()->get();
foreach($investments as $inv) {
    echo "Investment #{$inv->id}: \${$inv->amount} ({$inv->investmentPackage->name})\n";
}

echo "\n=== Balance Breakdown ===\n";
$deposits = $user->transactions()->where('type', 'deposit')->whereIn('status', ['completed', 'approved'])->sum('amount');
$transfers = $user->transactions()->where('type', 'transfer')->where('status', 'completed')->sum('amount');
$withdrawals = $user->transactions()->where('type', 'withdrawal')->where('status', 'completed')->sum('amount');
$other = $user->transactions()->where('type', 'other')->where('status', 'completed')->sum('amount');

echo "Deposits: \${$deposits}\n";
echo "Transfers: \${$transfers}\n";
echo "Withdrawals: \${$withdrawals}\n";
echo "Other (investment locks): \${$other}\n";
echo "Net: \$" . ($deposits + $transfers + $other - $withdrawals) . "\n";

echo "\n=== Problem Analysis ===\n";
if ($user->account_balance < 0) {
    echo "❌ ISSUE: Raw balance is negative (-\${$user->account_balance})\n";
    
    // Check if the user has more investments than deposits
    $totalInvested = $user->totalInvestedAmount();
    $totalReceived = $deposits + $transfers;
    
    if ($totalInvested > $totalReceived) {
        echo "❌ CAUSE: Total invested (\${$totalInvested}) exceeds total received (\${$totalReceived})\n";
        echo "   This suggests investments were activated without sufficient deposit approvals.\n";
    }
    
    // Check for duplicate investment locks
    $lockTransactions = $user->transactions()->where('type', 'other')->where('amount', '<', 0)->get();
    echo "\n=== Investment Lock Transactions ===\n";
    foreach($lockTransactions as $lock) {
        echo "{$lock->created_at->format('Y-m-d H:i')} | \${$lock->amount} | {$lock->description}\n";
    }
    
    if ($lockTransactions->count() > $investments->count()) {
        echo "❌ POTENTIAL ISSUE: More lock transactions (" . $lockTransactions->count() . ") than active investments (" . $investments->count() . ")\n";
    }
}
