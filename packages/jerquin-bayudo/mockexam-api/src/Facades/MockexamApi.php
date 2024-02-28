<?php

namespace Jerquin\Mockexam\Facades;

use Illuminate\Support\Facades\Facade;

class MockexamApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mockexam-api';
    }
}
