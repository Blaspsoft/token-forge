<?php

namespace Blaspsoft\TokenForge\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsBladeStack
{
    public function installBladeStack()
    {
        // Controller
        (new Filesystem)->copy(__DIR__.'/../../stubs/blade/app/Http/Controllers/BladeTokenController.php', app_path('Http/Controllers/BladeTokenController.php'));

        // Views
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/blade/resources/views/api', resource_path('views/api/'));

        // Tests
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Feature'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/inertia-vue/tests/Feature/TokenTest.php', base_path('tests/Feature/TokenTest.php'));
    }
}