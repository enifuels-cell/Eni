<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Adding Initial Funding to Fix Negative Balances ===\n\n";

// Add initial funding to Nagac Test so his balance makes sense
$nagac = User::where('email', 'nagac@test.com')->first();
if ($nagac && $nagac->accountBalance() < 0) {
    echo "Adding initial funding to Nagac Test:\n";
    
    // He transferred $500, so he needed at least $500 initially
    $nagac->transactions()->create([
        'type' => 'deposit',
        'amount' => 500,
        'status' => 'completed',
        'description' => 'Initial account funding [Data Fix]',
        'reference' => 'INIT_' . time(),
        'created_at' => now()->subDays(1), // Make it older than the transfer
    ]);
    
    echo "  Added \$500 initial deposit\n";
    echo "  New calculated balance: \$" . $nagac->fresh()->accountBalance() . "\n\n";
}

// Add initial funding to Emily Test 
$emily = User::where('email', 'emily@test.com')->first();
if ($emily && $emily->accountBalance() < 0) {
    echo "Adding initial funding to Emily Test:\n";
    
    // She made transfers totaling $220 after receiving $500 and investing $500
    // So she needed more initial funding
    $emily->transactions()->create([
        'type' => 'deposit',
        'amount' => 220,
        'status' => 'completed', 
        'description' => 'Initial account funding [Data Fix]',
        'reference' => 'INIT_' . time(),
        'created_at' => now()->subDays(1),
    ]);
    
    echo "  Added \$220 initial deposit\n";
    echo "  New calculated balance: \$" . $emily->fresh()->accountBalance() . "\n\n";
}

// Update raw balances to match calculated balances
echo "Syncing raw balances with calculated balances:\n";
$users = User::all();
foreach ($users as $user) {
    $calculatedBalance = $user->accountBalance();
    if ($user->account_balance != $calculatedBalance) {
        echo "  {$user->name}: \${$user->account_balance} -> \${$calculatedBalance}\n";
        $user->update(['account_balance' => $calculatedBalance]);
    }
}

echo "\n=== Final Verification ===\n";
foreach ($users as $user) {
    $balance = $user->fresh()->accountBalance();
    $invested = $user->totalInvestedAmount();
    
    if ($balance < 0) {
        echo "❌ User {$user->name} still has negative balance: \${$balance}\n";
    } else if ($balance > 0 || $invested > 0) {
        echo "✅ User {$user->name}: Available \${$balance}, Invested \${$invested}\n";
    }
}

echo "\nData consistency fix completed.\n";
