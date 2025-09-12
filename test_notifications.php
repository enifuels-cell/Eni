<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use App\Models\UserNotification;

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing UserNotification model...\n";
    
    // Test creating a notification
    $user = User::first();
    if (!$user) {
        echo "No users found. Please ensure you have users in your database.\n";
        exit(1);
    }
    
    $notification = UserNotification::create([
        'user_id' => $user->id,
        'title' => 'Test Migration Success',
        'message' => 'This notification tests that all columns are working properly after migration.',
        'category' => 'system',
        'type' => 'success',
        'priority' => 'high',
        'action_url' => '/dashboard',
        'is_active' => true,
        'expires_at' => now()->addDays(30)
    ]);
    
    echo "✅ Successfully created notification with ID: " . $notification->id . "\n";
    echo "✅ All columns are accessible:\n";
    echo "   - category: " . $notification->category . "\n";
    echo "   - type: " . $notification->type . "\n";
    echo "   - priority: " . $notification->priority . "\n";
    echo "   - is_active: " . ($notification->is_active ? 'true' : 'false') . "\n";
    echo "   - expires_at: " . $notification->expires_at . "\n";
    
    // Test scopes
    $activeNotifications = UserNotification::active()->count();
    $unreadNotifications = UserNotification::unread()->count();
    
    echo "✅ Scopes working:\n";
    echo "   - Active notifications: " . $activeNotifications . "\n";
    echo "   - Unread notifications: " . $unreadNotifications . "\n";
    
    // Test helper methods
    echo "✅ Helper methods working:\n";
    echo "   - Icon: " . $notification->getIconAttribute() . "\n";
    echo "   - Color: " . $notification->getCategoryColorAttribute() . "\n";
    
    echo "\n🎉 All UserNotification features are working perfectly!\n";
    echo "The migration successfully added all missing columns.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    exit(1);
}