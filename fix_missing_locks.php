<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Investment;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fix Missing Lock Transactions ===\n";

// Find all users with active investments that don't have corresponding lock transactions
$users = User::whereHas('investments', function($query) {
    $query->where('active', true);
})->get();

foreach($users as $user) {
    $activeInvestments = $user->investments()->active()->get();
    $lockTransactions = $user->transactions()
        ->where('type', 'other')
        ->where('amount', '<', 0)
        ->where('description', 'like', '%Investment activated - funds locked%')
        ->get();
    
    echo "\nChecking {$user->name} ({$user->email}):\n";
    echo "Active investments: {$activeInvestments->count()}\n";
    echo "Lock transactions: {$lockTransactions->count()}\n";
    
    if ($activeInvestments->count() > $lockTransactions->count()) {
        echo "Creating missing lock transactions...\n";
        
        foreach($activeInvestments as $investment) {
            // Check if this investment already has a lock transaction
            $hasLockTransaction = $user->transactions()
                ->where('type', 'other')
                ->where('amount', -$investment->amount)
                ->where('description', 'like', '%Investment #' . $investment->id . '%')
                ->exists();
            
            if (!$hasLockTransaction) {
                echo "  Creating lock transaction for Investment #{$investment->id} (\${$investment->amount})\n";
                
                // Create the missing lock transaction
                $user->transactions()->create([
                    'type' => 'other',
                    'amount' => -$investment->amount,
                    'status' => 'completed',
                    'description' => 'Investment activated - funds locked for ' . $investment->investmentPackage->effective_days . ' days (Investment #' . $investment->id . ') [Retroactive Fix]',
                    'reference' => 'LOCK_FIX_' . time() . rand(1000, 9999),
                    'created_at' => $investment->started_at ?: $investment->created_at,
                    'updated_at' => now()
                ]);
                
                echo "  ✅ Lock transaction created\n";
            }
        }
    }
}

echo "\n=== Verification ===\n";

// Verify the fix
foreach($users as $user) {
    if ($user->investments()->active()->count() > 0) {
        echo "\n{$user->name}:\n";
        echo "  Raw balance: \${$user->account_balance}\n";
        echo "  Calculated balance: \$" . number_format($user->accountBalance(), 2) . "\n";
        echo "  Total invested: \$" . number_format($user->totalInvestedAmount(), 2) . "\n";
        
        $expectedBalance = $user->account_balance - $user->totalInvestedAmount();
        $actualBalance = $user->accountBalance();
        
        if (abs($expectedBalance - $actualBalance) < 0.01) {
            echo "  ✅ Balance calculation is now correct\n";
        } else {
            echo "  ❌ Balance calculation still incorrect\n";
        }
    }
}

echo "\nFix completed!\n";
