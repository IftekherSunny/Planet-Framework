<?php

namespace Sun\Contracts\Http;

use Exception;

interface Response
{
    /**
     * To response with html
     *
     * @param string $data
     */
    public function html($data);

    /**
     * To response with json
     *
     * @param string $data
     */
    public function json($data);

    /**
     * To response with download
     *
     * @param string $filepath
     */
    public function download($filepath);

    /**
     * To show 404 page
     *
     * @throws Exception
     */
    public function abort();

    /**
     * To show message
     *
     * @param string|array $message
     */
    public function message($message);

    /**
     * To add http status code
     *
     * @param int $code
     *
     * @return \Sun\Http\Response
     */
    public function code($code);

    /**
     * To get header data
     *
     * @param string $message
     *
     * @return \Sun\Http\Response
     */
    public function header($message);
}