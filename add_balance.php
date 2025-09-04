<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== Adding Account Balance to Users ===\n";

$users = User::all();

foreach ($users as $user) {
    // Give different amounts based on role
    $balance = $user->role === 'admin' ? 50000.00 : 10000.00;
    
    $user->update(['account_balance' => $balance]);
    
    echo "✓ {$user->name} ({$user->role}) - Balance set to \${$balance}\n";
}

echo "\n=== Updated User Balances ===\n";
foreach (User::all() as $user) {
    echo "- {$user->name} | {$user->email} | Balance: \$" . number_format($user->account_balance, 2) . "\n";
}
