<?php

namespace Coderubix\GeminiSearch\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Coderubix\GeminiSearch\GeminiSearchServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            GeminiSearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Configure database for testing
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Configure Gemini API key for testing
        $app['config']->set('gemini.api_key', 'test-key');
    }
}
