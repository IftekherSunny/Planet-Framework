<?php

namespace Sun;

use Exception;
use Sun\Container\Container;
use Sun\Contracts\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * Planet Framework Version
     */
    const VERSION = 'v1.0-beta.3';

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
     * @var Capsule
     */
    public $db;

    /**
     * store application namespace
     */
    protected $appNamespace;

    /**
     * @var \Sun\Support\Config
     */
    public $config;

    /**
     * @var \Sun\Application
     */
    protected static $instance;

    /**
     * Create a new application
     *
     * @param $basePath
     */
    public function __construct($basePath = null)
    {
        $this->path = $basePath;

        $this->bootContainer();

        $this->setInstance();

        $this->bindObject('Sun\Contracts\Application', $this);

        $this->config = $this->make('Sun\Support\Config');

        $this->registerBindings();

        $this->route = $this->make('Sun\Routing\Route');
    }

    /**
     * Route GROUP
     *
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
     * Route GET
     *
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
     * Route POST
     *
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
     * Route DELETE
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function delete($uri, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('DELETE', $this->prefix . $uri, $pattern, $options);
        } else {
            $this->route->add('DELETE', $this->prefix . $uri, $this->namespace . $pattern, $options);
        }
    }

    /**
     * Route PUT
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function put($uri, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('PUT', $this->prefix . $uri, $pattern, $options);
        } else {
            $this->route->add('PUT', $this->prefix . $uri, $this->namespace . $pattern, $options);
        }
    }

    /**
     * Route PATCH
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function patch($uri, $pattern, array $options = [])
    {
        $options = array_merge($options, $this->filter);

        if (is_callable($pattern)) {
            $this->route->add('PATCH', $this->prefix . $uri, $pattern, $options);
        } else {
            $this->route->add('PATCH', $this->prefix . $uri, $this->namespace . $pattern, $options);
        }
    }

    /**
     * To run application
     */
    public function run()
    {
        $this->route->routeRegister();

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $this->make('Sun\Routing\UrlGenerator')->getUri();

        $data = $this->route->routeDispatcher($httpMethod, $uri);

        $this->make('Sun\Http\Response')->html($data);
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
        return empty($path) ? $this->base_path() . DIRECTORY_SEPARATOR . 'app' : $this->base_path() . 'app' . $path;
    }

    /**
     * To get application config directory path
     *
     * @return string
     */
    public function config_path()
    {
        return $this->base_path() . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * To get application storage directory path
     *
     * @return string
     */
    public function storage_path()
    {
        return $this->base_path() . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * To get application public directory path
     *
     * @return string
     */
    public function public_path()
    {
        return $this->base_path() . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * To load alien
     */
    public function loadAlien()
    {
        $alien = $this->config->getAlien();

        foreach ($alien as $alias => $namespace) {
            class_alias($namespace, $alias);
        }
    }

    /**
     * To boot database configuration
     */
    public function bootDatabase()
    {
        $database = $this->make('Sun\Database\Database');
        $database->boot();
        $this->db = $database->getCapsuleInstance();
    }

    /**
     * To get \Sun\Application instance
     *
     * @return mixed
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * To set \Sun\Application instance
     *
     * @return $this
     */
    private function setInstance()
    {
        static::$instance =  $this;
    }

    /**
     * To get application namespace
     *
     * @return string
     * @throws Exception
     */
    public function getNamespace()
    {
        if(!is_null($this->appNamespace)) {
            return $this->appNamespace;
        }

        $composer = json_decode(file_get_contents(base_path().'/composer.json'));

        foreach($composer->autoload->{"psr-4"} as $namespace => $path) {
            if(realpath(app_path()) === realpath(base_path(). '/' .$path))  {
                return $this->appNamespace = $namespace;
            }
        }

        throw new Exception("Namespace detect problem.");
    }

    /**
     *  To register all bindings
     */
    protected function registerBindings()
    {
        $binding = config('binding')?: [];

        foreach($binding as $contract => $implementation) {
            $this->bind($contract, $implementation);
        }
    }
}
