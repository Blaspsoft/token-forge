<?php

namespace Blaspsoft\TokenForge\Console;

use Illuminate\Support\Facades\Route;
use Illuminate\Filesystem\Filesystem;

trait InstallsBladeStack
{
    public function installBladeStack()
    {
        $this->installRoutes();
    }

    private function installRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->namespace('Blaspsoft\Http\Controllers')
            ->group(__DIR__.'../../routes/blade/web.php');
    }

}