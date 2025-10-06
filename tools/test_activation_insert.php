<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaction;
use App\Models\User;

try {
    $user = User::first();
    if (!$user) {
        throw new \Exception('No users found to attach transaction to.');
    }

    $tx = Transaction::create([
        'user_id' => $user->id,
        'type' => 'activation_fund',
        'amount' => 5000,
        'status' => 'approved',
        'description' => 'Test activation fund',
        'reference' => 'TEST' . time(),
        'processed_at' => now(),
    ]);
    echo "OK: " . json_encode(['id' => $tx->id, 'type' => $tx->type, 'amount' => (string)$tx->amount]) . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
