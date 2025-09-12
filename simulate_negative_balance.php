<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Transaction;
use App\Models\Investment;
use App\Models\InvestmentPackage;

echo "=== Recreating the Negative Balance Scenario ===\n";

// Get or create a user
$user = User::first();
if (!$user) {
    echo "Creating test user...\n";
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@eni.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
        'account_balance' => 0
    ]);
}

// Get investment packages
$packages = InvestmentPackage::all();
if ($packages->count() == 0) {
    echo "No investment packages found. Please run the seeders first.\n";
    exit(1);
}

$package = $packages->first();

echo "User: {$user->name}\n";
echo "Starting balance: \${$user->account_balance}\n\n";

echo "=== Scenario: Investment without proper deposit approval ===\n";

// Create a pending deposit
$deposit = $user->transactions()->create([
    'type' => 'deposit',
    'amount' => 200,
    'status' => 'pending',
    'description' => 'Investment in ' . $package->name . ' package deposit',
    'reference' => 'DEP_' . time()
]);
echo "1. Created pending deposit: \${$deposit->amount}\n";

// Create an investment (this should normally wait for deposit approval)
$investment = $user->investments()->create([
    'investment_package_id' => $package->id,
    'amount' => 200,
    'daily_shares_rate' => $package->daily_shares_rate,
    'remaining_days' => $package->effective_days,
    'total_interest_earned' => 0,
    'active' => false,
    'started_at' => now(),
]);
echo "2. Created inactive investment: \${$investment->amount}\n";

// Simulate a bug where investment gets activated without deposit approval
echo "\n=== BUG SIMULATION: Investment activated without deposit approval ===\n";

$investment->update(['active' => true]);
echo "3. Investment activated (BUG: without deposit approval)\n";

// The system tries to lock funds (deduct from balance)
$user->decrement('account_balance', $investment->amount);
echo "4. Deducted investment amount from balance: -\${$investment->amount}\n";

// Create lock transaction
$user->transactions()->create([
    'type' => 'other',
    'amount' => -$investment->amount,
    'status' => 'completed',
    'description' => 'Investment activated - funds locked for ' . $package->effective_days . ' days (Investment #' . $investment->id . ')',
    'reference' => 'LOCK' . time()
]);
echo "5. Created lock transaction: -\${$investment->amount}\n";

// Now show the result
$user = $user->fresh();
echo "\n=== RESULT (This causes negative balance) ===\n";
echo "Account Balance: \${$user->account_balance}\n";
echo "Total Invested: \$" . $user->totalInvestedAmount() . "\n";
echo "Calculated Balance: \$" . $user->accountBalance() . "\n";

if ($user->account_balance < 0) {
    echo "\n❌ NEGATIVE BALANCE CREATED!\n";
    echo "CAUSE: Investment was activated and funds locked BEFORE deposit was approved.\n";
    echo "SOLUTION: Always approve deposits BEFORE activating investments.\n";
}

echo "\n=== How this should be fixed ===\n";
echo "1. Approve the pending deposit first\n";
echo "2. Add deposit amount to user balance\n";
echo "3. THEN activate the investment and lock funds\n";

// Simulate the fix
echo "\n=== APPLYING FIX ===\n";

// Approve the deposit
$deposit->update(['status' => 'approved']);
$user->increment('account_balance', $deposit->amount);
echo "6. Approved deposit and added to balance: +\${$deposit->amount}\n";

$user = $user->fresh();
echo "Final Balance: \${$user->account_balance}\n";
echo "This should now be \$0 (deposit \$200 - investment lock \$200 = \$0)\n";

// Clean up
echo "\n=== Cleaning up test data ===\n";
Transaction::where('user_id', $user->id)->delete();
Investment::where('user_id', $user->id)->delete();
$user->update(['account_balance' => 0]);
echo "Test data cleaned up.\n";
