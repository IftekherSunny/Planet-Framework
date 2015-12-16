<?php

namespace Sun\Http;

use Exception;
use Sun\Contracts\Application;
use Sun\Contracts\Session\Session;
use Sun\Contracts\Http\Response as ResponseContract;

class Response implements ResponseContract
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new response instance
     *
     * @param \Sun\Contracts\Application
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Application $app, Session $session)
    {
        $this->app = $app;

        $this->session = $session;
    }
    /**
     * To response with html
     *
     * @param string $data
     */
    public function html($data)
    {
        if(is_array($data)) {
            echo $this->json($data);
        } else {
            echo $data;
        }

        if($this->app->config('session.enable')) {
            $this->session->create('previous_uri', $_SERVER['REQUEST_URI']);
            $this->session->create('planet_oldInput', null);
        }
    }

    /**
     * To response with json
     *
     * @param string $data
     * @param int    $status
     *
     * @return string
     */
    public function json($data, $status = 200)
    {
        $this->header('Content-Type: application/json');

        $this->statusCode($status);

        return json_encode($data);
    }

    /**
     * Http Response to download file
     *
     * @param string $path
     * @param bool   $removeDownloadedFile
     *
     * @throws Exception
     */
    public function download($path, $removeDownloadedFile = false)
    {
        if(!file_exists($path)) {
            throw new Exception("File [ $path ] not found.");
        }

        $filename = pathinfo($path, PATHINFO_BASENAME );

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        @readfile($path);

        if($removeDownloadedFile) {
            @unlink($path);
        }
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
    public function statusCode($code)
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
