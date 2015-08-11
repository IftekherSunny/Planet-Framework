<?php

namespace Sun\Support;

use Exception;
use ReflectionMethod;
use ReflectionException;
use DI\ContainerBuilder;
use Sun\Exception\BindingException;
use Sun\Exception\MethodNotFoundException;
use DI\Definition\Exception\DefinitionException;
use Sun\contracts\Support\Alien as AlienContract;

abstract class Alien implements AlienContract
{
    /**
     * Method name
     *
     * @var string $method
     */
    protected static $method;

    /**
     * Method arguments
     * @var array $arguments
     */
    protected static $arguments;

    /**
     * To execute method
     *
     * @param $namespace
     *
     * @return mixed
     * @throws BindingException
     * @throws MethodNotFoundException
     * @throws \DI\NotFoundException
     */
    public static function execute($namespace)
    {
        try {
            $instance = app()->make($namespace);

            $reflectionMethod = new ReflectionMethod($instance, static::$method);

            return $reflectionMethod->invokeArgs($instance, static::$arguments);
        } catch (DefinitionException $e) {
            throw new BindingException("Binding Error [ ". $e->getMessage() ." ]");
        } catch (ReflectionException $e) {
            throw new MethodNotFoundException("Method [ " . static::$method . " ] does not exist.");
        }
    }

    /**
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

        return static::execute(static::registerAlien());
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
}
