<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Application as App;

class Provider
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * Register services
     *
     * @var array
     */
    protected $services;

    /**
     * Create a new instance
     *
     * @param \Sun\Contracts\Application $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap services
     */
    public function bootstrap()
    {
        $this->services = $this->app->config('provider');

        if(!is_null($services = $this->services)) {
            foreach($services as $service) {
                $this->app->make($service)->bootstrap();
            }
        }
    }

    /**
     * Register route
     */
    public function registerRoute()
    {
        if(!is_null($services = $this->services)) {
            foreach($services as $service) {
                $this->app->make($service)->registerRoute();
            }
        }
    }
}