<?php

namespace SecTheater\Marketplace\Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->loadEnvironmentFrom(base_path('.env.testing'));
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
