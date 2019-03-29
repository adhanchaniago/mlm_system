<?php

namespace Intervention\Image\Facades;

use Illuminate\Support\Facades\Facade;

class image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image';
    }
}
