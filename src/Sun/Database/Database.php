<?php

namespace Sun\Database;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Sun\Contracts\Database\Database as DatabaseContract;

class Database implements DatabaseContract
{
    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    protected $capsule;

    /**
     * To get Capsule instance
     *
     * @return Capsule
     */
    public function getCapsuleInstance()
    {
        if(is_null($this->capsule)) {
            return new Capsule;
        }

        return $this->capsule;
    }

    /**
     * To boot database configuration
     */
    public function boot()
    {
        $this->capsule = $this->getCapsuleInstance();

        $this->{config('database.driver').'ConnectionSetup'}($this->capsule);

        $this->capsule->setAsGlobal();

        $this->bootPaginator();
    }

    /**
     * To boot Eloquent
     */
    public function bootEloquent()
    {
        $this->capsule->bootEloquent();
    }

    /**
     * To connect with mysql database
     *
     * @param $capsule
     */
    private function mysqlConnectionSetup($capsule)
    {
        $capsule->addConnection([
            'driver'        => 'mysql',
            'host'          => config('database.connection.mysql.host'),
            'database'      => config('database.connection.mysql.database'),
            'username'      => config('database.connection.mysql.username'),
            'password'      => config('database.connection.mysql.password'),
            'charset'       => config('database.connection.mysql.charset'),
            'collation'     => config('database.connection.mysql.collation'),
            'prefix'        => config('database.connection.mysql.prefix')
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
            'driver'        => 'sqlite',
            'database'      => config('database.connection.sqlite.database'),
            'charset'       => config('database.connection.sqlite.charset'),
            'collation'     => config('database.connection.sqlite.collation'),
            'prefix'        => config('database.connection.sqlite.prefix')
        ]);
    }

    /**
     * To boot Paginator
     */
    private function bootPaginator()
    {
        Paginator::currentPathResolver(function () {
            return url();
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            return request()->input($pageName);
        });
    }
}
