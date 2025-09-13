<?php

require_once 'vendor/autoload.php';

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔐 User Management Script\n";
echo "========================\n\n";

// List existing users
echo "📋 Existing Users:\n";
echo "------------------\n";

try {
    $users = User::all();
    
    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo "ID: {$user->id}\n";
            echo "Name: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Username: " . ($user->username ?? 'Not set') . "\n";
            echo "Role: {$user->role}\n";
            echo "Created: {$user->created_at}\n";
            echo "---\n";
        }
    } else {
        echo "No users found in the database.\n";
    }
    
    echo "\n🆕 Creating Test Admin User:\n";
    echo "-----------------------------\n";
    
    // Create a test admin user
    $adminEmail = 'admin@eni.com';
    $adminPassword = 'admin123456';
    
    $existingAdmin = User::where('email', $adminEmail)->first();
    
    if ($existingAdmin) {
        echo "✅ Admin user already exists!\n";
        echo "Email: {$adminEmail}\n";
        echo "Password: {$adminPassword} (if unchanged)\n";
        
        // Update password to ensure it's correct
        $existingAdmin->password = Hash::make($adminPassword);
        $existingAdmin->role = 'admin';
        $existingAdmin->username = $existingAdmin->username ?? 'admin';
        $existingAdmin->save();
        
        echo "🔄 Password reset to: {$adminPassword}\n";
        
    } else {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'role' => 'admin',
            'account_balance' => 0.00
        ]);
        
        echo "✅ Admin user created successfully!\n";
        echo "Email: {$adminEmail}\n";
        echo "Password: {$adminPassword}\n";
        echo "Username: admin\n";
    }
    
    echo "\n🆕 Creating Test Regular User:\n";
    echo "-------------------------------\n";
    
    $userEmail = 'user@eni.com';
    $userPassword = 'user123456';
    
    $existingUser = User::where('email', $userEmail)->first();
    
    if ($existingUser) {
        echo "✅ Test user already exists!\n";
        echo "Email: {$userEmail}\n";
        echo "Password: {$userPassword} (if unchanged)\n";
        
        // Update password to ensure it's correct
        $existingUser->password = Hash::make($userPassword);
        $existingUser->username = $existingUser->username ?? 'testuser';
        $existingUser->save();
        
        echo "🔄 Password reset to: {$userPassword}\n";
        
    } else {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => $userEmail,
            'password' => Hash::make($userPassword),
            'role' => 'user',
            'account_balance' => 1000.00
        ]);
        
        echo "✅ Test user created successfully!\n";
        echo "Email: {$userEmail}\n";
        echo "Password: {$userPassword}\n";
        echo "Username: testuser\n";
        echo "Balance: $1,000.00\n";
    }
    
    echo "\n🔗 Login URLs:\n";
    echo "--------------\n";
    echo "Local: http://127.0.0.1:8000/login\n";
    echo "Live: https://eni-1-main-wxjghw.laravel.cloud/login\n";
    
    echo "\n📋 Summary of Credentials:\n";
    echo "==========================\n";
    echo "ADMIN LOGIN:\n";
    echo "Email: {$adminEmail}\n";
    echo "Password: {$adminPassword}\n";
    echo "Username: admin\n";
    echo "\n";
    echo "USER LOGIN:\n";
    echo "Email: {$userEmail}\n";
    echo "Password: {$userPassword}\n";
    echo "Username: testuser\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Make sure your database is connected and running.\n";
}
