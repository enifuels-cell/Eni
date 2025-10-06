<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;

$user = User::first();
if (!$user) {
    echo "No users found\n";
    exit(1);
}

if (app()->environment('local', 'testing')) {
    $token = Password::broker()->createToken($user);
    $link = url(route('password.reset', $token, false)) . '?email=' . urlencode($user->email);
    echo "DEV LINK: " . $link . "\n";
    exit(0);
}

$status = Password::sendResetLink(['email' => $user->email]);
echo "Status: " . $status . "\n";
if ($status === Password::RESET_LINK_SENT) {
    echo "Link sent to: " . $user->email . "\n";
} else {
    echo "Failed: " . $status . "\n";
}
