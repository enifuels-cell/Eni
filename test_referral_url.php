<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== Testing Referral Link Parameter Passing ===\n\n";

// Get a user with referral code
$user = User::first();
if (!$user || !$user->referral_code) {
    echo "❌ No user with referral code found\n";
    exit;
}

echo "Testing user: {$user->name}\n";
echo "Referral code: {$user->referral_code}\n";
echo "Referral link: " . route('register', ['ref' => $user->referral_code]) . "\n\n";

// Test the URL generation
$generatedUrl = route('register', ['ref' => $user->referral_code]);
echo "Generated URL: {$generatedUrl}\n";

// Parse the URL to check the parameter
$parsedUrl = parse_url($generatedUrl);
if (isset($parsedUrl['query'])) {
    parse_str($parsedUrl['query'], $queryParams);
    echo "Query parameters: " . json_encode($queryParams) . "\n";
    echo "Ref parameter: " . ($queryParams['ref'] ?? 'NOT FOUND') . "\n";
} else {
    echo "❌ No query parameters found in URL\n";
}

// Test different referral code formats
echo "\n=== Testing Different URL Formats ===\n";
$testCodes = [
    'referral_code' => $user->referral_code,
    'user_id' => $user->id,
    'mixed_case' => strtolower($user->referral_code)
];

foreach($testCodes as $type => $code) {
    $url = route('register', ['ref' => $code]);
    echo "{$type}: {$url}\n";
}

echo "\n=== Manual URL Test ===\n";
echo "Visit this URL to test:\n";
echo route('register', ['ref' => $user->referral_code]) . "\n";
echo "\nThe referral code field should show: {$user->referral_code}\n";
