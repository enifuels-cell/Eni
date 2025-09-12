<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== All Users and Their Balances ===\n";
$users = User::all();
foreach($users as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    echo "  Raw Balance: \${$user->account_balance}\n";
    echo "  Calculated Balance: \$" . number_format($user->accountBalance(), 2) . "\n";
    echo "  Total Invested: \$" . number_format($user->totalInvestedAmount(), 2) . "\n";
    echo "  Transaction Count: " . $user->transactions()->count() . "\n\n";
}

// Find user with negative balance
$negativeBalanceUser = User::where('account_balance', '<', 0)->first();
if ($negativeBalanceUser) {
    echo "=== NEGATIVE BALANCE USER FOUND ===\n";
    echo "Analyzing: {$negativeBalanceUser->name} ({$negativeBalanceUser->email})\n\n";
    
    // Update the analyze script to use this specific user
    $content = file_get_contents('analyze_negative_balance.php');
    $content = str_replace('$user = User::first();', '$user = User::where("account_balance", "<", 0)->first();', $content);
    file_put_contents('analyze_negative_balance.php', $content);
    
    echo "Updated analysis script to target the correct user.\n";
    echo "Run: php analyze_negative_balance.php\n";
}
