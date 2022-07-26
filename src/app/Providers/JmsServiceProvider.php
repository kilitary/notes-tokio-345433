<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class JmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JmsServiceProvider::class, function ($app) {
            return SerializerBuilder::create()->build();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
