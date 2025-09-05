<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\InvestmentPackage;
use App\Models\User;

echo "Testing Investment System Components:\n";
echo "=====================================\n";

// Test 1: Check if packages exist
$packages = InvestmentPackage::all();
echo "Investment Packages: " . $packages->count() . "\n";

// Test 2: Check if user exists
$user = User::first();
if ($user) {
    echo "Test User: " . $user->email . "\n";
    echo "User Account Balance Method: " . (method_exists($user, 'accountBalance') ? 'EXISTS' : 'MISSING') . "\n";
    
    try {
        $balance = $user->accountBalance();
        echo "Account Balance: $" . number_format($balance, 2) . "\n";
    } catch (Exception $e) {
        echo "Account Balance Error: " . $e->getMessage() . "\n";
    }
    
    // Test 3: Check if user can create transactions
    echo "User Transactions Relationship: " . (method_exists($user, 'transactions') ? 'EXISTS' : 'MISSING') . "\n";
    echo "User Investments Relationship: " . (method_exists($user, 'investments') ? 'EXISTS' : 'MISSING') . "\n";
} else {
    echo "No users found in database\n";
}

// Test 4: Check if database tables exist
try {
    $transactionCount = \DB::table('transactions')->count();
    echo "Transactions table: OK ($transactionCount records)\n";
} catch (Exception $e) {
    echo "Transactions table: ERROR - " . $e->getMessage() . "\n";
}

try {
    $investmentCount = \DB::table('investments')->count();
    echo "Investments table: OK ($investmentCount records)\n";
} catch (Exception $e) {
    echo "Investments table: ERROR - " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
