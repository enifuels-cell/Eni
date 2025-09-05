<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ENI Investment Platform - Error Check ===\n\n";

// Check for potential issues
$issues = [];

// 1. Check for users with negative calculated balances (should not happen)
echo "1. Checking for users with negative calculated balances...\n";
$users = User::all();
foreach ($users as $user) {
    $calculatedBalance = $user->accountBalance();
    if ($calculatedBalance < 0) {
        $issues[] = "User {$user->name} ({$user->email}) has negative calculated balance: \${$calculatedBalance}";
    }
}

// 2. Check for investments without corresponding lock transactions
echo "2. Checking for active investments without lock transactions...\n";
$activeInvestments = Investment::where('active', true)->with('user')->get();
foreach ($activeInvestments as $investment) {
    $lockTransactions = $investment->user->transactions()
        ->where('type', 'other')
        ->where('amount', -$investment->amount)
        ->where('description', 'like', '%Investment%locked%')
        ->orWhere('description', 'like', '%funded by transfer%')
        ->count();
    
    if ($lockTransactions === 0) {
        $issues[] = "Investment #{$investment->id} (\${$investment->amount}) for user {$investment->user->name} has no lock transaction";
    }
}

// 3. Check for orphaned lock transactions
echo "3. Checking for orphaned lock transactions...\n";
$lockTransactions = Transaction::where('type', 'other')
    ->where('amount', '<', 0)
    ->where('description', 'like', '%Investment%locked%')
    ->with('user')
    ->get();

foreach ($lockTransactions as $transaction) {
    $matchingInvestment = $transaction->user->investments()
        ->where('active', true)
        ->where('amount', abs($transaction->amount))
        ->exists();
    
    if (!$matchingInvestment) {
        $issues[] = "Orphaned lock transaction: \${$transaction->amount} for user {$transaction->user->name}";
    }
}

// 4. Check for mismatched raw vs calculated balances
echo "4. Checking for significant discrepancies between raw and calculated balances...\n";
foreach ($users as $user) {
    $rawBalance = $user->account_balance;
    $calculatedBalance = $user->accountBalance();
    $totalInvested = $user->totalInvestedAmount();
    
    // Expected: calculatedBalance + totalInvested should equal the total flow of funds
    // Calculate exactly like the accountBalance() method
    $credits = $user->transactions()
        ->whereIn('type', ['deposit', 'interest', 'referral_bonus'])
        ->whereIn('status', ['completed', 'approved'])
        ->sum('amount');
        
    $transfers = $user->transactions()
        ->where('type', 'transfer')
        ->where('status', 'completed')
        ->sum('amount');
        
    $withdrawals = $user->transactions()
        ->where('type', 'withdrawal')
        ->where('status', 'completed')
        ->sum('amount');
        
    $other = $user->transactions()
        ->where('type', 'other')
        ->where('status', 'completed')
        ->sum('amount');
    
    $expectedCalculatedBalance = $credits + $transfers + $other - $withdrawals;
    $actualTotal = $calculatedBalance + $totalInvested;
    $expectedTotal = $expectedCalculatedBalance + $totalInvested;
    $difference = abs($expectedTotal - $actualTotal);
    
    if ($difference > 1) { // Allow for small rounding differences
        $issues[] = "Balance mismatch for user {$user->name}: Expected total \${$expectedTotal}, actual \${$actualTotal} (diff: \${$difference})";
    }
}

// 5. Check for pending transactions that might affect balance
echo "5. Checking for pending transactions...\n";
$pendingTransactions = Transaction::where('status', 'pending')
    ->whereIn('type', ['deposit', 'withdrawal', 'transfer'])
    ->with('user')
    ->get();

if ($pendingTransactions->count() > 0) {
    echo "Found {$pendingTransactions->count()} pending transactions:\n";
    foreach ($pendingTransactions as $transaction) {
        echo "  - {$transaction->type}: \${$transaction->amount} for {$transaction->user->name} ({$transaction->description})\n";
    }
    echo "\n";
}

// Report results
echo "\n=== Error Check Results ===\n";
if (empty($issues)) {
    echo "✅ No issues found! The investment balance system is working correctly.\n\n";
    
    echo "Summary:\n";
    echo "- All users have non-negative calculated balances\n";
    echo "- All active investments have corresponding lock transactions\n";
    echo "- No orphaned lock transactions found\n";
    echo "- Balance calculations are consistent\n";
} else {
    echo "❌ Found " . count($issues) . " issues:\n\n";
    foreach ($issues as $issue) {
        echo "- {$issue}\n";
    }
    echo "\nThese issues should be investigated and fixed.\n";
}

echo "\n=== User Balance Summary ===\n";
foreach ($users as $user) {
    if ($user->accountBalance() > 0 || $user->totalInvestedAmount() > 0) {
        echo "User: {$user->name}\n";
        echo "  Available: \$" . number_format($user->accountBalance(), 2) . "\n";
        echo "  Invested: \$" . number_format($user->totalInvestedAmount(), 2) . "\n";
        echo "  Total Value: \$" . number_format($user->accountBalance() + $user->totalInvestedAmount(), 2) . "\n\n";
    }
}
