<?php

namespace Blaspsoft\TokenForge;

use Illuminate\Support\Facades\Route;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class TokenForgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('token-forge.php'),
            ], 'token-forge-config');
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'token-forge');

        return [Console\InstallCommand::class];
    }
}
