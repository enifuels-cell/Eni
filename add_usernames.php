<?php

require_once 'vendor/autoload.php';

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Adding usernames to existing users...\n";

$users = User::whereNull('username')->get();

foreach ($users as $user) {
    // Generate username from email or name
    $baseUsername = strtolower(explode('@', $user->email)[0]);
    
    // Remove any non-alphanumeric characters
    $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
    
    // Ensure username is unique
    $username = $baseUsername;
    $counter = 1;
    
    while (User::where('username', $username)->exists()) {
        $username = $baseUsername . $counter;
        $counter++;
    }
    
    $user->username = $username;
    $user->save();
    
    echo "✅ Added username '{$username}' to user: {$user->name} ({$user->email})\n";
}

echo "\n📊 Summary:\n";
echo "Users with usernames: " . User::whereNotNull('username')->count() . "\n";
echo "Users without usernames: " . User::whereNull('username')->count() . "\n";

// Show all users with their referral info
echo "\n👥 All Users:\n";
$allUsers = User::all();
foreach ($allUsers as $user) {
    $referralUrl = url('/register?ref=' . $user->username);
    echo "User: {$user->name}\n";
    echo "  Email: {$user->email}\n";
    echo "  Username: {$user->username}\n";
    echo "  Referral Code: {$user->referral_code}\n";
    echo "  New Referral URL: {$referralUrl}\n";
    echo "  Old Referral URL: " . url('/register?ref=' . $user->referral_code) . "\n";
    echo "\n";
}

?>
