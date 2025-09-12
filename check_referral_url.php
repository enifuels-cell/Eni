<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
echo "Current user referral code: " . $user->referral_code . "\n";
echo "Correct referral URL: " . route('register', ['ref' => $user->referral_code]) . "\n";
echo "Alternative URL: http://127.0.0.1:8000/register?ref=" . $user->referral_code . "\n";

// Check if you're visiting the correct URL
echo "\n=== URL Validation ===\n";
echo "Make sure you're visiting EXACTLY this URL:\n";
echo route('register', ['ref' => $user->referral_code]) . "\n";
echo "\nThe URL should contain: ?ref=" . $user->referral_code . "\n";

// Check referrals controller route
echo "\n=== Route Check ===\n";
try {
    $url = route('user.referrals');
    echo "Referrals page: " . $url . "\n";
} catch (Exception $e) {
    echo "Route error: " . $e->getMessage() . "\n";
}
