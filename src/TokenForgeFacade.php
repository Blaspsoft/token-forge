<?php

namespace Blaspsoft\TokenForge;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Blaspsoft\TokenForge\Skeleton\SkeletonClass
 */
class TokenForgeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'token-forge';
    }
}
