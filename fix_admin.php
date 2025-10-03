<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Fixing Admin User ===\n\n";

$admin = User::where('role', 'admin')->first();

if (!$admin) {
    echo "No admin found. Creating new admin...\n";
    $admin = User::create([
        'name' => 'Admin ENI',
        'email' => 'admin@eniph.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "✅ Admin created!\n";
} else {
    echo "Found admin: {$admin->name} (ID: {$admin->id})\n";
    echo "Current email: {$admin->email}\n\n";

    echo "Updating admin credentials...\n";
    $admin->email = 'admin@eniph.com';
    $admin->password = Hash::make('admin123');
    $admin->email_verified_at = now();
    $admin->save();
    echo "✅ Admin updated!\n";
}

echo "\n=== Admin Login Credentials ===\n";
echo "Email: admin@eniph.com\n";
echo "Password: admin123\n";
echo "\nYou can now login at: http://127.0.0.1:8000/login\n";
