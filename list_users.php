<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ENI PLATFORM LOGIN CREDENTIALS ===\n";
echo "=======================================\n\n";

$users = \App\Models\User::all();

foreach ($users as $user) {
    $role = $user->role ?? 'client';
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Username: " . ($user->username ?? 'N/A') . "\n";
    echo "Role: " . strtoupper($role) . "\n";
    
    // Note: We can't show actual passwords as they are hashed
    // But we can show what the common passwords likely are
    if (strpos($user->email, 'admin') !== false) {
        echo "Password: admin123 (likely)\n";
    } else {
        echo "Password: password123 (likely)\n";
    }
    echo "-------------------\n";
}

echo "\nNOTE: If these passwords don't work, you may need to reset them.\n";
echo "Admin users can access the admin panel at /admin\n";
echo "Regular users access the dashboard at /dashboard\n";
