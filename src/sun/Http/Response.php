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
    }
    /**
     * To response with html
     *
     * @param $data
     */
    public function html($data)
    {
        echo $data;
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
     * @param $filename
     */
    public function download($filename)
    {
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        readfile($filename);
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
}
