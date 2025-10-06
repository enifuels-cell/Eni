<?php
// Tool: create_admin.php
// Creates or updates admin@example.com with a known password (development only).
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;

try {
    $password = 'password'; // development default

    $u = \App\Models\User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'account_balance' => 0.00,
            'role' => 'admin',
        ]
    );

    echo json_encode([
        'email' => $u->email,
        'password_plain' => $password,
        'password_hash' => $u->password,
        'role' => $u->role,
        'email_verified_at' => $u->email_verified_at ? $u->email_verified_at->toDateTimeString() : null,
    ]) . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
