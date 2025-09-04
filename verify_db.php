<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Users Table Structure ===\n";
$columns = Schema::getColumnListing('users');
foreach ($columns as $column) {
    echo "✓ $column\n";
}

echo "\n=== Detailed Table Info ===\n";
$result = DB::select("PRAGMA table_info(users)");
foreach ($result as $column) {
    echo "- {$column->name} ({$column->type})" . ($column->notnull ? ' NOT NULL' : '') . ($column->dflt_value ? " DEFAULT {$column->dflt_value}" : '') . "\n";
}

echo "\n=== Sample User Data ===\n";
$users = DB::table('users')->select(['name', 'email', 'username', 'role', 'account_balance'])->limit(3)->get();
foreach ($users as $user) {
    echo "- {$user->name} | {$user->email} | {$user->username} | {$user->role} | Balance: \${$user->account_balance}\n";
}
