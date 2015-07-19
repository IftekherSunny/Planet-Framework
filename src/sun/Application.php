<?php

namespace Sun;

use Sun\Container\Container;
use Sun\Routing\Route;

class Application extends Container
{
    protected $route;

    protected $routeOption;

    protected $namespace;

    protected $path;

    public function __construct($option)
    {
        $this->path = $option['path'];

        $container = $this->setup();
        $this->route = new Route($container);
    }

    public function group(array $routeOption, $callback)
    {
        if (isset($routeOption['namespace'])) {
            $this->namespace = DIRECTORY_SEPARATOR . $routeOption['namespace'] . DIRECTORY_SEPARATOR;
        }

        $callback();
    }

    public function get($url, $pattern)
    {
        if (is_callable($pattern)) {
            $this->route->add('GET', $url, $pattern);
        } else {
            $this->route->add('GET', $url, $this->namespace . $pattern);
        }
    }

    public function post($url, $pattern)
    {
        if (is_callable($pattern)) {
            $this->route->add('POST', $url, $pattern);
        } else {
            $this->route->add('POST', $url, $this->namespace . $pattern);
        }
    }

    public function run()
    {
        $this->route->routeRegister();

        $output = $this->route->routeDispatcher($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        if (is_array($output)) {
            echo json_encode($output);
        } else {
            echo $output;
        }
    }

    public function base_path($path = null)
    {
        return empty($path) ? $this->path : $this->path . $path;
    }

    public function app_path($path = null)
    {
        return empty($path) ? $this->base_path() . 'app' . DIRECTORY_SEPARATOR : $this->base_path() . 'app' . DIRECTORY_SEPARATOR . $path;
    }

    public function config_path()
    {
        return $this->base_path() . 'config' . DIRECTORY_SEPARATOR;
    }

}
