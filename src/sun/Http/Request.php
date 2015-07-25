<?php

namespace Sun\Http;

use Sun\Session;

class Request
{

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->storeInput();
    }
    /**
     * To know request method type
     *
     * @return string
     */
    public function method()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            return 'POST';
        }

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            return 'GET';
        }
    }

    /**
     * To check request type
     *
     * @param $name
     *
     * @return bool
     */
    public function isMethod($name)
    {
        if($this->method() === strtoupper($name)) {
            return true;
        }

        return false;
    }

    /**
     * To check request is ajax
     *
     * @return bool
     */
    public function isAjax()
    {
        $headers = apache_request_headers();
        $is_ajax = (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest');

        return $is_ajax ? true : false;
    }

    /**
     * To get value from a request
     *
     * @param $fieldName
     *
     * @return mixed
     */
    public function input($fieldName)
    {
        if($this->isMethod('post')) {
            return (isset($_POST[$fieldName]))? $_POST[$fieldName] : '';
        }

        if($this->isMethod('get')) {
            return (isset($_GET[$fieldName]))? $_GET[$fieldName] : '';
        }
    }

    /**
     * To get old input value
     *
     * @param $fieldName
     *
     * @return string
     */
    public function old($fieldName)
    {
       if($this->isMethod('get') && $this->session->has('planet_oldInput')) {

               $oldInput = $this->session->get('planet_oldInput');

               return ($oldInput != null)? $oldInput[$fieldName] : '';

       }
    }

    /**
     * Storing user input value
     */
    public function storeInput()
    {
        if($this->isMethod('post')) {
            $this->session->create('planet_oldInput', $_POST);
        }

    }

    /**
     * To get all values from a request
     *
     * @return mixed
     */
    public function all()
    {
        if($this->isMethod('POST')) {
            return (isset($_POST))? $_POST : [];
        }

        if($this->isMethod('GET')) {
            return (isset($_GET))? $_GET : [];
        }
    }

    /**
     * To get file from a request
     *
     * @param $name
     *
     * @return mixed
     */
    public function file($name)
    {
        return (isset($_FILES[$name]))? $_FILES[$name] : [];
    }

}
