<?php

namespace FredBradley\FilesystemHealthCheck\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Health\HealthServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            HealthServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('health.result_stores', []);
    }
}
