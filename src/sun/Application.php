<?php

namespace Sun;

use Sun\Container\Container;
use Sun\Database\Database;
use Sun\Http\Response;
use Sun\Routing\Route;
use Sun\Routing\UrlGenerator;

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
     * @var \Sun\Routing\UrlGenerator
     */
    protected $urlGenerator;

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

        $this->urlGenerator = new UrlGenerator;
    }

    /**
     * @param array $routeOption
     * @param       $callback
     */
    public function group(array $routeOption = [], $callback)
    {
        if (isset($routeOption['namespace'])) {
            $this->namespace = '\\' . $routeOption['namespace'] . '\\';
        }

        (isset($routeOption['prefix'])) ? $this->prefix = '/' . $routeOption['prefix'] : $this->prefix = '';

        (isset($routeOption['filter'])) ? $this->filter = ['filter' => $routeOption['filter']] : $this->filter = [];

        call_user_func_array($callback, $routeOption);
    }

    /**
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function get($uri, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('GET', $this->prefix . $uri, $pattern, $options);
        } else {
            $this->route->add('GET', $this->prefix . $uri, $this->namespace . $pattern, $options);
        }
    }

    /**
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function post($uri, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('POST', $this->prefix . $uri, $pattern, $options);
        } else {
            $this->route->add('POST', $this->prefix . $uri, $this->namespace . $pattern, $options);
        }
    }

    /**
     * To run application
     */
    public function run()
    {
        $this->route->routeRegister();

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $this->urlGenerator->getUri();

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
