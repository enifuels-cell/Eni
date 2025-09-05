<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Transaction;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fix Duplicate Lock Transactions ===\n";

$user = User::where('email', 'dycinne@gmail.com')->first();

if ($user) {
    echo "Checking {$user->name}:\n";
    
    // Get all lock transactions
    $lockTransactions = $user->transactions()
        ->where('type', 'other')
        ->where('amount', '<', 0)
        ->orderBy('created_at', 'asc')
        ->get();
    
    echo "Current lock transactions:\n";
    foreach($lockTransactions as $trans) {
        echo "  - ID {$trans->id}: \${$trans->amount} - {$trans->description}\n";
    }
    
    // Delete the duplicate retroactive fix transactions
    $duplicates = $user->transactions()
        ->where('type', 'other')
        ->where('description', 'like', '%[Retroactive Fix]%')
        ->get();
    
    echo "\nRemoving duplicate retroactive fix transactions:\n";
    foreach($duplicates as $dup) {
        echo "  - Removing: {$dup->description}\n";
        $dup->delete();
    }
    
    // Now create the correct lock transaction only for Investment #2 (the deposit-approved one)
    $investment2 = $user->investments()->find(2);
    if ($investment2) {
        // Check if Investment #2 already has a proper lock transaction
        $hasLock = $user->transactions()
            ->where('type', 'other')
            ->where('amount', -$investment2->amount)
            ->where('description', 'like', '%Investment #2%')
            ->exists();
        
        if (!$hasLock) {
            echo "\nCreating correct lock transaction for Investment #2:\n";
            $user->transactions()->create([
                'type' => 'other',
                'amount' => -$investment2->amount,
                'status' => 'completed',
                'description' => 'Investment activated - funds locked for ' . $investment2->investmentPackage->effective_days . ' days (Investment #' . $investment2->id . ')',
                'reference' => 'LOCK' . time() . rand(1000, 9999),
                'created_at' => $investment2->started_at ?: $investment2->created_at,
                'updated_at' => now()
            ]);
            echo "  ✅ Correct lock transaction created for Investment #2\n";
        }
    }
    
    echo "\n=== Final State ===\n";
    echo "Raw balance: \${$user->fresh()->account_balance}\n";
    echo "Calculated balance: \$" . number_format($user->fresh()->accountBalance(), 2) . "\n";
    echo "Total invested: \$" . number_format($user->fresh()->totalInvestedAmount(), 2) . "\n";
    
    $finalLocks = $user->fresh()->transactions()
        ->where('type', 'other')
        ->where('amount', '<', 0)
        ->get();
    
    echo "Final lock transactions (" . $finalLocks->count() . "):\n";
    foreach($finalLocks as $trans) {
        echo "  - \${$trans->amount} - {$trans->description}\n";
    }
}
