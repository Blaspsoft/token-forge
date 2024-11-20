<?php

namespace Blaspsoft\TokenForge\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsVueStack
{
    public function installVueStack()
    {
        // Composer packages
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0'])) {
            return 1;
        }

        // Controller
        (new Filesystem)->copy(__DIR__.'/../../stubs/inertia-vue/app/Http/Controllers/VueTokenController.php', app_path('Http/Controllers/VueTokenController.php'));

        // Views
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/Pages/API', resource_path('js/Pages/API'));

        // Tests
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Feature'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/inertia-vue/tests/Feature/TokenTest.php', base_path('tests/Feature/TokenTest.php'));
    }
}