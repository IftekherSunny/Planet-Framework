<?php

namespace Sun\Contracts\Routing;

interface UrlGenerator
{
    /**
     * To get uri
     *
     * @return string
     */
    public function getUri();

    /**
     * To get URL link
     *
     * @param string $path
     *
     * @return string
     */
    public function url($path);

    /**
     * To get base uri
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * TO check is secured request
     *
     * @return string
     */
    public function isSecure();

    /**
     * To get http port no
     *
     * @return string
     */
    public function port();
}