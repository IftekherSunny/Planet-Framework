<?php

namespace Sun\Contracts\Routing;

use Exception;

interface Route
{
    /**
     * To add route
     *
     * @param string $method
     * @param string $uri
     * @param string $pattern
     * @param array  $options
     */
    public function add($method = 'GET', $uri = '/', $pattern = '', array $options = []);

    /**
     * To register route
     */
    public function register();

    /**
     * To dispatch a route
     *
     * @param string $method
     * @param string $uri
     *
     * @return mixed
     * @throws Exception
     */
    public function dispatcher($method, $uri);
}