<?php

namespace Sun\Http;

use Sun\Contracts\Session\Session;
use Sun\Contracts\Http\Request as RequestContract;

class Request implements RequestContract
{
    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * To store all headers data
     */
    protected $headers;

    /**
     * Create a new request instance
     *
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->headers = $this->getAllHeaders();

        $this->storeInput();
    }

    /**
     * To know request method type
     *
     * @return string
     */
    public function method()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return 'POST';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return 'GET';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            return 'PUT';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            return 'PATCH';
        }

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            return 'DELETE';
        }
    }

    /**
     * To check request type
     *
     * @param string $name
     *
     * @return bool
     */
    public function isMethod($name)
    {
        if ($this->method() === strtoupper($name)) {
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
     * @param string $fieldName
     *
     * @return mixed
     */
    public function input($fieldName)
    {
        if ($this->isMethod('post')) {
            return (isset($_POST[$fieldName])) ? $_POST[$fieldName] : '';
        }

        if ($this->isMethod('get')) {
            return (isset($_GET[$fieldName])) ? $_GET[$fieldName] : '';
        }

        if ($this->isMethod('put')) {
            parse_str(file_get_contents("php://input"), $PUT);

            return (isset($PUT[$fieldName])) ? $PUT[$fieldName] : '';
        }

        if ($this->isMethod('patch')) {
            parse_str(file_get_contents("php://input"), $PATCH);

            return (isset($PATCH[$fieldName])) ? $PATCH[$fieldName] : '';
        }

        if ($this->isMethod('delete')) {
            parse_str(file_get_contents("php://input"), $DELETE);

            return (isset($DELETE[$fieldName])) ? $DELETE[$fieldName] : '';
        }
    }

    /**
     * To get old input value
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function old($fieldName)
    {
        if ($this->isMethod('get') && $this->session->has('planet_oldInput')) {

            $oldInput = $this->session->get('planet_oldInput');

            return ($oldInput != null) ? $oldInput[$fieldName] : '';

        }
    }

    /**
     * Storing user input value
     */
    public function storeInput()
    {
        if ($this->isMethod('post')) {
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
        if ($this->isMethod('POST')) {
            return (isset($_POST)) ? $_POST : [];
        }

        if ($this->isMethod('GET')) {
            return (isset($_GET)) ? $_GET : [];
        }
    }

    /**
     * To get file from a request
     *
     * @param string $name
     *
     * @return mixed
     */
    public function file($name)
    {
        return (isset($_FILES[$name])) ? $_FILES[$name] : [];
    }

    /**
     * To get request data from session
     *
     * @param string $name
     *
     * @return string
     */
    public function get($name)
    {
        return ($this->session->has($name)) ? $this->session->get($name) : '';
    }

    /**
     * To get header data
     *
     * @param string $name
     *
     * @return string
     */
    public function header($name)
    {
        return (isset($this->headers[$name])) ? $this->headers[$name] : '';
    }

    /**
     * To get all headers data
     *
     * @return array
     */
    public function getAllHeaders()
    {
            $headers = [];

            foreach ($_SERVER as $name => $value)
            {
                if (substr($name, 0, 5) == 'HTTP_')
                {
                    $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
                }
            }

            return $headers;
    }
}
