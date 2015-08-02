<?php

namespace Sun\Http;


use Sun\Session;

class Response
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

        $this->debugChecker();
    }
    /**
     * To response with html
     *
     * @param $data
     */
    public function html($data)
    {
        echo $data;
        $this->session->create('previous_uri', $_SERVER['REQUEST_URI']);
        $this->session->create('planet_oldInput', null);
    }

    /**
     * To response with json
     *
     * @param $data
     */
    public function json($data)
    {
        echo json_encode($data);
    }

    /**
     * To response with download
     *
     * @param $filepath
     */
    public function download($filepath)
    {
        $filename = pathinfo($filepath, PATHINFO_BASENAME );

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        readfile($filepath);
        exit();
    }

    /**
     * To show 404 page
     *
     * @return string
     */
    public function abort()
    {
        echo 'Page not found';
    }

    /**
     * To show message
     *
     * @param $message
     */
    public function message($message)
    {
        if(is_array($message)) {
            echo json_encode($message);
        }
        else {
            echo $message;
        }
    }

    /**
     * To add http status code
     *
     * @param $code
     *
     * @return $this
     */
    public function code($code)
    {
        http_response_code($code);

        return $this;
    }

    /**
     * Checking debug mode to show/hide error
     */
    private function debugChecker()
    {
        if(config('app.debug')) {
            error_reporting(-1);
            ini_set('display_errors', 1);
        }
    }
}
