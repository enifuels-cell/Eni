<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Investment;
use App\Models\Transaction;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fixing Investment Lock Transactions ===\n\n";

// Find all active investments that don't have corresponding lock transactions
$activeInvestments = Investment::with(['user', 'investmentPackage'])
    ->where('active', true)
    ->get();

$fixedCount = 0;
$alreadyLockedCount = 0;

foreach ($activeInvestments as $investment) {
    // Check if this investment already has a lock transaction
    $existingLock = $investment->user->transactions()
        ->where('type', 'other')
        ->where('amount', -$investment->amount)
        ->where('description', 'like', '%Investment activated - funds locked%Investment #' . $investment->id . '%')
        ->exists();

    if ($existingLock) {
        $alreadyLockedCount++;
        echo "✓ Investment #{$investment->id} for {$investment->user->name} already has lock transaction\n";
        continue;
    }

    // Check if this investment was created via transfer (these already have lock transactions)
    $transferLock = $investment->user->transactions()
        ->where('type', 'other')
        ->where('amount', -$investment->amount)
        ->where('description', 'like', '%Investment in ' . $investment->investmentPackage->name . ' (funded by transfer%')
        ->exists();

    if ($transferLock) {
        $alreadyLockedCount++;
        echo "✓ Investment #{$investment->id} for {$investment->user->name} is from transfer (already locked)\n";
        continue;
    }

    // This investment needs a lock transaction
    echo "🔧 Fixing Investment #{$investment->id} for {$investment->user->name} (\${$investment->amount})\n";

    // Check if user has sufficient raw balance to lock
    if ($investment->user->account_balance < $investment->amount) {
        echo "   ⚠️  Warning: User balance (\${$investment->user->account_balance}) is less than investment (\${$investment->amount})\n";
        echo "   This might indicate the user has withdrawn funds that should have been locked.\n";
        echo "   Proceeding anyway to maintain data integrity...\n";
    }

    // Create the lock transaction
    $lockTransaction = $investment->user->transactions()->create([
        'type' => 'other',
        'amount' => -$investment->amount,
        'status' => 'completed',
        'description' => 'Investment activated - funds locked for ' . $investment->investmentPackage->effective_days . ' days (Investment #' . $investment->id . ') [Retroactive Fix]',
        'reference' => 'RETROFIX' . time() . rand(1000, 9999),
        'created_at' => $investment->started_at ?? $investment->created_at,
        'updated_at' => now()
    ]);

    // Deduct from user's raw balance
    $investment->user->decrement('account_balance', $investment->amount);

    echo "   ✅ Created lock transaction #{$lockTransaction->id}\n";
    echo "   ✅ Deducted \${$investment->amount} from user balance\n";
    
    $fixedCount++;
}

echo "\n=== Summary ===\n";
echo "Fixed investments: {$fixedCount}\n";
echo "Already locked: {$alreadyLockedCount}\n";
echo "Total processed: " . ($fixedCount + $alreadyLockedCount) . "\n";

if ($fixedCount > 0) {
    echo "\n✅ Data fix completed! Investment funds are now properly locked.\n";
    echo "Users will now see correct available balances in their dashboard.\n";
} else {
    echo "\n✅ No investments needed fixing. All data is already correct.\n";
}

echo "\n=== Verification ===\n";
// Show updated balances for users with investments
$usersWithInvestments = User::whereHas('investments', function($q) {
    $q->where('active', true);
})->get();

foreach ($usersWithInvestments as $user) {
    $availableBalance = $user->accountBalance();
    $totalInvested = $user->totalInvestedAmount();
    echo "User: {$user->name}\n";
    echo "  Available Balance: \$" . number_format($availableBalance, 2) . "\n";
    echo "  Total Invested: \$" . number_format($totalInvested, 2) . "\n";
    echo "  Total Value: \$" . number_format($availableBalance + $totalInvested, 2) . "\n\n";
}
