<?php

namespace Sunarc\LaravelChat;

use Illuminate\Support\Facades\Facade;

class LaravelChat extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelChat';
    }
}
