<?php

namespace Blaspsoft\TokenForge;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Blaspsoft\TokenForge\Contracts\TokenForgeController;

class TokenForgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Route::middleware(['web', 'auth'])
            ->group(__DIR__.'../../routes/web.php');

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
        $filesystem = new Filesystem();

        // Dynamically bind the correct controller based on its presence
        $controllers = [
            'App\Http\Controllers\VueTokenController' => app_path('Http/Controllers/VueTokenController.php'),
            'App\Http\Controllers\BladeTokenController' => app_path('Http/Controllers/BladeTokenController.php'),
        ];

        foreach ($controllers as $class => $path) {
            if ($filesystem->exists($path)) {
                $this->app->bind(TokenForgeController::class, $class);
                break;
            }
        }
        
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'token-forge');

        return [Console\InstallCommand::class];
    }
}
