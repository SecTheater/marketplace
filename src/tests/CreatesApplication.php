<?php

namespace SecTheater\Marketplace\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use SecTheater\Marketplace\Providers\EloquentEventServiceProvider;
use SecTheater\Marketplace\Providers\EloquentServiceProvider;
trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ .'/../../../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $app->loadEnvironmentFrom('.env.testing');
        $app['config']->set('database.default','sqlite'); 
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app->make(EloquentFactory::class)->load(__DIR__ . '/factories');
        return $app;
    }
}
