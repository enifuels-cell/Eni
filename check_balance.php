<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Investment;

echo "=== User Balance Check ===\n\n";

$user = User::where('name', 'Test User')->first();

if (!$user) {
    echo "Test User not found!\n";
    exit(1);
}

$activeInvestments = Investment::where('user_id', $user->id)
    ->where('active', true)
    ->get();

echo "User: {$user->name} (ID: {$user->id})\n";
echo "Email: {$user->email}\n\n";

$accountBalance = is_object($user->account_balance) ? $user->account_balance->toFloat() : $user->account_balance;
$totalInvestment = is_object($user->total_investment) ? $user->total_investment->toFloat() : $user->total_investment;

echo "Account Balance: \${$accountBalance}\n";
echo "Total Investment: \${$totalInvestment}\n";
echo "Active Investments Count: {$activeInvestments->count()}\n\n";

echo "=== Active Investments ===\n";
foreach ($activeInvestments as $inv) {
    echo "ID: {$inv->id} | Amount: \${$inv->amount->toFloat()} | Package: {$inv->investmentPackage->name}\n";
}

echo "\n=== Expected Total ===\n";
$expectedTotal = $activeInvestments->sum(fn($inv) => $inv->amount->toFloat());
echo "Sum of all active investments: \${$expectedTotal}\n";
echo "User's total_investment field: \${$totalInvestment}\n";

if (abs($expectedTotal - $totalInvestment) < 0.01) {
    echo "✅ Balances match!\n";
} else {
    echo "❌ Mismatch! Updating user's total_investment...\n";
    $user->total_investment = $expectedTotal;
    $user->save();
    echo "✅ Updated to \${$expectedTotal}\n";
}
