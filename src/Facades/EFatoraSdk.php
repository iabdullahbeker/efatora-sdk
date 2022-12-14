<?php

namespace Iabdullahbeker\EfatoraSdk\Facades;

use Illuminate\Support\Facades\Facade;

class EFatoraSdk extends Facade
{
   
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'efatora';
    }
}