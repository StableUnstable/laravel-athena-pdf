<?php

namespace Olekjs\LaravelAthenaPdf;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Olekjs\LaravelAthenaPdf\Skeleton\SkeletonClass
 */
class LaravelAthenaPdfFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-athena-pdf';
    }
}
