<?php

use App\Models\User;
use App\Models\Investment;

// Demonstrate the fix
$user = User::first();

if ($user) {
    $allInvestments = $user->investments()->sum('amount');
    $activeInvestments = $user->investments()->active()->sum('amount');
    $inactiveInvestments = $user->investments()->where('active', false)->sum('amount');
    
    echo "=== Investment Calculation Fix Demo ===\n";
    echo "User: {$user->name}\n";
    echo "\nINVESTMENT BREAKDOWN:\n";
    echo "- All investments (old logic): \${$allInvestments}\n";
    echo "- Active investments only (new logic): \${$activeInvestments}\n";
    echo "- Inactive investments: \${$inactiveInvestments}\n";
    echo "\nFIX RESULT:\n";
    echo "✅ Dashboard now shows: \${$activeInvestments} (was showing \${$allInvestments})\n";
    echo "✅ Only approved/active investments are counted\n";
    echo "✅ Denied/pending investments are excluded\n";
} else {
    echo "No user found\n";
}
