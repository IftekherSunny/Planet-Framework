<?php

namespace Sun\Http;

use Sun\Session;
use Sun\Routing\UrlGenerator;

class Redirect
{
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var hasData
     */
    protected $hasData;

    /**
     * @var \Sun\Session
     */
    protected $session;

    /**
     * @param UrlGenerator $urlGenerator
     * @param Session      $session
     */
    public function __construct(UrlGenerator $urlGenerator, Session $session)
    {
        $this->urlGenerator = $urlGenerator;

        $this->session = $session;

        $this->hasData = false;
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

        if(! $this->hasData) exit();
    }

    /**
     * To store data in a session
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function with($key, $value)
    {
        $this->hasData = true;

        $this->session->create($key, $value);

        return $this;
    }

    /**
     * To redirect back
     */
    public function back()
    {
        $url = $this->session->get('previous_uri');

        header('location: ' . $url);
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

        $url = $this->session->get('previous_uri');

        header('location: ' . $url);
        exit();
    }
}
