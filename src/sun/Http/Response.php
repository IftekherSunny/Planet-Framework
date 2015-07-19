<?php

namespace Sun\Http;

class Response
{
    /**
     * To response with html
     *
     * @param $data
     */
    public function html($data)
    {
        echo $data;
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

    public function message($message)
    {
        echo $message;
    }

    public function code($code)
    {
        http_response_code($code);

        return $this;
    }
}
