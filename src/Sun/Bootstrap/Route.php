<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Application as App;

class Route
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

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
     * Bootstrap application configuration
     */
    public function bootstrap()
    {
        $this->app->bind('Sun\Contracts\Routing\Route', 'Sun\Routing\Route');
    }
}