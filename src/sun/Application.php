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
     * To store route prefix
     */
    protected $prefix;

    /**
     * To store route filter
     */
    protected $filter;

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

        $this->response = new Response(new Session());

        $container = $this->setup();
        $this->route = new Route($container, $this->response);

        $this->database = new Database($this);
    }

    /**
     * @param array $routeOption
     * @param       $callback
     */
    public function group(array $routeOption = [], $callback)
    {
        if (isset($routeOption['namespace'])) {
            $this->namespace = DIRECTORY_SEPARATOR . $routeOption['namespace'] . DIRECTORY_SEPARATOR;
        }

        (isset($routeOption['prefix']))? $this->prefix = '/'.$routeOption['prefix'] : $this->prefix = '';

        (isset($routeOption['filter']))? $this->filter = ['filter' => $routeOption['filter']] : $this->filter = [];

        call_user_func_array($callback, $routeOption);
    }

    /**
     * @param       $url
     * @param       $pattern
     * @param array $options
     */
    public function get($url, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('GET', $this->prefix . $url, $pattern, $options);
        } else {
            $this->route->add('GET', $this->prefix . $url, $this->namespace . $pattern, $options);
        }
    }

    /**
     * @param       $url
     * @param       $pattern
     * @param array $options
     */
    public function post($url, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('POST', $this->prefix . $url, $pattern, $options);
        } else {
            $this->route->add('POST', $this->prefix . $url, $this->namespace . $pattern, $options);
        }
    }

    /**
     * To run application
     */
    public function run()
    {
        $this->route->routeRegister();

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $data = $this->route->routeDispatcher($httpMethod, $uri);

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
