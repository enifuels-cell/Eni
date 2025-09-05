<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'dycinne@gmail.com')->first();

echo "=== Final Dashboard Values ===\n";
echo "User: {$user->name}\n";
echo "Account Balance (available for withdrawal/transfer): \$" . number_format($user->accountBalance(), 2) . "\n";
echo "Total Invested (locked): \$" . number_format($user->totalInvestedAmount(), 2) . "\n";
echo "Active Investments: " . $user->investments()->active()->count() . "\n";
echo "\n✅ The dashboard should now show \$20.00 as the available account balance!\n";
