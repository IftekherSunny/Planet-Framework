<?php

namespace Sun\Routing;

use Exception;
use Sun\Http\Response;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Sun\Contracts\Container\Container;
use DI\Definition\Exception\DefinitionException;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;

class Route
{
    /**
     * Route uri
     *
     * @var array
     */
    protected $uri = array();

    /**
     * Route pattern
     *
     * @var array
     */
    protected $pattern = array();

    /**
     * Route method
     *
     * @var array
     */
    protected $method = array();

    /**
     * Route params
     *
     * @var array
     */
    protected $params = array();

    /**
     * Route filter
     *
     * @var array
     */
    protected $filter = array();

    /**
     * Application Container
     *
     * @var Container
     */
    protected $container;

    /**
     * Route dispatcher
     *
     * @var \FastRoute\Dispatcher\GroupCountBased
     */
    protected $dispatcher;

    /**
     * @var \Sun\Http\Response
     */
    protected $response;

    /**
     * @param Container $container
     * @param Response  $response
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
    public function routeRegister()
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
     * @param $method
     * @param $uri
     *
     * @return mixed|void
     */
    public function routeDispatcher($method, $uri)
    {
        $this->filter($uri, $method);

        $routeInfo = $this->dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->response->code(404)->message("Route [ {$uri} ] not found.");

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->response->code(405)->message("Route [ {$uri} ] not found.");

                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $params = $routeInfo[2];

                return $this->methodDispatcher($handler, $params);

                break;
        }
    }

    /**
     * To execute route handler
     *
     * @param $handler
     * @param $params
     *
     * @return mixed|void
     * @throws BindingException
     * @throws Exception
     */
    protected function methodDispatcher($handler, $params)
    {
        /**
         *  call when pass closure as handler
         **/
        if (is_callable($handler)) {
            echo call_user_func_array($handler, $params);

            return;
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
     * @param $controller
     * @param $method
     * @param $params
     *
     * @return mixed
     * @throws BindingException
     * @throws Exception
     */
    public function invoke($controller, $method, $params)
    {
        if (!class_exists($controller)) {
            throw new Exception("Controller [ $controller ] does not exist.");
        }

        try {
            $instance = $this->container->make($controller);

            return call_user_func([$instance, $method], $params);

        } catch (DefinitionException $e) {
            throw new BindingException("Binding Error [ ". $e->getMessage() ." ]");
        }

    }

    /**
     * To filter http request
     *
     * @param $uri
     * @param $method
     */
    protected function filter($uri, $method)
    {
        if (!empty($this->filter[$uri][$method])) {
            $name = app()->getNamespace() . 'Filters\\' . $this->filter[$uri][$method];

            $instance = $this->container->make($name);

            $instance->handle();
        }
    }
}
