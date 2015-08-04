<?php
namespace Sun\Contracts;

use Exception;

interface Application
{
    /**
     * Route GROUP
     *
     * @param array $routeOption
     * @param       $callback
     */
    public function group(array $routeOption = [], $callback);

    /**
     * Route GET
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function get($uri, $pattern, array $options = []);

    /**
     * Route POST
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function post($uri, $pattern, array $options = []);

    /**
     * Route DELETE
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function delete($uri, $pattern, array $options = []);

    /**
     * Route PUT
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function put($uri, $pattern, array $options = []);

    /**
     * Route PATCH
     *
     * @param       $uri
     * @param       $pattern
     * @param array $options
     */
    public function patch($uri, $pattern, array $options = []);

    /**
     * To run application
     */
    public function run();

    /**
     * To get application base directory path
     *
     * @param null $path
     *
     * @return string
     */
    public function base_path($path = null);

    /**
     * To get application app directory path
     *
     * @param null $path
     *
     * @return string
     */
    public function app_path($path = null);

    /**
     * To get application config directory path
     *
     * @return string
     */
    public function config_path();

    /**
     * To get application storage directory path
     *
     * @return string
     */
    public function storage_path();

    /**
     * To get application public directory path
     *
     * @return string
     */
    public function public_path();

    /**
     * To load alien
     */
    public function loadAlien();

    /**
     * To boot database configuration
     */
    public function bootDatabase();

    /**
     * To get \Sun\Application instance
     *
     * @return mixed
     */
    public static function getInstance();

    /**
     * To get application namespace
     *
     * @return string
     * @throws Exception
     */
    public function getNamespace();
}