<?php

namespace Sun;

use Sun\Container\Container;
use Sun\Database\Database;
use Sun\Http\Response;
use Sun\Routing\Route;

class Application extends Container
{
    /**
     * @var \Sun\Routing\Route
     */
    protected $route;

    /**
     * To store route group namespace
     */
    protected $namespace;

    /**
     * Application base path
     */
    protected $path;

    /**
     * @var \Sun\Database\Database
     */
    protected $database;

    /**
     * @var \Sun\Http\Response
     */
    protected $response;

    /**
     * @param $option
     */
    public function __construct($option)
    {
        $this->path = $option['path'];

        $this->response = new Response;

        $container = $this->setup();
        $this->route = new Route($container, $this->response);

        $this->database = new Database($this);
    }

    /**
     * @param array $routeOption
     * @param       $callback
     */
    public function group(array $routeOption, $callback)
    {
        if (isset($routeOption['namespace'])) {
            $this->namespace = DIRECTORY_SEPARATOR . $routeOption['namespace'] . DIRECTORY_SEPARATOR;
        }

        $callback();
    }

    /**
     * @param $url
     * @param $pattern
     */
    public function get($url, $pattern)
    {
        if (is_callable($pattern)) {
            $this->route->add('GET', $url, $pattern);
        } else {
            $this->route->add('GET', $url, $this->namespace . $pattern);
        }
    }

    /**
     * @param $url
     * @param $pattern
     */
    public function post($url, $pattern)
    {
        if (is_callable($pattern)) {
            $this->route->add('POST', $url, $pattern);
        } else {
            $this->route->add('POST', $url, $this->namespace . $pattern);
        }
    }

    /**
     * To run application
     */
    public function run()
    {
        $this->route->routeRegister();

        $data = $this->route->routeDispatcher($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        $this->response->html($data);
    }

    /**
     * To get application base directory path
     *
     * @param null $path
     *
     * @return string
     */
    public function base_path($path = null)
    {
        return empty($path) ? $this->path : $this->path . $path;
    }

    /**
     * To get application app directory path
     *
     * @param null $path
     *
     * @return string
     */
    public function app_path($path = null)
    {
        return empty($path) ? $this->base_path() . 'app' . DIRECTORY_SEPARATOR : $this->base_path() . 'app' . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * To get application config directory path
     *
     * @return string
     */
    public function config_path()
    {
        return $this->base_path() . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * To boot database configuration
     */
    public function bootDatabase()
    {
        $this->database->boot();
    }

}
