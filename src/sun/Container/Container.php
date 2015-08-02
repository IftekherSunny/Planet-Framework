<?php

namespace Sun\Container;

use ArrayAccess;
use DI\ContainerBuilder;
use Sun\Contracts\Container\Container as ContainerContract;

class Container implements ContainerContract, ArrayAccess
{
    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * To boot DI Container
     */
    public function bootContainer()
    {
        if(empty($this->container)) {
            $this->container = ContainerBuilder::buildDevContainer();
            $this->bindObject('Sun\Contracts\Container\Container', $this);
        }
    }

    /**
     * To get Container
     *
     * @return \DI\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * To bind class with interface
     *
     * @param $contract
     * @param $implementation
     */
    public function bind($contract, $implementation)
    {
        $this->container->set($contract, \DI\object($implementation));
    }

    /**
     * To bind object with interface
     *
     * @param $contract
     * @param $object
     */
    public function bindObject($contract, $object)
    {
        $this->container->set($contract, $object);
    }

    /**
     * To inject all dependencies of a given class
     *
     * @param $class
     *
     * @return mixed
     */
    public function make($class)
    {
        return $this->container->get($class);
    }

    /**
     * @param mixed $class
     *
     * @return mixed
     */
    public function offsetExists($class)
    {
        return $this->has($class);
    }

    /**
     * @param mixed $class
     *
     * @return mixed
     */
    public function offsetGet($class)
    {
       return $this->make($class);
    }

    /**
     * @param mixed $contract
     * @param mixed $implementation
     */
    public function offsetSet($contract, $implementation)
    {
        $this->set($contract, $implementation);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->make[$offset]);
    }
}
