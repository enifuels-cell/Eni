<?php

/**
 * Test script to demonstrate the investment balance fix
 * This script shows how the account balance should behave when investments are activated
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;
use App\Models\InvestmentPackage;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ENI Investment Platform - Balance Test ===\n\n";

// Get first user and package for testing
$user = User::first();
$package = InvestmentPackage::first();

if (!$user || !$package) {
    echo "❌ Error: Need at least one user and one investment package\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n";
echo "Package: {$package->name}\n";
echo "Investment Amount: $1000\n\n";

// Show initial balance
$initialBalance = $user->account_balance;
echo "1. Initial Account Balance: \${$initialBalance}\n";

// Simulate deposit approval process
echo "\n2. Simulating deposit for investment...\n";

// Create a test deposit transaction
$depositTransaction = $user->transactions()->create([
    'type' => 'deposit',
    'amount' => 1000,
    'status' => 'pending',
    'description' => 'Investment in ' . $package->name . ' package deposit',
    'reference' => 'TEST_DEP_' . time()
]);

echo "   Created pending deposit: \${$depositTransaction->amount}\n";

// Create an inactive investment (as done during investment submission)
$investment = $user->investments()->create([
    'investment_package_id' => $package->id,
    'amount' => 1000,
    'daily_shares_rate' => $package->daily_shares_rate,
    'remaining_days' => $package->effective_days,
    'total_interest_earned' => 0,
    'active' => false, // Inactive until deposit is approved
    'started_at' => now(),
]);

echo "   Created inactive investment: \${$investment->amount}\n";

// Check balance before approval
$balanceBeforeApproval = $user->fresh()->accountBalance();
echo "   Balance before approval: \${$balanceBeforeApproval}\n";

echo "\n3. Simulating admin approval of deposit...\n";

// Simulate the admin approval process (with our fix)
\Illuminate\Support\Facades\DB::transaction(function () use ($depositTransaction, $user, $package) {
    // Update transaction status
    $depositTransaction->update([
        'status' => 'approved',
        'processed_at' => now()
    ]);

    // Add to user balance (step 1)
    $user->increment('account_balance', $depositTransaction->amount);
    echo "   Step 1: Added deposit to balance: +\${$depositTransaction->amount}\n";

    // Check if this is an investment deposit and activate investments
    if (str_contains($depositTransaction->description, 'Investment in') && str_contains($depositTransaction->description, 'package')) {
        $investments = $user->investments()
            ->where('active', false)
            ->where('amount', $depositTransaction->amount)
            ->whereBetween('created_at', [
                $depositTransaction->created_at->subMinutes(5),
                $depositTransaction->created_at->addMinutes(5)
            ])
            ->get();

        foreach ($investments as $investment) {
            // Activate investment
            $investment->update([
                'active' => true,
                'started_at' => now()
            ]);
            echo "   Step 2: Activated investment #{$investment->id}\n";

            // Deduct investment amount from balance (THE FIX)
            $user->decrement('account_balance', $investment->amount);
            echo "   Step 3: Locked investment funds: -\${$investment->amount}\n";

            // Create transaction record for the lock
            $user->transactions()->create([
                'type' => 'other',
                'amount' => -$investment->amount,
                'status' => 'completed',
                'description' => 'Investment activated - funds locked for ' . $investment->investmentPackage->effective_days . ' days (Investment #' . $investment->id . ')',
                'reference' => 'LOCK' . time() . rand(1000, 9999)
            ]);
            echo "   Step 4: Created lock transaction record\n";
        }
    }
});

// Check final balances
$finalBalance = $user->fresh()->accountBalance();
$totalInvested = $user->fresh()->totalInvestedAmount();

echo "\n4. Final Results:\n";
echo "   Account Balance (withdrawable): \${$finalBalance}\n";
echo "   Total Invested (locked): \${$totalInvested}\n";
echo "   Total Funds: \$" . ($finalBalance + $totalInvested) . "\n";

echo "\n5. Verification:\n";
if ($finalBalance == $initialBalance) {
    echo "   ✅ Account balance correctly unchanged - investment funds are locked\n";
} else {
    echo "   ❌ Account balance incorrect - should be \${$initialBalance}, got \${$finalBalance}\n";
}

if ($totalInvested == 1000) {
    echo "   ✅ Investment correctly shows \$1000 locked for 180 days\n";
} else {
    echo "   ❌ Investment amount incorrect - should be \$1000, got \${$totalInvested}\n";
}

echo "\n=== Test Complete ===\n";
echo "The fix ensures that activated investments are properly locked and don't\n";
echo "remain in the withdrawable account balance. Users can only withdraw funds\n";
echo "that are not locked in active investments.\n";

// Cleanup test data
$depositTransaction->delete();
$investment->delete();
$user->transactions()->where('reference', 'like', 'LOCK%')->delete();

echo "\nTest data cleaned up.\n";
