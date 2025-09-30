<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminRole::class,
            'admin.session' => \App\Http\Middleware\ExtendAdminSession::class,
            'check.suspended' => \App\Http\Middleware\CheckSuspended::class,
            'track.attendance' => \App\Http\Middleware\TrackDailyAttendance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "Current Investment Packages:\n";
echo "==========================\n";

$packages = \App\Models\InvestmentPackage::all();
foreach ($packages as $package) {
    echo "Name: {$package->name}\n";
    echo "Daily Rate: {$package->daily_shares_rate}%\n";
    echo "Min Amount: \${$package->min_amount}\n";
    echo "Max Amount: \${$package->max_amount}\n";
    echo "Duration: {$package->effective_days} days\n";
    echo "Referral Bonus: {$package->referral_bonus_rate}%\n";
    echo "Available Slots: " . ($package->available_slots ?? 'Unlimited') . "\n";
    echo "Active: " . ($package->active ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
