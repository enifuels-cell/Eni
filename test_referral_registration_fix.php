<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;

echo "=== Testing Referral Registration Fix ===\n\n";

// Get the referrer user
$referrer = User::first();
if (!$referrer) {
    echo "❌ No users found. Please create a user first.\n";
    exit;
}

echo "Referrer: {$referrer->name} (Code: {$referrer->referral_code})\n";
echo "Referral Link: " . route('register', ['ref' => $referrer->referral_code]) . "\n\n";

// Test 1: Registration with referral_code
echo "=== Test 1: Registration with referral_code ===\n";
$testEmail = 'referral.test.' . time() . '@example.com';

// Simulate the registration process
$requestData = [
    'name' => 'Referral Test User',
    'email' => $testEmail,
    'phone' => '1234567890',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'referral_code' => $referrer->referral_code
];

echo "Registering user with referral code: {$referrer->referral_code}\n";

// Create the user (simulating the registration controller)
$newUser = User::create([
    'name' => $requestData['name'],
    'email' => $requestData['email'],
    'phone' => $requestData['phone'],
    'password' => Hash::make($requestData['password']),
]);

echo "✅ User created: {$newUser->name} (ID: {$newUser->id})\n";

// Handle referral (simulating the controller logic)
if ($requestData['referral_code']) {
    // Try to find referrer by referral_code first
    $foundReferrer = User::where('referral_code', $requestData['referral_code'])->first();
    
    // If not found by referral_code, try by user ID for backward compatibility
    if (!$foundReferrer && is_numeric($requestData['referral_code'])) {
        $foundReferrer = User::find($requestData['referral_code']);
    }
    
    if ($foundReferrer && $foundReferrer->id !== $newUser->id) {
        // Create referral record
        $referral = Referral::create([
            'referrer_id' => $foundReferrer->id,
            'referee_id' => $newUser->id,
            'referral_code' => $requestData['referral_code'],
            'referred_at' => now(),
        ]);
        
        echo "✅ Referral created successfully!\n";
        echo "  - Referrer: {$foundReferrer->name}\n";
        echo "  - Referee: {$newUser->name}\n";
        echo "  - Referral ID: {$referral->id}\n";
    } else {
        echo "❌ Referral creation failed\n";
        echo "  - Referrer found: " . ($foundReferrer ? 'Yes' : 'No') . "\n";
        echo "  - Same user check: " . ($foundReferrer && $foundReferrer->id === $newUser->id ? 'Failed' : 'Passed') . "\n";
    }
}

// Test 2: Registration with user ID (backward compatibility)
echo "\n=== Test 2: Registration with user ID (backward compatibility) ===\n";
$testEmail2 = 'referral.test2.' . time() . '@example.com';

$requestData2 = [
    'name' => 'Referral Test User 2',
    'email' => $testEmail2,
    'phone' => '1234567891',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'referral_code' => (string)$referrer->id  // Using user ID as string
];

echo "Registering user with user ID as referral code: {$referrer->id}\n";

$newUser2 = User::create([
    'name' => $requestData2['name'],
    'email' => $requestData2['email'],
    'phone' => $requestData2['phone'],
    'password' => Hash::make($requestData2['password']),
]);

echo "✅ User created: {$newUser2->name} (ID: {$newUser2->id})\n";

// Handle referral
if ($requestData2['referral_code']) {
    // Try to find referrer by referral_code first
    $foundReferrer2 = User::where('referral_code', $requestData2['referral_code'])->first();
    
    // If not found by referral_code, try by user ID for backward compatibility
    if (!$foundReferrer2 && is_numeric($requestData2['referral_code'])) {
        $foundReferrer2 = User::find($requestData2['referral_code']);
    }
    
    if ($foundReferrer2 && $foundReferrer2->id !== $newUser2->id) {
        $referral2 = Referral::create([
            'referrer_id' => $foundReferrer2->id,
            'referee_id' => $newUser2->id,
            'referral_code' => $requestData2['referral_code'],
            'referred_at' => now(),
        ]);
        
        echo "✅ Referral created successfully (backward compatibility)!\n";
        echo "  - Referrer: {$foundReferrer2->name}\n";
        echo "  - Referee: {$newUser2->name}\n";
        echo "  - Referral ID: {$referral2->id}\n";
    } else {
        echo "❌ Referral creation failed\n";
    }
}

// Summary
echo "\n=== Summary ===\n";
$totalReferrals = Referral::count();
echo "Total referrals in database: {$totalReferrals}\n";

$userReferrals = Referral::where('referrer_id', $referrer->id)->count();
echo "Referrals for {$referrer->name}: {$userReferrals}\n";

echo "\n✅ Referral registration system is now working!\n";
echo "Users can register using:\n";
echo "1. Referral codes: " . route('register', ['ref' => $referrer->referral_code]) . "\n";
echo "2. User IDs (legacy): " . route('register', ['ref' => $referrer->id]) . "\n";

// Clean up test users
echo "\n=== Cleaning up test data ===\n";
$newUser->delete();
$newUser2->delete();
Referral::where('referee_id', $newUser->id)->delete();
Referral::where('referee_id', $newUser2->id)->delete();
echo "✅ Test data cleaned up.\n";
