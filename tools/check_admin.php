<?php
// Tool: check_admin.php
// Boot the Laravel app and print basic info for admin@example.com
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $u = \App\Models\User::where('email', 'admin@example.com')->first();
    if (!$u) {
        echo "NOT_FOUND\n";
        exit(0);
    }

    // Print fields safely
    $out = [
        'email' => $u->email,
        'password' => $u->password,
        'role' => $u->role,
        'email_verified_at' => $u->email_verified_at ? $u->email_verified_at->toDateTimeString() : null,
    ];

    echo json_encode($out) . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
