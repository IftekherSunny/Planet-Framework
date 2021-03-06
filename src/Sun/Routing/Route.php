<?php

namespace Sun\Routing;

use Exception;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Sun\Contracts\Http\Request;
use Sun\Contracts\Http\Response;
use Sun\Contracts\Container\Container;
use Sun\Validation\Form\Request as FormRequest;
use Sun\Contracts\Routing\Route as RouteContract;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;

class Route implements RouteContract
{
    /**
     * Route uri
     *
     * @var array
     */
    protected $uri = [];

    /**
     * Route pattern
     *
     * @var array
     */
    protected $pattern = [];

    /**
     * Route method
     *
     * @var array
     */
    protected $method = [];

    /**
     * Route params
     *
     * @var array
     */
    protected $params = [];

    /**
     * Route filter
     *
     * @var array
     */
    protected $filter = [];

    /**
     * Application Container
     *
     * @var \Sun\Contracts\Container\Container
     */
    protected $container;

    /**
     * Route dispatcher
     *
     * @var \FastRoute\Dispatcher\GroupCountBased
     */
    protected $dispatcher;

    /**
     * @var \Sun\Contracts\Http\Response
     */
    protected $response;

    /**
     * Create a new route instance
     *
     * @param \Sun\Contracts\Container\Container $container
     * @param \Sun\Contracts\Http\Response       $response
     */
    public function __construct(Container $container, Response $response)
    {
        $this->container = $container;

        $this->response = $response;
    }

    /**
     * To add route
     *
     * @param string $method
     * @param string $uri
     * @param string $pattern
     * @param array  $options
     */
    public function add($method = 'GET', $uri = '/', $pattern = '', array $options = [])
    {
        $this->method[] = $method;

        $this->uri[] = $uri;

        $this->pattern[] = $pattern;

        $this->filter[$uri][$method] = (isset($options['filter'])) ? $options['filter'] : '';
    }

    /**
     * To register route
     */
    public function register()
    {
        $route = new RouteCollector(new Std(), new DataGenerator());
        for ($i = 0; $i < count($this->uri); $i++) {
            $route->addRoute($this->method[$i], $this->uri[$i], $this->pattern[$i]);
        }

        $this->dispatcher = new Dispatcher($route->getData());
    }

    /**
     * To dispatch a route
     *
     * @param string $method
     * @param string $uri
     *
     * @return mixed
     * @throws Exception
     */
    public function dispatch($method, $uri)
    {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);

        if($routeInfo[0] === Dispatcher::NOT_FOUND) {

            $this->filterRequest('*', $method);

            $routeInfo = $this->dispatcher->dispatch($method, '*');

            if(!$routeInfo[0]) {
                throw new Exception("Route [ {$uri} ] not found.");
            }
        }

        if($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            $method = $_SERVER['REQUEST_METHOD'];
            throw new Exception("[ $method ] method not allowed.");
        }

        if($routeInfo[0] === Dispatcher::FOUND) {
            $handler = $routeInfo[1];
            $params = $routeInfo[2];

            if(!empty($filterResponse = $this->filterRequest($uri, $method))) {
                return $filterResponse;
            }

            return $this->methodDispatcher($handler, $params);
        }
    }

    /**
     * To execute route handler
     *
     * @param string $handler
     * @param array $params
     *
     * @return mixed
     * @throws BindingException
     * @throws Exception
     */
    protected function methodDispatcher($handler, $params)
    {
        /**
         *  call when pass closure as handler
         **/
        if (is_callable($handler)) {
            $resolving = $this->container->resolveCallback($handler, $params);

            $this->validateForm($resolving);

            return call_user_func_array($handler, $resolving);
        }

        /**
         * call when pass controller as handler
         **/
        list($controller, $method) = explode('@', $handler);

        return $this->invoke($controller, $method, $params);
    }

    /**
     * To execute handler
     *
     * @param string $controller
     * @param string $method
     * @param string $params
     *
     * @return mixed
     * @throws BindingException
     * @throws Exception
     */
    protected function invoke($controller, $method, $params)
    {
        if (!class_exists($controller)) {
            throw new Exception("Controller [ $controller ] does not exist.");
        }

        try {
            $instance = $this->container->make($controller);

            $resolving = $this->container->resolveMethod($controller, $method, $params, true);

            $this->validateForm($resolving);

            return call_user_func_array([$instance, $method], $resolving);
        } catch (DefinitionException $e) {
            throw new BindingException("Binding Error [ ". $e->getMessage() ." ]");
        }

    }

    /**
     * To filter http request
     *
     * @param string $uri
     * @param string $method
     *
     * @return mixed
     */
    protected function filterRequest($uri, $method)
    {
        if (!empty($filtersPattern = $this->filter[$uri][$method])) {

            $filters = explode('|', $filtersPattern);

            foreach ($filters as $filter) {

                list($className, $params) = $this->getClassNameWithParams($filter);

                $name = app()->getNamespace() . 'Filters\\' . $className;

                $instance = $this->container->make($name);

                $filterResponse = call_user_func_array([$instance, 'handle'], $params);

                if(!$filterResponse instanceof Request) {
                    return $filterResponse;
                }
            }
        }
    }

    /**
     * Get filterable class name and params
     *
     * @param string $filter
     *
     * @return array
     */
    protected function getClassNameWithParams($filter)
    {
        $pattern = explode(':', $filter);

        $className = trim($pattern[0]);

        $params = [];

        if(isset($pattern[1])) {
            $params = array_map('trim', explode(',', $pattern[1]));
        }

        return [$className, $params];
    }

    /**
     * Validate requested form.
     *
     * @param array $classes
     */
    protected function validateForm($classes)
    {
        foreach($classes as $class) {
            if($class instanceof FormRequest) {
                $class->validate();
            }
        }
    }
}
