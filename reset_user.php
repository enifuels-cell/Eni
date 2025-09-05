<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete existing test user
User::where('email', 'test@example.com')->delete();

// Create fresh test user with only basic fields
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
    'email_verified_at' => now(),
]);

echo "User created successfully!\n";
echo "Email: test@example.com\n";
echo "Password: password\n";
echo "User ID: " . $user->id . "\n";
