<?php

namespace Sun\Container;

use Closure;
use Exception;
use ArrayAccess;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use Sun\Contracts\Container\Container as ContainerContract;

class Container implements ContainerContract, ArrayAccess
{
    /**
     * Array of all resolved types.
     *
     * @var array
     */
    protected $resolved = [];

    /**
     * Array of all registered type hints.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Resolved dependencies for a key.
     *
     * @param string $key
     * @param array  $params
     *
     * @return object
     * @throws Exception
     */
    public function make($key, $params = [])
    {
        if($this->aliasExist($key)) {
            return $this->make($this->aliases[$key], $params);
        }

        if(is_callable($key)) {
            return call_user_func_array($key, $this->resolveCallback($key, $params));
        }

        return $this->resolveClass($key, $params);
    }

    /**
     * Get the resolved type for a key that already resolved by the container.
     * If the key does not resolve, at first resolved it,
     * then returns the resolved type.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function need($key)
    {
        if(!isset($this->resolved[$key])) {
            return $this->make($key);
        }

        return $this->resolved[$key];
    }

    /**
     * Bind a value for a key.
     *
     * @param string                 $key
     * @param string|callback|object $value
     *
     * @throws Exception
     */
    public function bind($key, $value)
    {
        if(is_callable($value)) {
            $this->aliases[$key] = $value;
        } elseif(is_object($value)) {
            $this->resolved[$key]  = $value;
        } else {
            if(class_exists($value)) $this->aliases[$key] =  $value;
            else throw new Exception("Class [ $value ] does not exist.");
        }
    }


    /**
     * Resolve dependencies for a callback.
     *
     * @param Closure $callback
     * @param array   $params
     *
     * @return array
     * @throws Exception
     */
    public function resolveCallback(Closure $callback, $params = [])
    {
        $reflectionParameter = new ReflectionFunction($callback);

        $dependencies = $reflectionParameter->getParameters();

        return $this->getDependencies($dependencies, $params);
    }

    /**
     * Resolve dependencies for a method.
     *
     * @param string $class
     * @param string $method
     * @param array  $params
     *
     * @return array
     * @throws Exception
     */
    public function resolveMethod($class, $method, $params = [])
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        $dependencies = $reflectionMethod->getParameters();

        return $this->getDependencies($dependencies, $params);
    }

    /**
     * Resolve dependencies for a class.
     *
     * @param string $name
     * @param array $params
     *
     * @return object
     * @throws Exception
     */
    public function resolveClass($name, $params = [])
    {
        $reflectionClass = new ReflectionClass($name);

        if (!$reflectionClass->isInstantiable()) {
            throw new Exception("The [ {$name} ] is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!is_null($constructor)) {
            $dependencies = $constructor->getParameters();

            $resolving = $this->getDependencies($dependencies, $params);

            $instance = $reflectionClass->newInstanceArgs($resolving);

            return $this->resolved[$name] = $this->getInstanceWithAppProperty($instance);
        }

        $instance = new $name;

        return $this->resolved[$name] = $this->getInstanceWithAppProperty($instance);
    }

    /**
     * Get all required dependencies.
     *
     * @param string $dependencies
     * @param array  $params
     *
     * @return array
     * @throws Exception
     */
    protected function getDependencies($dependencies, $params = [])
    {
        $resolving = [];

        foreach ($dependencies as $dependency) {
            if ($dependency->isDefaultValueAvailable()) {
                $resolving[] = $dependency->getDefaultValue();
            } elseif(isset($params[$dependency->name])) {
                $resolving[] = $params[$dependency->name];
            } else {
                if ($dependency->getClass() === null) {
                    throw new Exception("The [ $" . "{$dependency->name} ] is not instantiable.");
                }

                $className = $dependency->getClass()->name;

                if (!isset($this->resolved[$className])) {
                    $this->resolved[$className] = $this->make($className);
                    $resolving[] = $this->resolved[$className];
                } else {
                    $resolving[] = $this->resolved[$className];
                }
            }
        }

        return $resolving;
    }

    /**
     * Check a key is already exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        if(array_key_exists($key, $this->aliases) || array_key_exists($key, $this->resolved)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check alias existence.
     *
     * @param $key
     *
     * @return bool
     */
    protected function aliasExist($key)
    {
        return gettype($key) === 'string' && array_key_exists($key, $this->aliases);
    }

    /**
     * Get instance with app property.
     *
     * @param $instance
     *
     * @return object
     */
    protected function getInstanceWithAppProperty($instance)
    {
        if(!property_exists($instance, 'app')) {
            $instance->app = $this;
        }

        return $instance;
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        if(array_key_exists($key, $this->resolved)) {
            return $this->resolved[$key];
        }

        return $this->make($key);
    }


    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->resolved[$key] = $value;
    }

    /**
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        if(array_key_exists($key, $this->aliases)) {
            unset($this->aliases[$key]);
        } else {
            unset($this->resolved[$key]);
        }
    }

    /**
     * To get Container
     *
     * @return \Sun\Container\Container
     */
    public function getContainer()
    {
        return $this;
    }
}