<?php

namespace Sun\Database;

use Phpmig\Adapter;
use Sun\Contracts\Application;

class Schema
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * Create a new schema instance
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Bootstrap PHP Mig
     */
    public function bootstrap()
    {
        $this->app->bind('phpmig.adapter', function() {
            return new Adapter\Illuminate\Database($this->app['Sun\Contracts\Database\Database']->getCapsuleInstance(), 'migrations');
        });

        $this->app->bind('phpmig.migrations_path', function() {
            return $this->app->migrations_path();
        });

        $this->app->bind('phpmig.migrations_template_path', function() {
            return __DIR__ . '/../Console/stubs/MigrateGenerate.txt';
        });

        $this->app->bind('schema', function() {
            return $this->app['Sun\Contracts\Database\Database']->getCapsuleInstance()->schema();
        });
    }
}