<?php

namespace Shoperti\PayMe;

use Illuminate\Support\ServiceProvider;

class PayMeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('Shoperti\PayMe\Contracts\Factory', function ($app) {
            return new PayMeManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Shoperti\PayMe\Contracts\Factory'];
    }
}
