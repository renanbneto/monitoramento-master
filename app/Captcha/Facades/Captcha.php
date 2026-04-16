<?php

namespace App\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see App\Captcha\src\Captcha
 */
class Captcha extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}
