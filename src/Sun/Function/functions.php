<?php

if (!function_exists('app')) {
    /**
     * To get Sun Application instance
     *
     * @param $make
     *
     * @return mixed
     */
    function app($make = null)
    {
        if(is_null($make)) {
            return Sun\Application::getInstance();
        }

        return Sun\Application::getInstance()->make($make);
    }
}

if(!function_exists('app_path')) {
    /**
     * To get App directory path
     *
     * @return mixed
     */
    function app_path()
    {
        return app()->app_path();
    }
}

if(!function_exists('base_path')) {
    /**
     * To get base directory path
     *
     * @return mixed
     */
    function base_path()
    {
        return app()->base_path();
    }
}

if(!function_exists('config_path')) {
    /**
     * To get config directory path
     *
     * @return mixed
     */
    function config_path()
    {
        return app()->config_path();
    }
}

if(!function_exists('storage_path')) {
    /**
     * To get storage directory path
     *
     * @return mixed
     */
    function storage_path()
    {
        return app()->storage_path();
    }
}

if(!function_exists('public_path')) {
    /**
     * To get public directory path
     *
     * @return mixed
     */
    function public_path()
    {
        return app()->public_path();
    }
}

if (!function_exists('csrf_token')) {
    /**
     * To get csrf token
     *
     * @return mixed
     */
    function csrf_token()
    {
        return app()->make('Sun\Contracts\Security\Csrf')->token();
    }
}

if (!function_exists('config')) {
    /**
     * To get configuration
     *
     * @param $location
     *
     * @return mixed
     */
    function config($location)
    {
        return app()->config($location);
    }
}

if (!function_exists('view')) {
    /**
     * To render view
     *
     * @param       $name
     * @param array $data
     *
     * @return mixed
     */
    function view($name, array $data = [])
    {
        return app()->make('Sun\Contracts\View\View')->render($name, $data);
    }
}

if (!function_exists('redirect')) {
    /**
     * To redirect
     *
     * @return \Sun\Http\Redirect
     */
    function redirect()
    {
        return app()->make('Sun\Contracts\Http\Redirect');
    }
}

if (!function_exists('request')) {
    /**
     * To get request data
     *
     * @return \Sun\Http\Request
     */
    function request()
    {
        return app()->make('Sun\Contracts\Http\Request');
    }
}

if (!function_exists('response')) {
    /**
     * To response incoming request
     *
     * @return \Sun\Http\Response
     */
    function response()
    {
        return app()->make('Sun\Contracts\Http\Response');
    }
}

if (!function_exists('validator')) {
    /**
     * To validate form
     *
     * @return \Sun\Validation\Validator
     */
    function validator()
    {
        return app()->make('Sun\Contracts\Validation\Validator');
    }
}

if (!function_exists('url')) {
    /**
     * To get url
     *
     * @param $path
     *
     * @return string
     */
    function url($path = null)
    {
        if(is_null($path)) {
            $url = app()->make('Sun\Contracts\Routing\UrlGenerator');
            return $url->url($url->getUri());
        }

        return app()->make('Sun\Contracts\Routing\UrlGenerator')->url($path);
    }
}

if(!function_exists('dispatch')) {
    /**
     * To dispatch command
     *
     * @return \Sun\Bus\Dispatcher
     */
    function dispatch()
    {
        return app()->make('Sun\Contracts\Bus\Dispatcher');
    }
}

if(!function_exists('bcrypt')) {
    /**
     * To make password hash
     *
     * @param $password
     *
     * @return mixed
     */
    function bcrypt($password)
    {
        return app()->make('Sun\Contracts\Security\Hash')->make($password);
    }
}

if(!function_exists('bcrypt_verify')) {
    /**
     * To make password hash
     *
     * @param $password
     * @param $hash
     *
     * @return mixed
     */
    function bcrypt_verify($password, $hash)
    {
        return app()->make('Sun\Contracts\Security\Hash')->verify($password, $hash);
    }
}

if(!function_exists('encrypt')) {
    /**
     * To encrypt given data
     *
     * @param $data
     *
     * @return mixed
     */
    function encrypt($data)
    {
        return app()->make('Sun\Contracts\Security\Encrypter')->encrypt($data);
    }
}

if(!function_exists('decrypt')) {
    /**
     * To decrypt given data
     *
     * @param $data
     *
     * @return mixed
     */
    function decrypt($data)
    {
        return app()->make('Sun\Contracts\Security\Encrypter')->decrypt($data);
    }
}

if(!function_exists('env')) {
    /**
     * To get environment variable data
     *
     * @param string $name
     * @param string $default
     *
     * @return string
     */
    function env($name, $default = null)
    {
        if($value = getenv($name)) {
            return $value;
        }

        return $default;
    }
}

if(!function_exists('group')) {
    /**
     * Route GROUP
     *
     * @param array $routeOption
     * @param callback $callback
     */
    function group(array $routeOption = [], $callback)
    {
        app()->group($routeOption, $callback);
    }
}

if(!function_exists('get')) {
    /**
     * Route GET
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function get($uri, $pattern, array $options = [])
    {
        app()->get($uri, $pattern, $options);
    }
}

if(!function_exists('post')) {
    /**
     * Route POST
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function post($uri, $pattern, array $options = [])
    {
        app()->post($uri, $pattern, $options);
    }
}

if(!function_exists('delete')) {
    /**
     * Route DELETE
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function delete($uri, $pattern, array $options = [])
    {
        app()->delete($uri, $pattern, $options);
    }
}

if(!function_exists('put')) {
    /**
     * Route PUT
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function put($uri, $pattern, array $options = [])
    {
        app()->put($uri, $pattern, $options);
    }
}

if(!function_exists('patch')) {
    /**
     * Route PATCH
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function patch($uri, $pattern, array $options = [])
    {
        app()->patch($uri, $pattern, $options);
    }
}

if(!function_exists('any')) {
    /**
     * Route ANY
     *
     * @param string $uri
     * @param string $pattern
     * @param array $options
     */
    function any($uri, $pattern, array $options = [])
    {
        app()->any($uri, $pattern, $options);
    }
}

if(!function_exists('resource')) {
    /**
     * Route RESOURCE
     *
     * @param string $uri
     * @param string $controller
     * @param array $options
     */
    function resource($uri, $controller, array $options = [])
    {
        app()->resource($uri, $controller, $options);
    }
}

if(!function_exists('db')) {
    /**
     * Capsule Instance
     *
     * @return \Illuminate\Database\Capsule\Manager
     */
    function db()
    {
        return app()->db;
    }
}

if(!function_exists('event')) {
    /**
     * Broadcast event
     *
     * @param string $name
     * @param array $data
     *
     * @return \Sun\Contracts\Event\Event
     */
    function event($name = null, $data = [])
    {
        if(is_null($name)) {
            return app()->make('Sun\Contracts\Event\Event');
        }

        app()->make('Sun\Contracts\Event\Event')->broadcast($name, $data);
    }
}

if(!function_exists('broadcast')) {
    /**
     * Broadcast event
     *
     * @param string $event
     * @param array $data
     *
     * @throws \Sun\Event\EventNotFoundException
     */
    function broadcast($event, $data = [])
    {
        app()->make('Sun\Contracts\Event\Event')->broadcast($event, $data);
    }
}


if(!function_exists('listen')) {
    /**
     * Event listener to listen broadcast
     *
     * @param string $event
     * @param mixed $handler
     */
    function listen($event, $handler)
    {
        app()->make('Sun\Contracts\Event\Event')->listen($event, $handler);
    }
}