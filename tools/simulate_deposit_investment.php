<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\InvestmentPackage;

$user = User::first();
$package = InvestmentPackage::first();

if (!$user || !$package) {
    echo "No user or package found\n";
    exit(1);
}

try {
    $transaction = $user->transactions()->create([
        'type' => 'deposit',
        'amount' => 200,
        'reference' => 'Deposit via bank_transfer (BPI)',
        'status' => 'pending',
        'description' => 'Investment in ' . $package->name . ' package',
    ]);

    echo "Transaction created: {$transaction->id}\n";

    $investment = $user->investments()->create([
        'investment_package_id' => $package->id,
        'amount' => 200,
        'daily_shares_rate' => $package->daily_shares_rate,
        'remaining_days' => $package->effective_days,
        'total_interest_earned' => 0,
        'active' => false,
        'started_at' => null,
        'ended_at' => null,
    ]);

    echo "Investment created: {$investment->id}, started_at: {$investment->started_at}\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
