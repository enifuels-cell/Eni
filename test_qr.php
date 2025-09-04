<?php

// Test QrCode functionality
require_once 'vendor/autoload.php';

use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Create a simple QR code to test
echo "Testing QR Code generation...\n";

try {
    $qr = QrCode::size(200);
    echo "QrCode size method works\n";
    
    $qr = $qr->generate('test');
    echo "QrCode generate method works\n";
    echo "QR Code generated successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
