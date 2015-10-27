<?php

namespace Sun\Contracts\Http;

interface Request
{
    /**
     * To know request method type
     *
     * @return string
     */
    public function method();

    /**
     * To check request type
     *
     * @param string $name
     *
     * @return bool
     */
    public function isMethod($name);

    /**
     * To check request is ajax
     *
     * @return bool
     */
    public function isAjax();

    /**
     * To get value from a request
     *
     * @param string $fieldName
     *
     * @return mixed
     */
    public function input($fieldName);

    /**
     * To get old input value
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function old($fieldName);

    /**
     * Storing user input value
     */
    public function storeInput();

    /**
     * To get all values from a request
     *
     * @return mixed
     */
    public function all();

    /**
     * To get file from a request
     *
     * @param string $name
     *
     * @return mixed
     */
    public function file($name);

    /**
     * To get request data from session
     *
     * @param string $name
     *
     * @return string
     */
    public function get($name);

    /**
     * To get header data
     *
     * @param string $name
     *
     * @return string
     */
    public function header($name);

    /**
     * To get all headers data
     *
     * @return array
     */
    public function getAllHeaders();
}