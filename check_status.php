<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('email', 'dycinne@gmail.com')->first();

echo "=== Transaction Status Check ===\n";

$allTransactions = $user->transactions()->orderBy('created_at', 'asc')->get();

foreach($allTransactions as $trans) {
    echo "{$trans->type} | \${$trans->amount} | Status: {$trans->status} | {$trans->description}\n";
}

echo "\n=== Fix Deposit Status ===\n";

// Find the deposit transaction and check its status
$depositTransaction = $user->transactions()->where('type', 'deposit')->first();

if ($depositTransaction) {
    echo "Found deposit: \${$depositTransaction->amount} - Status: {$depositTransaction->status}\n";
    
    if ($depositTransaction->status !== 'completed') {
        echo "Updating deposit status to 'completed'\n";
        $depositTransaction->update(['status' => 'completed']);
    }
} else {
    echo "No deposit transaction found!\n";
}

echo "\n=== Recalculate Balance ===\n";

$user = $user->fresh(); // Reload the user

$deposits = $user->transactions()->where('type', 'deposit')->where('status', 'completed')->sum('amount');
$transfers = $user->transactions()->where('type', 'transfer')->where('status', 'completed')->sum('amount');
$withdrawals = $user->transactions()->where('type', 'withdrawal')->where('status', 'completed')->sum('amount');
$other = $user->transactions()->where('type', 'other')->where('status', 'completed')->sum('amount');

echo "Deposits: \${$deposits}\n";
echo "Transfers: \${$transfers}\n";
echo "Withdrawals: \${$withdrawals}\n";
echo "Other: \${$other}\n";
echo "New calculated balance: \$" . ($deposits + $transfers + $other - $withdrawals) . "\n";
echo "accountBalance() method: \$" . number_format($user->accountBalance(), 2) . "\n";
