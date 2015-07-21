<?php

namespace Sun\Routing;

abstract class Filter
{
    /**
     * To create filter
     */
    public function __construct()
    {

    }

    /**
     * To handle request
     */
    abstract public function handle();

}
