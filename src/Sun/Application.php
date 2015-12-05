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
    const VERSION = 'v1.0-beta.5';

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
     * @var \Sun\Contracts\Database\Database
     */
    protected $database;

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
     * @param string $basePath
     */
    public function __construct($basePath = null)
    {
        $this->path = $basePath;

        $this->booting();

        $this->registerBindings();

        $this->bootstrap();

        $this->bootDatabase();
    }

    /**
     * Route GROUP
     *
     * @param array $routeOption
     * @param callback $callback
     */
    public function group(array $routeOption = [], $callback)
    {
        if (isset($routeOption['namespace'])) {
            $this->namespace = '\\' . $routeOption['namespace'] . '\\';
        }

        (isset($routeOption['prefix'])) ? $this->prefix = '/' . $routeOption['prefix'] : $this->prefix = '';

        (isset($routeOption['filter'])) ? $this->filter = ['filter' => $routeOption['filter']] : $this->filter = [];

        call_user_func_array($callback, $routeOption);

        $this->prefix = '';

        $this->filter = [];
    }

    /**
     * Route GET
     *
     * @param string $uri
     * @param string $pattern
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
     * @param string $uri
     * @param string $pattern
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
     * @param string $uri
     * @param string $pattern
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
     * @param string $uri
     * @param string $pattern
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
     * @param string $uri
     * @param string $pattern
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
     * Route ANY
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    public function any($uri, $pattern, array $options = [])
    {
        $patterns = explode('@', $pattern);
        $controller = $patterns[0];
        $method = ucfirst($patterns[1]);

        $this->get($uri, "$controller@get$method", $options);
        $this->post($uri, "$controller@post$method", $options);
    }

    /**
     * Route RESOURCE
     *
     * @param string $uri
     * @param string $controller
     * @param array $options
     */
    public function resource($uri, $controller, array $options = [])
    {
        if (!empty($options['nested']) === true) {
            list($uri, $options) = $this->generateNestedUri($uri, $options);
        }

        $this->get($uri, "$controller@index", $options);
        $this->post($uri, "$controller@store", $options);
        $this->get("$uri/{placeholder}", "$controller@show", $options);
        $this->put("$uri/{placeholder}", "$controller@update", $options);
        $this->delete("$uri/{placeholder}", "$controller@delete", $options);
    }

    /**
     * To generate nested uri
     *
     * @param string $uri
     * @param string $options
     *
     * @return array
     */
    private function generateNestedUri($uri, $options)
    {
        $base = '/';

        $patterns = explode('/', trim($uri, '/'));

        $uri = array_pop($patterns);

        foreach ($patterns as $pattern) {
            $base .= "$pattern/{{$pattern}}/";
        }

        $uri = $base . $uri;

        return [$uri, $options];
    }

    /**
     * To run application
     */
    public function run()
    {
        $this->make('Sun\Bootstrap\Provider')->registerRoute();

        $this->route->register();

        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $uri = $this->make('Sun\Contracts\Routing\UrlGenerator')->getUri();

        $data = $this->route->dispatch($httpMethod, $uri);

        $this->make('Sun\Bootstrap\Provider')->dispatch();

        $this->make('Sun\Contracts\Http\Response')->html($data);
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
     * To get application migrations directory path
     *
     * @return string
     */
    public function migrations_path()
    {
        return $this->base_path() . DIRECTORY_SEPARATOR . 'migrations';
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
     * To get log file path
     *
     * @return string
     */
    public function logFilePath()
    {
        return $this->storage_path() . '/framework/logs/planet.log';
    }

    /**
     * To get session directory path
     *
     * @return string
     */
    public function sessionDirectoryPath()
    {
        return $this->storage_path().'/framework/sessions/';
    }

    /**
     * To get configuration cache file path
     *
     * @return string
     */
    public function configurationCacheFilePath()
    {
        return $this->storage_path() . '/framework/cache/config.php';
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
    protected function bootDatabase()
    {
        $this->database = $this->make('Sun\Contracts\Database\Database');

        $this->database->boot();

        $this->db = $this->database->getCapsuleInstance();
    }

    /**
     * To boot Eloquent
     */
    public function bootEloquent()
    {
        $this->database->bootEloquent();
    }

    /**
     * To get \Sun\Application instance
     *
     * @return \Sun\Application
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * To set \Sun\Application instance
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
     * Booting application
     */
    protected function booting()
    {
        $this->bootContainer();

        $this->setInstance();

        $this->bindObject('Sun\Contracts\Application', $this);

        $this->config = $this->make('Sun\Support\Config');
    }

    /**
     * Bootstrap application required class
     */
    protected function bootstrap()
    {
        $this->make('Sun\Bootstrap\Application')->bootstrap();

        $this->make('Sun\Bootstrap\HandleExceptions')->bootstrap();

        $this->make('Sun\Bootstrap\Route')->bootstrap();

        $this->make('Sun\Bootstrap\Provider')->bootstrap();

        $this->route = $this->make('Sun\Contracts\Routing\Route');
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

    /**
     * To get configuration
     *
     * @param string $location
     *
     * @return mixed
     */
    public function config($location)
    {
        $keys = explode('.', $location);

        $filename = 'get' . strtoupper(array_shift($keys));

        $location = implode('.', $keys);

        if(empty($location)) {
            return $this->config->{$filename}();
        }

        return $this->config->{$filename}($location);
    }
}
