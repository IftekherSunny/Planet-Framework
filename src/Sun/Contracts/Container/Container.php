<?php

namespace Sun\Contracts\Container;

use Closure;

interface Container
{
    /**
     * Resolved dependencies for a key.
     *
     * @param string $key
     * @param array  $params
     *
     * @return object
     * @throws Exception
     */
    public function make($key, $params = []);

    /**
     * Get the resolved type for a key that already resolved by the container.
     * If the key does not resolve, at first resolved it,
     * then returns the resolved type.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function need($key);

    /**
     * Bind a value for a key.
     *
     * @param string                 $key
     * @param string|callback|object $value
     *
     * @throws Exception
     */
    public function bind($key, $value);

    /**
     * Resolve dependencies for a callback.
     *
     * @param Closure $callback
     * @param array   $params
     *
     * @return array
     * @throws Exception
     */
    public function resolveCallback(Closure $callback, $params = []);

    /**
     * Resolve dependencies for a method.
     *
     * @param string $class
     * @param string $method
     * @param array  $params
     *
     * @return array
     * @throws Exception
     */
    public function resolveMethod($class, $method, $params = []);

    /**
     * Resolve dependencies for a class.
     *
     * @param string $name
     * @param array $params
     *
     * @return object
     * @throws Exception
     */
    public function resolveClass($name, $params = []);

    /**
     * Check a key is already exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetExists($key);

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key);

    /**
     * @param mixed $key
     * @param mixed $implementation
     */
    public function offsetSet($key, $implementation);

    /**
     * @param mixed $key
     */
    public function offsetUnset($key);

    /**
     * To get Container
     *
     * @return \Sun\Container\Container
     */
    public function getContainer();
}