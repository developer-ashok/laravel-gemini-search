<?php

namespace Coderubix\GeminiSearch\Facades;

use Illuminate\Support\Facades\Facade;

class GeminiSearch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gemini-search';
    }
}
