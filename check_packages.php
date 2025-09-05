<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\InvestmentPackage;

echo "Investment Packages in Database:\n";
echo "================================\n";

$packages = InvestmentPackage::all();

if ($packages->count() === 0) {
    echo "No packages found in database.\n";
} else {
    foreach ($packages as $pkg) {
        echo "ID: " . $pkg->id . "\n";
        echo "Name: " . $pkg->name . "\n";
        echo "Price: $" . number_format($pkg->min_amount) . " - $" . number_format($pkg->max_amount) . "\n";
        echo "Daily Rate: " . $pkg->daily_shares_rate . "%\n";
        echo "Duration: " . $pkg->effective_days . " days\n";
        echo "Image: " . $pkg->image . "\n";
        echo "Available Slots: " . $pkg->available_slots . "\n";
        echo "----------------------------\n";
    }
}
