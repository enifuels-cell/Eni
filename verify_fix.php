<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Investment;

echo "=== VERIFICATION: Test User Balances ===\n\n";

$user = User::where('name', 'Test User')->first();

if (!$user) {
    echo "❌ Test User not found!\n";
    exit(1);
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n\n";

// Count investments
$totalInvestments = Investment::where('user_id', $user->id)->count();
$activeInvestments = Investment::where('user_id', $user->id)->where('active', true)->count();
$inactiveInvestments = Investment::where('user_id', $user->id)->where('active', false)->count();

echo "=== Investment Summary ===\n";
echo "Total Investments: {$totalInvestments}\n";
echo "Active Investments: {$activeInvestments}\n";
echo "Inactive Investments: {$inactiveInvestments}\n\n";

// Calculate total invested using the model method
$totalInvested = $user->totalInvestedAmount();

echo "=== Dashboard Values ===\n";
$accountBalance = is_object($user->account_balance) ? $user->account_balance->toFloat() : $user->account_balance;
echo "Account Balance: \${$accountBalance}\n";
echo "Total Invested: \${$totalInvested}\n\n";

// Show active investments
if ($activeInvestments > 0) {
    echo "=== Active Investments Detail ===\n";
    $investments = Investment::where('user_id', $user->id)
        ->where('active', true)
        ->with('investmentPackage')
        ->get();

    foreach ($investments as $inv) {
        $amount = $inv->amount instanceof \App\Support\Money ? $inv->amount->toFloat() : (float)$inv->amount;
        echo "ID: {$inv->id} | \${$amount} | {$inv->investmentPackage->name} | Started: {$inv->start_date}\n";
    }

    echo "\n";
}

// Final status
if ($activeInvestments === 18 && $totalInvested === 3600.0) {
    echo "✅ SUCCESS! All 18 investments are active and Total Invested = \$3,600.00\n";
    echo "✅ The user should now see \$3,600.00 in their dashboard's 'Total Invested' section.\n";
} else {
    echo "⚠️ Warning: Expected 18 active investments with total of \$3,600\n";
    echo "   Found: {$activeInvestments} active investments with total of \${$totalInvested}\n";
}
