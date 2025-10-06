<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('notifications')->orderBy('created_at', 'desc')->limit(10)->get();

if ($rows->isEmpty()) {
    echo "No notifications found.\n";
    exit(0);
}

foreach ($rows as $r) {
    echo "ID: {$r->id}\n";
    echo "Type: {$r->type}\n";
    echo "Notifiable: {$r->notifiable_type}#{$r->notifiable_id}\n";
    echo "Created: {$r->created_at}\n";
    echo "Data: {$r->data}\n";
    echo str_repeat('-', 60) . "\n";
}
