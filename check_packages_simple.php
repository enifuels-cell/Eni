<?php

// Simple script to check investment packages
$db = new PDO('sqlite:database/database.sqlite');

echo "Current Investment Packages:\n";
echo "==========================\n";

$stmt = $db->query("SELECT name, daily_shares_rate, min_amount, max_amount, effective_days, referral_bonus_rate, available_slots, active FROM investment_packages");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Name: {$row['name']}\n";
    echo "Daily Rate: {$row['daily_shares_rate']}%\n";
    echo "Min Amount: \${$row['min_amount']}\n";
    echo "Max Amount: \${$row['max_amount']}\n";
    echo "Duration: {$row['effective_days']} days\n";
    echo "Referral Bonus: {$row['referral_bonus_rate']}%\n";
    echo "Available Slots: " . ($row['available_slots'] ?? 'Unlimited') . "\n";
    echo "Active: " . ($row['active'] ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
