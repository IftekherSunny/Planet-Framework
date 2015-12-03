<?php

namespace Sun\Contracts\Container;

interface Container
{
    /**
     * To boot DI Container
     */
    public function bootContainer();

    /**
     * To get Container
     *
     * @return \DI\Container
     */
    public function getContainer();

    /**
     * To bind class with interface
     *
     * @param $contract
     * @param $implementation
     */
    public function bind($contract, $implementation);

    /**
     * To bind object with interface
     *
     * @param $contract
     * @param $object
     */
    public function bindObject($contract, $object);

    /**
     * To inject all dependencies of a given class
     *
     * @param $class
     *
     * @return mixed
     */
    public function make($class);

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
}