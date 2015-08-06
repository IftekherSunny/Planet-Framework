<?php

namespace Sun\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Sun\Contracts\Database\Database as DatabaseContract;

class Database implements DatabaseContract
{
    /**
     * To get Capsule instance
     *
     * @return Capsule
     */
    public function getCapsuleInstance()
    {
        return new Capsule;
    }

    /**
     * To boot Eloquent
     */
    public function boot()
    {
        $capsule = $this->getCapsuleInstance();

        if (config('database.driver') == 'mysql') {
            $this->mysqlConnectionSetup($capsule);
        }

        if (config('database.driver') == 'sqlite') {
            $this->sqliteConnectionSetup($capsule);
        }

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }

    /**
     * To connect with mysql database
     *
     * @param $capsule
     */
    private function mysqlConnectionSetup($capsule)
    {
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => config('database.connection.mysql.host'),
            'database' => config('database.connection.mysql.database'),
            'username' => config('database.connection.mysql.username'),
            'password' => config('database.connection.mysql.password'),
            'charset' => config('database.connection.mysql.charset'),
            'collation' => config('database.connection.mysql.collation'),
            'prefix' => config('database.connection.mysql.prefix')
        ]);
    }

    /**
     * To connect with sqlite database
     *
     * @param $capsule
     */
    private function sqliteConnectionSetup($capsule)
    {
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => config('database.connection.sqlite.database'),
            'charset' => config('database.connection.sqlite.charset'),
            'collation' => config('database.connection.sqlite.collation'),
            'prefix' => config('database.connection.sqlite.prefix')
        ]);
    }
}
