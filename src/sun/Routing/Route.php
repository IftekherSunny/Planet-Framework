<?php

namespace Sun\Routing;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Sun\Container\Container;
use Sun\Support\Abort;

class Route
{
    protected $url = array();

    protected $pattern = array();

    protected $method = array();

    protected $params = array();

    protected $container;

    protected $dispatcher;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function add($method = 'GET', $url = '/', $pattern = '')
    {
        $this->method[] = $method;

        $this->url[] = $url;

        $this->pattern[] = $pattern;
    }

    public function routeRegister()
    {
        $route = new RouteCollector(new Std(), new DataGenerator());
        for ($i = 0; $i < count($this->url); $i++) {
            $route->addRoute($this->method[$i], $this->url[$i], $this->pattern[$i]);
        }

        $this->dispatcher = new Dispatcher($route->getData());
    }

    public function routeDispatcher($method, $url)
    {
        $routeInfo = $this->dispatcher->dispatch($method, $url);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                Abort::message("Page not found.");
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                Abort::message("Method not found.");
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $params = $routeInfo[2];

                return $this->methodDispatcher($handler, $params);

                break;
        }
    }

    protected function methodDispatcher($handler, $params)
    {

        /**
         *  When send clouser
         **/
        if (is_callable($handler)) {
            echo call_user_func_array($handler, $params);

            return;
        }

        /**
         * if send controller
         * **/
        list($controller, $method) = explode('@', $handler);

        return $this->invoke($controller, $method, $params);
    }

    public function invoke($controller, $method, $params)
    {
        if (!class_exists($controller)) {
            throw new Exception("Class not found.");
        }

        try {
            $instance = $this->container->get($controller);

            $reflectionMethod = new \ReflectionMethod($instance, $method);

            return $reflectionMethod->invokeArgs($instance, $params);

        } catch (\DI\Definition\Exception\DefinitionException $e) {
            throw new BindingException("Binding Error");
        }

    }

}
