<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->boot();

echo "Available users:\n";
echo "================\n";

foreach(App\Models\User::all() as $user) {
    echo "Email: " . $user->email . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Role: " . ($user->role ?? 'user') . "\n";
    echo "---\n";
}
