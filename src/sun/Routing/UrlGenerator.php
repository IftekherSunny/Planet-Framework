<?php

namespace Sun\Routing;

class UrlGenerator
{
    public function getUri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $baseUri = $this->getBaseUri();
        $uri = str_replace($baseUri, '', $uri);

        return $uri;
    }

    public function url($path)
    {
        $protocol = "http" . $this->isSecure();
        $port = $this->port();

        return $protocol . $_SERVER['SERVER_NAME'] .  $port .$this->getBaseUri() . $path;
    }

    public function getBaseUri()
    {
        return strtolower(strstr($_SERVER['SCRIPT_NAME'], '/index.php', true));
    }

    public function isSecure()
    {
        return (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? "s://" : "://";
    }

    public function port()
    {
        return (($_SERVER['SERVER_PORT'] == 80) || ($_SERVER['SERVER_PORT'] == 443))? "" : ":". $_SERVER['SERVER_PORT'];
    }
}
