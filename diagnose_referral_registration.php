<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Referral;

echo "=== Referral Registration Diagnosis ===\n\n";

// Check if we have users with referral codes
$users = User::whereNotNull('referral_code')->get();
echo "Users with referral codes: " . $users->count() . "\n\n";

if ($users->count() == 0) {
    echo "❌ No users have referral codes assigned!\n";
    echo "This is likely the problem. Let me fix this...\n\n";
    
    // Assign referral codes to existing users
    $allUsers = User::all();
    foreach($allUsers as $user) {
        if (empty($user->referral_code)) {
            $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
            
            // Ensure uniqueness
            while (User::where('referral_code', $user->referral_code)->where('id', '!=', $user->id)->exists()) {
                $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
            }
            
            $user->save();
            echo "✅ Assigned referral code '{$user->referral_code}' to {$user->name}\n";
        }
    }
    
    $users = User::whereNotNull('referral_code')->get();
    echo "\nNow " . $users->count() . " users have referral codes.\n\n";
}

foreach($users as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    echo "  Referral Code: {$user->referral_code}\n";
    echo "  Registration URL: " . url('/register?ref=' . $user->referral_code) . "\n";
    echo "  Alternative URL: " . url('/register?ref=' . $user->id) . "\n\n";
}

// Check the registration controller logic
echo "=== Testing Registration Controller Logic ===\n";

$testUser = $users->first();
if ($testUser) {
    echo "Testing with user: {$testUser->name} (Code: {$testUser->referral_code})\n";
    
    // Test both referral code formats
    $formats = [
        'By referral_code' => $testUser->referral_code,
        'By user ID' => $testUser->id
    ];
    
    foreach($formats as $type => $code) {
        echo "\n{$type}: {$code}\n";
        
        // Check if we can find the referrer
        $referrer1 = User::where('referral_code', $code)->first();
        $referrer2 = User::find($code);
        
        echo "  Looking for referrer with referral_code = '{$code}': " . ($referrer1 ? "Found ({$referrer1->name})" : "Not found") . "\n";
        echo "  Looking for referrer with id = '{$code}': " . ($referrer2 ? "Found ({$referrer2->name})" : "Not found") . "\n";
    }
}

// Check the actual registration process
echo "\n=== Simulating Registration Process ===\n";

if ($testUser) {
    echo "Simulating registration with referral code: {$testUser->referral_code}\n";
    
    // This is what happens in RegisteredUserController
    $referralCode = $testUser->referral_code;
    $referrer = User::where('referral_code', $referralCode)->first();
    
    if ($referrer) {
        echo "✅ Referrer found: {$referrer->name}\n";
        echo "  - This registration would create a referral relationship\n";
    } else {
        echo "❌ Referrer not found with code: {$referralCode}\n";
        echo "  - This is why referral registration fails\n";
    }
}

// Check existing referrals
echo "\n=== Existing Referrals Check ===\n";
$referrals = Referral::with(['referrer', 'referee'])->get();
echo "Total referrals in database: " . $referrals->count() . "\n";

foreach($referrals as $referral) {
    echo "- Referrer: {$referral->referrer->name} (Code: {$referral->referrer->referral_code})\n";
    echo "  Referee: {$referral->referee->name}\n";
    echo "  Used code: {$referral->referral_code}\n\n";
}

echo "=== Diagnosis Complete ===\n";
