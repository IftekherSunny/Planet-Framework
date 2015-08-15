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
        $config = app()->config;
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
    function url($path = null)
    {
        if(is_null($path)) {
            $url = app()->make('Sun\Routing\UrlGenerator');
            return $url->url($url->getUri());
        }

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
        return app()->make('Sun\Security\Hash')->make($password);
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
        return app()->make('Sun\Security\Hash')->verify($password, $hash);
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
        return app()->make('Sun\Security\Encrypter')->encrypt($data);
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
        return app()->make('Sun\Security\Encrypter')->decrypt($data);
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