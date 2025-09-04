<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Investments Table Structure ===\n";
$columns = Schema::getColumnListing('investments');
foreach ($columns as $column) {
    echo "✓ $column\n";
}

echo "\n=== Detailed Investments Table Info ===\n";
$result = DB::select("PRAGMA table_info(investments)");
foreach ($result as $column) {
    echo "- {$column->name} ({$column->type})" . ($column->notnull ? ' NOT NULL' : '') . ($column->dflt_value ? " DEFAULT {$column->dflt_value}" : '') . "\n";
}
