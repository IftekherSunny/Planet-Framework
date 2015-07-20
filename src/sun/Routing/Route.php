<?php

namespace Sun\Routing;

use Exception;
use DI\Definition\Exception\DefinitionException;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Sun\Http\Response;

class Route
{
    protected $url = array();

    protected $pattern = array();

    protected $method = array();

    protected $params = array();

    protected $filter = array();

    protected $container;

    protected $dispatcher;

    protected $response;

    /**
     * @param          $container
     * @param Response $response
     */
    public function __construct($container, Response $response)
    {
        $this->container = $container;

        $this->response = $response;
    }

    /**
     * To add route
     *
     * @param string $method
     * @param string $url
     * @param string $pattern
     * @param array  $options
     */
    public function add($method = 'GET', $url = '/', $pattern = '', array $options = [])
    {
        $this->method[] = $method;

        $this->url[] = $url;

        $this->pattern[] = $pattern;

        $this->filter[$url] = (isset($options['filter']))? $options['filter'] : '';
    }

    /**
     * To register route
     */
    public function routeRegister()
    {
        $route = new RouteCollector(new Std(), new DataGenerator());
        for ($i = 0; $i < count($this->url); $i++) {
            $route->addRoute($this->method[$i], $this->url[$i], $this->pattern[$i]);
        }

        $this->dispatcher = new Dispatcher($route->getData());
    }

    /**
     * To dispatch a route
     *
     * @param $method
     * @param $url
     *
     * @return mixed|void
     */
    public function routeDispatcher($method, $url)
    {
        $this->filter($url);

        $routeInfo = $this->dispatcher->dispatch($method, $url);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->response->abort();

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $method = $routeInfo[1];
                $this->response->code(405)->message("Method [ {$method} ] not found.");
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
            throw new Exception("Class not found.");
        }

        try {
            $instance = $this->container->get($controller);

            $reflectionMethod = new \ReflectionMethod($instance, $method);

            return $reflectionMethod->invokeArgs($instance, $params);

        } catch (DefinitionException $e) {
            throw new BindingException("Binding Error.");
        }

    }

    protected function filter($url)
    {
        if(!empty($this->filter[$url])) {
            $name = 'App\Filters\\'.$this->filter[$url];
            $name::handle();
        }
    }
}
