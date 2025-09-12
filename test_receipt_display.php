<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Receipt Display ===\n\n";

// Find a transaction with a receipt
$transactionWithReceipt = \App\Models\Transaction::whereNotNull('receipt_path')->first();

if ($transactionWithReceipt) {
    echo "✅ Found transaction with receipt:\n";
    echo "  - Transaction ID: {$transactionWithReceipt->id}\n";
    echo "  - User: {$transactionWithReceipt->user->name}\n";
    echo "  - Amount: $" . number_format($transactionWithReceipt->amount, 2) . "\n";
    echo "  - Receipt path: {$transactionWithReceipt->receipt_path}\n";
    
    $fullPath = storage_path('app/public/' . $transactionWithReceipt->receipt_path);
    echo "  - File exists: " . (file_exists($fullPath) ? 'Yes' : 'No') . "\n";
    echo "  - File size: " . (file_exists($fullPath) ? number_format(filesize($fullPath) / 1024, 2) . ' KB' : 'N/A') . "\n";
    
    echo "\n  Investment Receipt URL: http://127.0.0.1:8000/user/investment/receipt/{$transactionWithReceipt->id}\n";
    echo "  Transactions Page URL: http://127.0.0.1:8000/user/transactions\n";
    
} else {
    echo "❌ No transactions with receipts found\n";
}

echo "\n=== Transactions with Receipts Summary ===\n";
$receiptsCount = \App\Models\Transaction::whereNotNull('receipt_path')->count();
echo "Total transactions with receipts: {$receiptsCount}\n";
