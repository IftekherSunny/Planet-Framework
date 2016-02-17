<?php

namespace Sun\Support;

use Mockery;
use Exception;
use ReflectionMethod;
use ReflectionException;

abstract class Alien
{
    /**
     * Method name
     *
     * @var string
     */
    protected static $method;

    /**
     * Method arguments
     *
     * @var array
     */
    protected static $arguments;

    /**
     * Application container
     *
     * @var \Sun\Contracts\Container\Container
     */
    protected static $container;

    /**
     * Store mocked class
     *
     * @var array
     */
    protected static $mocked;

    /**
     * Execute method of the registered alien
     *
     * @return mixed
     * @throws Exception
     */
    protected static function execute()
    {
        try {
            $instance = static::getInstance();

            $reflectionMethod = new ReflectionMethod($instance, static::$method);

            return $reflectionMethod->invokeArgs($instance, static::$arguments);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Set application container
     */
    protected static function setContainer()
    {
        static::$container = app()->getContainer();
    }

    /**
     * Get the class instance of the registered alien
     *
     * @return mixed
     * @throws Exception
     */
    protected static function getInstance()
    {
        return isset(static::$mocked[static::registerAlien()])? static::$mocked[static::registerAlien()] : static::$container->make(static::registerAlien());
    }

    /**
     * Initiate mock expectation for the registered alien
     *
     * @return mixed
     * @throws Exception
     */
    public static function shouldReceive()
    {
        if(!isset(static::$mocked[static::registerAlien()])) {
            static::$mocked[static::registerAlien()] = Mockery::mock(static::registerAlien());
        }

        return call_user_func_array([static::$mocked[static::registerAlien()], 'shouldReceive'], func_get_args());
    }

    /**
     * To register Alien
     *
     * @return string namespace
     * @throws Exception
     */
    protected static function registerAlien()
    {
        throw new Exception('Alien does not implement registerAlien method.');
    }

    /**
     * To handle static call dynamically
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($method, $arguments)
    {
        static::$method = $method;
        static::$arguments = $arguments;

        if(is_null(static::$container)) {
            static::setContainer();
        }

        return static::execute();
    }
}