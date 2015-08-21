<?php

namespace Sun\Http;

use Sun\Contracts\Session\Session;
use Sun\Contracts\Routing\UrlGenerator;
use Sun\Contracts\Http\Redirect as RedirectContract;

class Redirect implements RedirectContract
{
    /**
     * @var \Sun\Contracts\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var hasData
     */
    protected $hasData;

    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new redirect instance
     *
     * @param \Sun\Contracts\Routing\UrlGenerator $urlGenerator
     * @param \Sun\Contracts\Session\Session      $session
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
     * @param string $url
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

        if($this->hasData) exit();
    }

    /**
     * To store data in a session
     *
     * @param string $key
     * @param mixed $value
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
     * @param string $key
     * @param mixed $value
     */
    public function backWith($key, $value)
    {
        $this->with($key, $value);

        $url = $this->session->get('previous_uri');

        header('location: ' . $url);
        exit();
    }
}
