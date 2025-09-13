<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\Generator;

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application so facades & providers are registered
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing QR code generation (facade + direct generator) ...\n";

// Direct generator (bypasses facade) as control
$generator = new Generator();
$svg = $generator->size(100)->generate('control test');
echo "Direct generator produced " . strlen($svg) . " bytes of SVG\n";

try {
    $qr = QrCode::size(200);
    echo "Facade size() call succeeded\n";
    $result = $qr->generate('test data');
    echo "Facade generate() produced " . strlen($result) . " bytes\n";
    echo "QR code generated successfully via facade!\n";
} catch (Throwable $e) {
    echo "Facade error: " . $e->getMessage() . "\n";
}

echo "Done.\n";
