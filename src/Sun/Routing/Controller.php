<?php

namespace Sun\Routing;

class Controller
{
    /**
     * To dispatch command
     *
     * @param $object
     *
     * @return mixed
     */
    public function dispatch($object)
    {
        return dispatch()->dispatch($object);
    }

    /**
     * To call method dynamically
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments) {
        if(method_exists($this, $name)) {
            return call_user_func_array($name,$arguments);
        }

        throw new MethodNotFoundException("Method [ $name ] does not exist.");
    }
}
