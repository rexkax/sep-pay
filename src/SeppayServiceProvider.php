<?php

namespace Aries\Seppay;

use Illuminate\Support\ServiceProvider;

class SeppayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/seppay.php'    =>  config_path('Seppay.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/seppay.php', 'Seppay'
        );
    }
}
