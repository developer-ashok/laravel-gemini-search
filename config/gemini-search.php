<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gemini API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Gemini API settings.
    |
    */

    'api_key' => env('GEMINI_API_KEY', ''),
    
    'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
    
    'max_tokens' => env('GEMINI_MAX_TOKENS', 1000),
    
    'temperature' => env('GEMINI_TEMPERATURE', 0.1),
];
