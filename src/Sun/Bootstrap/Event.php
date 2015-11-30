<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Application as App;

class Event
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
     * Bootstrap events
     */
    public function bootstrap()
    {
        $this->app->bind('Sun\Contracts\Event\Event', 'Sun\Event\Event');

        $this->app->make('Sun\Contracts\Event\Event')->register();
    }
}