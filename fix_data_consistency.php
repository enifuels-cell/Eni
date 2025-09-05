<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Data Consistency Fix ===\n\n";

// Fix Nagac Test - his raw balance should be 0, not 500
$nagac = User::where('email', 'nagac@test.com')->first();
if ($nagac) {
    echo "Fixing Nagac Test:\n";
    echo "  Current raw balance: \${$nagac->account_balance}\n";
    echo "  Calculated balance: \$" . $nagac->accountBalance() . "\n";
    
    // His calculated balance is correct (-500), so set raw balance to match
    $nagac->update(['account_balance' => $nagac->accountBalance()]);
    echo "  Fixed raw balance to: \${$nagac->fresh()->account_balance}\n\n";
}

// Fix Emily Test - her raw balance should match calculated balance
$emily = User::where('email', 'emily@test.com')->first();
if ($emily) {
    echo "Fixing Emily Test:\n";
    echo "  Current raw balance: \${$emily->account_balance}\n";
    echo "  Calculated balance: \$" . $emily->accountBalance() . "\n";
    
    // Set raw balance to match calculated balance
    $emily->update(['account_balance' => $emily->accountBalance()]);
    echo "  Fixed raw balance to: \${$emily->fresh()->account_balance}\n\n";
}

echo "=== Verification ===\n";

// Check all users again
$users = User::all();
foreach ($users as $user) {
    $rawBalance = $user->account_balance;
    $calculatedBalance = $user->accountBalance();
    $totalInvested = $user->totalInvestedAmount();
    
    if ($rawBalance != $calculatedBalance || $calculatedBalance < 0) {
        echo "User {$user->name}:\n";
        echo "  Raw: \${$rawBalance}, Calculated: \${$calculatedBalance}, Invested: \${$totalInvested}\n";
        
        if ($calculatedBalance < 0) {
            echo "  ❌ Still has negative calculated balance!\n";
        } else if ($rawBalance != $calculatedBalance) {
            echo "  ❌ Raw balance doesn't match calculated balance!\n";
        }
        echo "\n";
    } else if ($calculatedBalance > 0 || $totalInvested > 0) {
        echo "✅ User {$user->name}: Available \${$calculatedBalance}, Invested \${$totalInvested}\n";
    }
}

echo "\nData consistency fix completed.\n";
