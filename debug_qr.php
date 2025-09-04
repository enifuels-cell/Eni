<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Test basic QR code generation
try {
    echo "Testing basic QR code generation...\n";
    $qr = QrCode::size(200);
    echo "Size method works\n";
    
    $result = $qr->generate('test data');
    echo "Generate method works\n";
    echo "QR code generated successfully!\n";
    
    // Test methods available
    $qrObject = QrCode::size(200);
    echo "Available methods: " . implode(', ', get_class_methods($qrObject)) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
