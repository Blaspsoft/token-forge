<?php

namespace Blaspsoft\TokenForge\Console;

use Illuminate\Support\Facades\Route;
use Illuminate\Filesystem\Filesystem;

trait InstallsVueStack
{
    public function installVueStack()
    {
        $this->installRoutes();
        $this->installControllers();
        $this->installViews();
        $this->installTests();
    }

    private function installRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace('Blaspsoft\Http\Controllers')
            ->group(__DIR__.'../../routes/inertia/web.php');
    }

    private function installControllers()
    {
        (new Filesystem)->copy(__DIR__.'/Controllers/Inertia/ApiTokenController.php', app_path('Http/Controllers/ApiTokenController.php'));
    }

    private function installViews()
    {
        (new Filesystem)->copyDirectory(__DIR__.'/../stubs/inertia-vue/Pages/API', resource_path('js/Pages/API'));
    }

    private function installTests()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Feature'));
        (new Filesystem)->copy(__DIR__.'/../stubs/tests/Feature/TokenTest.php', base_path('tests/Feature/TokenTest.php'));
    }

}