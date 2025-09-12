<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Receipt Storage ===\n\n";

// Check if receipt_path column exists in transactions table
try {
    $columns = \DB::getSchemaBuilder()->getColumnListing('transactions');
    echo "Transactions table columns:\n";
    foreach($columns as $column) {
        echo "- $column\n";
    }
    echo "\n";
    
    if (in_array('receipt_path', $columns)) {
        echo "✅ receipt_path column exists in transactions table\n\n";
        
        // Check transactions with receipts
        $transactionsWithReceipts = \App\Models\Transaction::whereNotNull('receipt_path')->get();
        echo "Transactions with receipts: " . $transactionsWithReceipts->count() . "\n";
        
        foreach($transactionsWithReceipts as $transaction) {
            echo "- Transaction ID: {$transaction->id}\n";
            echo "  User: {$transaction->user->name}\n";
            echo "  Amount: $" . number_format($transaction->amount, 2) . "\n";
            echo "  Receipt path: {$transaction->receipt_path}\n";
            echo "  File exists: " . (file_exists(storage_path('app/public/' . $transaction->receipt_path)) ? 'Yes' : 'No') . "\n\n";
        }
    } else {
        echo "❌ receipt_path column does NOT exist in transactions table\n";
        echo "This means receipts are not being stored in the database.\n\n";
    }
    
} catch (\Exception $e) {
    echo "Error checking database: " . $e->getMessage() . "\n";
}

echo "=== Checking Transaction Model ===\n";
$transaction = new \App\Models\Transaction();
$fillable = $transaction->getFillable();
echo "Transaction fillable fields:\n";
foreach($fillable as $field) {
    echo "- $field\n";
}

if (in_array('receipt_path', $fillable)) {
    echo "✅ receipt_path is in fillable array\n";
} else {
    echo "❌ receipt_path is NOT in fillable array\n";
}
