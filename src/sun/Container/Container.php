<?php

namespace Sun\Container;

use DI\ContainerBuilder;

class Container
{
    /**
     * @var \DI\Container
     */
    protected $container;

    /**
     * To setup DI Container
     *
     * @return \DI\Container
     */
    public function setup()
    {
        $this->container = ContainerBuilder::buildDevContainer();
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
}
