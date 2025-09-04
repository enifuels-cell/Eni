<?php

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

// Create a test user and deposit
$user = User::firstOrCreate(
    ['email' => 'testuser@example.com'],
    [
        'name' => 'Test User',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]
);

// Create a test deposit transaction
$deposit = Transaction::create([
    'user_id' => $user->id,
    'type' => 'deposit',
    'amount' => 500.00,
    'status' => 'pending',
    'reference' => 'TEST-' . time(),
    'description' => 'Test bank transfer deposit',
    'receipt_path' => 'receipts/test-receipt.jpg', // This would be a real file path
]);

echo "Test deposit created with ID: {$deposit->id}\n";
echo "User: {$user->name} ({$user->email})\n";
echo "Amount: \${$deposit->amount}\n";
echo "Reference: {$deposit->reference}\n";
echo "Status: {$deposit->status}\n";
echo "\nYou can now test the admin pending deposits page.\n";
