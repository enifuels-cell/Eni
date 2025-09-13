<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Investment;
use App\Models\InvestmentPackage;

echo "=== Investment Relationship Debug ===\n\n";

// Test 1: Check if Investment model has the correct relationship
echo "1. Testing Investment model relationships:\n";
$investment = Investment::with('investmentPackage')->first();

if ($investment) {
    echo "   ✅ Found investment ID: {$investment->id}\n";
    
    try {
        $package = $investment->investmentPackage;
        echo "   ✅ investmentPackage relationship works: {$package->name}\n";
    } catch (Exception $e) {
        echo "   ❌ investmentPackage relationship failed: {$e->getMessage()}\n";
    }
    
    // Test the old relationship name that was causing issues
    try {
        $package = $investment->package;
        echo "   ⚠️  WARNING: 'package' relationship still exists: {$package->name}\n";
    } catch (Exception $e) {
        echo "   ✅ 'package' relationship properly doesn't exist: {$e->getMessage()}\n";
    }
    
} else {
    echo "   ❌ No investments found in database\n";
}

echo "\n";

// Test 2: Check recent investments
echo "2. Recent investments (last 5):\n";
$recentInvestments = Investment::with('investmentPackage')->latest()->take(5)->get();

foreach ($recentInvestments as $inv) {
    echo "   ID: {$inv->id}, Amount: \${$inv->amount}, Package: {$inv->investmentPackage->name}, Created: {$inv->created_at}\n";
}

echo "\n";

// Test 3: Check relationship definitions
echo "3. Checking Model relationship definitions:\n";
$reflection = new ReflectionClass(Investment::class);
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

$relationshipMethods = [];
foreach ($methods as $method) {
    if (strpos($method->getName(), 'package') !== false) {
        $relationshipMethods[] = $method->getName();
    }
}

echo "   Found package-related methods: " . implode(', ', $relationshipMethods) . "\n";

echo "\n=== Debug Complete ===\n";
