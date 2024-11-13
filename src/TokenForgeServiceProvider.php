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
        Route::middleware(['web', 'auth'])
            ->namespace('YourPackageNamespace\Http\Controllers')
            ->group(__DIR__.'../../routes/web.php');

        (new Filesystem)->copy(__DIR__.'/Controllers/Inertia/ApiTokenController.php', app_path('Http/Controllers/ApiTokenController.php'));

        (new Filesystem)->copy(__DIR__.'/../stubs/tests/TokenTest.php', base_path('tests/Feature/TokenTest.php'));

        (new Filesystem)->copyDirectory(__DIR__.'/../stubs/inertia-vue/Pages/API', resource_path('js/Pages/API'));

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('token-forge.php'),
            ], 'token-forge-config');
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
