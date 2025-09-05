<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('email', 'dycinne@gmail.com')->first();

echo "=== Detailed Balance Calculation for {$user->name} ===\n";

echo "\nAll Transactions (chronological order):\n";
$allTransactions = $user->transactions()->orderBy('created_at', 'asc')->get();

$runningBalance = 0;

foreach($allTransactions as $trans) {
    $runningBalance += $trans->amount;
    echo "{$trans->created_at} | {$trans->type} | \${$trans->amount} | {$trans->description} | Running: \${$runningBalance}\n";
}

echo "\n=== Manual Balance Calculation ===\n";

$deposits = $user->transactions()->where('type', 'deposit')->where('status', 'completed')->sum('amount');
$transfers = $user->transactions()->where('type', 'transfer')->where('status', 'completed')->sum('amount');
$withdrawals = $user->transactions()->where('type', 'withdrawal')->where('status', 'completed')->sum('amount');
$other = $user->transactions()->where('type', 'other')->where('status', 'completed')->sum('amount');

echo "Deposits: \${$deposits}\n";
echo "Transfers: \${$transfers}\n";
echo "Withdrawals: \${$withdrawals}\n";
echo "Other: \${$other}\n";
echo "Formula: deposits + transfers + other - withdrawals\n";
echo "Calculation: {$deposits} + {$transfers} + {$other} - {$withdrawals} = " . ($deposits + $transfers + $other - $withdrawals) . "\n";

echo "\naccountBalance() method result: \$" . number_format($user->accountBalance(), 2) . "\n";
echo "Raw account_balance field: \${$user->account_balance}\n";

echo "\n=== Expected Result ===\n";
echo "User received: \$200 (deposit) + \$200 (transfer) + \$20 (transfer) = \$420\n";
echo "User invested: \$200 (Investment #2) + \$200 (Investment #5) = \$400\n";
echo "Available balance should be: \$420 - \$400 = \$20\n";
