<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// --- ENHANCED MAINTENANCE MODE CHECK START ---

// 1. Define Whitelisted IPs
// Add your own public IP(s) here to bypass maintenance mode for testing/development.
// Also include common local IPs for local development (127.0.0.1, ::1).
$whitelisted_ips = [
    '127.0.0.1', // Localhost IP
    '::1',       // IPv6 Localhost IP
    'YOUR_PUBLIC_IP_ADDRESS', // <-- REPLACE THIS WITH YOUR OWN IP ADDRESS
];

// Get the client's current IP address
$client_ip = $_SERVER['REMOTE_ADDR'] ?? null;
$is_whitelisted = in_array($client_ip, $whitelisted_ips);

// 2. Check for Laravel's default maintenance file (created by 'php artisan down')
$laravelMaintenance = __DIR__.'/../storage/framework/maintenance.php';

// 3. Define the path for your Git-committed maintenance file (in the project root)
$gitMaintenance = __DIR__.'/../.maintenance';

// Determine if the application is in maintenance mode AND if the client is NOT whitelisted...
if (!$is_whitelisted) {
    if (file_exists($laravelMaintenance)) {
        // Laravel's maintenance file exists, use its handler
        require $laravelMaintenance;
    } elseif (file_exists($gitMaintenance)) {
        // Your Git-committed file exists, display a simple maintenance message

        // Send 503 HTTP status code for SEO
        http_response_code(503);
        // Suggest checking back in 1 hour
        header('Retry-After: 3600');

        // IMPORTANT: This assumes your maintenance page is in public/maintenance.html
        readfile(__DIR__ . '/maintenance.html');
        exit();
    }
}

// --- ENHANCED MAINTENANCE MODE CHECK END ---

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
