<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Login Troubleshooting Tool ===\n\n";

// Check if users exist
$users = User::all();
echo "Total users in database: " . $users->count() . "\n\n";

if ($users->count() > 0) {
    echo "=== Available User Accounts ===\n";
    foreach($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Phone: " . ($user->phone ?? 'Not set') . "\n";
        echo "Role: " . ($user->role ?? 'Not set') . "\n";
        echo "Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
        echo "Suspended: " . ($user->suspended_at ? 'Yes' : 'No') . "\n";
        echo "Created: " . $user->created_at->format('Y-m-d H:i:s') . "\n";
        echo "---\n";
    }
}

// Test specific login credentials
$testCredentials = [
    ['email' => 'test@example.com', 'password' => 'password'],
    ['email' => 'admin@eni.com', 'password' => 'password'],
    ['email' => 'user@example.com', 'password' => 'password'],
];

echo "\n=== Testing Login Credentials ===\n";
foreach($testCredentials as $creds) {
    echo "Testing: {$creds['email']} / {$creds['password']}\n";
    
    $user = User::where('email', $creds['email'])->first();
    if (!$user) {
        echo "❌ User not found\n";
        continue;
    }
    
    if (Hash::check($creds['password'], $user->password)) {
        echo "✅ Password correct\n";
        
        // Test actual login
        $loginResult = \Illuminate\Support\Facades\Auth::attempt($creds);
        if ($loginResult) {
            echo "✅ Login successful\n";
            \Illuminate\Support\Facades\Auth::logout();
        } else {
            echo "❌ Login failed (auth system issue)\n";
        }
    } else {
        echo "❌ Password incorrect\n";
    }
    echo "---\n";
}

// Check auth configuration
echo "\n=== Auth Configuration Check ===\n";
$authConfig = config('auth');
echo "Default guard: " . $authConfig['defaults']['guard'] . "\n";
echo "User provider: " . $authConfig['guards']['web']['provider'] . "\n";
echo "User model: " . $authConfig['providers']['users']['model'] . "\n";

// Provide login instructions
echo "\n=== Login Instructions ===\n";
echo "1. Go to: http://127.0.0.1:8000/login\n";
echo "2. Use these credentials:\n";
echo "   Email: test@example.com\n";
echo "   Password: password\n";
echo "3. If login fails, check:\n";
echo "   - Make sure Laravel server is running: php artisan serve\n";
echo "   - Clear browser cache/cookies\n";
echo "   - Check browser console for JavaScript errors\n";
echo "   - Try incognito/private browsing mode\n";

// Create admin user if none exists
$adminUser = User::where('role', 'admin')->first();
if (!$adminUser) {
    echo "\n=== Creating Admin User ===\n";
    $admin = User::create([
        'name' => 'Administrator',
        'email' => 'admin@eni.com',
        'phone' => '0987654321',
        'password' => Hash::make('admin123'),
        'email_verified_at' => now(),
        'role' => 'admin',
        'account_balance' => 50000,
    ]);
    
    echo "✅ Admin user created:\n";
    echo "   Email: admin@eni.com\n";
    echo "   Password: admin123\n";
    echo "   Role: admin\n";
}
