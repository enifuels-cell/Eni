<?php

require_once 'vendor/autoload.php';

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "🧪 Testing Username-Based Referral System\n";
echo "==========================================\n\n";

// Test existing users
$testUser = User::where('email', 'test@example.com')->first();
if ($testUser) {
    echo "📋 Test User Details:\n";
    echo "  Name: {$testUser->name}\n";
    echo "  Email: {$testUser->email}\n"; 
    echo "  Username: {$testUser->username}\n";
    echo "  Referral Code: {$testUser->referral_code}\n";
    echo "\n";
    
    echo "🔗 Referral Links:\n";
    echo "  Username-based (NEW): " . url('/register?ref=' . $testUser->username) . "\n";
    echo "  Code-based (OLD): " . url('/register?ref=' . $testUser->referral_code) . "\n";
    echo "  ID-based (LEGACY): " . url('/register?ref=' . $testUser->id) . "\n";
    echo "\n";
}

// Test referrer lookup logic
echo "🔍 Testing Referrer Lookup Logic:\n";
echo "==================================\n";

function testReferrerLookup($param, $description) {
    echo "\nTesting: {$description} ('{$param}')\n";
    
    // Simulate the controller logic
    $referrer = null;
    
    // First try username
    $referrer = User::where('username', $param)->first();
    if ($referrer) {
        echo "✅ Found by username: {$referrer->name} (username: {$referrer->username})\n";
        return;
    }
    
    // Then try referral code
    $referrer = User::where('referral_code', $param)->first();
    if ($referrer) {
        echo "✅ Found by referral_code: {$referrer->name} (code: {$referrer->referral_code})\n";
        return;
    }
    
    // Finally try user ID
    if (is_numeric($param)) {
        $referrer = User::find($param);
        if ($referrer) {
            echo "✅ Found by user ID: {$referrer->name} (ID: {$referrer->id})\n";
            return;
        }
    }
    
    echo "❌ No referrer found\n";
}

// Test various referral parameters
testReferrerLookup('test', 'Username lookup');
testReferrerLookup('admin', 'Another username lookup');
testReferrerLookup('CC1YEOFB', 'Referral code lookup');
testReferrerLookup('WZQZHQTO', 'Another referral code lookup');
testReferrerLookup('1', 'User ID lookup');
testReferrerLookup('2', 'Another user ID lookup');
testReferrerLookup('nonexistent', 'Non-existent username');
testReferrerLookup('INVALID123', 'Invalid referral code');

echo "\n✨ All tests completed!\n";

?>
