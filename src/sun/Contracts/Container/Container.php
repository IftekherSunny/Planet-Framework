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
     * @param mixed $class
     *
     * @return mixed
     */
    public function offsetExists($class);

    /**
     * @param mixed $class
     *
     * @return mixed
     */
    public function offsetGet($class);

    /**
     * @param mixed $contract
     * @param mixed $implementation
     */
    public function offsetSet($contract, $implementation);

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset);
}