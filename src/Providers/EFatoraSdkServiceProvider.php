<?php

namespace Iabdullahbeker\EfatoraSdk\Providers;

use Iabdullahbeker\EfatoraSdk\EFatoraSdk;
use Illuminate\Support\ServiceProvider;

class EFatoraSdkServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('efatora', function () {
            return new EFatoraSdk;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('efatora.php'),
        ],'config');
    }
}
