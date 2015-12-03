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
        if(is_string($implementation)) {
          $this->container->set($contract, \DI\object($implementation));
        }

        if(is_callable($implementation) || is_object($implementation)) {
          $this->container->set($contract, $implementation);
        }
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
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetExists($key)
    {
        return $this->container->has($key);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
       return $this->make($key);
    }

    /**
     * @param mixed $key
     * @param mixed $implementation
     */
    public function offsetSet($key, $implementation)
    {
        $this->bind($key, $implementation);
    }

    /**
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        $this->container->set($key, null);
    }
}
