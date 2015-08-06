<?php

namespace Sun\Contracts\Routing;

use Exception;
use Sun\Routing\BindingException;

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
    public function routeRegister();

    /**
     * To dispatch a route
     *
     * @param $method
     * @param $uri
     *
     * @return mixed|void
     */
    public function routeDispatcher($method, $uri);

    /**
     * To execute handler
     *
     * @param $controller
     * @param $method
     * @param $params
     *
     * @return mixed
     * @throws BindingException
     * @throws Exception
     */
    public function invoke($controller, $method, $params);
}