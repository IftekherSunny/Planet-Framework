<?php

namespace Sun\Container;

use DI\ContainerBuilder;

class Container
{
    protected $container;

    public function setup()
    {
        $this->container = ContainerBuilder::buildDevContainer();
        return $this->container;
    }

    public function bind($contract, $implementation)
    {
        $this->container->set($contract, \DI\object($implementation));
    }

}
