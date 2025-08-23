<?php

namespace Ashok\GeminiSearch;

use Illuminate\Support\ServiceProvider;
use Ashok\GeminiSearch\Services\GeminiSearchService;

class GeminiSearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('gemini-search', function () {
            return new GeminiSearchService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gemini-search.php' => config_path('gemini-search.php'),
        ], 'gemini-search-config');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
