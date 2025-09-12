<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Debug Registration Form Referral Code ===\n\n";

// Simulate the registration controller's create method
function simulateRegistrationCreate($refParam) {
    echo "Simulating registration page with ref parameter: '{$refParam}'\n";
    
    $referralCode = $refParam; // This is what the controller does: $request->get('ref')
    
    echo "Referral code passed to view: " . ($referralCode ?: 'null') . "\n";
    
    // This is what would be in the form field
    $formValue = old('referral_code') ?? $referralCode ?? '';
    echo "Form field value would be: '{$formValue}'\n";
    
    // Check if the referral code exists in database
    if ($referralCode) {
        $user = \App\Models\User::where('referral_code', $referralCode)->first();
        if ($user) {
            echo "✅ Referrer found: {$user->name}\n";
        } else {
            echo "❌ No user found with referral code: {$referralCode}\n";
            
            // Try by ID for backward compatibility
            if (is_numeric($referralCode)) {
                $userById = \App\Models\User::find($referralCode);
                if ($userById) {
                    echo "✅ Referrer found by ID: {$userById->name}\n";
                }
            }
        }
    }
    
    return $referralCode;
}

// Test with different scenarios
$testCases = [
    'Valid referral code' => 'KJOS0AJ3',
    'User ID' => '1', 
    'Invalid code' => 'INVALID123',
    'Empty parameter' => '',
    'Null parameter' => null
];

foreach($testCases as $scenario => $refParam) {
    echo "\n=== Testing: {$scenario} ===\n";
    simulateRegistrationCreate($refParam);
}

// Test the actual URL
echo "\n=== Testing Actual URL ===\n";
$user = \App\Models\User::first();
if ($user) {
    $testUrl = route('register', ['ref' => $user->referral_code]);
    echo "Test URL: {$testUrl}\n";
    echo "Expected form value: {$user->referral_code}\n";
    
    // Show what the browser should display
    echo "\nWhat you should see in the browser:\n";
    echo "1. Visit: {$testUrl}\n";
    echo "2. The 'Referral Code (Optional)' field should contain: {$user->referral_code}\n";
    echo "3. You should see the message: '✓ You were referred by a friend! You'll both earn bonuses.'\n";
}

echo "\n=== Troubleshooting Steps ===\n";
echo "1. Clear browser cache and cookies\n";
echo "2. Try the URL in an incognito/private window\n";
echo "3. Check browser console for JavaScript errors\n";
echo "4. Verify the URL has the ?ref= parameter\n";
echo "5. Check if the Laravel session is working properly\n";
