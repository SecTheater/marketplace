<?php

namespace SecTheater\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;

class MarketplaceServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }
    public function register()
    {
        $this->registerPublishables();
        $this->registerHelpers();
        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommands();
        }
    }
    public function registerHelpers()
    {
        require_once __DIR__.'/../Helpers/Helpers.php';
    }

    public function registerConsoleCommands()
    {
        $this->commands(\SecTheater\Marketplace\Commands\InstallCommand::class); //
    }
    public function registerPublishables()
    {
        $publishablePath =  __DIR__.'/../Publishables';
        $this->publishes([
            $publishablePath.'/config' => config_path('/'),
            $publishablePath.'/migrations'        => database_path('migrations'),
            $publishablePath.'/seeders'           => database_path('seeds'),
        ], 'marketplace');
    }

}