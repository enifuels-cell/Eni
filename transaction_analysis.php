<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Transaction Analysis for Users with Issues ===\n\n";

$problemUsers = ['nagac@test.com', 'emily@test.com', 'dr.cedricplaza25@gmail.com'];

foreach ($problemUsers as $email) {
    $user = User::where('email', $email)->first();
    if (!$user) continue;
    
    echo "User: {$user->name} ({$user->email})\n";
    echo "Raw account_balance: \${$user->account_balance}\n";
    echo "Calculated balance: \$" . number_format($user->accountBalance(), 2) . "\n\n";
    
    // Break down the calculation
    $credits = $user->transactions()
        ->whereIn('type', ['deposit', 'interest', 'referral_bonus'])
        ->where('status', 'completed')
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
    
    echo "Balance Breakdown:\n";
    echo "  Credits (deposit, interest, referral): +\${$credits}\n";
    echo "  Transfers: +\${$transfers}\n";
    echo "  Other transactions: \${$other}\n";
    echo "  Withdrawals: -\${$withdrawals}\n";
    echo "  Total: \$" . ($credits + $transfers + $other - $withdrawals) . "\n\n";
    
    echo "All Transactions:\n";
    $transactions = $user->transactions()->orderBy('created_at', 'asc')->get();
    foreach ($transactions as $t) {
        echo "  - {$t->type}: \${$t->amount} [{$t->status}] - {$t->description}\n";
    }
    echo "\n" . str_repeat("-", 80) . "\n\n";
}
