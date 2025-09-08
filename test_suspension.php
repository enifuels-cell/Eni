<?php
// Quick test script to verify suspension functionality
require_once 'vendor/autoload.php';

use App\Models\User;

// Find a test user (not admin) to suspend
$user = User::where('role', '!=', 'admin')->first();

if ($user) {
    echo "Found user: {$user->email}\n";
    echo "Current suspension status: " . ($user->isSuspended() ? 'SUSPENDED' : 'NOT SUSPENDED') . "\n";
    
    if ($user->isSuspended()) {
        echo "Unsuspending user...\n";
        $user->unsuspend();
        echo "User unsuspended\n";
    } else {
        echo "Suspending user...\n";
        $user->suspend();
        echo "User suspended\n";
    }
    
    echo "New suspension status: " . ($user->isSuspended() ? 'SUSPENDED' : 'NOT SUSPENDED') . "\n";
    echo "Suspended at: " . ($user->suspended_at ? $user->suspended_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
} else {
    echo "No non-admin users found to test with\n";
}
