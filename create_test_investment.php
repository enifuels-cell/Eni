<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Creating Test Investment for Activation Command ===\n\n";

// Find a user for testing
$user = \App\Models\User::where('email', 'dycinne@gmail.com')->first();
if (!$user) {
    echo "❌ Test user not found\n";
    exit;
}

// Find a package
$package = \App\Models\InvestmentPackage::where('name', 'Growth Power')->first();
if (!$package) {
    echo "❌ Growth Power package not found\n";
    exit;
}

// Create a test investment
$investment = \App\Models\Investment::create([
    'user_id' => $user->id,
    'investment_package_id' => $package->id,
    'amount' => $package->min_amount,
    'daily_shares_rate' => $package->daily_shares_rate,
    'remaining_days' => $package->effective_days,
    'total_interest_earned' => 0,
    'active' => false,
    'started_at' => now(),
    'ended_at' => null,
]);

// Create a corresponding transaction
$transaction = \App\Models\Transaction::create([
    'user_id' => $user->id,
    'type' => 'deposit',
    'amount' => $investment->amount,
    'reference' => 'Test deposit for investment #' . $investment->id,
    'status' => 'pending',
    'description' => 'Investment in ' . $package->name . ' package',
]);

echo "✅ Created test investment and transaction:\n";
echo "  - User: {$user->name} ({$user->email})\n";
echo "  - Investment ID: {$investment->id}\n";
echo "  - Transaction ID: {$transaction->id}\n";
echo "  - Package: {$package->name}\n";
echo "  - Amount: $" . number_format($investment->amount, 2) . "\n";
echo "  - Investment Status: " . ($investment->active ? 'Active' : 'Inactive') . "\n";
echo "  - Transaction Status: {$transaction->status}\n\n";

echo "Commands to test:\n";
echo "  1. Activate by user ID: php artisan investments:activate --user-id={$user->id}\n";
echo "  2. Activate by transaction ID: php artisan investments:activate --transaction-id={$transaction->id}\n";
echo "  3. Activate all: php artisan investments:activate --all\n";
