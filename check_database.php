<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;

echo "=== Database Status ===\n";
echo "Total Users: " . User::count() . "\n";
echo "Total Transactions: " . Transaction::count() . "\n";
echo "Total Investments: " . Investment::count() . "\n\n";

if (Transaction::count() > 0) {
    echo "=== Recent Transactions ===\n";
    $transactions = Transaction::latest()->take(10)->get();
    foreach($transactions as $t) {
        echo "ID: {$t->id} | User: {$t->user_id} | Type: {$t->type} | Amount: \${$t->amount} | Status: {$t->status}\n";
    }
}

if (Investment::count() > 0) {
    echo "\n=== Recent Investments ===\n";
    $investments = Investment::latest()->take(5)->get();
    foreach($investments as $inv) {
        echo "ID: {$inv->id} | User: {$inv->user_id} | Amount: \${$inv->amount} | Active: " . ($inv->active ? 'Yes' : 'No') . "\n";
    }
}

// Check if there are any users with transactions
$usersWithTransactions = User::has('transactions')->get();
echo "\n=== Users with Transactions ===\n";
foreach($usersWithTransactions as $user) {
    echo "User: {$user->name} ({$user->email}) - {$user->transactions()->count()} transactions\n";
}
