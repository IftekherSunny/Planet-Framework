<?php

namespace Sun\Contracts\Http;

interface Response
{
    /**
     * To response with html
     *
     * @param $data
     */
    public function html($data);

    /**
     * To response with json
     *
     * @param $data
     */
    public function json($data);

    /**
     * To response with download
     *
     * @param $filepath
     */
    public function download($filepath);

    /**
     * To show 404 page
     *
     * @return string
     */
    public function abort();

    /**
     * To show message
     *
     * @param $message
     */
    public function message($message);

    /**
     * To add http status code
     *
     * @param $code
     *
     * @return \Sun\Http\Response
     */
    public function code($code);
}