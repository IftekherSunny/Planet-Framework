<?php

namespace Sun\Http;

use Exception;
use Sun\Contracts\Session\Session;
use Sun\Contracts\Http\Response as ResponseContract;

class Response implements ResponseContract
{
    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new response instance
     *
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    /**
     * To response with html
     *
     * @param string $data
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
     * @param string $data
     */
    public function json($data)
    {
        echo json_encode($data);
    }

    /**
     * To response with download
     *
     * @param string $filepath
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
     * @throws Exception
     */
    public function abort()
    {
        throw new Exception('Page not found.');
    }

    /**
     * To show message
     *
     * @param string|array $message
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
     * @param int $code
     *
     * @return $this
     */
    public function code($code)
    {
        http_response_code($code);

        return $this;
    }

    /**
     * To get header data
     *
     * @param string $message
     *
     * @return $this
     */
    public function header($message)
    {
        header($message);

        return $this;
    }
}
