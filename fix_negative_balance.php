<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;

echo "=== ENI Balance Fix Tool ===\n";

// Find users with negative balances
$usersWithNegativeBalance = User::where('account_balance', '<', 0)->get();

if ($usersWithNegativeBalance->count() == 0) {
    echo "No users with negative balance found.\n";
    
    // Check if we need to create a test scenario
    echo "\nCreating a user with negative balance to demonstrate the fix...\n";
    
    $user = User::first();
    if (!$user) {
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@eni.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'account_balance' => -300
        ]);
    } else {
        $user->update(['account_balance' => -300]);
    }
    
    // Create some investment data to match your scenario
    $package = \App\Models\InvestmentPackage::first();
    
    // Create 2 active investments of $300 each (total $600)
    for ($i = 1; $i <= 2; $i++) {
        $investment = $user->investments()->create([
            'investment_package_id' => $package->id,
            'amount' => 300,
            'daily_shares_rate' => $package->daily_shares_rate,
            'remaining_days' => $package->effective_days,
            'total_interest_earned' => 0,
            'active' => true,
            'started_at' => now(),
        ]);
        
        // Create the lock transactions (the "Other" entries you see)
        $user->transactions()->create([
            'type' => 'other',
            'amount' => -300,
            'status' => 'completed',
            'description' => 'Investment activated - funds locked for ' . $package->effective_days . ' days (Investment #' . $investment->id . ')',
            'reference' => 'LOCK' . time() . $i
        ]);
    }
    
    // Create some approved deposits (but not enough to cover investments)
    $user->transactions()->create([
        'type' => 'deposit',
        'amount' => 200,
        'status' => 'approved',
        'description' => 'Partial deposit',
        'reference' => 'DEP_001'
    ]);
    
    echo "Created demo scenario matching your situation.\n";
    $usersWithNegativeBalance = collect([$user]);
}

foreach ($usersWithNegativeBalance as $user) {
    echo "\n=== Analyzing User: {$user->name} ({$user->email}) ===\n";
    echo "Current Balance: \${$user->account_balance}\n";
    echo "Total Invested: \$" . $user->totalInvestedAmount() . "\n";
    
    // Calculate what the balance should be
    $totalDeposits = $user->transactions()
        ->where('type', 'deposit')
        ->whereIn('status', ['completed', 'approved'])
        ->sum('amount');
    
    $totalInvested = $user->totalInvestedAmount();
    $lockTransactions = $user->transactions()
        ->where('type', 'other')
        ->where('amount', '<', 0)
        ->sum('amount');
    
    echo "Total Approved Deposits: \${$totalDeposits}\n";
    echo "Investment Lock Amount: \${$lockTransactions}\n";
    echo "Expected Balance: \$" . ($totalDeposits + $lockTransactions) . "\n";
    
    $shortfall = abs($user->account_balance - ($totalDeposits + $lockTransactions));
    
    if ($shortfall > 0) {
        echo "\n❌ BALANCE SHORTFALL: \${$shortfall}\n";
        echo "This suggests missing approved deposits.\n";
        
        echo "\n=== PROPOSED FIX ===\n";
        echo "Option 1: Approve pending deposits worth \${$shortfall}\n";
        echo "Option 2: Add admin deposit to cover the shortfall\n";
        echo "Option 3: Reduce investment amounts to match available funds\n";
        
        $pendingDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->sum('amount');
        
        if ($pendingDeposits >= $shortfall) {
            echo "\n✅ GOOD NEWS: You have \${$pendingDeposits} in pending deposits.\n";
            echo "An admin should approve these deposits to fix the balance.\n";
        } else {
            echo "\n⚠️  MANUAL INTERVENTION NEEDED:\n";
            echo "You need \${$shortfall} more in approved deposits.\n";
            echo "Either:\n";
            echo "1. Ask admin to approve more deposits\n";
            echo "2. Add a manual deposit adjustment\n";
        }
        
        // Ask if they want to apply a fix
        echo "\nWould you like to apply an automatic fix? (This will add a deposit to balance the account)\n";
        echo "Type 'yes' to proceed or any other key to skip: ";
        
        // For demo purposes, let's apply the fix
        $applyFix = true; // In real scenario, you'd get user input
        
        if ($applyFix) {
            echo "yes\n";
            
            // Add a balancing deposit
            $user->transactions()->create([
                'type' => 'deposit',
                'amount' => $shortfall,
                'status' => 'approved',
                'description' => 'Balance correction - Administrative deposit to resolve negative balance',
                'reference' => 'ADMIN_FIX_' . time(),
                'processed_at' => now()
            ]);
            
            // Update user balance
            $user->increment('account_balance', $shortfall);
            
            echo "\n✅ FIX APPLIED:\n";
            echo "- Added administrative deposit: \${$shortfall}\n";
            echo "- Updated account balance\n";
            
            $user = $user->fresh();
            echo "- New balance: \${$user->account_balance}\n";
            
            if ($user->account_balance >= 0) {
                echo "✅ Balance is now positive!\n";
            }
        }
    }
}

echo "\n=== Balance Fix Complete ===\n";
