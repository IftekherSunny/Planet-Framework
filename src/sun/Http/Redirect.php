<?php

namespace Sun\Http;

use Session;
use Sun\Routing\UrlGenerator;

class Redirect
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * To redirect
     *
     * @param       $url
     * @param array $values
     */
    public function to($url, array $values = [])
    {
        $url = $this->urlGenerator->getBaseUri() . $url;
        if (count($values)) {
            foreach ($values as $key => $value) {
                $this->with($key, $value);
            }
        }

        header('location: ' . $url);
        exit();
    }

    /**
     * To store data in a session
     *
     * @param $key
     * @param $value
     */
    public function with($key, $value)
    {
        Session::create($key, $value);
    }

    /**
     * To redirect back
     */
    public function back()
    {
        $url = $_SERVER['REQUEST_URI'];

        header('location: ' . $url);
        exit();
    }

    /**
     * To redirect back with value
     *
     * @param $key
     * @param $value
     */
    public function backWith($key, $value)
    {
       $this->with($key, $value);

        $url = $_SERVER['REQUEST_URI'];

        header('location: ' . $url);
        exit();
    }
}
