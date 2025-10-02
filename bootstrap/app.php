<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
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
        // Handle CSRF token mismatch (419) gracefully
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                // Clear any stale session data
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect back to login with a friendly message
                return redirect()->route('login')
                    ->with('error', 'Your session has expired. Please log in again.')
                    ->withInput($request->except(['password', 'pin', 'pin_confirmation']));
            }
        });
    })->create();
