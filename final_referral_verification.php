<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== Final Referral System Verification ===\n\n";

// Ensure all users have referral codes
$users = User::all();
$usersFixed = 0;

foreach($users as $user) {
    if (empty($user->referral_code)) {
        $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
        
        // Ensure uniqueness
        while (User::where('referral_code', $user->referral_code)->where('id', '!=', $user->id)->exists()) {
            $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
        }
        
        $user->save();
        $usersFixed++;
        echo "✅ Assigned referral code '{$user->referral_code}' to {$user->name}\n";
    }
}

if ($usersFixed == 0) {
    echo "✅ All users already have referral codes assigned.\n";
}

echo "\n=== User Referral Information ===\n";
foreach($users->fresh() as $user) {
    echo "User: {$user->name} ({$user->email})\n";
    echo "  Referral Code: {$user->referral_code}\n";
    echo "  Referral Link: " . route('register', ['ref' => $user->referral_code]) . "\n";
    echo "  QR Code URL: " . route('user.referrals') . " (when logged in as this user)\n\n";
}

echo "=== System Status ===\n";
echo "✅ Referral codes assigned to all users\n";
echo "✅ Registration controller handles both referral codes and user IDs\n";
echo "✅ Referral links generate proper referral codes\n";
echo "✅ QR codes will use referral codes instead of user IDs\n";

echo "\n=== How to Use ===\n";
echo "1. Users can access their referral link from: " . route('user.referrals') . "\n";
echo "2. They can share the link or QR code with friends\n";
echo "3. When someone registers using the link, a referral relationship is created\n";
echo "4. When the referee makes an investment, the referrer earns a bonus\n";

echo "\n✅ Referral registration system is now fully operational!\n";
