<?php

namespace Sun\Bootstrap;

use Sun\Support\Str;
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
     * Register service provider routes
     */
    public function registerRoute()
    {
        if(!is_null($services = $this->services)) {
            foreach($services as $service) {

                $routes = $this->app->make($service)->registerRoute();

                $this->requiredRoute($routes);
            }
        }
    }

    /**
     * Dispatch services
     */
    public function dispatch()
    {
        if(!is_null($services = $this->services)) {
            foreach($services as $service) {
                $this->app->make($service)->dispatch();
            }
        }
    }

    /**
     * Required all register routes
     *
     * @param array $routes
     */
    protected function requiredRoute($routes)
    {
        foreach($routes as $route) {
            require_once(str::path($route));
        }
    }
}
