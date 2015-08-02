<?php

if (!function_exists('app')) {
    /**
     * To get Sun Application instance
     *
     * @return mixed
     */
    function app()
    {
        return Sun\Application::getInstance();
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
        return app()->make('Sun\Security\Csrf')->token();
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
        $config = new Sun\Support\Config('../config');
        $hold = explode('.', $location);

        $filename = 'get' . strtoupper(array_shift($hold));
        $location = implode('.', $hold);
        if(empty($location)) {
            return $config->{$filename}();
        }

        return $config->{$filename}($location);
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
        return app()->make('Sun\View\View')->render($name, $data);
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
        return app()->make('Sun\Http\Redirect');
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
        return app()->make('Sun\Http\Request');
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
        return app()->make('Sun\Http\Response');
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
        return app()->make('Sun\Validation\Validator');
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
    function url($path)
    {
        return app()->make('Sun\Routing\UrlGenerator')->url($path);
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
        return app()->make('Sun\Bus\Dispatcher');
    }
}
