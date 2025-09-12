<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete existing test user
User::where('email', 'test@example.com')->delete();

// Create fresh test user with all required fields
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '1234567890',
    'password' => Hash::make('password'),
    'email_verified_at' => now(),
    'role' => 'user',
    'account_balance' => 0,
]);

echo "User created successfully!\n";
echo "Email: test@example.com\n";
echo "Password: password\n";
echo "Phone: 1234567890\n";
echo "User ID: " . $user->id . "\n";
echo "Referral Code: " . $user->referral_code . "\n";
echo "Account Balance: $" . $user->account_balance . "\n";

// Test login credentials
echo "\n=== Login Test ===\n";
$testLogin = \Illuminate\Support\Facades\Auth::attempt([
    'email' => 'test@example.com',
    'password' => 'password'
]);

if ($testLogin) {
    echo "✅ Login test successful!\n";
    \Illuminate\Support\Facades\Auth::logout();
} else {
    echo "❌ Login test failed!\n";
    echo "Checking user in database...\n";
    
    $checkUser = User::where('email', 'test@example.com')->first();
    if ($checkUser) {
        echo "✅ User found in database\n";
        echo "Password hash: " . substr($checkUser->password, 0, 20) . "...\n";
        
        // Test password verification
        if (\Illuminate\Support\Facades\Hash::check('password', $checkUser->password)) {
            echo "✅ Password hash is correct\n";
        } else {
            echo "❌ Password hash verification failed\n";
        }
    } else {
        echo "❌ User not found in database\n";
    }
}
