<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== Referral Link Test Instructions ===\n\n";

$user = User::first();
if (!$user) {
    echo "❌ No users found\n";
    exit;
}

echo "Test User: {$user->name}\n";
echo "Referral Code: {$user->referral_code}\n\n";

echo "=== TESTING STEPS ===\n";
echo "1. Make sure your Laravel server is running:\n";
echo "   php artisan serve\n\n";

echo "2. Open this URL in your browser:\n";
echo "   " . route('register', ['ref' => $user->referral_code]) . "\n\n";

echo "3. Check the following:\n";
echo "   ✓ The URL should show ?ref={$user->referral_code}\n";
echo "   ✓ The 'Referral Code' field should contain: {$user->referral_code}\n";
echo "   ✓ You should see: '✓ You were referred by a friend!' message\n";
echo "   ✓ Check browser console (F12) for debug messages\n\n";

echo "4. If the field is empty:\n";
echo "   - Check browser console for JavaScript errors\n";
echo "   - The JavaScript should auto-populate the field\n";
echo "   - Check storage/logs/laravel.log for server-side logs\n\n";

echo "5. Alternative test URLs to try:\n";
echo "   By referral code: " . route('register', ['ref' => $user->referral_code]) . "\n";
echo "   By user ID: " . route('register', ['ref' => $user->id]) . "\n\n";

echo "=== Manual Registration Test ===\n";
echo "Try registering with these details:\n";
echo "Name: Test Registration\n";
echo "Email: test" . time() . "@example.com\n";
echo "Phone: 1234567890\n";
echo "Password: password123\n";
echo "Referral Code: {$user->referral_code} (should be pre-filled)\n\n";

echo "=== Check Results ===\n";
echo "After registration, run this to verify:\n";
echo "php -r \"require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo 'Referrals: ' . App\Models\Referral::count();\"\n";

// Clear logs to make debugging easier
if (file_exists(storage_path('logs/laravel.log'))) {
    file_put_contents(storage_path('logs/laravel.log'), '');
    echo "\n✅ Cleared Laravel log file for easier debugging\n";
}
