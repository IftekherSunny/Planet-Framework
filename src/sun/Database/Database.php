<?php

namespace Sun\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /**
     * @var \Sun\Application
     */
    protected $app;

    /**
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * To boot Eloquent
     */
    public function boot()
    {
        $capsule = new Capsule;

        $database = require_once $this->app->config_path() . '/database.php';

        if ($database['driver'] == 'mysql') {
            $this->mysqlConnectionSetup($capsule, $database);
        }

        $capsule->bootEloquent();
    }

    /**
     * To connect with mysql database
     *
     * @param $capsule
     * @param $database
     */
    private function mysqlConnectionSetup($capsule, $database)
    {
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $database['connection']['mysql']['host'],
            'database' => $database['connection']['mysql']['database'],
            'username' => $database['connection']['mysql']['username'],
            'password' => $database['connection']['mysql']['password'],
            'charset' => $database['connection']['mysql']['charset'],
            'collation' => $database['connection']['mysql']['collation'],
            'prefix' => $database['connection']['mysql']['prefix']
        ]);
    }
}
