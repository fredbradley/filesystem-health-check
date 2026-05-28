<?php

namespace FredBradley\FilesystemHealthCheck;

use Spatie\Health\Facades\Health;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        Health::checks([
            HealthCheck::new()->name('Laravel Version')
        ]);
    }
}
