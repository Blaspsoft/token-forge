<?php

namespace Blaspsoft\TokenForge;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class TokenForgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        (new Filesystem)->copyFile(__DIR__.'/Middleware/FlashInertiaData.php', app_path('Http/Middleware/FlashInertiaData.php'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Pages/API', resource_path('js/Pages'));

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('token-forge.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'token-forge');

        // Register the main class to use with the facade
        $this->app->singleton('token-forge', function () {
            return new TokenForge;
        });
    }
}
