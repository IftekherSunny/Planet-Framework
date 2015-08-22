<?php

namespace Sun\Bootstrap;

use Sun\Contracts\Application as App;

class Application
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
        # set application timezone
        date_default_timezone_set($this->app->config->getApp('timezone'));

        # set application error log path
        ini_set("error_log", $this->app->logFilePath());

        # set application session storage path
        session_save_path(realpath($this->app->sessionDirectoryPath()));
    }
}