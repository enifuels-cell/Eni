<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Create and bootstrap the application for testing.
     */
    public function createApplication(): Application
    {
        // Require the bootstrap file which returns the configured Application instance
        $app = require __DIR__.'/../bootstrap/app.php';

        // In some custom setups (and to remain forward compatible) we explicitly
        // bootstrap the console kernel so that facades, providers, and the
        // exception handler are fully initialized for the test environment.
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}