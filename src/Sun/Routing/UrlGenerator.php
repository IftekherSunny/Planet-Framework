<?php

namespace Sun\Routing;

use Sun\Contracts\Routing\UrlGenerator as UrlGeneratorContract;

class UrlGenerator implements UrlGeneratorContract
{
    /**
     * To get uri
     *
     * @return string
     */
    public function getUri()
    {
        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');
        $baseUri = $this->getBaseUri();
        $uri = str_replace($baseUri, '', $uri)?: '/';

        return $uri;
    }

    /**
     * To get URL link
     *
     * @param string $path
     *
     * @return string
     */
    public function url($path)
    {
        $protocol = "http" . $this->isSecure();
        $port = $this->port();

        return $protocol . $_SERVER['SERVER_NAME'] .  $port .$this->getBaseUri() . $path;
    }

    /**
     * To get base uri
     *
     * @return string
     */
    public function getBaseUri()
    {
        return strtolower(strstr($_SERVER['SCRIPT_NAME'], '/index.php', true));
    }

    /**
     * TO check is secured request
     *
     * @return string
     */
    public function isSecure()
    {
        return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? "s://" : "://";
    }

    /**
     * To get http port no
     *
     * @return string
     */
    public function port()
    {
        return (($_SERVER['SERVER_PORT'] == 80) || ($_SERVER['SERVER_PORT'] == 443))? "" : ":". $_SERVER['SERVER_PORT'];
    }
}
