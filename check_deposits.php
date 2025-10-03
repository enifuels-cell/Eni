<?php

use App\Models\Transaction;
use App\Models\Investment;

$transactions = Transaction::where('type', 'deposit')
    ->where('status', 'approved')
    ->with('user')
    ->get();

echo "Approved Deposits:\n";
echo "==================\n\n";

foreach ($transactions as $t) {
    echo "ID: {$t->id}\n";
    echo "User: {$t->user->name} (ID: {$t->user_id})\n";
    echo "Description: {$t->description}\n";
    echo "Amount: {$t->amount}\n";
    echo "Created: {$t->created_at}\n";
    echo "Processed: {$t->processed_at}\n";

    // Check for matching investments
    $investments = Investment::where('user_id', $t->user_id)
        ->whereBetween('created_at', [
            $t->created_at->copy()->subMinutes(10),
            $t->created_at->copy()->addMinutes(10)
        ])
        ->get();

    echo "Matching investments: {$investments->count()}\n";
    foreach ($investments as $inv) {
        $amount = $inv->amount instanceof \App\Support\Money ? $inv->amount->toFloat() : (float) $inv->amount;
        echo "  - Investment #{$inv->id}: \${$amount}, Active: " . ($inv->active ? 'Yes' : 'No') . "\n";
    }

    echo "\n";
}
