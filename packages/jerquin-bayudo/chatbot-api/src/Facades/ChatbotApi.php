<?php

namespace Jerquin\ChatbotApi\Facades;

use Illuminate\Support\Facades\Facade;

class ChatbotApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'chatbot-api';
    }
}
